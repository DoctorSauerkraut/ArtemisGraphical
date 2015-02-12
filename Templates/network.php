		
<?php
	//require '../Library/Entities/CriticalitySwitch.class.php';
	
	$dom = new DomDocument();
	
	$network=$dom->createElement("Network");
	
	/* General configuration */
	$timeLimitTag=$dom->createElement("time-limit");
	$timeLimitTag->appendChild($dom->createTextNode($timeLimit));
	$network->appendChild($timeLimitTag);
	
	$eLatencyTag=$dom->createElement("elatency");
	$eLatencyTag->appendChild($dom->createTextNode($eLatency));
	$network->appendChild($eLatencyTag);
	
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

	
	
	
	
	foreach($donnees1 as $element1){

		$machine=$dom->createElement("machine");
		$machine->setAttribute("id",$element1->id());
		$machine->setAttribute("name",$element1->name());
		
			$config = $dom->createElement("Config");
				$name = $dom->createElement("name");
			$config->appendChild($name);
		$machine->appendChild($config);

			$messages = $dom->createElement("Messages");	
			foreach($donnees3 as $element3){
				$arr=explode(",", $element3->path(), 2);										
				
				if($arr[0] == trim($element1->name()) ){
					$message = $dom->createElement("message");
					$message->setAttribute("id", $element3->id());
					$criticality = $dom->createElement("criticality");
					$criticality->setAttribute("level",$element1->criticality());
					
					$path = $dom->createElement("path");
					$path->appendChild($dom->createTextNode($pathId[$element3->id()]));
					$priority = $dom->createElement("priority");
					$priority->appendChild($dom->createTextNode("0"));
					$period = $dom->createElement("period");
					$period->appendChild($dom->createTextNode($element3->period()));
					$offset = $dom->createElement("offset");
					$offset->appendChild($dom->createTextNode($element3->offset()));
					$wcet = $dom->createElement("wcet");
					$wcet->appendChild($dom->createTextNode($element3->wcet()));
					
					$criticality->appendChild($path);
					$criticality->appendChild($priority);
					$criticality->appendChild($period);
					$criticality->appendChild($offset);
					$criticality->appendChild($wcet);
				$message->appendChild($criticality);	
			$messages->appendChild($message);


		$machine->appendChild($messages);
				}
			}


		
				$links = $dom->createElement("Links");
			foreach ($donnees2 as $element2){
					
				if($element2->node2() == $element1->id()){
				$machinel=$dom->createElement("machinel");
				$machinel->setAttribute("id", $element2->node1());
			$links->appendChild($machinel);
		$machine->appendChild($links);
				}else if($element2->node1() == $element1->id()){			
				$machinel=$dom->createElement("machinel");
				$machinel->setAttribute("id", $element2->node2());
			$links->appendChild($machinel);
		$machine->appendChild($links);	
				}else{}
				

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
