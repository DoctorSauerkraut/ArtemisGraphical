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

echo "Simulation time:<input type=\"text\" /><br />";
echo "Automatic task-generation: <input type=\"radio\" name=\"radiotask\" checked/> No <input type=\"radio\" name=\"radiotask\" /> Yes<br />";
echo "Mixed-criticality management: <input type=\"radio\" name=\"radiomc\" checked/> No <input type=\"radio\" name=\"radiomc\" /> Yes";
?>