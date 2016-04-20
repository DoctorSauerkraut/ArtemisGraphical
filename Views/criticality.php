<?php

echo "<div class=\"tabledetailsdiv\" >";
/* Crit Switches request */
$req = CriticalitySwitch::load($simuKey);
$cptSwitches = 0;
while($req->fetch()) {
	$cptSwitches++;	
}

echo "Mixed-criticality management";

echo "<div id=\"critTableDiv\">";
include("criticalityTable.php");
echo "</div>";

echo "</div>";

?>