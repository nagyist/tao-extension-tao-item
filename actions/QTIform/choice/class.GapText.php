<?php

error_reporting(E_ALL);

/**
 * This container initialize the qti choice form:
 *
 * @author CRP Henri Tudor - TAO Team - {@link http://www.tao.lu}
 * @package tao
 * @subpackage actions_form
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/**
 *
 * @access public
 * @author CRP Henri Tudor - TAO Team - {@link http://www.tao.lu}
 * @package taoItems
 * @subpackage actions_form
 */
class taoItems_actions_QTIform_choice_GapText
    extends taoItems_actions_QTIform_choice_AssociableChoice{
	
	public function initElements(){
		
		parent::setCommonElements();
		
		//add textbox:
		$dataElt = tao_helpers_form_FormFactory::getElement('data', 'Textbox');
		$dataElt->setDescription(__('Value'));
		$choiceData = $this->choice->getData();
		if(!empty($choiceData)){
			$dataElt->setValue($choiceData);
		}
		$this->form->addElement($dataElt);
		
		$this->form->createGroup('choicePropOptions_'.$this->choice->getSerial(), __('Advanced properties'), array('fixed', 'matchGroup'));
	}
	
	public function getMatchGroupOptions(){
	
		$options = array();
		
		foreach($this->interaction->getGroups() as $group){
			$options[$group->getSerial()] = $group->getIdentifier();
		}
		
		return $options;
		
	}

}

?>