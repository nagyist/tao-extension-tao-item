<?php

error_reporting(E_ALL);

/**
 * Generis Object Oriented API - taoItems/models/classes/class.ItemsService.php
 *
 * $Id$
 *
 * This file is part of Generis Object Oriented API.
 *
 * Automatically generated on 19.11.2009, 11:16:25 with ArgoUML PHP module 
 * (last revised $Date: 2008-04-19 08:22:08 +0200 (Sat, 19 Apr 2008) $)
 *
 * @author Bertrand Chevrier, <chevrier.bertrand@gmail.com>
 * @package taoItems
 * @subpackage models_classes
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/**
 * The Service class is an abstraction of each service instance. 
 * Used to centralize the behavior related to every servcie instances.
 *
 * @author Bertrand Chevrier, <chevrier.bertrand@gmail.com>
 */
require_once('tao/models/classes/class.Service.php');

/* user defined includes */
// section 10-13-1-45-792423e0:12398d13f24:-8000:00000000000017BE-includes begin
// section 10-13-1-45-792423e0:12398d13f24:-8000:00000000000017BE-includes end

/* user defined constants */
// section 10-13-1-45-792423e0:12398d13f24:-8000:00000000000017BE-constants begin
// section 10-13-1-45-792423e0:12398d13f24:-8000:00000000000017BE-constants end

/**
 * Short description of class taoItems_models_classes_ItemsService
 *
 * @access public
 * @author Bertrand Chevrier, <chevrier.bertrand@gmail.com>
 * @package taoItems
 * @subpackage models_classes
 */
class taoItems_models_classes_ItemsService
    extends tao_models_classes_Service
{
    // --- ASSOCIATIONS ---


    // --- ATTRIBUTES ---

    /**
     * Short description of attribute itemClass
     *
     * @access protected
     * @var Class
     */
    protected $itemClass = null;

    /**
     * Short description of attribute itemsOntologies
     *
     * @access protected
     * @var array
     */
    protected $itemsOntologies = array('http://www.tao.lu/Ontologies/TAOItem.rdf');

    // --- OPERATIONS ---

    /**
     * Short description of method __construct
     *
     * @access public
     * @author Bertrand Chevrier, <chevrier.bertrand@gmail.com>
     * @return void
     */
    public function __construct()
    {
        // section 10-13-1-45--20a3dc13:1239ebd775d:-8000:0000000000001897 begin
		
		parent::__construct();
		$this->itemClass			= new core_kernel_classes_Class( TAO_ITEM_CLASS );
		$this->loadOntologies($this->itemsOntologies);
		
        // section 10-13-1-45--20a3dc13:1239ebd775d:-8000:0000000000001897 end
    }

    /**
     * access to the top level Item class
     *
     * @access public
     * @author Bertrand Chevrier, <chevrier.bertrand@gmail.com>
     * @param  string uri
     * @return core_kernel_classes_Class
     */
    public function getItemClass($uri = '')
    {
        $returnValue = null;

        // section 127-0-1-1--5cd530d7:1249feedb80:-8000:0000000000001AE4 begin
		
		
		if(empty($uri) && !is_null($this->itemClass)){
			$returnValue= $this->itemClass;
		}
		else{
			$clazz = new core_kernel_classes_Class($uri);
			if($this->isItemClass($clazz)){
				$returnValue = $clazz;
			}
		}
		
        // section 127-0-1-1--5cd530d7:1249feedb80:-8000:0000000000001AE4 end

        return $returnValue;
    }

    /**
     * Short description of method isItemClass
     *
     * @access public
     * @author Bertrand Chevrier, <chevrier.bertrand@gmail.com>
     * @param  Class clazz
     * @return boolean
     */
    public function isItemClass( core_kernel_classes_Class $clazz)
    {
        $returnValue = (bool) false;

        // section 127-0-1-1-4cd2d1f1:124910fbd83:-8000:0000000000001AD2 begin
		
		foreach($clazz->getParentClasses(true) as $parent){
			if($parent->uriResource == $this->itemClass->uriResource){
				$returnValue = true;
				break;
			}
		}
		
        // section 127-0-1-1-4cd2d1f1:124910fbd83:-8000:0000000000001AD2 end

        return (bool) $returnValue;
    }

    /**
     * Short description of method getItem
     *
     * @access public
     * @author Bertrand Chevrier, <chevrier.bertrand@gmail.com>
     * @param  string identifier
     * @param  Class itemClazz
     * @param  string mode
     * @return core_kernel_classes_Resource
     */
    public function getItem($identifier,  core_kernel_classes_Class $itemClazz = null, $mode = 'uri')
    {
        $returnValue = null;

        // section 10-13-1-45-792423e0:12398d13f24:-8000:0000000000001815 begin
		if(is_null($itemClazz)){
			$itemClazz = $this->itemClass;
		}
		if($itemClazz->uriResource != $this->itemClass->uriResource){
			if(!$this->isItemClass($itemClazz)){
				throw new Exception("The item class is not a valid item sub class");
			}
		}
		$returnValue = $this->getOneInstanceBy( $itemClazz, $identifier, $mode);
		
        // section 10-13-1-45-792423e0:12398d13f24:-8000:0000000000001815 end

        return $returnValue;
    }

    /**
     * Short description of method getItems
     *
     * @access public
     * @author Bertrand Chevrier, <chevrier.bertrand@gmail.com>
     * @param  array options
     * @return core_kernel_classes_ContainerCollection
     */
    public function getItems($options = array())
    {
        $returnValue = null;

        // section 10-13-1-45-792423e0:12398d13f24:-8000:00000000000017B5 begin
		
		$returnValue = $this->itemClass->getInstances();
		
        // section 10-13-1-45-792423e0:12398d13f24:-8000:00000000000017B5 end

        return $returnValue;
    }

    /**
     * Short description of method createItem
     *
     * @access public
     * @author Bertrand Chevrier, <chevrier.bertrand@gmail.com>
     * @param  string itemModel
     * @param  string itemContent
     * @return core_kernel_classes_Resource
     */
    public function createItem($itemModel = null, $itemContent = '')
    {
        $returnValue = null;

        // section 10-13-1-45--20a3dc13:1239ebd775d:-8000:000000000000186A begin
		
		if(is_string($itemModel)){
			$itemModel = $this->getItemModel(
				$itemModel, 
				(strpos($itemModel, '#') === false) ? 'label' : 'uri'
			);
		}
		if( ! $itemModel instanceof core_kernel_classes_Resource || is_null($itemModel) ){
			throw new Exception("itemModel instance is a mandatory property to create a new item");
		}
		
		$itemInstance = core_kernel_classes_ResourceFactory::create(
			$this->itemClass,
			'item_' . ($this->itemClass->getInstances()->count() + 1),
			'item created from ' . get_class($this) . ' the '. date('Y-m-d h:i:s') 
		);
		
		$itemInstance->setPropertyValue(
			$this->itemModelProperty,
			$itemModel->uriRessource
		);
		
		$itemInstance->setPropertyValue(
			$this->itemContentProperty,
			$itemContent
		);
		
		$returnValue = $itemInstance;
		
        // section 10-13-1-45--20a3dc13:1239ebd775d:-8000:000000000000186A end

        return $returnValue;
    }

    /**
     * Short description of method deleteItem
     *
     * @access public
     * @author Bertrand Chevrier, <chevrier.bertrand@gmail.com>
     * @param  Resource item
     * @return boolean
     */
    public function deleteItem( core_kernel_classes_Resource $item)
    {
        $returnValue = (bool) false;

        // section 10-13-1-45-792423e0:12398d13f24:-8000:00000000000017BB begin
		
		if(!is_null($item)){
			$returnValue = $item->delete();
		}
		
        // section 10-13-1-45-792423e0:12398d13f24:-8000:00000000000017BB end

        return (bool) $returnValue;
    }

    /**
     * Short description of method deleteItemClass
     *
     * @access public
     * @author Bertrand Chevrier, <chevrier.bertrand@gmail.com>
     * @param  Class clazz
     * @return boolean
     */
    public function deleteItemClass( core_kernel_classes_Class $clazz)
    {
        $returnValue = (bool) false;

        // section 127-0-1-1-4cd2d1f1:124910fbd83:-8000:0000000000001ACF begin
		
		if(!is_null($clazz)){
			if($this->isItemClass($clazz)){
				$returnValue = $clazz->delete();
			}
		}
        // section 127-0-1-1-4cd2d1f1:124910fbd83:-8000:0000000000001ACF end

        return (bool) $returnValue;
    }

    /**
     * Short description of method getAuthoringFileUriById
     *
     * @access public
     * @author Bertrand Chevrier, <chevrier.bertrand@gmail.com>
     * @param  string id
     * @return string
     */
    public function getAuthoringFileUriById($id)
    {
        $returnValue = (string) '';

        // section 127-0-1-1-188be92e:12507f7441c:-8000:0000000000001B79 begin
		
		if(strlen($id) > 0){
			if(preg_match("/^[0-9a-f]{12,16}$/", $id)){
				$returnValue = TAO_ITEM_AUTHORING_BASE_URI.'/'.$id.'.xml';
			}
		}
        // section 127-0-1-1-188be92e:12507f7441c:-8000:0000000000001B79 end

        return (string) $returnValue;
    }

    /**
     * Short description of method getAuthoringFileIdByUri
     *
     * @access public
     * @author Bertrand Chevrier, <chevrier.bertrand@gmail.com>
     * @param  string uri
     * @return string
     */
    public function getAuthoringFileIdByUri($uri)
    {
        $returnValue = (string) '';

        // section 127-0-1-1-188be92e:12507f7441c:-8000:0000000000001B7D begin
		if(strlen($uri) > 0){
			if(file_exists($uri)){
				$returnValue = str_replace(TAO_ITEM_AUTHORING_BASE_URI.'/', '',
					str_replace('.xml', '', $uri)
				);
			}
		}
        // section 127-0-1-1-188be92e:12507f7441c:-8000:0000000000001B7D end

        return (string) $returnValue;
    }

    /**
     * Short description of method getAuthoringFile
     *
     * @access public
     * @author Bertrand Chevrier, <chevrier.bertrand@gmail.com>
     * @param  string id
     * @return array
     */
    public function getAuthoringFile($id = '')
    {
        $returnValue = array();

        // section 127-0-1-1-34d7bcb9:1250bcb34b1:-8000:0000000000001B6E begin
		if(strlen($id) == 0){
			$id = uniqid();
		}
		$uri = $this->getAuthoringFileUriById($id);
		
		if(!file_exists($uri)){
			file_put_contents($uri, '');
		}
		$returnValue = array(
			'id'	=> $id,
			'uri'	=> $uri
		);
		
        // section 127-0-1-1-34d7bcb9:1250bcb34b1:-8000:0000000000001B6E end

        return (array) $returnValue;
    }

} /* end of class taoItems_models_classes_ItemsService */

?>