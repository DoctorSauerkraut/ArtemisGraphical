<?php 
	echo "<div id='results' style='text-align:center;'>";
	
	echo "<div id=\"graphoptionstoolbar\">";
	
	$startTime = Settings::getParameter("startgraphtime", $simuKey);
	$endTime = Settings::getParameter("endgraphtime", $simuKey);
	
	echo "Start time :<input type=\"text\" id=\"starttimegraph\" value=\"$startTime\" />";
	echo "End time :<input type=\"text\" id=\"endtimegraph\" value=\"$endTime\" />";
	
	echo "<a class=\"button green\" onclick=\"reloadGraph()\">Reload graph</a><br /><br />";
	
	foreach($list_nodes as $node) {
		echo "<input type=\"checkbox\" ";
		
		echo "onclick=\"loadNodeOnCheck('".$node->id()."','".$node->isDisplayed()."')\" ";
		
		if($node->isDisplayed()==0) {
			echo " checked ";
		}
		echo "/>".$node->name();
	}
	echo "</div>";
	
	echo "<img src=\"ressources/".$simuKey."/gen/histos/".Settings::getParameter("graphname", $simuKey).".PNG\" />";
	
	echo "<br />Export results :";
	echo " <a class=\"button green\" href=\"gen/histos/".Settings::getParameter("graphname", $simuKey).".PNG"."\">PNG</a>"; 
	echo " <a class=\"button green\">PDF</a>"; 
	echo " <a class=\"button green\">Latex</a>"; 
	
	echo "</div>";
?>