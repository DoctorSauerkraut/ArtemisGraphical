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
			echo ($info);		
			
			
			/*$id = session_id();
			$ret = create_session($id);
			$manager->setSimuId($_SESSION["simuid"]);
			$manager->setSessionId($id);*/
		}
	}
	else if($action_server=="displayCritTable"){
		include("./Views/criticalityTable.php");
	}		
	else if($action_server=="addCritLevel") {
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
		
	}
	else if($action_server=="saveSettings") {
		/* Getting general settings */
		$timeLimit	= isset($_POST["time"]) ? $_POST["time"]:"";
		$eLatency 	= isset($_POST["elatency"]) ? $_POST["elatency"]:"";
		$tasks 		= isset($_POST["autotasks"]) ? $_POST["autotasks"]:"";
		$hWcet 		= isset($_POST["highestwcet"]) ? $_POST["highestwcet"]:"";
		$autogen 	= isset($_POST["autogen"]) && ($_POST["autogen"] == "y")? "0":"1";
		$autoload 	= isset($_POST["autoload"]) ? $_POST["autoload"]:"";
		
		
		Settings::save("timelimit", $timeLimit, $simuKey);
		Settings::save("elatency", $eLatency, $simuKey);
		Settings::save("autotasks", $tasks, $simuKey);
		Settings::save("highestwcet", $hWcet, $simuKey);
		Settings::save("autogen", $autogen, $simuKey);
		Settings::save("autoload", $autoload, $simuKey);
		
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
			
	}else if ($action_server=="updateNode"){
		
		$manager->updateNode($_POST["id"],$_POST["name"],$_POST["ip"],$_POST["sched"]);
			
	}else if ($action_server=="addMessage"){
		$path 	= $_POST["path"];
		$offset = $_POST["offset"];
		$period = $_POST["period"];
		
		$wcetStr= $_POST["wcetStr"];
		 
		$message = new Message();
		
		$newpath = $manager->verrifyPath($path);
		if ($newpath != ""){
			$insertedId = $manager->addMessage($newpath,$period,$offset);
			
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
	
		$donnees=$manager->displayNode($_POST['id']);
		$manager->deleteNode($_POST['id']);
		$manager->verifyNodeDeletion($_POST['id'],$donnees->name());
		$donnees1= $manager->displayListNode();	
		$donnees2= $manager->displayListLink();	
		$donnees3= $manager->displayListMessage();	
		
		$tabNames =[];
		foreach ($donnees2 as $element){
			$name1 = $manager->displayNode($element->node1());
			$name2 = $manager->displayNode($element->node2());				
			array_push($tabNames,$name1->name(),$name2->name());
		}
		include('./Views/show.php');
		
	}else if($action_server=="deleteLink"){
	
		$manager->deleteLink($_POST['id']);	
		$manager->verifyLinkDeletion($_POST['source'],$_POST['destination']);
		$donnees1= $manager->displayListNode();	
		$donnees2= $manager->displayListLink();	
		$donnees3= $manager->displayListMessage();
		$tabNames =[];
		foreach ($donnees2 as $element){
			$name1 = $manager->displayNode($element->node1());
			$name2 = $manager->displayNode($element->node2());				
			array_push($tabNames,$name1->name(),$name2->name());
		}				
		include('./Views/show.php');
			
	}else if ($action_server=="deleteMessage"){
		$idMsg = $_POST['id'];
		$manager->deleteMessage($idMsg);	
		Message::deleteMessage($idMsg);
		
		$donnees1= $manager->displayListNode();	
		$donnees2= $manager->displayListLink();	
		$donnees3= $manager->displayListMessage();
		$tabNames =[];
		foreach ($donnees2 as $element){
			$name1 = $manager->displayNode($element->node1());
			$name2 = $manager->displayNode($element->node2());				
			array_push($tabNames,$name1->name(),$name2->name());
		}
		include('./Views/show.php');
			
	}else if ($action_server=="generate"){
		
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
		$timeLimit 	= Settings::getParameter("timelimit", $simuKey);
		$eLatency 	= Settings::getParameter("elatency", $simuKey);
		$autogen 	= Settings::getParameter("autogen", $simuKey);
		
		if($autogen == 0) {
			$highestwcet	= Settings::getParameter("highestwcet", $simuKey);
			$autotasks 		= Settings::getParameter("autotasks", $simuKey);
			$autoload		= Settings::getParameter("autoload", $simuKey);
		}
		
		Settings::save("startgraphtime", 0, $simuKey);
		Settings::save("endgraphtime", $timeLimit, $simuKey);
		
	
		include('./Templates/graphconfig.php');
		include('./Templates/network.php');
	
		
	}else if ($action_server=="generateSimu"){
		$list_nodes= $manager->displayListNode($simuKey);

		include('./Views/results.php');
		
	}else if($action_server=="editNode"){
		
		$manager->updateNodeS($_POST['id'],$_POST['label'],$_POST['ipAddress'],$_POST['scheduling'],$_POST['speed']);
		$donnees1= $manager->displayListNode();	
		$donnees2= $manager->displayListLink();	
		$donnees3= $manager->displayListMessage();
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
		$donnees1= $manager->displayListNode();	
		$donnees2= $manager->displayListLink();	
		$donnees3= $manager->displayListMessage();
		$tabNames =[];
		foreach ($donnees2 as $element){
			$name1 = $manager->displayNode($element->node1());
			$name2 = $manager->displayNode($element->node2());				
			array_push($tabNames,$name1->name(),$name2->name());
		}
		include('./Views/show.php');
			
	}else if($action_server=="editMessage"){
		/* Get infos from AJAX request */
		$id 		= $_POST["id"];
		$period 	= $_POST["period"];
		$offset		= $_POST["offset"];
		$wcetStr	= $_POST["wcetStr"];
		$path		= $_POST["path"];
		
		/* Verify network path */
		$newpath = $manager->verrifyPath($path);	
		
		
		if ($newpath != ""){
			$manager->updateMessage($id,$newpath,$period,$offset);	
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
		$donnees3= $manager->displayListMessage();
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
		echo "test";
		Settings::save("startgraphtime", $startTimeGraph, $simuKey);
		
		$timeLimit = Settings::getParameter("timelimit", $simuKey);
		if($endTimeGraph > $timeLimit ) {
			$endTimeGraph = $timeLimit;
		}
		if($startTimeGraph <0 || $startTimeGraph >= $endTimeGraph) {
			$startTimeGraph = 0;
		}
		
		
		$list_nodes= $manager->displayListNode();
		
		Settings::save("endgraphtime", $endTimeGraph, $simuKey);
		Settings::save("startgraphtime", $startTimeGraph, $simuKey);
		
		include('./Templates/graphconfig.php');
		
		//$command = "java -jar artemis_grapher.jar 2>&1 > gen/logs/weblog.txt";
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
	}
?>		