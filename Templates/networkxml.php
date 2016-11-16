<?php
	/* Network file */
	$networkDom = new DomDocument();
	
	$network=$networkDom->createElement("Network");
	
	foreach($list_nodes as $currentNode){
		$machine=$networkDom->createElement("machine");
		$machine->setAttribute("id", $currentNode->ipAddress());
		$machine->setAttribute("name", $currentNode->name());
		$machine->setAttribute("speed", $currentNode->getSpeed());
        $machine->setAttribute("shape", $currentNode->shape());
		
        $config = $networkDom->createElement("Config");
        $name = $networkDom->createElement("name");
        $config->appendChild($name);
		$machine->appendChild($config);
		
        $links = $networkDom->createElement("Links");
        foreach ($donnees2 as $element2){
            $ipNodeS = $manager->displayNode($element2->node1())->ipAddress();
            $ipNodeD = $manager->displayNode($element2->node2())->ipAddress();

            if($ipNodeD == $currentNode->ipAddress()){
                $machinel=$networkDom->createElement("machinel");
                $machinel->setAttribute("id", $ipNodeD);
                $links->appendChild($machinel);
                $machine->appendChild($links);
            }else if($ipNodeS == $currentNode->ipAddress()){	
                $machinel=$networkDom->createElement("machinel");
                $machinel->setAttribute("id", $ipNodeS);
                $links->appendChild($machinel);
                $machine->appendChild($links);	
            }
        }
        
        $network->appendChild($machine);
    }
		
	$networkDom->appendChild($network);

	$networkDom->save("ressources/".$simuKey.'/input/network.xml');
?>