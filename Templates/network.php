		
<?php
	//require '../Library/Entities/CriticalitySwitch.class.php';
	$criticalityLevelsReq = CriticalityLevel::load($simuKey);
	$cptLevelsLimit = 0;
	
	while($levelCrit = $criticalityLevelsReq->fetch()){
		$criticalityLevels[$cptLevelsLimit] = new CriticalityLevel($levelCrit["name"], $levelCrit["code"]);	
		$cptLevelsLimit++;
	}
	
	
	/* General configuration */
	$simuConfigDom = new DomDocument();
	
	$simuConfig = $simuConfigDom->createElement("Config");

	$timeLimitTag=$simuConfigDom->createElement("time-limit");
	$timeLimitTag->appendChild($simuConfigDom->createTextNode($timeLimit));
	$simuConfig->appendChild($timeLimitTag);
	
	$eLatencyTag=$simuConfigDom->createElement("elatency");
	$eLatencyTag->appendChild($simuConfigDom->createTextNode($eLatency));
	$simuConfig->appendChild($eLatencyTag);
	
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
	
	
	/* Network file */
	$networkDom = new DomDocument();
	
	$network=$networkDom->createElement("Network");
	
	foreach($list_nodes as $currentNode){

		$machine=$networkDom->createElement("machine");
		$machine->setAttribute("id",$currentNode->id());
		$machine->setAttribute("name",$currentNode->name());
		$machine->setAttribute("speed", $currentNode->getSpeed());
		
        $config = $networkDom->createElement("Config");
        $name = $networkDom->createElement("name");
        $config->appendChild($name);
		$machine->appendChild($config);
        
        $network->appendChild($machine);
    }
			
		
    $links = $networkDom->createElement("Links");
    foreach ($donnees2 as $element2){

        if($element2->node2() == $node->id()){
            $machinel=$networkDom->createElement("machinel");
            $machinel->setAttribute("id", $element2->node1());
            $links->appendChild($machinel);
            $machine->appendChild($links);
        }else if($element2->node1() == $node->id()){			
            $machinel=$networkDom->createElement("machinel");
            $machinel->setAttribute("id", $element2->node2());
            $links->appendChild($machinel);
            $machine->appendChild($links);	
        }
    }
			
	$networkDom->appendChild($network);
	
	/* Messages file */
	$messagesDom = new DomDocument();
	$messages=$messagesDom->createElement("Messages");
	
 	foreach($list_nodes as $currentNode){
		foreach ($listMessages as $singleMessage) {
 			$arr=explode(",", $singleMessage->path(), 2);
 			
			if($arr[0] == trim($currentNode->name()) ){
				$message = $messagesDom->createElement("message");
				$message->setAttribute("id", $singleMessage->id());
				
				for($cptLvl =0;$cptLvl < $cptLevelsLimit;$cptLvl++) {
					$critLevel = $criticalityLevels[$cptLvl];

					$criticality = $messagesDom->createElement("criticality");
					$criticality->setAttribute("level", $critLevel->getCode());

					/* Path */
					$path = $messagesDom->createElement("path");
					$path->appendChild($messagesDom->createTextNode($pathId[$singleMessage->id()]));
                
					/* Priority */
					$priority = $messagesDom->createElement("priority");
					$priority->appendChild($messagesDom->createTextNode("0"));
                
					/* Period */
					$period = $messagesDom->createElement("period");
					$period->appendChild($messagesDom->createTextNode($singleMessage->period()));
                
					/* Offset */
					$offset = $messagesDom->createElement("offset");
					$offset->appendChild($messagesDom->createTextNode($singleMessage->offset()));
                
					/* WCTT */
					$wcetXML = 	$messagesDom->createElement("wcet");
					$wcetValue = $singleMessage->wcet_($critLevel->getCode());
					$wcetXML->appendChild($messagesDom->createTextNode($wcetValue));

					$criticality->appendChild($path);
					$criticality->appendChild($priority);
					$criticality->appendChild($period);
					$criticality->appendChild($offset);
					$criticality->appendChild($wcetXML);
					$message->appendChild($criticality);
			}
					
			$messages->appendChild($message);
			}
 		}
 	}
	$messagesDom->appendChild($messages);

	$simuConfigDom->save("ressources/".$simuKey.'/input/config.xml');
	$networkDom->save("ressources/".$simuKey.'/input/network.xml');
	$messagesDom->save("ressources/".$simuKey.'/input/messages.xml');
	
//	$command = "/usr/bin/java -jar artemis_launcher.jar ".$simuKey." 2>&1 > weblog.txt";
	$command = "java -jar artemis_launcher.jar ".$simuKey;
	exec($command, $output);

	include_once('./Views/results.php'); 

?>
