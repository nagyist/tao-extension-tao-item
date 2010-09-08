<?php

error_reporting(E_ALL);

/**
 * TAO - taoItems/actions/form/class.ItemContentIO.php
 *
 * $Id$
 *
 * This file is part of TAO.
 *
 * Automatically generated on 08.09.2010, 16:23:32 with ArgoUML PHP module 
 * (last revised $Date: 2010-01-12 20:14:42 +0100 (Tue, 12 Jan 2010) $)
 *
 * @author Bertrand Chevrier, <bertrand.chevrier@tudor.lu>
 * @package taoItems
 * @subpackage actions_form
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/**
 * Create a form from a  resource of your ontology. 
 * Each property will be a field, regarding it's widget.
 *
 * @author Bertrand Chevrier, <bertrand.chevrier@tudor.lu>
 */
require_once('tao/actions/form/class.Instance.php');

/* user defined includes */
// section 127-0-1-1-7c161ae7:12af1a41c59:-8000:0000000000002595-includes begin
// section 127-0-1-1-7c161ae7:12af1a41c59:-8000:0000000000002595-includes end

/* user defined constants */
// section 127-0-1-1-7c161ae7:12af1a41c59:-8000:0000000000002595-constants begin
// section 127-0-1-1-7c161ae7:12af1a41c59:-8000:0000000000002595-constants end

/**
 * Short description of class taoItems_actions_form_ItemContentIO
 *
 * @access public
 * @author Bertrand Chevrier, <bertrand.chevrier@tudor.lu>
 * @package taoItems
 * @subpackage actions_form
 */
class taoItems_actions_form_ItemContentIO
    extends tao_actions_form_Instance
{
    // --- ASSOCIATIONS ---


    // --- ATTRIBUTES ---

    // --- OPERATIONS ---

    /**
     * Short description of method initForm
     *
     * @access public
     * @author Bertrand Chevrier, <bertrand.chevrier@tudor.lu>
     * @return mixed
     */
    public function initForm()
    {
        // section 127-0-1-1-7c161ae7:12af1a41c59:-8000:0000000000002596 begin
        
    	(isset($this->options['name'])) ? $name = $this->options['name'] : $name = ''; 
    	if(empty($name)){
			$name = 'form_'.(count(self::$forms)+1);
		}
		unset($this->options['name']);
			
		$this->form = tao_helpers_form_FormFactory::getForm($name, $this->options);
    	
        // section 127-0-1-1-7c161ae7:12af1a41c59:-8000:0000000000002596 end
    }

    /**
     * Short description of method initElements
     *
     * @access public
     * @author Bertrand Chevrier, <bertrand.chevrier@tudor.lu>
     * @return mixed
     */
    public function initElements()
    {
        // section 127-0-1-1-7c161ae7:12af1a41c59:-8000:0000000000002598 begin
        
    	
    	try{
			$itemContent = (string)$this->instance->getUniquePropertyValue(new core_kernel_classes_Property(TAO_ITEM_CONTENT_PROPERTY));
				
			if(!empty($itemContent)){

				$downloadUrl = _url('getItemContent', null, null, array(
						'uri' 		=> tao_helpers_Uri::encode($this->instance->uriResource),
						'classUri' 	=> tao_helpers_Uri::encode($this->clazz->uriResource)
				));
				
				$downloadFileElt = tao_helpers_form_FormFactory::getElement("file_download", 'Free');
				$downloadFileElt->setValue("<a href='$downloadUrl' target='_blank'>".__('download item content')."</a>");
				$this->form->addElement($downloadFileElt);
				
				$this->form->createGroup('i1', 'Download', array($downloadFileElt->getName()));
			}
		}
		catch(common_Exception $ce){}
    	
    	$importFileElt = tao_helpers_form_FormFactory::getElement("file_import", 'AsyncFile');
		$importFileElt->setDescription(__("Upload an item content file (XML format required)"));
		$importFileElt->addValidator(
			tao_helpers_form_FormFactory::getValidator(
				"FileMimeType", 
				array(
					'mimetype' => 'application/xml', 
					'extension' => array('xml', 'qti'))
				)
		);
		$this->form->addElement($importFileElt);
		
		$this->form->createGroup('i2', 'Import',  array($importFileElt->getName()));
			
    	//add an hidden elt for the class uri
		$classUriElt = tao_helpers_form_FormFactory::getElement('classUri', 'Hidden');
		$classUriElt->setValue(tao_helpers_Uri::encode($this->clazz->uriResource));
		$this->form->addElement($classUriElt);
			
		if(!is_null($this->instance)){
			//add an hidden elt for the instance Uri
			$instanceUriElt = tao_helpers_form_FormFactory::getElement('uri', 'Hidden');
			$instanceUriElt->setValue(tao_helpers_Uri::encode($this->instance->uriResource));
			$this->form->addElement($instanceUriElt);
		}
    	
        // section 127-0-1-1-7c161ae7:12af1a41c59:-8000:0000000000002598 end
    }

} /* end of class taoItems_actions_form_ItemContentIO */

?>