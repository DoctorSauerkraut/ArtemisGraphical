<?php
$req = CriticalitySwitch::load();

echo "<table class=\"tableShow\">";
echo "<tr><th>Time</th><th>Level</th><th>Add</th></tr>";

while($switches = $req->fetch()) {
	echo "<tr><td>".$switches["time"]."</td><td>".$switches["level"]."</td><td><img src=\"Templates/edit.png\" /></td></tr>";	
}

echo "<tr><td><input type=\"text\" id=\"critTimeText\"/></td>";
echo "<td><input type=\"text\"id=\"critLvlText\"/> </td>";
echo "<td><img src=\"Templates/add.png\" onclick=\"addCritLevel()\"/></td></tr>";
echo "</table>";
?>