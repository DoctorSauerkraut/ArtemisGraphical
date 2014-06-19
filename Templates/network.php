		
<?php
	$dom = new DomDocument();
	$network=$dom->createElement("Network");

	foreach($donnees1 as $element1){

		$machine=$dom->createElement("machine");
		$machine->setAttribute("id",$element1->id());
		
			$config = $dom->createElement("Config");
				$name = $dom->createElement("name");
				$name->appendChild($dom->createTextNode($element1->name()));
			$config->appendChild($name);
		$machine->appendChild($config);

			$messages = $dom->createElement("Messages");
			foreach($donnees3 as $element3){
				$arr=explode(",", $element3->path(), 2);
				if($arr[0] == $element1->name()) {

			$message = $dom->createElement("message");
				$message->setAttribute("id", $element3->id());
					$criticality = $dom->createElement("criticality");
					$criticality->setAttribute("level",$element1->criticality());
						$path = $dom->createElement("path");
						$path->appendChild($dom->createTextNode($pathId[$element3->id()]));
						$priority = $dom->createElement("priority");
						$priority->appendChild($dom->createTextNode("Non définie"));
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
				$machinel->setAttribute("id", $element2->id());
				$machinel->appendChild($dom->createTextNode($element2->node1()));
			$links->appendChild($machinel);
		$machine->appendChild($links);
				}else if($element2->node1() == $element1->id()){			
				$machinel=$dom->createElement("machinel");
				$machinel->setAttribute("id", $element2->id());
				$machinel->appendChild($dom->createTextNode($element2->node2()));
			$links->appendChild($machinel);
		$machine->appendChild($links);	
				}else{}
				

			}
			
	$network->appendChild($machine);
	}
	$dom->appendChild($network);


	$dom->save('network.xml');
	include_once('./Views/show.php'); 


?>