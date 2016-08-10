<?php
/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2008-2010 (original work) Deutsche Institut für Internationale Pädagogische Forschung (under the project TAO-TRANSFER);
 *               2009-2012 (update and modification) Public Research Centre Henri Tudor (under the project TAO-SUSTAIN & TAO-DEV);
 *               2012-2016 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT)
 *
 */

use oat\tao\model\lock\LockManager;
use oat\taoItems\model\event\ItemDuplicatedEvent;
use oat\taoItems\model\event\ItemRemovedEvent;
use oat\taoItems\model\event\ItemUpdatedEvent;
use oat\generis\model\fileReference\FileReferenceSerializer;
use oat\oatbox\filesystem\Directory;
use oat\oatbox\filesystem\FileSystemService;

/**
 * Service methods to manage the Items business models using the RDF API.
 *
 * @access public
 * @author Joel Bout, <joel@taotesting.com>
 * @package taoItems
 
 */
class taoItems_models_classes_ItemsService extends tao_models_classes_ClassService
{
    /**
     * Key to use to store the default filesource to be used in for new items
     *
     * @var string
     */
    const CONFIG_DEFAULT_FILESOURCE = 'defaultItemFileSource';

    /**
     * Instance of the itemContent property
     *
     * @access public
     * @var core_kernel_classes_Property
     */
    public $itemContentProperty = null;

    /**
     * The RDFS top level item class
     *
     * @access protected
     * @var core_kernel_classes_Class
     */
    protected $itemClass = null;

    /**
     * Instance of the itemModel property
     *
     * @access protected
     * @var core_kernel_classes_Property
     */
    protected $itemModelProperty = null;

    /**
     * taoItems_models_classes_ItemsService constructor.
     * Set $this->itemClass and related properties (model & content properties)
     */
    protected function __construct()
    {
        $this->itemClass = $this->getClass(TAO_ITEM_CLASS);
        $this->itemModelProperty = $this->getProperty(TAO_ITEM_MODEL_PROPERTY);
        $this->itemContentProperty = $this->getProperty(TAO_ITEM_CONTENT_PROPERTY);
    }

    public function getRootClass(){
        return $this->itemClass;
    }

    /**
     * get an item subclass by uri. 
     * If the uri is not set, it returns the  item class (the top level class.
     * If the uri don't reference an item subclass, it returns null
     *
     * @access public
     * @author Joel Bout, <joel@taotesting.com>
     * @param  string uri
     * @return core_kernel_classes_Class
     * @deprecated
     */
    public function getItemClass($uri = ''){
        $returnValue = null;

        if(empty($uri) && !is_null($this->itemClass)){
            $returnValue = $this->itemClass;
        }else{
            $clazz = $this->getClass($uri);
            if($this->isItemClass($clazz)){
                $returnValue = $clazz;
            }
        }

        return $returnValue;
    }

    /**
     * check if the class is a or a subclass of an Item
     *
     * @access public
     * @author Joel Bout, <joel@taotesting.com>
     * @param  core_kernel_classes_Class clazz
     * @return boolean
     */
    public function isItemClass(core_kernel_classes_Class $clazz){
        $returnValue = (bool) false;

        if($this->itemClass->getUri() == $clazz->getUri()){
            return true;
        }

        foreach($clazz->getParentClasses(true) as $parent){

            if($parent->getUri() == $this->itemClass->getUri()){
                $returnValue = true;
                break;
            }
        }

        return (bool) $returnValue;
    }

    /**
     * please call deleteResource() instead
     * @deprecated
     */
    public function deleteItem(core_kernel_classes_Resource $item){
        return $this->deleteResource($item);
    }
    
    /**
     * delete an item
     * @param core_kernel_classes_Resource $resource
     * @throws common_exception_Unauthorized
     * @return boolean
     */
    public function deleteResource(core_kernel_classes_Resource $resource)
    {
        if (LockManager::getImplementation()->isLocked($resource)) {
            $userId = common_session_SessionManager::getSession()->getUser()->getIdentifier();
            LockManager::getImplementation()->releaseLock($resource, $userId);
        }

        $result = $this->deleteItemContent($resource) && parent::deleteResource($resource);

        if ($result) {
            $this->getEventManager()->trigger(new ItemRemovedEvent($resource->getUri()));
        }

        return $result;
    }

    /**
     * delete an item class or subclass
     * @deprecated
     */
    public function deleteItemClass(core_kernel_classes_Class $clazz)
    {
        return $this->deleteClass($clazz);
    }

    /**
     * Short description of method getDefaultItemFolder
     *
     * @deprecated use getItemDirectory instead
     *
     * @access public
     * @author Joel Bout, <joel@taotesting.com>
     * @param  core_kernel_classes_Resource item
     * @param  string lang
     * @return string
     */
    public function getDefaultItemFolder(core_kernel_classes_Resource $item, $lang = ''){
        $returnValue = (string) '';

        if(!is_null($item)){

            $lang = empty($lang) ? $this->getSessionLg() : $lang;

            $itemRepo = $this->getDefaultFileSource();
            $repositoryPath = $itemRepo->getPath();
            $repositoryPath = substr($repositoryPath, strlen($repositoryPath) - 1, 1) == DIRECTORY_SEPARATOR ? $repositoryPath : $repositoryPath.DIRECTORY_SEPARATOR;
			$returnValue = $repositoryPath.tao_helpers_Uri::getUniqueId($item->getUri()).DIRECTORY_SEPARATOR.'itemContent'.DIRECTORY_SEPARATOR.$lang.DIRECTORY_SEPARATOR;
        }

        return (string) $returnValue;
    }

    /**
     * define the content of item to be inserted by default (to prevent null
     * after creation).
     * The item content's folder is created.
     *
     * @deprecated use getItemDirectory instead
     *
     * @access public
     * @author Bertrand Chevrier <bertrand@taotesting.com>
     * @param  core_kernel_classes_Resource item
     * @return core_kernel_classes_Resource the same item
     */
    public function setDefaultItemContent(core_kernel_classes_Resource $item){
        if(!is_null($item)){

            //we create the item folder by default. 
            //TODO this should be implemented through the filesystem abstraction but it doesn't work for directory
            $itemFolder = $this->getDefaultItemFolder($item);
            if(!file_exists($itemFolder) && strpos($itemFolder, ROOT_PATH) >= 0){
                if(!mkdir($itemFolder, 0770, true)){
                    common_Logger::w('Unable to create default item folder at location : ' . $itemFolder);
                }
            }
        }

        return $item;
    }

    /**
     * Enables you to get the content of an item, 
     * usually an xml string
     *
     * @deprecated use \oat\taoQtiItem\model\qti\Service::getDataItemByRdfItem instead
     *
     * @access public
     * @author Joel Bout, <joel@taotesting.com>
     * @param  Resource item
     * @param  boolean preview
     * @param  string lang
     * @return string
     */
    public function getItemContent(core_kernel_classes_Resource $item, $lang = ''){
        $returnValue = (string) '';

        common_Logger::i('Get itemContent for item '.$item->getUri());

        if(!is_null($item)){

            $itemContent = null;

            if(empty($lang)){
                $itemContents = $item->getPropertyValuesCollection($this->itemContentProperty);
            }else{
                $itemContents = $item->getPropertyValuesByLg($this->itemContentProperty, $lang);
            }

            if($itemContents->count() > 0){
                $itemContent = $itemContents->get(0);
            }
            //if no itemContent is set for the lang get the default one and copy it into a new repository
            else if(!empty($lang)){
                $itemContents = $item->getPropertyValuesCollection($this->itemContentProperty);
                if($itemContents->count() > 0){
                    $itemContent = $itemContents->get(0);
                    $this->setDefaultItemContent($item, $itemContent, $lang);
                    tao_helpers_File::copy($this->getItemFolder($item, DEFAULT_LANG), $this->getItemFolder($item, $lang));
                }
            }
            if(!is_null($itemContent) && $this->isItemModelDefined($item)){

                if(core_kernel_file_File::isFile($itemContent)){

                    $file = new core_kernel_file_File($itemContent->getUri());
                    $returnValue = file_get_contents($file->getAbsolutePath());
                    if($returnValue == false){
                        common_Logger::w('File '.$file->getAbsolutePath().' not found for fileressource '.$itemContent->getUri());
                    }
                }
            }else{
                common_Logger::w('No itemContent for item '.$item->getUri());
            }
        }

        return (string) $returnValue;
    }

    /**
     * Check if the item has an itemContent Property
     *
     * @param core_kernel_classes_Resource $item
     * @param string $lang
     * @return bool
     * @throws Exception
     */
    public function hasItemContent(core_kernel_classes_Resource $item, $lang = '')
    {
        if (is_null($item)) {
            return false;
        }

        if (empty($lang)) {
            $lang = $this->getSessionLg();
        }

        $itemContents = $item->getPropertyValuesByLg($this->itemContentProperty, $lang);
        return !$itemContents->isEmpty();
    }

    /**
     * Short description of method setItemContent
     *
     * @deprecated use \oat\taoQtiItem\model\qti\Service::saveXmlItemToRdfItem instead
     *
     * @access public
     * @author Joel Bout, <joel@taotesting.com>
     * @param  core_kernel_classes_Resource $item
     * @param  string $content
     * @param  string $lang
     * @param  string $commitMessage
     * @return bool
     * @throws common_Exception
     * @throws common_exception_Error
     */
    public function setItemContent(core_kernel_classes_Resource $item, $content, $lang = '', $commitMessage = ''){
        $returnValue = false;


        if(is_null($item) && !$this->isItemModelDefined($item)){
            throw new common_exception_Error('No item or itemmodel in '.__FUNCTION__);
        }

        $lang = empty($lang) ? $lang = $this->getSessionLg() : $lang;
        $itemModel = $item->getUniquePropertyValue($this->itemModelProperty);
        $dataFile = (string) $itemModel->getOnePropertyValue(new core_kernel_classes_Property(TAO_ITEM_MODEL_DATAFILE_PROPERTY));

        if($this->hasItemContent($item, $lang)){

            $itemContents = $item->getPropertyValuesByLg($this->itemContentProperty, $lang);
            $itemContent = $itemContents->get(0);
            if(!core_kernel_file_File::isFile($itemContent)){
                throw new common_Exception('Item '.$item->getUri().' has none file itemContent');
            }
            $file = new core_kernel_versioning_File($itemContent);
            $returnValue = $file->setContent($content);
        }else{

            $repository = $this->getDefaultFileSource();
            $file = $repository->createFile(
                    $dataFile, tao_helpers_Uri::getUniqueId($item->getUri()).DIRECTORY_SEPARATOR.'itemContent'.DIRECTORY_SEPARATOR.$lang
            );
            $item->setPropertyValueByLg($this->itemContentProperty, $file->getUri(), $lang);
            $returnValue = $file->setContent($content);
        }

        if ($returnValue) {
            $this->getEventManager()->trigger(new ItemUpdatedEvent($item->getUri()));
        }

        return (bool) $returnValue;
    }

    /**
     * Check if the Item has on of the itemModel property in the models array
     *
     * @access public
     * @author Joel Bout, <joel@taotesting.com>
     * @param  Resource item
     * @param  array models the list of URI of the itemModel to check
     * @return boolean
     */
    public function hasItemModel(core_kernel_classes_Resource $item, $models){
        $returnValue = (bool) false;

        $itemModel = $item->getOnePropertyValue($this->itemModelProperty);
        if($itemModel instanceof core_kernel_classes_Resource){
            if(in_array($itemModel->getUri(), $models)){
                $returnValue = true;
            }
        }

        return (bool) $returnValue;
    }

    /**
     * Check if the itemModel has been defined for that item
     *
     * @access public
     * @author Joel Bout, <joel@taotesting.com>
     * @param  Resource item
     * @return boolean
     */
    public function isItemModelDefined(core_kernel_classes_Resource $item){
        $returnValue = (bool) false;

        if(!is_null($item)){

            $model = $item->getOnePropertyValue($this->itemModelProperty);
            if($model instanceof core_kernel_classes_Literal){
                if(strlen((string) $model) > 0){
                    $returnValue = true;
                }
            }else if(!is_null($model)){
                $returnValue = true;
            }
        }

        return (bool) $returnValue;
    }

    /**
     * Get the runtime associated to the item model.
     *
     * @access public
     * @author Joel Bout, <joel@taotesting.com>
     * @param  Resource item
     * @return core_kernel_classes_Resource
     */
    public function getModelRuntime(core_kernel_classes_Resource $item){
        $returnValue = null;

        if(!is_null($item)){
            $itemModel = $item->getOnePropertyValue($this->itemModelProperty);
            if(!is_null($itemModel)){
                $returnValue = $itemModel->getOnePropertyValue($this->getProperty(TAO_ITEM_MODEL_RUNTIME_PROPERTY));
            }
        }

        return $returnValue;
    }

    /**
     * Short description of method hasModelStatus
     *
     * @access public
     * @author Joel Bout, <joel@taotesting.com>
     * @param  Resource item
     * @param  array status
     * @return boolean
     */
    public function hasModelStatus(core_kernel_classes_Resource $item, $status){
        $returnValue = (bool) false;

        if(!is_null($item)){
            if(!is_array($status) && is_string($status)){
                $status = array($status);
            }
            try{
                $itemModel = $item->getOnePropertyValue($this->itemModelProperty);
                if($itemModel instanceof core_kernel_classes_Resource){
                    $itemModelStatus = $itemModel->getUniquePropertyValue($this->getProperty(TAO_ITEM_MODEL_STATUS_PROPERTY));
                    if(in_array($itemModelStatus->getUri(), $status)){
                        $returnValue = true;
                    }
                }
            }catch(common_exception_EmptyProperty $ce){
                $returnValue = false;
            }
        }

        return (bool) $returnValue;
    }

    /**
     * render used for deploy and preview
     *
     * @access public
     * @author Joel Bout, <joel@taotesting.com>
     * @param  Resource item
     * @return string
     * @throws taoItems_models_classes_ItemModelException
     */
    public function render(core_kernel_classes_Resource $item, $language){

        $itemModel = $this->getItemModel($item);
        if(is_null($itemModel)){
            throw new common_exception_NoImplementation('No item model for item '.$item->getUri());
        }
        $impl = $this->getItemModelImplementation($itemModel);
        if(is_null($impl)){
            throw new common_exception_NoImplementation('No implementation for model '.$itemModel->getUri());
        }
        return $impl->render($item, $language);
    }

    /**
     * Woraround for item content
     * (non-PHPdoc)
     * @see tao_models_classes_GenerisService::cloneInstanceProperty()
     */
    protected function cloneInstanceProperty( core_kernel_classes_Resource $source, core_kernel_classes_Resource $destination, core_kernel_classes_Property $property) {
        if ($property->getUri() == TAO_ITEM_CONTENT_PROPERTY) {
            return $this->cloneItemContent($source, $destination, $property);
        } else {
            return parent::cloneInstanceProperty($source, $destination, $property);
        }
    }
    
    protected function cloneItemContent(
        core_kernel_classes_Resource $source,
        core_kernel_classes_Resource $destination,
        core_kernel_classes_Property $property
    ) {

        /** @var \oat\generis\model\fileReference\ResourceFileSerializer $serializer */
        $serializer = $this->getFileReferenceSerializer();

        $this->setItemModel($destination, $this->getItemModel($source));
        $sourceItemDirectory = $this->getItemDirectory($source);
        $destinationItemDirectory = $this->getItemDirectory($destination);

        foreach ($source->getPropertyValuesCollection($property)->getIterator() as $propertyValue) {

            $sourceDirectory = $serializer->unserializeDirectory($propertyValue->getUri());
            $iterator = $sourceDirectory->getFlyIterator(Directory::ITERATOR_FILE | Directory::ITERATOR_RECURSIVE);

            while ($iterator->valid()) {
                $newFile = $destinationItemDirectory->getFile($sourceItemDirectory->getRelPath($iterator->current()));
                $newFile->write($iterator->current()->readPsrStream());
                $serial = $serializer->serialize($newFile);
//                $destination->setPropertyValue($property, $serial);
                $iterator->next();
            }

        }
    }

    public function cloneInstance(core_kernel_classes_Resource $instance, core_kernel_classes_Class $clazz = null)
    {
        $result = parent::cloneInstance($instance, $clazz);
        if ($result) {
            $this->getEventManager()->trigger(new ItemDuplicatedEvent($instance->getUri(), $result->getUri()));
        }
        return $result;
    }


    public function getPreviewUrl(core_kernel_classes_Resource $item, $lang = '') {
        $itemModel = $this->getItemModel($item);
        if(is_null($itemModel)){
            return null;
        }
        return $this->getItemModelImplementation($itemModel)->getPreviewUrl($item, $lang);
    }

    /**
     * Short description of method getItemModel
     *
     * @access public
     * @author Joel Bout, <joel@taotesting.com>
     * @param  Resource item
     * @return core_kernel_classes_Resource
     */
    public function getItemModel(core_kernel_classes_Resource $item){
        $returnValue = null;

        $itemModel = $item->getOnePropertyValue($this->itemModelProperty);
        if($itemModel instanceof core_kernel_classes_Resource){
            $returnValue = $itemModel;
        }

        return $returnValue;
    }
    
    /**
     * Set the model of an item
     * 
     * @param core_kernel_classes_Resource $item
     * @param core_kernel_classes_Resource $model
     * @return boolean
     */
    public function setItemModel(core_kernel_classes_Resource $item, core_kernel_classes_Resource $model)
    {
        return $item->editPropertyValues($this->getProperty(TAO_ITEM_MODEL_PROPERTY), $model);
    }
    

    /**
     * Rertrieve current user's language from the session object to know where
     * item content should be located
     *
     * @access public
     * @author Joel Bout, <joel@taotesting.com>
     * @return string
     */
    public function getSessionLg(){

        $sessionLang = \common_session_SessionManager::getSession()->getDataLanguage();
        if(empty($sessionLang)){
            throw new Exception('the data language of the user cannot be found in session');
        }

        return (string) $sessionLang;
    }

    /**
     * Deletes the content but does not unreference it
     *
     * @access public
     * @author Joel Bout, <joel@taotesting.com>
     * @param  core_kernel_classes_Resource item
     * @return boolean
     */
    public function deleteItemContent(core_kernel_classes_Resource $item)
    {
        // Delete item directory from filesystem
        $definitonFileValues = $item->getPropertyValues($this->itemContentProperty);
        if (! empty($definitonFileValues)) {
            /** @var Directory $directory */
            $directory = $this->getFileReferenceSerializer()->unserializeDirectory(reset($definitonFileValues));
            if ($directory->exists()) {
                $directory->deleteSelf();
            }
        }

        //delete the folder for all languages!
        foreach($item->getUsedLanguages($this->itemContentProperty) as $lang){
            $files = $item->getPropertyValuesByLg($this->itemContentProperty, $lang);
            foreach($files->getIterator() as $file){
                if ($file instanceof core_kernel_classes_Resource) {
                    $this->getFileReferenceSerializer()->cleanUp($file->getUri());
                }
            }
        }

        return true;
    }

    /**
     * Short description of method getItemModelImplementation
     *
     * @access public
     * @author Joel Bout, <joel@taotesting.com>
     * @param  Resource itemModel
     * @return taoItems_models_classes_itemModel
     */
    public function getItemModelImplementation(core_kernel_classes_Resource $itemModel){
        $returnValue = null;

        $services = $itemModel->getPropertyValues($this->getProperty(PROPERTY_ITEM_MODEL_SERVICE));
        if(count($services) > 0){
            if(count($services) > 1){
                throw new common_exception_Error('Conflicting services for itemmodel '.$itemModel->getLabel());
            }
            $serviceName = (string) current($services);
            if(class_exists($serviceName) && in_array('taoItems_models_classes_itemModel', class_implements($serviceName))){
                $returnValue = new $serviceName();
            }else{
                throw new common_exception_Error('Item model service '.$serviceName.' not found, or not compatible for item model '.$itemModel->getLabel());
            }
        }else{
            common_Logger::d('No implementation for '.$itemModel->getLabel());
        }

        return $returnValue;
    }

    /**
     * Short description of method getItemFolder
     *
     * @deprecated use getItemDirectory instead
     *
     * @access public
     * @author Joel Bout, <joel@taotesting.com>
     * @param  Resource item
     * @param  string lang
     * @return string
     */
    public function getItemFolder(core_kernel_classes_Resource $item, $lang = ''){
        $returnValue = (string) '';

        if($lang === ''){
            $files = $item->getPropertyValues(new core_kernel_classes_Property(TAO_ITEM_CONTENT_PROPERTY));
        }else{
            $files = $item->getPropertyValuesByLg(new core_kernel_classes_Property(TAO_ITEM_CONTENT_PROPERTY), $lang)->toArray();
        }
        if(count($files) == 0){
            // no content found assign default
            $returnValue = $this->getDefaultItemFolder($item, $lang);
        }else{
            if(count($files) > 1){
                throw new common_Exception(__METHOD__.': Item '.$item->getUri().' has multiple.');
            }
            $content = new core_kernel_file_File(current($files));
            $returnValue = dirname($content->getAbsolutePath()).DIRECTORY_SEPARATOR;
        }

        return (string) $returnValue;
    }
    
    public function getCompilerClass(core_kernel_classes_Resource $item) {
        $itemModel = $this->getItemModel($item);
        if(is_null($itemModel)){
            throw new common_exception_Error('undefined itemmodel for test '.$item->getUri());
        }
        return $this->getItemModelImplementation($itemModel)->getCompilerClass();;
    }

    /**
     * sets the filesource to use for new items
     *
     * @access public
     * @author Joel Bout, <joel@taotesting.com>
     * @param  Repository filesource
     * @return mixed
     */
    public function setDefaultFilesource(core_kernel_versioning_Repository $filesource){
        $ext = common_ext_ExtensionsManager::singleton()->getExtensionById('taoItems');
        $ext->setConfig(self::CONFIG_DEFAULT_FILESOURCE, $filesource->getUri());
    }

    /**
     * Returns the items flysystem directory
     *
     * @param core_kernel_classes_Resource $item
     * @param string $language
     * @return \oat\oatbox\filesystem\Directory
     * @throws Exception
     * @throws common_Exception
     * @throws core_kernel_persistence_Exception
     */
    public function getItemDirectory(core_kernel_classes_Resource $item, $language = '')
    {
        // Get file by language
        if ($language === '') {
            $files = $item->getPropertyValues($this->itemContentProperty);
        } else {
            $files = $item->getPropertyValuesByLg($this->itemContentProperty, $language)->toArray();
        }

        // If multiple files then throw exception
        if (count($files) > 1) {
            common_Logger::i(print_r($files, true));
            throw new common_Exception(__METHOD__ . ': Item ' . $item->getUri() . ' has multiple.');
        }

        // If there is one file then return directory
        if (count($files) == 1) {
            return $this->getFileReferenceSerializer()->unserializeDirectory($this->getResource(current($files))->getUri());
        }

        // Otherwise there is no file, create one and return directory
        $model = $this->getItemModel($item);
        if (is_null($model)) {
            throw new common_Exception('Call to ' . __FUNCTION__ . ' for item without model');
        }

        // File does not exist, let's create it
        $actualLang = empty($language) ? $this->getSessionLg() : $language;
        $filePath = tao_helpers_Uri::getUniqueId($item->getUri())
            . DIRECTORY_SEPARATOR . 'itemContent' . DIRECTORY_SEPARATOR . $actualLang;

        // Create item directory
        $itemDirectory = $this->getDefaultItemDirectory()->getDirectory($filePath);

        // Set uri file value as serial to item persistence
        $serial = $this->getFileReferenceSerializer()->serialize($itemDirectory);

        $item->setPropertyValueByLg($this->itemContentProperty, $serial, $actualLang);

        // Store file into persistence, purpose of serializer ?
        $dataFile = (string) $model->getOnePropertyValue($this->getProperty(TAO_ITEM_MODEL_DATAFILE_PROPERTY));
        $this->getFileReferenceSerializer()->serialize($itemDirectory->getFile($dataFile));

        return $itemDirectory;
    }

    /**
     * Returns the filesource to use for new items
     *
     * @deprecated use getItemRootDirectory instead
     *
     * @return core_kernel_versioning_Repository
     * @throws common_Exception
     * @throws common_ext_ExtensionException
     */
    public function getDefaultFileSource()
    {
        $uri = common_ext_ExtensionsManager::singleton()
            ->getExtensionById('taoItems')
            ->getConfig(self::CONFIG_DEFAULT_FILESOURCE);
        if (empty($uri)) {
            throw new common_Exception('No default repository defined for Items storage.');
        }
        return new core_kernel_versioning_Repository($uri);
    }

    /**
     * Returns the defaul item directory
     * @return \oat\oatbox\filesystem\Directory
     * @throws common_ext_ExtensionException
     */
    public function getDefaultItemDirectory()
    {
        $filesystemId = common_ext_ExtensionsManager::singleton()
            ->getExtensionById('taoItems')
            ->getConfig(self::CONFIG_DEFAULT_FILESOURCE);

        return $this->getServiceManager()
            ->get(FileSystemService::SERVICE_ID)
            ->getDirectory($filesystemId);
    }

    /**
     * Get items of a specific model
     * @param string|core_kernel_classes_Resource $itemModel - the item model URI
     * @return core_kernel_classes_Resource[] the found items
     */
    public function getAllByModel($itemModel){
        if(!empty($itemModel)){
            $uri = ($itemModel instanceof core_kernel_classes_Resource) ? $itemModel->getUri() : $itemModel;
            return $this->itemClass->searchInstances(array(
                    $this->itemModelProperty->getUri() => $uri
                ), array(
                    'recursive' => true
                ));
        }
        return array();
    }

    /**
     * Get serializer to persist filesystem object
     *
     * @return FileReferenceSerializer
     */
    protected function getFileReferenceSerializer()
    {
        return $this->getServiceManager()->get(FileReferenceSerializer::SERVICE_ID);
    }
}
