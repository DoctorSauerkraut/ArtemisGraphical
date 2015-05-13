<?php
$dom = new DomDocument();

$graphConfig = $dom->createElement("GraphConfig");

/* Start time configuration */
$startTimeTag	= $dom->createElement("starttime");
$startTimeTag->appendChild($dom->createTextNode(Settings::getParameter("startgraphtime")));
$graphConfig->appendChild($startTimeTag);

/* End time configuration */
$endTimeTag=$dom->createElement("endtime");
$endTimeTag->appendChild($dom->createTextNode(Settings::getParameter("endgraphtime")));
$graphConfig->appendChild($endTimeTag);

/* Graph name */
$date = new DateTime();
Settings::save("graphname", "histo_".$date->getTimestamp());

$nameTag=$dom->createElement("graphname");
$nameTag->appendChild($dom->createTextNode(Settings::getParameter("graphname")));
$graphConfig->appendChild($nameTag);

$dom->appendChild($graphConfig);

$dom->save('input/graphconfig.xml');
?>