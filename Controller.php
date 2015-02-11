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
		
		$manager->addNode($_POST["name"],$_POST["ip"],$_POST["sched"],$_POST["crit"]);
			
	}else if ($action_server=="updateNode"){
		
		$manager->updateNode($_POST["id"],$_POST["name"],$_POST["ip"],$_POST["sched"],$_POST["crit"]);
			
	}else if ($action_server=="addMessage"){
	
		$newpath = $manager->verrifyPath($_POST["path"]);
		if ($newpath != ""){
			$manager->addMessage($newpath,$_POST["period"],$_POST["offset"],$_POST["wcet"]);
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

		$manager->deleteMessage($_POST['id']);	
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
		$donnees3= $manager->displayListMessage();	
		
		foreach ($donnees3 as $element3) {
			$pathId[$element3->id()]=$element3->path();
			foreach ($donnees1 as $element1) {
				$pathId[$element3->id()] = str_replace(trim($element1->name()),$element1->id(),$pathId[$element3->id()]);
			}
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
	
		$newpath = $manager->verrifyPath($_POST["path"]);
		if ($newpath != ""){
			$manager->updateMessage($_POST['id'],$newpath,$_POST['period'],$_POST['offset'],$_POST['wcet']);	
		}else {
			echo "/!\ Impossible Path, you need to create the corresponding links or nodes. /!\ ";
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

	}else if($action_server == "clearGraph"){
		$manager->clearAll();
	}
?>		