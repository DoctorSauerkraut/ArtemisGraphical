<?php
	$popupFunction = $_POST["popupFunction"];
	include("functions.php");

	if($popupFunction == "popupConfirmSimu") {		
		if(is_dir_empty("gen/histos/") == TRUE) {
			echo "ok";
		}
		else {
			echo "There are already simulation results. Would you like to clean them ?";
			echo "<input type=\"button\" value=\"No\" onclick=\"showSimulationResults()\" />";
			echo "<input type=\"button\" value=\"Yes\" onclick=\"generate()\" />";
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
	
?>