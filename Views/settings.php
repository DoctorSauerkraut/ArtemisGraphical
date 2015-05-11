<?php 

echo "<div class=\"tabledetailsdiv\" >";
echo "Welcome to ARTEMIS settings<br />";

/*echo "<form method=\"post\" action=\"#\">";
	echo "Global scheduling policy:";

	echo "<select>";
		echo "<option>FIFO</option>";
		echo "<option>FIFO*</option>";
		echo "<option>FP</option>";
	echo "</select>";


echo "</form>";*/

echo "<table class=\"tableShow\">";
echo "<tr><td>Parameter</td><td>Value</td></tr>";

echo "<tr><td>Simulation Time</td>";
echo "<td><input type=\"text\" id=\"timelimit\" value=\"".Settings::getParameter("timelimit")."\"/> ms</td></tr>";

echo "<tr><td>Electronical latency</td>";
echo "<td><input type=\"text\" id=\"elatency\" value=\"".Settings::getParameter("elatency")."\"/> ms</td></tr>";

echo "<tr><td>Automatic task generation</td>";
echo "<td><input type=\"radio\" name=\"radiotask\" checked/> ";
echo "No <input type=\"radio\" name=\"radiotask\" /> Yes</td></tr>";
echo "<tr><td>Tasks</td>";
echo "<td><input type=\"text\" id=\"autotasks\" value=\"".Settings::getParameter("autotasks")."\"/> tasks</td></td></tr>";

echo "<tr><td>Highest WCET</td>";
echo "<td><input type=\"text\" id=\"highestwcet\" value=\"".Settings::getParameter("highestwcet")."\"/> ms</td></td></tr>";
echo "</table>";

echo "<br /><a class=\"button blue\" onclick=\"saveSettings()\">Save</a>";

echo "</div>";

?>