<?php
define("LGFR",

"<TAO:LABELS>
		<!-- ----------------------------------------------------------------- -->
		<!-- ---------    Messages de la fenêtre des variables    ------------ -->
		<!-- ----------------------------------------------------------------- -->
		<!-- Titre de la première colonne de la partie variable -->
		<TAO:LABEL key=\"variableNameHeader\">Comment</TAO:LABEL>
		<!-- Titre de la colonne contenant les valeurs de la partie variable -->
		<TAO:LABEL key=\"variableValueHeader\">Value</TAO:LABEL>
		<!-- Titre de la colonne contenant les unités de la partie variable -->
		<TAO:LABEL key=\"variableUnitHeader\">Unit</TAO:LABEL>
		<!-- Erreur rencontrée lors du chargement du fichier décrivant l'item -->
		<TAO:LABEL key=\"loadFileErr\">The XML file could not be loaded.</TAO:LABEL>
		<!-- Label d'un événement lorsque le sujet essaies d'ajouter une valeur dans le tableau des variables  -->		
		<!-- ----------------------------------------------------------------- -->
		<!-- ---------    Messages liés à l'ajout d'une valeur    ------------ -->
		<!-- ----------------------------------------------------------------- -->
		<TAO:LABEL key=\"testValueTitle\">Testing the value.</TAO:LABEL>
		<TAO:LABEL key=\"testValueOK\">OK</TAO:LABEL>
		<TAO:LABEL key=\"testValueErr01\">You didn't select a value in the text.</TAO:LABEL>
		<TAO:LABEL key=\"testValueErr02\">The selected value does not match the unit.</TAO:LABEL>
		<TAO:LABEL key=\"testValueErr03\">You selected values AND units in the text.</TAO:LABEL>
		<TAO:LABEL key=\"testValueErr04\">You selected more tha one value simultaneously.</TAO:LABEL>
		<!-- ----------------------------------------------------------------- -->
		<!-- ---------    Messages liés à l'ajout d'une unité     ------------ -->
		<!-- ----------------------------------------------------------------- -->
		<TAO:LABEL key=\"testUnitTitle\">Testing the unit.</TAO:LABEL>
		<TAO:LABEL key=\"testUnitOK\">OK</TAO:LABEL>		
		<TAO:LABEL key=\"testUnitErr01\">You didn't select a unit in the text.</TAO:LABEL>
		<TAO:LABEL key=\"testUnitErr02\">The selected unit does not match the value.</TAO:LABEL>
		<TAO:LABEL key=\"testUnitErr03\">You selected values AND units in the text.</TAO:LABEL>
		<!-- ----------------------------------------------------------------- -->
		<!-- ---------        Test de réponse intermédiaire       ------------ -->
		<!-- ----------------------------------------------------------------- -->
		<TAO:LABEL key=\"testSolEventLabel\">Testing the intermediate result</TAO:LABEL>		
		<TAO:LABEL key=\"testSolErr01\">Please, choose another unit !</TAO:LABEL>
		<TAO:LABEL key=\"testSolErr02\">Wrong result.</TAO:LABEL>
		<TAO:LABEL key=\"testSolOK\">Correct !</TAO:LABEL>
		<!-- ----------------------------------------------------------------- -->
		<!-- ---------          Test de réponse finale            ------------ -->
		<!-- ----------------------------------------------------------------- -->
		<TAO:LABEL key=\"testFinalEventLabel\">Testing the end result</TAO:LABEL>		
		<TAO:LABEL key=\"testFinalErr\">Wring result; please try again !</TAO:LABEL>		
		<TAO:LABEL key=\"testFinalOK\">Congratulations ! You've got it right !</TAO:LABEL>
		<!-- ----------------------------------------------------------------- -->
		<!-- ----   Ajout d'une variable sur l'axe  et opérations   -------- -->
		<!-- ----------------------------------------------------------------- -->
		
		<TAO:LABEL key=\"biggerValueError\">The selected value is bigger than the number line ; please extend it !</TAO:LABEL>
		<TAO:LABEL key=\"smallerValueError\">The selected value is smaller than the number line ; please change it !</TAO:LABEL>
		<TAO:LABEL key=\"unitError\">Unit does not match</TAO:LABEL>
		<TAO:LABEL key=\"addValueOK\">OK</TAO:LABEL>
		<TAO:LABEL key=\"addValueEventLabel\">The first number will be added to the number line.</TAO:LABEL>
		<TAO:LABEL key=\"addSecondValueEventLabel\">The second number will be added to the number line.</TAO:LABEL>
		<TAO:LABEL key=\"addOperationEventLabel\">Choose a calculation method.</TAO:LABEL>
		<!-- ----------------------------------------------------------------- -->
		<!-- ---------             Changement d'échelle           ------------ -->
		<!-- ----------------------------------------------------------------- -->
		<TAO:LABEL key=\"scaleChangeErr01\">The lowest value cannot be higher than the maximum: please change the number line</TAO:LABEL>
		<TAO:LABEL key=\"scaleChangeErr02\">The selected range does not include any of the values present on the number line.</TAO:LABEL>
		<TAO:LABEL key=\"scaleChangeOK\">OK</TAO:LABEL>
		<TAO:LABEL key=\"scaleChangeEventLabel\">Modifying the number line</TAO:LABEL>
		<!-- ----------------------------------------------------------------- -->
		<!-- ---------      Fenetre d'affichage de message        ------------ -->
		<!-- ----------------------------------------------------------------- -->
		<TAO:LABEL key=\"messageWindowTitle\">Message from CAMPUS</TAO:LABEL>
		<TAO:LABEL key=\"messageWindowText\">Message :</TAO:LABEL>
		<TAO:LABEL key=\"messageWindowButton\">OK !</TAO:LABEL>	
		<!-- ----------------------------------------------------------------- -->
		<!-- ---------      Fenetre de choix d'une variable       ------------ -->
		<!-- ----------------------------------------------------------------- -->
		<TAO:LABEL key=\"variableChoiceWindowTitle\">Choosing a value</TAO:LABEL>
		<TAO:LABEL key=\"variableChoiceWindowText\">Please choose :</TAO:LABEL>
		<TAO:LABEL key=\"variableChoiceWindowButton\">Select</TAO:LABEL>
		<!-- ----------------------------------------------------------------- -->
		<!-- ---------     Fenetre de choix d'une solution        ------------ -->
		<!-- ----------------------------------------------------------------- -->		
		<TAO:LABEL key=\"testSolWindowTitle\">Entering the solution</TAO:LABEL>
		<TAO:LABEL key=\"solChoiceWindowValue\">Value :</TAO:LABEL>
		<TAO:LABEL key=\"solChoiceWindowUnit\">Unit :</TAO:LABEL>
		<TAO:LABEL key=\"solChoiceWindowButton\">OK !</TAO:LABEL>
		<!-- ----------------------------------------------------------------- -->
		<!-- ---------     Fenetre de changement d'échelle        ------------ -->
		<!-- ----------------------------------------------------------------- -->
		<TAO:LABEL key=\"scaleChoiceWindowTitle\">Choosing a scale</TAO:LABEL>
		<TAO:LABEL key=\"scaleChoiceWindowButton\">OK !</TAO:LABEL>			
		<TAO:LABEL key=\"scaleChoiceWindowText\">Range of the number line :</TAO:LABEL>
		<TAO:LABEL key=\"scaleChoiceWindowMin\">Min</TAO:LABEL>
		<TAO:LABEL key=\"scaleChoiceWindowMax\">Max</TAO:LABEL>
		<!-- ----------------------------------------------------------------- -->
		<!-- ---------              Fenetre d'erreur              ------------ -->
		<!-- ----------------------------------------------------------------- -->
		<TAO:LABEL key=\"errorWindowTitle\">CAMPUS Error !</TAO:LABEL>
		<TAO:LABEL key=\"errorWindowButton\">OK !</TAO:LABEL>			
		<TAO:LABEL key=\"errorWindowText\">Message</TAO:LABEL>	
		<!-- ----------------------------------------------------------------- -->
		<!-- ---------       Boutons  et Titre Fenêtre de calcul            ------------ -->
		<!-- ----------------------------------------------------------------- -->
		<TAO:LABEL key=\"WindowTitle\">Number line</TAO:LABEL>
		<TAO:LABEL key=\"btnValider\">Confirm</TAO:LABEL>
		<TAO:LABEL key=\"btnScale\">Change scale</TAO:LABEL>			
		<TAO:LABEL key=\"btnReset\">Cancel all</TAO:LABEL>	
		<TAO:LABEL key=\"btnResetNext\">New step</TAO:LABEL>
		<!-- ----------------------------------------------------------------- -->
		<!-- ---------          Fenêtre récapitulatif             ------------ -->
		<!-- ----------------------------------------------------------------- -->
		<TAO:LABEL key=\"operationTxt\">Calculation method</TAO:LABEL>
		<TAO:LABEL key=\"recapWindowTitle\">Calculation list</TAO:LABEL>
		<!-- ----------------------------------------------------------------- -->
		<!-- ---------          Fenêtre Choix Final             ------------ -->
		<!-- ----------------------------------------------------------------- -->
		<TAO:LABEL key=\"finalChoiceWindowTitle\">Final result</TAO:LABEL>
		<TAO:LABEL key=\"finalChoiceWindowText\">Choose the final result !</TAO:LABEL>
		<TAO:LABEL key=\"finalChoiceWindowButton\">Select</TAO:LABEL>				
	</TAO:LABELS>"
, true);
?>