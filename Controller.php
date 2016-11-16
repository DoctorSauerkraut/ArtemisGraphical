 <?php		
 	session_start();
 
    $pathToCore = "./core/";

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

	/* Selecting actions */
	if($action_server == "") {
		return;	
	}
	else if ($action_server == "create") {
		$donnees1= $manager->displayListNode($simuKey);
		$donnees2= $manager->displayListLink($simuKey);	
		
		if ($donnees1 != null){
			$info="";			
			foreach ($donnees1 as $element1) {
				$info=$info.$element1->id().','.$element1->name().',';
			}
			$info=substr($info,0,-1);
			$info=$info.";";
			foreach ($donnees2 as $element2) {
				$info=$info.$element2->node1().','.$element2->node2().','.$element2->id().',';
			}
			$info=substr($info,0,-1);
			$info=$info.";";			
		}
	}
	else if ($action_server == "createSchema"){
		$donnees1= $manager->displayListNode($simuKey); 	// récupération de tous les noeuds
		$donnees2= $manager->displayListLink($simuKey); 	// récupération de tous les liens
		$topologie=prepareTopo($donnees2,$donnees1);
		$topo=drawTopo($topologie);
		$_SESSION['topo']=$topo;
		// print_r($donnees1);
	}
	else if ($action_server == "getTopo"){
		$topo=$_SESSION['topo'];
		$i=0;
		foreach ($topo as $node) {
			$topoToDraw['topo'][$i]['id']=$node['id'];
			$topoToDraw['topo'][$i]['name']=$node['name'];
			$topoToDraw['topo'][$i]['shape']=$node['shape'];
			$topoToDraw['topo'][$i]['posX']=$node['posX'];
			$topoToDraw['topo'][$i]['posY']=$node['posY'];
			if(!isset($node['disp'])){
				$topoToDraw['topo'][$i]['disp']='true';
			}else{
				$topoToDraw['topo'][$i]['disp']=$node['disp'];
			}
			
			$i++;
		}
		$topoToDraw=json_encode($topoToDraw);
		print_r($topoToDraw);
	}
	else if ($action_server == "getLinks") {
		$donnees2= $manager->displayListLink($simuKey); 	// récupération de tous les liens
		$links.='{"links":[
		';
		foreach ($donnees2 as $values) {
		$links.='
		{"node1":"'.$values->node1().'", "node2":"'.$values->node2().'"},';
		}
		$links=substr($links, 0,-1);
		$links.='
		]}';
		echo $links;
		
	}
    else if ($action_server == "getMessage"){
		$nodeSel=$_POST['nodeSel'];
		$path=$_POST['path'];
		$topo=$_SESSION['topo'];
		// print_r($topo);
		$idNode='';
		$donnees1= $manager->displayListNode($simuKey);
		$donnees2= $manager->displayListLink($simuKey);
		$paths=explode(',',$path); // on separe les noeud du path

		foreach ($donnees1 as $node) { // pour chaque noeud
			if($topo[$node->id()]['disp']!='sel'){
				$topo[$node->id()]['disp']='false';
			}
			if($nodeSel==$node->name()){// si le noeud correspond au noeud selectionné
				$idNode=$node->id();// on recupère l'id
				$topo[$node->id()]['disp']='sel';
			}
		}
		foreach ($donnees2 as $link) { //pour chaque lien
			if($idNode==$link->node1()){ // si le noeud selectionné est lié à un autre noeud
				$listNodePossible[$link->node2()]['name']=$topo[$link->node2()]['name']; //on recupere le nom du noeud auquel il est lié
				$listNodePossible[$link->node2()]['id']=$topo[$link->node2()]['id']; //on recupere l'id du noeud auquel il est lié
				$topo[$link->node2()]['disp']='true';
			}
			if($idNode==$link->node2()){
				$listNodePossible[$link->node1()]['name']=$topo[$link->node1()]['name'];
				$listNodePossible[$link->node1()]['id']=$topo[$link->node1()]['id'];
				$topo[$link->node1()]['disp']='true';
			}
		}
		// print_r($listNodePossible);
		foreach ($listNodePossible as $nodeDisp) {//pour tous les noeuds disponibles
			$ok=0;
			foreach ($paths as $nodeIndisp) {// pour tous les noeuds déjà dans le path
				if($nodeDisp['name']==$nodeIndisp){ // si les noeuds disponibles sont déja dans le path, on ne les prends pas
					$ok++;
					$topo[$nodeDisp['id']]['disp']='sel';
				}
			}
			if($ok==0){// on ecrit les options pour chauque noeud retenu
				echo '<option value="'.$nodeDisp['name'].'">'.$nodeDisp['name'].'</option>';
			}
		}
		print_r($topo);
		$_SESSION['topo']=$topo;
		$_POST['path']=null;
	}
	else if ($action_server == "displayCritTable"){
		include("./Views/criticalityTable.php");
	}		
    else if ($action_server == "addCritSwitch") {
		$critTime 	= $_POST["critTime"];
		$critLvl	= $_POST["critLvl"];
		
		$critSwitch = new CriticalitySwitch($critTime, $critLvl, $simuKey);
		$res = $critSwitch->save();	
	}
	else if ($action_server == "delCritSwitch") {
		$time 	= $_POST["time"];

		CriticalitySwitch::delete($time, $simuKey);
	}
	else if ($action_server == "addCritState") {
		$critName = $_POST["critName"];
        $critCode = $_POST["critCode"];        
        
        $level = new CriticalityLevel($critName, $critCode);
        $level->save();
	}
	else if ($action_server == "saveSettings") {
		/* Getting general settings */
		$timeLimit	= (isset($_POST["time"]) && $_POST["time"] != "") ? $_POST["time"]:0;
		$eLatency 	= (isset($_POST["elatency"]) && $_POST["elatency"] != "") ? $_POST["elatency"]:0;
        $wcttmodel  = (isset($_POST["wcttmodel"]) && $_POST["wcttmodel"] != "") ? $_POST["wcttmodel"]:"STR";
        $wcttrate   = (isset($_POST["wcttrate"]) && $_POST["wcttrate"] != "") ? $_POST["wcttrate"]:10;
        $switch     = (isset($_POST["switch"]) && $_POST["switch"] != "") ? $_POST["switch"]:"STR";
        $protocol   = (isset($_POST["protocol"]) && $_POST["protocol"] != "") ? $_POST["protocol"]:10;
        $wcanalysis = (isset($_POST["wcanalysis"]) && $_POST["wcanalysis"] != "") ? $_POST["wcanalysis"]:"true";
        
		Settings::save("timelimit", $timeLimit, $simuKey);
		Settings::save("elatency", $eLatency, $simuKey);
        Settings::save("wcttmodel", $wcttmodel, $simuKey);
        Settings::save("wcttrate", $wcttrate, $simuKey);
        Settings::save("switch", $switch, $simuKey);
        Settings::save("protocol", $protocol, $simuKey);
        Settings::save("wcanalysis", $wcanalysis, $simuKey);
        
	}


    /* Simulation identification management */
	else if ($action_server == "select_simu"){
        $simuIdMgr = new SimuIdManager($manager, $simuKey);
        $manager->setSimuId( $simuIdMgr->selectSimu($_POST["id_sel"]));
	}	
	else if ($action_server == "delete_simu"){
        $simuIdMgr = new SimuIdManager($manager, $simuKey, $pathToCore);
        $simuIdMgr->deleteSimu($_POST["id_sel"]);
	}

    else if ($action_server == "results"){
		$donnees1= $manager->displayListNode();	
		include('./Views/results.php');
			
	}
    else if ($action_server == "generate"){
        $simulationMgr = new SimulationManager($manager, $simuKey);
        $simulationMgr->generate($pathToCore);
        
        $command = "java -jar ".$pathToCore."artemis_launcher.jar ".$simuKey;  
	    exec($command, $output);
	    
        $command = "java -jar ".$pathToCore."artemis_grapher.jar ".$simuKey;
		exec($command, $output);
        
        include_once('./Views/results.php');
	}
    else if ($action_server == "generateSimu"){
		$list_nodes= $manager->displayListNode($simuKey);
		include('./Views/results.php');	
	}

	/* DB Actions */
	else if ($action_server == "recupInfoAndDeleteLink"){
		
		$donnees= $manager->displayLink($_POST["id"]);
		$manager->deleteLink($_POST['id']);
		$manager->verifyLinkDeletion($donnees->node1(),$donnees->node2());
	}
    else if ($action_server == "recupInfoAndDeleteNode"){
	
		$donnees=$manager->displayNode($_POST['id']);
		$manager->deleteNode($_POST['id']);
		$manager->verifyNodeDeletion($_POST["id"],$donnees->name());
	}
    else if ($action_server == "fillPopUp"){
	
		$donnees= $manager->displayNode($_POST["id"]);
		echo ("".$donnees->ipAddress().",".$donnees->scheduling().",".$donnees->criticality());
			
	}

    else if ($action_server == "addNodeTopo"){
		$donnees1= $manager->displayListNode($simuKey);
		foreach ($donnees1 as $node) {
			if($node->name()==$_POST["name"]){
				echo 'There is already a node with this name in this simulation';
				return;
			}
		}
		$manager->addNode($_POST["name"],$_POST["ip"],$_POST["sched"],'ES');
		$donnees1= $manager->displayListNode($simuKey);
		// print_r($donnees1);
		echo $_POST['id1'];
		foreach ($donnees1 as $node) {
			if($node->name()==$_POST["id1"]){
				$node1=$node->id();
				echo 'node1 :'.$node1.' fin';
			}
			elseif($node->name()==$_POST["id2"]){
				$node2=$node->id();
				echo '   node2 : '.$node2;
			}else{}
		}
		$sql = "SELECT shape FROM node WHERE id = \"$node2\" AND id_simu=\"$simuKey\"";
		$bdd = connectBDD();
		$result = $bdd->query($sql);
		$shape=$result->fetch();
        
		if($shape['shape']=='ES'){
			$sql="UPDATE node SET shape='S' WHERE id=\"$node2\"" ;
			$result = $bdd->query($sql);
		}

		$manager->addLink($node1,$node2);
	}
	else if ($action_server == "updateNode"){
		
		$manager->updateNode($_POST["id"],$_POST["name"],$_POST["ip"],$_POST["sched"]);
		}

    /* Edit network components */
    else if ($action_server == "deleteNode"){
	    $elements = new ElementsEditor($manager, $simuKey);
        $elements->deleteNode($_POST['id']);
	}
	else if ($action_server=="deleteNodeByName"){
	
		$nodeSel=$_POST['name'];
		$donnees1=$manager->displayListNode($simuKey);
		foreach ($donnees1 as $node) {
			if($node->name()==$nodeSel){
				$nodeID=$node->id();
				$messages=$manager->displayListMessage_(); // récupération des messages
				foreach ($messages as $message) { //pour chaque message 
					$path=$message->path(); // on récupère le path
					$path=explode(",",$path); // on en fait un tableau 
					print_r($path);
					foreach ($path as $nodepath) { // pour chaque noeud du path
						if($node->name()==$nodepath){  // on regarde si le noeud à supprimer est dans le path
							$manager->deleteMessage($message->id()); // si le noeud fait parti du path du message on supprime le message
						}					// ca n'a aucun sens de garder un message si l'un de ses noeuds n'existe plus
					}
				}
				$manager->deleteNode($nodeID); // suppression du noeud
				$manager->verifyNodeDeletion($nodeID,$nodeSel);
			}
		}
	}
    else if ($action_server == "editNode"){
        $elements = new ElementsEditor($manager, $simuKey);
        $elements->editNode($_POST['id'],$_POST['label'],$_POST['ipAddress'],$_POST['scheduling'],$_POST['speed']);
	}
	else if ($action_server == "editNodeSchema"){
        $elements = new ElementsEditor($manager, $simuKey);
        $elements->editNodeSchema($_POST['id'],$_POST['label']);
	}
    else if ($action_server == "addNode"){
        $elements = new ElementsEditor($manager, $simuKey);
		$elements->addNode($_POST["name"],$_POST["ip"],$_POST["sched"]);
	}
    else if ($action_server == "deleteLink"){
        $elements = new ElementsEditor($manager, $simuKey);
        $elements->deleteLink($_POST['id'], $_POST['source'], $_POST['destination']);
	}
    else if ($action_server == "addLink"){
        $elements = new ElementsEditor($manager, $simuKey);
        $elements->addLink($_POST["id1"],$_POST["id2"]);	
	}
    else if ($action_server == "editLink"){
        $elements = new ElementsEditor($manager, $simuKey);
        $elements->editLink($_POST['node1'], $_POST['node2']);	
	}
    else if ($action_server == "editMessage"){
        $elements = new ElementsEditor($manager, $simuKey);
        $elements->editMessage($_POST["id"], $_POST["period"], $_POST["offset"], $_POST["wcetStr"], $_POST["path"], $_POST["color"]);
	}
    else if ($action_server == "deleteMessage"){
        $elements = new ElementsEditor($manager, $simuKey);
        $elements->deleteMessage($_POST["id"]);	
	}
    else if ($action_server == "addMessage"){
        $wcetStr = (isset($_POST["wcetStr"]) && $_POST["wcetStr"] != "NC=:") ? $_POST["wcetStr"]:"NC=-1:";
        $elements = new ElementsEditor($manager, $simuKey);
        $elements->addMessage($_POST["path"], $_POST["offset"], $_POST["period"], $_POST["color"], $wcetStr, 0);		
	}
        


    else if ($action_server == "clearGraph"){
		$manager->clearAll();
	}
    else if ($action_server == "reloadGraph"){
		$startTimeGraph = isset($_POST["starttimegraph"]) ? $_POST["starttimegraph"]	: "";
		$endTimeGraph	= isset($_POST["endtimegraph"]) ? $_POST["endtimegraph"]	: "";

		Settings::save("startgraphtime", $startTimeGraph, $simuKey);
		
		$timeLimit = Settings::getParameter("timelimit", $simuKey);
		if($endTimeGraph > $timeLimit ) {
			$endTimeGraph = $timeLimit;
		}
		if($startTimeGraph <0 || $startTimeGraph >= $endTimeGraph) {
			$startTimeGraph = 0;
		}	
		
		$list_nodes= $manager->displayListNode($simuKey);
		
		Settings::save("endgraphtime", $endTimeGraph, $simuKey);
		Settings::save("startgraphtime", $startTimeGraph, $simuKey);
		
		include('./Templates/graphconfig.php');
		
		$command = "java -jar ".$pathToCore."artemis_grapher.jar ".$simuKey;
		exec($command, $output);
		
		//Execute grapher to reload the new graph
	}
    else if ($action_server == "loadNodeForGraph") {
		$nodeId = isset($_POST["nodeId"]) ? $_POST["nodeId"] : "";
		$checked = isset($_POST["checked"]) ? $_POST["checked"] : "";
		
		if($checked==0){
			$checked=1;
		}
		else{
			$checked=0;
		}
		
		$node = $manager->displayNode($nodeId);
		$manager->updateNodeC($node->id(), $node->name(), $node->ipAddress(), $node->scheduling(), $checked, $node->getSpeed());
	
	/*	$list_nodes= $manager->displayListNode();
		
		include('./Views/results.php');*/
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
        
        
        $file = simplexml_load_file("ressources/".$simuKey."/input/messages.xml");
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
		$depth = isset($_POST["topoDepth"]) ? $_POST["topoDepth"] : "0";  
        include('./Templates/networkxml.php');
        
        $command = "java -jar ".$pathToCore."artemis_topology.jar ".$simuKey." ".$depth;
		exec($command, $output);   
        
        $file = simplexml_load_file("ressources/".$simuKey."/input/network.xml");
         
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
		
?>
