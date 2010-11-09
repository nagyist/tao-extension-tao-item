<?php

error_reporting(E_ALL);

/**
 * This container initialize the qti item form:
 *
 * @author CRP Henri Tudor - TAO Team - {@link http://www.tao.lu}
 * @package tao
 * @subpackage actions_form
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/**
 * This class provide a container for a specific form instance.
 * It's subclasses instanciate a form and it's elements to be used as a
 *
 * @author CRP Henri Tudor - TAO Team - {@link http://www.tao.lu}
 */
require_once('tao/helpers/form/class.FormContainer.php');

/**
 * This container initialize the login form.
 *
 * @access public
 * @author CRP Henri Tudor - TAO Team - {@link http://www.tao.lu}
 * @package tao
 * @subpackage actions_form
 */
class taoItems_actions_QTIform_interaction_ExtendedtextInteraction
    extends taoItems_actions_QTIform_interaction_StringInteraction
{

    /**
     * Short description of method initElements
     *
     * @access public
     * @author CRP Henri Tudor - TAO Team - {@link http://www.tao.lu}
     * @return mixed
     */
    public function initElements()
    {
		$interaction = $this->getInteraction();
		
		//the prompt field is the interaction's data for a block interaction: an extend text interaction is both a "string" and a "block" interaction
		$promptElt = tao_helpers_form_FormFactory::getElement('prompt', 'Textarea');//should be a text... need to solve the conflict with the 
		$promptElt->setAttribute('class', 'qti-html-area');
		$promptElt->setDescription(__('Prompt'));
		// $promptElt->addValidator(tao_helpers_form_FormFactory::getValidator('NotEmpty'));//no validator required for prompt
		$interactionData = $this->interaction->getPrompt();
		if(!empty($interactionData)){
			$promptElt->setValue($interactionData);
		}
		$this->form->addElement($promptElt);
		
		//set common elements of string interaction:
		$this->setCommonElements();
				
		//other elements:		
		$maxStringsElt = tao_helpers_form_FormFactory::getElement('maxStrings', 'Textbox');
		$maxStringsElt->setDescription(__('Maximum number of separate strings'));
		//validator: is int??
		$maxStrings = $interaction->getOption('maxStrings');
		if(!empty($maxStrings)){
			$maxStringsElt->setValue($maxStrings);
		}
		$this->form->addElement($maxStringsElt);
		
		$expectedLinesElt = tao_helpers_form_FormFactory::getElement('expectedLines', 'Textbox');
		$expectedLinesElt->setDescription(__('Expected lines'));
		$expectedLines = $interaction->getOption('expectedLines');
		if(!empty($expectedLines)){
			$expectedLinesElt->setValue($expectedLines);
		}
		$this->form->addElement($expectedLinesElt);
    }
	
	public function setCommonElements(){
		parent::setCommonElements();
	}
}

?>