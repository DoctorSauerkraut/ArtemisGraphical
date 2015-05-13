<?php 
	echo "<div id='results' style='text-align:center;'>";
	
	echo "<div id=\"graphoptionstoolbar\">";
	
	$startTime = Settings::getParameter("startgraphtime");
	$endTime = Settings::getParameter("endgraphtime");
	
	echo "Start time :<input type=\"text\" id=\"starttimegraph\" value=\"$startTime\" />";
	echo "End time :<input type=\"text\" id=\"endtimegraph\" value=\"$endTime\" />";
	
	echo "<a class=\"button green\" onclick=\"reloadGraph()\">Reload graph</a>";
	
	echo "</div>";
	
	echo "<img src=\"gen/histos/".Settings::getParameter("graphname").".PNG\" />";
	

	//echo "</br>Simulation Time: 0.35s";
	
	echo "<br />Export results :";
	echo " <a class=\"button green\" href=\"gen/histos/".Settings::getParameter("graphname").".PNG"."\">PNG</a>"; 
	echo " <a class=\"button green\">PDF</a>"; 
	echo " <a class=\"button green\">Latex</a>"; 
	
	echo "</div>";
?>