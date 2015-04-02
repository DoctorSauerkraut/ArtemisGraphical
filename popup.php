<?php
	include("functions.php");

	$titre = isset($_POST["titre"]) ? $_POST["titre"]:"Popup";
	$popupFunction = $_POST["popupFunction"];
	
	/* Popup Head */
	echo "<div id=\"popupHead\">";
	echo "<div id=\"popupTitle\">$titre</div>";
	echo "<div id=\"closePopup\" onclick=\"closePopup()\"></div>";
	echo "</div>";

	
	/* Popup content */
	echo "<div id=\"popupBody\">";
	if($popupFunction == "popupConfirmSimu") {		
		if(is_dir_empty("gen/histos/") == TRUE) {
			echo "ok";
		}
		else {
			echo "<div id=\"popupBodyText\">There are already simulation results. Would you like to clean them ?</div>";
			echo "<a class=\"button red\" onclick=\"showSimulationResults()\" />No</a>";
			echo "<a class=\"button green\" onclick=\"generate()\" />Yes</a>";
		}
	}
	else if($popupFunction == "loadingSimu") {
		echo "Loading....";
	}
	else if($popupFunction == "critButton") {
		echo "New criticality level<br />";
		
		echo "Name:<input type=\"text\" id=\"nameNCL\" />";
		echo "Code:<input type=\"text\" id=\"codeNCL\" />";
		echo "<input type=\"button\" value=\"Add\" onclick=\"addCriticalityState()\" />";	
	}
	echo "</div>";
	
?>