<?php

error_reporting(E_ALL);

/**
 * TAO -
 *
 * $Id$
 *
 * This file is part of TAO.
 *
 * Automatically generated on 24.01.2012, 17:13:08 with ArgoUML PHP module 
 * (last revised $Date: 2010-01-12 20:14:42 +0100 (Tue, 12 Jan 2010) $)
 *
 * @author Joel Bout, <joel.bout@tudor.lu>
 * @package taoItems
 * @subpackage models_classes_QTI_response_interactionResponseProcessing
 */

if (0 > version_compare(PHP_VERSION, '5')) {
    die('This file was generated for PHP 5');
}

/**
 * include
 *
 * @author Joel Bout, <joel.bout@tudor.lu>
 */
require_once('taoItems/models/classes/QTI/response/interactionResponseProcessing/class.Template.php');

/* user defined includes */
// section 127-0-1-1-786830e4:134f066fb13:-8000:0000000000009009-includes begin
// section 127-0-1-1-786830e4:134f066fb13:-8000:0000000000009009-includes end

/* user defined constants */
// section 127-0-1-1-786830e4:134f066fb13:-8000:0000000000009009-constants begin
// section 127-0-1-1-786830e4:134f066fb13:-8000:0000000000009009-constants end

/**
 * Short description of class
 *
 * @access public
 * @author Joel Bout, <joel.bout@tudor.lu>
 * @package taoItems
 * @subpackage models_classes_QTI_response_interactionResponseProcessing
 */
class taoItems_models_classes_QTI_response_interactionResponseProcessing_MapResponseTemplate
    extends taoItems_models_classes_QTI_response_interactionResponseProcessing_Template
{
    // --- ASSOCIATIONS ---


    // --- ATTRIBUTES ---

    /**
     * Short description of attribute CLASS_ID
     *
     * @access public
     * @var string
     */
    const CLASS_ID = 'map';

    // --- OPERATIONS ---

    /**
     * Short description of method getRule
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @return string
     */
    public function getRule()
    {
        $returnValue = (string) '';

        // section 127-0-1-1-786830e4:134f066fb13:-8000:0000000000009013 begin
        $returnValue = 'if(isNull(null, getResponse("'.$this->getResponse()->getIdentifier().'"))) { '.
        	'setOutcomeValue("'.$this->getOutcome()->getIdentifier().'", 0); } else { '.
        	'setOutcomeValue("'.$this->getOutcome()->getIdentifier().'", '.
        		'mapResponse(null, getMap("'.$this->getResponse()->getIdentifier().'"), getResponse("'.$this->getResponse()->getIdentifier().'"))); }';
        // section 127-0-1-1-786830e4:134f066fb13:-8000:0000000000009013 end

        return (string) $returnValue;
    }

    /**
     * Short description of method toQTI
     *
     * @access public
     * @author Joel Bout, <joel.bout@tudor.lu>
     * @return string
     */
    public function toQTI()
    {
        $returnValue = (string) '';

        // section 127-0-1-1-4c0a0972:134fa47975d:-8000:000000000000362A begin
        $returnValue = '<responseCondition>
		    <responseIf>
		        <not>
		            <isNull>
		                <variable identifier="'.$this->getResponse()->getIdentifier().'" />
		            </isNull>
		        </not>
		        <setOutcomeValue identifier="'.$this->getOutcome()->getIdentifier().'">
	                <mapResponse identifier="'.$this->getResponse()->getIdentifier().'" />
		        </setOutcomeValue>
		    </responseIf>
		</responseCondition>';
        // section 127-0-1-1-4c0a0972:134fa47975d:-8000:000000000000362A end

        return (string) $returnValue;
    }

} /* end of class taoItems_models_classes_QTI_response_interactionResponseProcessing_MapResponseTemplate */

?>