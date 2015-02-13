<?php		
	
	/* We get the action sent by the client */
	$action_server = isset($_POST["action"]) ? $_POST["action"]	: "";
	
	include('functions.php');
		
	spl_autoload_register('chargerClasse');
	
	$manager = initManager();
		
	if($action_server == "") {
		return;	
	}
	else if($action_server=="create") {
		$donnees1= $manager->displayListNode();
		$donnees2= $manager->displayListLink();	
		
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
		}
	}
	else if($action_server=="displayCritTable"){
		include("./Views/criticalityTable.php");
	}		
	else if($action_server=="addCritLevel") {
		$critTime 	= $_POST["critTime"];
		$critLvl	= $_POST["critLvl"];
		
		$critSwitch = new CriticalitySwitch($critTime, $critLvl);
		$res = $critSwitch->save();	
	}
	else if($action_server == "addCritState") {
		$critName 	= $_POST["critName"];
		$critCode	= $_POST["critCode"];
		
		echo "::".$critName."::".$critCode;
		
		$critState = new CriticalityLevel($critName, $critCode);
		$res = $critState->save();
	}
	else if($action_server=="saveSettings") {
		$timeLimit = isset($_POST["time"]) ? $_POST["time"]:"";
		$eLatency = isset($_POST["elatency"]) ? $_POST["elatency"]:"";
		
		Settings::save("timelimit", $timeLimit);
		Settings::save("elatency", $eLatency);
		
	}else if ($action_server=="results"){
		$donnees1= $manager->displayListNode();	
		include('./Views/results.php');
			
	}else if($action_server=="settings") {
		include("./Views/settings.php");
	}	/* content load */
	else if($action_server=="messages" || $action_server=="links" || $action_server=="details") {
		$donnees1= $manager->displayListNode();	
		$donnees2= $manager->displayListLink();	
		$donnees3= $manager->displayListMessage();
		
		$tabNames =[];
		foreach ($donnees2 as $element){
			$name1 = $manager->displayNode($element->node1());
			$name2 = $manager->displayNode($element->node2());				
			array_push($tabNames,$name1->name(),$name2->name());
		}	
		
		foreach($donnees3 as $message) {
			$path = explode(",", $message->path());
			
			foreach($path as $machineName) {
				$tempPeriod = $message->period();
				if($tempPeriod == 0) {
					$tempPeriod = 80;
				}
				$currentLoad = $message->wcet()/$tempPeriod;
				
				$machineName = trim($machineName);
				$loadArray[$machineName] += $currentLoad;	
			}
		}
		if($action_server == "messages") 	
			include("./Views/messages.php");
			
		if($action_server == "links") 	
			include("./Views/links.php");
			
		if($action_server == "details") 
			include("./Views/show.php");	
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
		$donnees1= $manager->displayListNode();	
		$donnees2= $manager->displayListLink();	
		$listMessages= $manager->displayListMessage();	
		
		/* Parsing the path string */
		foreach ($listMessages as $singleMessage) {
			$pathId[$singleMessage->id()]=$singleMessage->path();
			
			$path = split(",", $pathId[$singleMessage->id()]);
			
			foreach ($donnees1 as $element1) {				
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
		
		$timeLimit = Settings::getParameter("timelimit");
		$eLatency = Settings::getParameter("elatency");
		
		include('./Templates/network.php');
		
	}else if ($action_server=="generateSimu"){
		include('./Views/results.php');
	}else if($action_server=="editNode"){
		
		$manager->updateNode($_POST['id'],$_POST['label'],$_POST['ipAddress'],$_POST['scheduling'],$_POST['criticality']);
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

	}else if($action_server == "clearGraph"){
		$manager->clearAll();
	}
?>		