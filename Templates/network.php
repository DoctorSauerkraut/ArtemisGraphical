		
<?php
	//require '../Library/Entities/CriticalitySwitch.class.php';
	$criticalityLevelsReq = CriticalityLevel::load();
	$cptLevelsLimit = 0;
	
	while($levelCrit = $criticalityLevelsReq->fetch()){
		$criticalityLevels[$cptLevelsLimit] = new CriticalityLevel($levelCrit["name"], $levelCrit["code"]);	
		$cptLevelsLimit++;
	}
	
	$dom = new DomDocument();
	
	$network=$dom->createElement("Network");
	
	/* General configuration */
	$timeLimitTag=$dom->createElement("time-limit");
	$timeLimitTag->appendChild($dom->createTextNode($timeLimit));
	$network->appendChild($timeLimitTag);
	
	$eLatencyTag=$dom->createElement("elatency");
	$eLatencyTag->appendChild($dom->createTextNode($eLatency));
	$network->appendChild($eLatencyTag);
	
	$eAutogenTag=$dom->createElement("autogen");
	$eAutogenTag->appendChild($dom->createTextNode($autogen));
	$network->appendChild($eAutogenTag);
	
	if($autogen==0) {
		$eHWcetTag=$dom->createElement("highestwctt");
		$eHWcetTag->appendChild($dom->createTextNode($highestwcet));
		$network->appendChild($eHWcetTag);
		
		$eAutoTasksTag=$dom->createElement("autotasks");
		$eAutoTasksTag->appendChild($dom->createTextNode($autotasks));
		$network->appendChild($eAutoTasksTag);
		
		$eAutoLoadTag=$dom->createElement("autoload");
		$eAutoLoadTag->appendChild($dom->createTextNode($autoload));
		$network->appendChild($eAutoLoadTag);
	}
	
	/* MC management */
	$critSwitches = $dom->createElement("CritSwitches");
	$network->appendChild($critSwitches);
	
	$req = CriticalitySwitch::load();
	
	while($switches = $req->fetch()) {
		$critSwitch = $dom->createElement("critswitch");
		$critSwitch->setAttribute("time", $switches["time"]);
		$critSwitch->appendChild($dom->createTextNode($switches["level"]));
		
		$critSwitches->appendChild($critSwitch);
		
	//	
	}
	
	foreach($list_nodes as $currentNode){

		$machine=$dom->createElement("machine");
		$machine->setAttribute("id",$currentNode->id());
		$machine->setAttribute("name",$currentNode->name());
		$machine->setAttribute("speed", $currentNode->getSpeed());
		
			$config = $dom->createElement("Config");
				$name = $dom->createElement("name");
			$config->appendChild($name);
		$machine->appendChild($config);

			$messages = $dom->createElement("Messages");	
			foreach ($listMessages as $singleMessage) {
				$arr=explode(",", $singleMessage->path(), 2);										
				
				if($arr[0] == trim($currentNode->name()) ){
					$message = $dom->createElement("message");
					$message->setAttribute("id", $singleMessage->id());

					for($cptLvl =0;$cptLvl < $cptLevelsLimit;$cptLvl++) {	
						$critLevel = $criticalityLevels[$cptLvl];
						
						$criticality = $dom->createElement("criticality");
						$criticality->setAttribute("level", $critLevel->getCode());
					
						/* Path */
						$path = $dom->createElement("path");
						$path->appendChild($dom->createTextNode($pathId[$singleMessage->id()]));
						
						/* Priority */
						$priority = $dom->createElement("priority");
						$priority->appendChild($dom->createTextNode("0"));
						
						/* Period */
						$period = $dom->createElement("period");
						$period->appendChild($dom->createTextNode($singleMessage->period()));
						
						/* Offset */
						$offset = $dom->createElement("offset");
						$offset->appendChild($dom->createTextNode($singleMessage->offset()));
				
						/* WCTT */
						$wcetXML = 	$dom->createElement("wcet");
						$wcetValue = $singleMessage->wcet_($critLevel->getCode());
						$wcetXML->appendChild($dom->createTextNode($wcetValue));					
					
						$criticality->appendChild($path);
						$criticality->appendChild($priority);
						$criticality->appendChild($period);
						$criticality->appendChild($offset);
						$criticality->appendChild($wcetXML);
						$message->appendChild($criticality);
					}
						
					$messages->appendChild($message);

					$machine->appendChild($messages);
				}
			}


		
				$links = $dom->createElement("Links");
			foreach ($donnees2 as $element2){
					
				if($element2->node2() == $node->id()){
				$machinel=$dom->createElement("machinel");
				$machinel->setAttribute("id", $element2->node1());
			$links->appendChild($machinel);
			$machine->appendChild($links);

			}else if($element2->node1() == $node->id()){			
				$machinel=$dom->createElement("machinel");
				$machinel->setAttribute("id", $element2->node2());
				$links->appendChild($machinel);
				$machine->appendChild($links);	
			}
				

		}
			
	$network->appendChild($machine);
	}
	$dom->appendChild($network);


	$dom->save('input/network.xml');
	//$command = "/usr/bin/java -jar artemis_launcher.jar 2>&1 > gen/logs/weblog.txt";
	$command = "java -jar artemis_launcher.jar 2>&1 > gen/logs/weblog.txt";
	exec($command, $output);

	include_once('./Views/results.php'); 

?>
