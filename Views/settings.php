<?php 

echo "Welcome to ARTEMIS settings";

echo "<form method=\"post\" action=\"#\">";
	echo "Global scheduling policy:";

	echo "<select>";
		echo "<option>FIFO</option>";
		echo "<option>FIFO*</option>";
		echo "<option>FP</option>";
	echo "</select>";


echo "</form>";

echo "Simulation time:<input type=\"text\" id=\"timelimit\" value=\"".Settings::getParameter("timelimit")."\"/>ms<br />";
echo "Electronical latency:<input type=\"text\" id=\"elatency\" value=\"".Settings::getParameter("elatency")."\"/>ms<br />";

/* Crit Switches request */
$req = CriticalitySwitch::load();
$cptSwitches = 0;
while($req->fetch()) {
	$cptSwitches++;	
}

echo "Automatic task-generation: <input type=\"radio\" name=\"radiotask\" checked/> No <input type=\"radio\" name=\"radiotask\" /> Yes<br />";

/* Auto-check MC option */
echo "Mixed-criticality management: <input type=\"radio\" name=\"radiomc\" ";
if($cptSwitches == 0) {
	echo "checked />No";
}
else {
	echo " />No";
}

echo "<input type=\"radio\" name=\"radiomc\" onclick=\"displayCriticalityTable()\"";
if($cptSwitches != 0) {
	echo "checked /> Yes<br />";
	echo "<div id=\"critTableDiv\">";
	include("criticalityTable.php");
	echo "</div>";
}
else {
	echo " /> Yes<br />";
	echo "<div id=\"critTableDiv\"></div>";
}



echo "<input type=\"button\" value=\"save\" onclick=\"saveSettings()\" />";


?>