<?php 

echo "<div class=\"tabledetailsdiv\" >";
echo "General Settings<br />";

/*echo "<form method=\"post\" action=\"#\">";
	echo "Global scheduling policy:";

	echo "<select>";
		echo "<option>FIFO</option>";
		echo "<option>FIFO*</option>";
		echo "<option>FP</option>";
	echo "</select>";


echo "</form>";*/

echo "<table class=\"tableShow\">";
echo "<tr><th>Parameter</th><th>Value</th></tr>";

echo "<tr><td>Simulation Time</td>";
echo "<td><input type=\"text\" id=\"timelimit\" value=\"".Settings::getParameter("timelimit")."\"/> ms</td></tr>";

echo "<tr><td>Electronical latency</td>";
echo "<td><input type=\"text\" id=\"elatency\" value=\"".Settings::getParameter("elatency")."\"/> ms</td></tr>";

/* Task autogeneration radiobox */
echo "<tr><td>Automatic task generation</td><td>";
$autogen = Settings::getParameter("autogen");

echo "<input type=\"radio\" value=\"n\" name=\"radiotask\"";
if ($autogen == "1") echo "checked";
echo "  onclick=\"desactivateGenerateTextFields()\" /> No";

echo "<input type=\"radio\" value=\"y\" name=\"radiotask\"";
if ($autogen == "0") echo "checked";
echo " onclick=\"activateGenerateTextFields()\" /> Yes</td></tr>";
		
echo "<tr><td>Tasks</td>";
echo "<td><input type=\"text\" class=\"autogenTextField\" id=\"autotasks\" value=\"".Settings::getParameter("autotasks")."\"/> tasks</td></td></tr>";

echo "<tr><td>Highest WCET</td>";
echo "<td><input type=\"text\" class=\"autogenTextField\" id=\"highestwcet\" value=\"".Settings::getParameter("highestwcet")."\"/> ms</td></td></tr>";

echo "<tr><td>Load</td>";
echo "<td><input type=\"text\" class=\"autogenTextField\" id=\"autoload\" value=\"".Settings::getParameter("autoload")."\"/> < 1.0</td></td></tr>";

echo "</table>";

echo "<br /><a class=\"button blue\" onclick=\"saveSettings()\">Save</a>";

echo "</div>";

?>