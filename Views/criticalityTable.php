<?php
$req = CriticalitySwitch::load();
$levels = CriticalityLevel::load();

$cptLevel = 0;
while($level = $levels->fetch()) {
	$levelsTab[$cptLevel] = new CriticalityLevel($level["name"], $level["code"]);
	$cptLevel++;
}

if($cptLevel ==0) {
	echo "Not any criticality level defined. Please define one in the messages tab.";
	return;
}

echo "<table class=\"tableShow\">";
echo "<tr><th>Time</th><th>Level</th><th>Add</th></tr>";

while($switches = $req->fetch()) {
	echo "<tr><td>".$switches["time"]."</td><td>".$switches["level"]."</td><td><img src=\"Templates/delete.png\" /></td></tr>";	
}

echo "<tr><td><input type=\"text\" id=\"critTimeText\"/></td>";
echo "<td><select id=\"critLvlText\">";
for($cptOptions=0;$cptOptions<$cptLevel;$cptOptions++) {
	echo "<option value=\"".$levelsTab[$cptOptions]->getCode()."\">".$levelsTab[$cptOptions]->getName()."</option>";
}
echo "</select></td>";
echo "<td><img src=\"Templates/add.png\" onclick=\"addCritLevel()\"/></td></tr>";
echo "</table>";
?>