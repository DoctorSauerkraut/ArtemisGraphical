<?php
$req = CriticalitySwitch::load();

echo "<table class=\"tableShow\">";
echo "<tr><th>Time</th><th>Level</th></tr>";

while($switches = $req->fetch()) {
	echo "<tr><td>".$switches["time"]."</td><td>".$switches["level"]."</td></tr>";	
}

echo "</table>";
?>