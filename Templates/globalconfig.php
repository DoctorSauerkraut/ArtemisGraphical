<?php

/* General configuration */
	$simuConfigDom = new DomDocument();
	
	$simuConfig = $simuConfigDom->createElement("Config");

	$timeLimitTag=$simuConfigDom->createElement("time-limit");
	$timeLimitTag->appendChild($simuConfigDom->createTextNode($timeLimit));
	$simuConfig->appendChild($timeLimitTag);
	
	$eLatencyTag=$simuConfigDom->createElement("elatency");
	$eLatencyTag->appendChild($simuConfigDom->createTextNode($eLatency));
	$simuConfig->appendChild($eLatencyTag);
	
    $wcttComputeTag=$simuConfigDom->createElement("wcttcompute");
	$wcttComputeTag->appendChild($simuConfigDom->createTextNode($wcttCompute));
	$simuConfig->appendChild($wcttComputeTag);
    
	$eAutogenTag=$simuConfigDom->createElement("autogen");
	$eAutogenTag->appendChild($simuConfigDom->createTextNode($autogen));
	$simuConfig->appendChild($eAutogenTag);
	
	if($autogen==0) {
		$eHWcetTag=$simuConfigDom->createElement("highestwctt");
		$eHWcetTag->appendChild($simuConfigDom->createTextNode($highestwcet));
		$simuConfig->appendChild($eHWcetTag);
		
		$eAutoTasksTag=$simuConfigDom->createElement("autotasks");
		$eAutoTasksTag->appendChild($simuConfigDom->createTextNode($autotasks));
		$simuConfig->appendChild($eAutoTasksTag);
		
		$eAutoLoadTag=$simuConfigDom->createElement("autoload");
		$eAutoLoadTag->appendChild($simuConfigDom->createTextNode($autoload));
		$simuConfig->appendChild($eAutoLoadTag);
	}
	
	/* MC management */
	$critSwitches = $simuConfigDom->createElement("CritSwitches");
	$simuConfig->appendChild($critSwitches);
	
	$req = CriticalitySwitch::load($simuKey);
	
	while($switches = $req->fetch()) {
		$critSwitch = $simuConfigDom->createElement("critswitch");
		$critSwitch->setAttribute("time", $switches["time"]);
		$critSwitch->appendChild($simuConfigDom->createTextNode($switches["level"]));
		
		$critSwitches->appendChild($critSwitch);
	}
	$simuConfigDom->appendChild($simuConfig);

    $simuConfigDom->save("ressources/".$simuKey.'/input/config.xml');
?>