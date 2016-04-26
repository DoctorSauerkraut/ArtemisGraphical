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

	$wcttModelTag=$simuConfigDom->createElement("wcttmodel");
	$wcttModelTag->appendChild($simuConfigDom->createTextNode($wcttmodel));
	$simuConfig->appendChild($wcttModelTag);
	
    $wcttRateTag=$simuConfigDom->createElement("wcttrate");
	$wcttRateTag->appendChild($simuConfigDom->createTextNode($wcttrate));
	$simuConfig->appendChild($wcttRateTag);

	$switchTag=$simuConfigDom->createElement("switch");
	$switchTag->appendChild($simuConfigDom->createTextNode($switch));
	$simuConfig->appendChild($switchTag);

	$protocoleTag=$simuConfigDom->createElement("protocol");
	$protocoleTag->appendChild($simuConfigDom->createTextNode($protocole));
	$simuConfig->appendChild($protocoleTag);
    
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
	$critswitchs = $simuConfigDom->createElement("Critswitchs");
	$simuConfig->appendChild($critswitchs);
	
	$req = CriticalitySwitch::load($simuKey);
	
	while($switchs = $req->fetch()) {
		$critSwitch = $simuConfigDom->createElement("critswitch");
		$critSwitch->setAttribute("time", $switchs["time"]);
		$critSwitch->appendChild($simuConfigDom->createTextNode($switchs["level"]));
		
		$critswitchs->appendChild($critSwitch);
	}
	$simuConfigDom->appendChild($simuConfig);
    $simuConfigDom->save("ressources/".$simuKey.'/input/config.xml');
?>