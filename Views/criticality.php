<?php

echo "<div class=\"tabledetailsdiv\" >";
/* Crit Switches request */
$req = CriticalitySwitch::load();
$cptSwitches = 0;
while($req->fetch()) {
	$cptSwitches++;	
}

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



echo "<a class=\"button blue\" onclick=\"saveSettings()\">Save</a>";
echo "</div>";

?>