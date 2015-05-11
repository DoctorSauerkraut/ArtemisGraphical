<?php
	include("functions.php");

	$titre = isset($_POST["titre"]) ? $_POST["titre"]:"Popup";
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
	else if($popupFunction == "loadingSimu") {
		echo $popupHead;
		echo "Loading....";
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