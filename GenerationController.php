 <?php
    session_start();

    $pathToCore = "core/";
	
    /* We get the action sent by the client */
	$action_server = isset($_POST["action"]) ? $_POST["action"]	: "";
	
	include('functions.php');
	
	spl_autoload_register('chargerClasse');
	
	/* Session initialization */
	$manager = initManager();
	
	$id = getSessionId();

	$ret = create_session($id);
	$manager->setSimuId($_SESSION["simuid"]);
	$simuKey = $_SESSION["simuid"];

     $elements = new ElementsEditor($manager, $simuKey);

    /* Selecting actions */
	if($action_server == "") {
		return;	
	}
    else if ($action_server == "generateMessagesSet") {      
        $tasks 		= (isset($_POST["autotasks"]) && $_POST["autotasks"] != "") ? $_POST["autotasks"]:0;
		$hWcet 		= (isset($_POST["highestwcet"]) && $_POST["highestwcet"] != "") ? $_POST["highestwcet"]:0;
		$autogen 	= (isset($_POST["autogen"]) && $_POST["autogen"] != "") && ($_POST["autogen"] == "y")? "0":"1";
		$autoload 	= (isset($_POST["autoload"]) && $_POST["autoload"] != "") ? $_POST["autoload"]:0;
        
        Settings::save("highestwcet", $hWcet, $simuKey);
		Settings::save("autogen", $autogen, $simuKey);
		Settings::save("autoload", $autoload, $simuKey);
        Settings::save("autotasks", $tasks, $simuKey);
        
        if(Settings::getParameter("timelimit") == "") {Settings::save("timelimit", 100, $simuKey);}
        if(Settings::getParameter("elatency") == "") {Settings::save("elatency", 0, $simuKey);}
        if(Settings::getParameter("endgraphtime") == "") {Settings::save("endgraphtime", 100, $simuKey);}
        if(Settings::getParameter("startgraphtime") == "") {Settings::save("startgraphtime", 0, $simuKey);} 
        
        $timeLimit  	= Settings::getParameter("timelimit", $simuKey);
		$eLatency 	    = Settings::getParameter("elatency", $simuKey);
		$autogen 	    = Settings::getParameter("autogen", $simuKey);	
        $highestwcet	= Settings::getParameter("highestwcet", $simuKey);
        $autotasks 		= Settings::getParameter("autotasks", $simuKey);
        $autoload		= Settings::getParameter("autoload", $simuKey);
        $wcttrate 		= Settings::getParameter("wcttrate", $simuKey);
        $wcttmodel		= Settings::getParameter("wcttmodel", $simuKey);
        $switch 		= Settings::getParameter("switch", $simuKey);
        $protocol		= Settings::getParameter("protocol", $simuKey);
        $wcAnalysis     = Settings::getParameter("wcanalysis", $simuKey);
        
        include("./Templates/globalconfig.php");
        include('./Templates/networkxml.php');
        include('./Templates/messagesxml.php');
        
        $command = "java -jar ".$pathToCore."artemis_messages.jar ".$simuKey;
        exec($command, $output);  
        
        
        $file = simplexml_load_file("./ressources/".$simuKey."/input/messages.xml");
        if($file === FALSE) {
            return;
        }
        else {
             foreach($file->children() as $message) {
                 $idKernel = $message->attributes()["id"];
                 $path      = "";
                 $period    = "";
                 $offset    = "";
                 $wcetStr   = "";
                 $elementsEditor = new ElementsEditor($manager, $simuKey);
                 
                  foreach($message->children() as $critLvl) {
                       $lvl = $critLvl->attributes()["level"];
                      
                       foreach($critLvl->children() as $property) {
                           if($property->getName() == "path") {
                            $path = explode(",", $property);
                            $i = 0;
                            $finalPath = "";
                             
                            while($path[$i] != "") {
                                $finalPath .= ($manager->displayNodeByIp($path[$i], $simuKey)->name().",");
                                $i++;
                            }
                             /* We delete the last comma */
                            $finalPath = substr($finalPath, 0, -1);
                         }
                          if($property->getName() == "period") {
                             $period = $property;
                         }
                          if($property->getName() == "offset") {
                             $offset = $property;
                         } 
                         if($property->getName() == "wcet") {
                             $wcetStr = $property.":";
                         } 
                       }
                  }
                 
                $elementsEditor->addMessage($finalPath, $period, $offset, "#0000FF", $wcetStr);
                 
                 /* We get the message generated id */
                $idCreatedMessage = $manager->getMessageID($finalPath, $period, $offset);

                 $message = new Message();
                $message->setId($idCreatedMessage);
                 $message->_setWcet($wcet, $lvl);
             }
        }
    }
    else if ($action_server == "generateTopology") {
     echo "test";
		$depth = isset($_POST["topoDepth"]) ? $_POST["topoDepth"] : "0";  
        include('./Templates/networkxml.php');
        
        $command = "java -jar ".$pathToCore."artemis_topology.jar ".$simuKey." ".$depth;
		exec($command, $output);   
        
        $file = simplexml_load_file("./ressources/".$simuKey."/input/network.xml");
         
        if($file === FALSE) {
            echo "NO";
            return;
        }
        else {

            /* We create a list to store the pre-generated node ids */
            $idArray = array();
            
            foreach($file->children() as $machine) {
                $name = $machine->attributes()["name"];
                $id = $machine->attributes()["id"];
                $idArray["".$id] = $name;
                $manager->addNode($name, $id, 'FIFO');
            }

             foreach($file->children() as $machine) {
                $idFirstMachine = $machine->attributes()["id"];
                 
                foreach($machine->children() as $link) {
                    foreach($link->children() as $machinel) {
                        /* We need to convert pre-id to real db id */
                        $preIdLink = $machinel->attributes()["id"];
                        
                        /* We get the node name from pre-id */
                       $nodeName = $idArray["".$preIdLink];
                        
                        /* We get the real db id */
                       $nodeDestId = $manager->displayNodeByName($nodeName)->id();
                        $nodeSrcId  = $manager->displayNodeByName($idArray["".$idFirstMachine])->id();
                        
                        /* We finally add a new node */
                        $manager->addLink($nodeSrcId, $nodeDestId);
                    }
                } 
            }
        }
        
        $pathId =[];
		$list_nodes= $manager->displayListNode($simuKey);	
		$donnees2= $manager->displayListLink($simuKey);	
			
		$tabNames =[];
        
		foreach ($donnees2 as $element){
			$name1 = $manager->displayNode($element->node1());
			$name2 = $manager->displayNode($element->node2());		
            
			array_push($tabNames,$name1->name(),$name2->name());
			
            $nodes[$element->node1()]=$nodes[$element->node1()]+1;// on crée un tableau du nombre de 
			$nodes[$element->node2()]=$nodes[$element->node2()]+1;// liens par noeud
		}
		print_r($nodes);
		foreach ($nodes as $id=>$nblinks) {
			echo $id;
			if($nblinks>1){
				$manager->insertShape($id,'S');
			}else{
				$manager->insertShape($id,'ES');
			}
		}
        include('./Templates/networkxml.php');
	}
    else if ($action_server == "generateSimu"){
		$list_nodes= $manager->displayListNode($simuKey);
		include('./Views/resultChoices.php');	
	}

?>