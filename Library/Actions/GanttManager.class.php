<?php
class GanttManager {
    private $manager;
    private $simuKey;
    
     public function __construct($managerP, $simuKeyP) {
        $this->manager  = $managerP;
        $this->simuKey  = $simuKeyP;
    }
    
    /* Rewrite the xml graph config file */
    private function reloadGraphConfig() {
        $list_nodes= $this->manager->displayListNode($this->simuKey);
        
        $simuKey = $this->simuKey;
        $manager = $this->manager;
        
		include('./Templates/graphconfig.php');	 
    }
    
    public function loadNodeForGraph($checked, $nodeId) {
        if($checked==0){
			$checked=1;
		}
		else{
			$checked=0;
		}
		
		$node = $this->manager->displayNode($nodeId);
		$this->manager->updateNodeC($node->id(), $node->name(), $node->ipAddress(), $node->scheduling(), $checked, $node->getSpeed());
        
        $this->reloadGraphConfig();
    }
    
    /* Refresh the Gantt graph
    according to new properties
    */
    public function reloadGraph($startTimeGraph, $endTimeGraph) {
        Settings::save("startgraphtime", $startTimeGraph, $this->simuKey);
		
		$timeLimit = Settings::getParameter("timelimit", $this->simuKey);
		if($endTimeGraph > $timeLimit ) {
			$endTimeGraph = $timeLimit;
		}
		if($startTimeGraph <0 || $startTimeGraph >= $endTimeGraph) {
			$startTimeGraph = 0;
		}	
			
		Settings::save("endgraphtime", $endTimeGraph, $this->simuKey);
		Settings::save("startgraphtime", $startTimeGraph, $this->simuKey);
		
       $this->reloadGraphConfig();  
    }
    
    
}

?>