<?php

include ("../../functions.php");

/* Class used to create nodes, links and messages in the network, and manipulate them */
class SimulationManager {
    private $manager;
    private $simuKey;
    
     public function __construct($managerP, $simuKeyP) {
        $this->manager  = $managerP;
        $this->simuKey  = $simuKeyP;
    }
    
    /* Builds XML files and launches a simulation */
    public function generate($pathToCore) {
         /* Default config values */
        if(Settings::getParameter("timelimit") == "") {Settings::save("timelimit", 100, $this->simuKey);}
        if(Settings::getParameter("elatency") == "") {Settings::save("elatency", 0, $this->simuKey);}
        if(Settings::getParameter("endgraphtime") == "") {Settings::save("endgraphtime", 100, $this->simuKey);}
        if(Settings::getParameter("startgraphtime") == "") {Settings::save("startgraphtime", 0, $this->simuKey);}     
        
		$pathId =[];
		$list_nodes   = $this->manager->displayListNode($this->simuKey);	
		$donnees2     = $this->manager->displayListLink($this->simuKey);	
		$listMessages = $this->manager->displayListMessage($this->simuKey);	
		
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
							$path[$cptPath] = $element1->ipAddress();
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
			$name1 = $this->manager->displayNode($element->node1());
			$name2 = $this->manager->displayNode($element->node2());				
			array_push($tabNames,$name1->name(),$name2->name());
		}

		/* Get general settings */
		$timeLimit 	  = Settings::getParameter("timelimit", $this->simuKey);
		$eLatency 	  = Settings::getParameter("elatency", $this->simuKey);
		$autogen 	  = Settings::getParameter("autogen", $this->simuKey);
		$wcttmodel    = Settings::getParameter("wcttmodel", $this->simuKey);
		$wcttrate     = Settings::getParameter("wcttrate", $this->simuKey);
		$switch 	  = Settings::getParameter("switch", $this->simuKey);
        $protocol	  = Settings::getParameter("protocol", $this->simuKey);
        $wcAnalysis     = Settings::getParameter("wcanalysis", $this->simuKey);

		if($autogen == 0) {
			$highestwcet	= Settings::getParameter("highestwcet", $this->simuKey);
			$autotasks 		= Settings::getParameter("autotasks", $this->simuKey);
			$autoload		= Settings::getParameter("autoload", $this->simuKey);
		}
		
		Settings::save("startgraphtime", 0, $this->simuKey);
		Settings::save("endgraphtime", $timeLimit, $this->simuKey);
        
        $simuKey = $this->simuKey;
        $manager = $this->manager;
        
		include('./Templates/graphconfig.php');
		include('./Templates/networkxml.php');
        include('./Templates/messagesxml.php');
        include('./Templates/globalconfig.php');
    }
}

?>