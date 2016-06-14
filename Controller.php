 <?php		
 	session_start();
 
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
	else if($action_server=="create") {
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
			// echo($info);			
		}
	}
	else if($action_server=='createSchema'){
		$donnees1= $manager->displayListNode($simuKey); 	// récupération de tous les noeuds
		$donnees2= $manager->displayListLink($simuKey); 	// récupération de tous les liens

		$topologie=prepareTopo($donnees2,$donnees1);
		$topo=drawTopo($topologie);
		$_SESSION['topo']=$topo;
	}
	else if($action_server=='getTopo'){
		$topo=$_SESSION['topo'];
		$i=0;
		foreach ($topo as $node) {
			$topoToDraw['topo'][$i]['id']=$node['id'];
			$topoToDraw['topo'][$i]['name']=$node['name'];
			$topoToDraw['topo'][$i]['shape']=$node['shape'];
			$topoToDraw['topo'][$i]['posX']=$node['posX'];
			$topoToDraw['topo'][$i]['posY']=$node['posY'];
			$topoToDraw['topo'][$i]['parent']=$node['parent'];
			$topoToDraw['topo'][$i]['rank']=$node['rank'];
			$i++;
		}
		$topoToDraw=json_encode($topoToDraw);
		print_r($topoToDraw);
	}
	elseif ($action_server=='getLinks') {
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
	elseif($action_server=='getMessage'){
		$nodeSel=$_POST['nodeSel'];
		$path=$_POST['path'];
		$topo=$_SESSION['topo'];
		$idNode='';
		$donnees1= $manager->displayListNode($simuKey);
		$donnees2= $manager->displayListLink($simuKey);

		$paths=explode(',',$path);
		foreach ($donnees1 as $node) {
			if($nodeSel==$node->name()){
				$idNode=$node->id();
			}
		}
		foreach ($donnees2 as $link) {
			if($idNode==$link->node1()){
				$listNodePossible[]=$topo[$link->node2()]['name'];
			}
			if($idNode==$link->node2()){
				$listNodePossible[]=$topo[$link->node1()]['name'];
			}
		}

		foreach ($listNodePossible as $nodeDisp) {
			$ok=0;
			foreach ($paths as $nodeIndisp) {
				if($nodeDisp==$nodeIndisp){
					$ok++;
				}
			}
			if($ok==0){
				echo '<option value="'.$nodeDisp.'">'.$nodeDisp.'</option>';
			}
		}
	}
	else if($action_server=="select_simu"){
		$_SESSION['id_sel']=$_POST["id_sel"];
		$id=getSessionId();
		$ret = create_session($id);
		$manager->setSimuId($_SESSION["simuid"]);
	}	
	else if($action_server=="delete_simu"){
		$id_sel=$_POST["id_sel"];
		// suppression des simulations et de tous les éléments associés dans la base de données
		$bdd 	= connectBDD();
		$tables=array('simulations','config','node','link','message');
		foreach($tables as $tbl) {
			$rq_sel='SELECT id FROM '.$tbl.' WHERE id_simu='.$id_sel;
			$res = $bdd->query($rq_sel);
			while($select=$res->fetch()){
				if(isset($select)){
					$rq_del='DELETE FROM '.$tbl.' WHERE id='.$select['id'];
					$resu = $bdd->query($rq_del);
					if($tbl=="message"){
						$rq_del_message='DELETE FROM wcets WHERE id_msg='.$select['id'];
						$resultats = $bdd->query($rq_del_message);
					}
				}
			}
		}
		$fileToDel='./ressources/'.$id_sel;
		if(file_exists($fileToDel)){
			$dir_iterator = new RecursiveDirectoryIterator($fileToDel, RecursiveDirectoryIterator::SKIP_DOTS);;
			$iterator = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::CHILD_FIRST);
			foreach($iterator as $fichier){
				$fichier->isDir() ? rmdir($fichier) : unlink($fichier);
			}
			rmdir($fileToDel);
		}
		
	}

	else if($action_server=="displayCritTable"){
		include("./Views/criticalityTable.php");
	}		
    else if($action_server=="addCritSwitch") {
		$critTime 	= $_POST["critTime"];
		$critLvl	= $_POST["critLvl"];
		
		$critSwitch = new CriticalitySwitch($critTime, $critLvl, $simuKey);
		$res = $critSwitch->save();	
	}
	else if($action_server == "delCritSwitch") {
		$time 	= $_POST["time"];

		CriticalitySwitch::delete($time, $simuKey);
	}
	else if($action_server == "addCritState") {
		$critName = $_POST["critName"];
        $critCode = $_POST["critCode"];        
        
        $level = new CriticalityLevel($critName, $critCode);
        $level->save();
	}
	else if($action_server=="saveSettings") {
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
        
	}else if ($action_server=="results"){
		$donnees1= $manager->displayListNode();	
		include('./Views/results.php');
			
	}
	/* DB Actions */
	else if ($action_server=="recupInfoAndDeleteLink"){
		
		$donnees= $manager->displayLink($_POST["id"]);
		$manager->deleteLink($_POST['id']);
		$manager->verifyLinkDeletion($donnees->node1(),$donnees->node2());
			
	}else if ($action_server=="recupInfoAndDeleteNode"){
	
		$donnees=$manager->displayNode($_POST['id']);
		$manager->deleteNode($_POST['id']);
		$manager->verifyNodeDeletion($_POST["id"],$donnees->name());
			
	}else if ($action_server=="fillPopUp"){
	
		$donnees= $manager->displayNode($_POST["id"]);
		echo ("".$donnees->ipAddress().",".$donnees->scheduling().",".$donnees->criticality());
			
	}else if ($action_server=="addLink"){
	
		$manager->addLink($_POST["id1"],$_POST["id2"]);	
		
	}else if ($action_server=="addNode"){
		
		$manager->addNode($_POST["name"],$_POST["ip"],$_POST["sched"]);
			
	}else if ($action_server=="addNodeTopo"){
		$donnees1= $manager->displayListNode($simuKey);
		foreach ($donnees1 as $node) {
			if($node->name()==$_POST["name"]){
				echo 'There is already a node with this name in this simulation';
				return;
			}
		}
		$manager->addNode($_POST["name"],$_POST["ip"],$_POST["sched"]);
		$donnees1= $manager->displayListNode($simuKey);
		// print_r($donnees1);
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

		$manager->addLink($node1,$node2);
		// echo '  oui  ';
			
	}
	else if ($action_server=="updateNode"){
		
		$manager->updateNode($_POST["id"],$_POST["name"],$_POST["ip"],$_POST["sched"]);
			
	}else if ($action_server=="addMessage"){
		$path 	= $_POST["path"];
		$offset = $_POST["offset"];
		$period = $_POST["period"];
		$color = $_POST['color'];
		$wcetStr = (isset($_POST["wcetStr"]) && $_POST["wcetStr"] != "NC=:") ? $_POST["wcetStr"]:"NC=0:";
		$message = new Message();
		
		$newpath = $manager->verrifyPath($path);
		if ($newpath != ""){
			$insertedId = $manager->addMessage($newpath,$period,$offset,$color);
			if($insertedId != "") {
				$msg = new Message();
				$msg->setId($insertedId);
				
				/* Getting all the wctts sent by the server */
				$critLvls = split(":", $wcetStr);
				$cptStr = 0;
				while($critLvls[$cptStr] != "") {
					$critLvl	= split("=", $critLvls[$cptStr])[0]; 
					$wcet 		= split("=", $critLvls[$cptStr])[1]; 	
					
					$msg->_setWcet($wcet, $critLvl);
					$cptStr++;
				}

			}
			}else {
				echo "/!\ Impossible Path, you need to create the corresponding links or nodes. /!\ ";
			}

			
	}else if ($action_server=="deleteNode"){
	
		$donnees=$manager->displayNode($_POST['id']); // recupération du noeud à supprimer
		$messages=$manager->displayListMessage_(); // récupération des messages
		foreach ($messages as $message) { //pour chaque message 
			$path=$message->path(); // on récupère le path
			$path=explode(",",$path); // on en fait un tableau 
			foreach ($path as $node) { // pour chaque noeud du path
				if($donnees->name()==$node){  // on regarde si le noeud à supprimer est dans le path
					$manager->deleteMessage($message->id()); // si le noeud fait parti du path du message on supprime le message
				}					// ca n'a aucun sens de garder un message si l'un de ses noeuds n'existe plus
			}
		}
		$manager->deleteNode($_POST['id']); // suppression du noeud
		$manager->verifyNodeDeletion($_POST['id'],$donnees->name());


	}else if($action_server=="deleteLink"){
	
		$manager->deleteLink($_POST['id']);	
		$manager->verifyLinkDeletion($_POST['source'],$_POST['destination']);
        
		$list_links= $manager->displayListLink($simuKey);	
		$tabNames =[];
        
		foreach ($list_links as $element){
			$name1 = $manager->displayNode($element->node1());
			$name2 = $manager->displayNode($element->node2());				
			array_push($tabNames,$name1->name(),$name2->name());
		}				
		include('./Views/links.php');
			
	}else if ($action_server=="deleteMessage"){
		$idMsg = $_POST['id'];
		$manager->deleteMessage($idMsg);	
		Message::deleteMessage($idMsg);
		
		$donnees1= $manager->displayListNode_();	
		$donnees2= $manager->displayListLink_();	
		$donnees3= $manager->displayListMessage_();
		$tabNames =[];
		foreach ($donnees2 as $element){
			$name1 = $manager->displayNode($element->node1());
			$name2 = $manager->displayNode($element->node2());				
			array_push($tabNames,$name1->name(),$name2->name());
		}
		include('./Views/show.php');
			
	}
    else if ($action_server=="generate"){
        /* Default config values */
        if(Settings::getParameter("timelimit") == "") {Settings::save("timelimit", 100, $simuKey);}
        if(Settings::getParameter("elatency") == "") {Settings::save("elatency", 0, $simuKey);}
        if(Settings::getParameter("endgraphtime") == "") {Settings::save("endgraphtime", 100, $simuKey);}
        if(Settings::getParameter("startgraphtime") == "") {Settings::save("startgraphtime", 0, $simuKey);}     
        
		$pathId =[];
		$list_nodes= $manager->displayListNode($simuKey);	
		$donnees2= $manager->displayListLink($simuKey);	
		$listMessages= $manager->displayListMessage($simuKey);	
		
		
		/* Parsing the path string */
		foreach ($listMessages as $singleMessage) {
			$pathId[$singleMessage->id()]=$singleMessage->path();
			
			$path = split(",", $pathId[$singleMessage->id()]);
			
			foreach ($list_nodes as $element1) {				
				$cptPath = 0;
				$strPath = "";
				
				while($path[$cptPath] != "") {
					$currentPath = trim($path[$cptPath]);
					if($currentPath == trim($element1->name())) {
							$path[$cptPath] = $element1->id();
					}
					$strPath .= $path[$cptPath].",";
					$cptPath++;
				}
				
				$pathId[$singleMessage->id()] = $strPath;
			}
			$pathId[$singleMessage->id()] = trim($pathId[$singleMessage->id()], ",");
		}
		
	
		$tabNames =[];
		foreach ($donnees2 as $element){
			$name1 = $manager->displayNode($element->node1());
			$name2 = $manager->displayNode($element->node2());				
			array_push($tabNames,$name1->name(),$name2->name());
		}

		/* Get general settings */
		$timeLimit 	  = Settings::getParameter("timelimit", $simuKey);
		$eLatency 	  = Settings::getParameter("elatency", $simuKey);
		$autogen 	  = Settings::getParameter("autogen", $simuKey);
		$wcttmodel    = Settings::getParameter("wcttmodel", $simuKey);
		$wcttrate     = Settings::getParameter("wcttrate", $simuKey);
		$switch 	  = Settings::getParameter("switch", $simuKey);
        $protocol	  = Settings::getParameter("protocol", $simuKey);
        $wcAnalysis     = Settings::getParameter("wcanalysis", $simuKey);

		if($autogen == 0) {
			$highestwcet	= Settings::getParameter("highestwcet", $simuKey);
			$autotasks 		= Settings::getParameter("autotasks", $simuKey);
			$autoload		= Settings::getParameter("autoload", $simuKey);
		}
		
		Settings::save("startgraphtime", 0, $simuKey);
		Settings::save("endgraphtime", $timeLimit, $simuKey);

		echo '<script language="javascript">';
		include('./Templates/graphconfig.php');
		include('./Templates/network.php');
        
        $command = "java -jar artemis_launcher.jar ".$simuKey;
	    exec($command, $output);
	    

	   include_once('./Views/results.php'); 
	
		
	}else if ($action_server=="generateSimu"){
		$list_nodes= $manager->displayListNode($simuKey);

		include('./Views/results.php');
		
	}else if($action_server=="editNode"){
		$manager->updateNodeS($_POST['id'],$_POST['label'],$_POST['ipAddress'],$_POST['scheduling'],$_POST['speed']);
		$donnees1= $manager->displayListNode_();
		$donnees2= $manager->displayListLink_();	
		$donnees3= $manager->displayListMessage_();
		$tabNames =[];
		foreach ($donnees2 as $element){
			$name1 = $manager->displayNode($element->node1());
			$name2 = $manager->displayNode($element->node2());				
			array_push($tabNames,$name1->name(),$name2->name());
		}
		include('./Views/show.php');
			
	}else if($action_server=="editLink"){
		$id1=$manager->displayNodeByName($_POST['node1']);
		$id2=$manager->displayNodeByName($_POST['node2']);
		if ($id1 != null && $id2 != null){
			$manager->updateLink($_POST['id'],$id1->id(),$id2->id());
		}else {
			echo "/!\ Impossible Link, you need to create the corresponding nodes. /!\ ";
		}
		$donnees1= $manager->displayListNode_();	
		$list_links= $manager->displayListLink_();	
		$donnees3= $manager->displayListMessage_();
		$tabNames =[];
		foreach ($list_links as $element){
			$name1 = $manager->displayNode($element->node1());
			$name2 = $manager->displayNode($element->node2());				
			array_push($tabNames,$name1->name(),$name2->name());
		}
		include('./Views/links.php');
			
	}else if($action_server=="editMessage"){
		/* Get infos from AJAX request */
		$id 		= $_POST["id"];
		$period 	= $_POST["period"];
		$offset		= $_POST["offset"];
		$wcetStr	= $_POST["wcetStr"];
		$path		= $_POST["path"];
		$color		= $_POST["color"];
		
		/* Verify network path */
		$newpath = $manager->verrifyPath($path);	
		
		
		if ($newpath != ""){
			$manager->updateMessage($id,$newpath,$period,$offset,$color);	
			echo "::".$id;
			
			$msg = new Message();
			$msg->setId($id);
			
			/* Getting all the wctts sent by the server */
			$critLvls = split(":", $wcetStr);
			$cptStr = 0;
			
			while($critLvls[$cptStr] != "") {
				$critLvl	= split("=", $critLvls[$cptStr])[0]; 
				$wcet 		= split("=", $critLvls[$cptStr])[1]; 	
				
				$msg->_setWcet($wcet, $critLvl);
				$cptStr++;
			}			
		}else {
			echo "/!\ Impossible Path, you need to create the corresponding links or nodes. /!\ ";
		}
		
		/* Reload page content */
		$donnees1= $manager->displayListNode($simuKey);	
		$donnees2= $manager->displayListLink($simuKey);	
		$donnees3= $manager->displayListMessage_();
		$tabNames =[];
		
		foreach ($donnees2 as $element){
			$name1 = $manager->displayNode($element->node1());
			$name2 = $manager->displayNode($element->node2());				
			array_push($tabNames,$name1->name(),$name2->name());
		}
		include('./Views/show.php');		

	}else if($action_server == "clearGraph"){
		$manager->clearAll();
	}else if($action_server == "reloadGraph"){
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
		
	//	$command = "java -jar artemis_grapher.jar 2>&1 > weblog.txt";
		$command = "java -jar artemis_grapher.jar ".$simuKey;
		exec($command, $output);
		
		//Execute grapher to reload the new graph
	}else if($action_server == "loadNodeForGraph") {
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
	}  else if($action_server=="generateMessagesSet") {
        
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
        include('./Templates/network.php');
        
        $command = "java -jar artemis_messages.jar ".$simuKey;
		exec($command, $output);  
        
        $file = simplexml_load_file("ressources/".$simuKey."/input/messages.xml");
        if($file === FALSE) {
            echo "->".$command;
            return;
        }
        else {
             foreach($file->children() as $message) {
                 $id = $message->attributes()["id"];
                 $path      = "";
                 $period    = "";
                 $offset    = "";

                  foreach($message->children() as $critLvl) {
                       $lvl = $critLvl->attributes()["level"];
                      
                       foreach($critLvl->children() as $property) {
                           if($property->getName() == "path") {
                            $path = explode(",", $property);
                            $i = 0;
                            $finalPath = "";
                             
                            while($path[$i] != "") {
                                $finalPath .= ($manager->displayNode($path[$i])->name().",");
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
                             $wcet = $property;
                         } 
                       }
                  }
                 
                 $manager->addMessage($finalPath, $period, $offset);
                 /* We get the message generated id */
                 $idCreatedMessage = $manager->getMessageID($finalPath, $period, $offset);
                 $message = new Message();
                 $message->setId($idCreatedMessage);
                 $message->_setWcet($wcet, $lvl);
             }
        }
    } else if($action_server == "generateTopology") {
		$depth = isset($_POST["topoDepth"]) ? $_POST["topoDepth"] : "0";  
        
        $command = "java -jar artemis_topology.jar ".$simuKey." ".$depth;
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
                $manager->addNode($name, 0, 'FIFO');
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
		}
        include('./Templates/networkxml.php');
	}
?>		