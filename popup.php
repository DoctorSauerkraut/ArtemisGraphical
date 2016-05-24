<?php
	include("functions.php");
	$titre = isset($_POST["titre"]) ? $_POST["titre"]:"Popup";
	$id_sel= isset($_POST["id_sel"]) ? $_POST["id_sel"]:null;
	$popupFunction = $_POST["popupFunction"];
	
	/* Popup Head */
	$popupHead = "<div id=\"popupHead\">";
	$popupHead .= "<div id=\"popupTitle\">$titre</div>";
	$popupHead .= "<div id=\"closePopup\" onclick=\"closePopup()\"></div>";
	$popupHead .= "</div>";

	
	/* Popup content */
	$popupHead .= "<div id=\"popupBody\">";
	
	if($popupFunction == "popupConfirmSimu") {		
		if(is_dir_empty("gen/histos/") == TRUE) {
			echo "ok";
			return;
		}
		else {
			echo $popupHead;
			echo "<div id=\"popupBodyText\">There are already simulation results. Would you like to clean them ?</div>";
			echo "<a class=\"button red\" onclick=\"showSimulationResults()\" />No</a>";
			echo "<a class=\"button green\" onclick=\"generate()\" />Yes</a>";
		}
	}
	elseif($popupFunction == "confirmDelSimu"){
		echo $popupHead;
		echo "<div id=\"popupBodyText\">You're going to delete the simulation n° ".$id_sel.", would you like to continue?</div>";
		echo "<a class=\"button red\" onclick=\"closePopup()\" />No</a>";
		echo "<a class=\"button green\" onclick=\"delete_simu(".$id_sel.")\" />Yes</a>";
	}
	elseif ($popupFunction == "confirmExportSimu") {
		echo $popupHead;
		echo "<div id=\"popupBodyText\">You're going to export the simulation n° ".$id_sel.", would you like to continue?</div>";
		echo "<a class=\"button red\" onclick=\"closePopup()\" />No</a>";
		echo "<a href=\"exportsimu.php?id_sel=".$id_sel."\" class=\"button green\" onclick=\"closePopup()\" />Yes</a>";
	}
	elseif($popupFunction == "confirmImportSimu"){
		echo $popupHead;
		echo "<div id=\"popupBodyText\">You're going to import a new simulation, would you like to continue?</div>";
		echo "<a class=\"button red\" onclick=\"closePopup()\" />No</a>";
		echo "<a class=\"button green\" onclick=\"verifyFile()\" />Yes</a>";
	}
	elseif($popupFunction == "wrongFile"){
		echo $popupHead;
		echo "<div id=\"popupBodyText\">You have to select a ZIP archive exported from ARTEMIS to import a simulation, please verify the file and try again.</div>";
		echo "<a href=\"index.php\" class=\"button green\" onclick=\"closePopup()\" />Ok</a>";
	}
	else if($popupFunction == "loadingSimu") {
		$progress = 0;
		
		echo $popupHead;
		echo "Loading....";
		/*echo "<div id=\"progressbar\">";
		echo "$progress %";
		echo "</div>";*/
	}
	else if($popupFunction == "critButton") {
		echo $popupHead;
		echo "New criticality level<br />";
		
		echo "Name:<input type=\"text\" id=\"nameNCL\" />";
		echo "Code:<input type=\"text\" id=\"codeNCL\" />";
		echo "<input type=\"button\" value=\"Add\" onclick=\"addCriticalityState()\" />";	
	}
	echo "</div>";
	
?>