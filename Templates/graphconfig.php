<?php
$dom = new DomDocument();

$graphConfig = $dom->createElement("GraphConfig");

/* Start time configuration */
$startTimeTag	= $dom->createElement("starttime");
$startTimeTag->appendChild($dom->createTextNode(Settings::getParameter("startgraphtime", $simuKey)));
$graphConfig->appendChild($startTimeTag);

/* End time configuration */
$endTimeTag=$dom->createElement("endtime");
$endTimeTag->appendChild($dom->createTextNode(Settings::getParameter("endgraphtime", $simuKey)));
$graphConfig->appendChild($endTimeTag);
echo "::$simuKey";

/* Graph name */
$date = new DateTime();
Settings::save("graphname", $date->getTimestamp(), $simuKey);

$nameTag=$dom->createElement("graphname");
$nameTag->appendChild($dom->createTextNode(Settings::getParameter("graphname", $simuKey)));
$graphConfig->appendChild($nameTag);

$dom->appendChild($graphConfig);

$nodesTag=$dom->createElement("nodes");

foreach($list_nodes as $node) {
	if($node->isDisplayed() == 0) {
		$string .= trim($node->name()).",";
	}
}
$nodesTag->appendChild($dom->createTextNode(substr($string, 0, strlen($string)-1)));
$graphConfig->appendChild($nodesTag);
echo "::";

if (!file_exists("ressources/".Settings::getParameter("graphname", $simuKey))) {
	mkdir("ressources/".Settings::getParameter("graphname", $simuKey)."/input/", 0777, true);
}
$dom->save("ressources/".Settings::getParameter("graphname", $simuKey).'/input/graphconfig.xml');

?>