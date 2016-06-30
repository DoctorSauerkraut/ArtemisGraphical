<?php
	/* Network file */
	$networkDom = new DomDocument();
	
	$network=$networkDom->createElement("Network");
	
	foreach($list_nodes as $currentNode){
        
		$machine=$networkDom->createElement("machine");
		$machine->setAttribute("id", $currentNode->id());
		$machine->setAttribute("name", $currentNode->name());
		$machine->setAttribute("speed", $currentNode->getSpeed());
        $machine->setAttribute("shape", $currentNode->shape());
		
        $config = $networkDom->createElement("Config");
        $name = $networkDom->createElement("name");
        $config->appendChild($name);
		$machine->appendChild($config);
		
        $links = $networkDom->createElement("Links");
        foreach ($donnees2 as $element2){
           // echo "::pika".$currentNode->id()."/".$element2->node2()."/".$element2->node1()." ";

            if($element2->node2() == $currentNode->id()){
                $machinel=$networkDom->createElement("machinel");
                $machinel->setAttribute("id", $element2->node1());
                $links->appendChild($machinel);
                $machine->appendChild($links);
            }else if($element2->node1() == $currentNode->id()){	
                $machinel=$networkDom->createElement("machinel");
                $machinel->setAttribute("id", $element2->node2());
                $links->appendChild($machinel);
                $machine->appendChild($links);	
            }
        }
        
        $network->appendChild($machine);
    }
		
	$networkDom->appendChild($network);

	$networkDom->save($pathToCore."ressources/".$simuKey.'/input/network.xml');
?>