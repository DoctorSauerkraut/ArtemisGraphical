<?php
session_start();

	$action_server = isset($_POST["action"]) ? $_POST["action"]	: "";
	
	include('functions.php');
		
	spl_autoload_register('chargerClasse');
	
	$manager = initManager();
	$id = session_id();
	$ret = create_session($id);
	$manager->setSimuId($_SESSION["simuid"]);
	$simuKey = $_SESSION["simuid"];
 
	/* content load */
	if($action_server=="messages" || $action_server=="links" || $action_server=="details") {
		
		
		$donnees1= $manager->displayListNode($simuKey);	
		$list_links= $manager->displayListLink($simuKey);	
		$donnees3= $manager->displayListMessage($simuKey);
		
		$tabNames =[];
		foreach ($list_links as $element){
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
	} 	else if($action_server=="settings") {
		include("./Views/settings.php");
	}	else if($action_server=="mixedc"){
		include("./Views/criticality.php");	
	}	else if($action_server == "simus") {
		include("./Views/simulations.php");	
	}   else if($action_server == "credits") {
		include("./Views/credits.php");	
	}
?>