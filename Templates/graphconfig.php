<?php
$dom = new DomDocument();

$graphConfig = $dom->createElement("GraphConfig");

/* Start time configuration */
$startTimeTag = $dom->createElement("starttime");
$startTimeTag->appendChild($dom->createTextNode(Settings::getParameter("startgraphtime", $simuKey)));
$graphConfig->appendChild($startTimeTag);

/* End time configuration */
$endTimeTag = $dom->createElement("endtime");
$endTimeTag->appendChild($dom->createTextNode(Settings::getParameter("endgraphtime", $simuKey)));
$graphConfig->appendChild($endTimeTag);
echo "::$simuKey";

/* Graph name */
$date = new DateTime();
Settings::save("graphname", $simuKey."_".$date->getTimestamp(), $simuKey);


$nameTag=$dom->createElement("graphname");
$nameTag->appendChild($dom->createTextNode(Settings::getParameter("graphname", $simuKey)));
$graphConfig->appendChild($nameTag);

$dom->appendChild($graphConfig);

$nodesTag=$dom->createElement("nodes");
foreach($list_nodes as $node) {
	if($node->isDisplayed() == 0) {
		$string .= trim($node->id()).",";
	}
}
$nodesTag->appendChild($dom->createTextNode(substr($string, 0, strlen($string)-1)));
$graphConfig->appendChild($nodesTag);

$colorTag=$dom->createElement("color");
$messages=$manager->displayListMessage($simuKey);

foreach ($messages as $element) {
	echo'okokok<pre>'.print_r($element).'</pre>';
	echo 'color : '.$element->color().'</br>';
	$colorTag->appendChild($dom->createTextNode($element->color()));
	
}
$graphConfig->appendChild($colorTag);

if(!file_exists("ressources/".$simuKey."/input/")){
    $oldmask = umask(0);
    if(!mkdir("ressources/".$simuKey."/input/", 0777, true)) {
           die("Les permissions sont insuffisantes pour lancer la simulation");
    }
    umask($oldmask);
}


$dom->save("ressources/".$simuKey.'/input/graphconfig.xml');

?>