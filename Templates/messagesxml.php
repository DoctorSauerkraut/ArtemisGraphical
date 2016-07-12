		
<?php
	//require '../Library/Entities/CriticalitySwitch.class.php';
	$criticalityLevelsReq = CriticalityLevel::load($simuKey);
	$cptLevelsLimit = 0;
	
	while($levelCrit = $criticalityLevelsReq->fetch()){
		$criticalityLevels[$cptLevelsLimit] = new CriticalityLevel($levelCrit["name"], $levelCrit["code"]);	
		$cptLevelsLimit++;
	}

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

    $messagesDom->save("ressources/".$simuKey.'/input/messages.xml');
?>
