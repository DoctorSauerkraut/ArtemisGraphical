<?php
include ("../../functions.php");

/* Class used to create nodes, links and messages in the network, and manipulate them */
class ElementsEditor {
    private $manager;
    private $simuKey;
    
    public function __construct($managerP, $simuKeyP) {
        $this->manager  = $managerP;
        $this->simuKey  = $simuKeyP;
    }
    
    public function editNode($id, $label, $ip, $sched, $speed) {
        $this->manager->updateNodeS($id,$label,$ip,$sched,$speed);
        $manager=$this->manager;
		$donnees1= $this->manager->displayListNode($this->simuKey);
		$donnees2= $this->manager->displayListLink($this->simuKey);	
		$donnees3= $this->manager->displayListMessage($this->simuKey);
		$tabNames =[];
        
		foreach ($donnees2 as $element){
			$name1 = $this->manager->displayNode($element->node1());
			$name2 = $this->manager->displayNode($element->node2());				
			array_push($tabNames,$name1->name(),$name2->name());
		}
		include('./Views/show.php');
    }
    public function editNodeSchema($id, $label) {
        $this->manager->updateNodeSchema($id,$label);
        $manager=$this->manager;
		$donnees1= $this->manager->displayListNode($this->simuKey);
		$donnees2= $this->manager->displayListLink($this->simuKey);	
		$donnees3= $this->manager->displayListMessage($this->simuKey);
		$tabNames =[];
        
		foreach ($donnees2 as $element){
			$name1 = $this->manager->displayNode($element->node1());
			$name2 = $this->manager->displayNode($element->node2());				
			array_push($tabNames,$name1->name(),$name2->name());
		}
		include('./Views/create.php');
    }
    
    public function deleteNode($id) {
        $data=$this->manager->displayNode($id); // recupération du noeud à supprimer
		$messages=$this->manager->displayListMessage_(); // récupération des messages
        
		foreach ($messages as $message) { //pour chaque message     
			$path=$message->path(); // on récupère le path
			$path=explode(",",$path); // on en fait un tableau 
			foreach ($path as $node) { // pour chaque noeud du path
				if($data->name()==$node){  // on regarde si le noeud à supprimer est dans le path
					$this->manager->deleteMessage($message->id()); // si le noeud fait parti du path du message on supprime le message
				}					// ca n'a aucun sens de garder un message si l'un de ses noeuds n'existe plus
			}
		}
		$this->manager->deleteNode($id); // suppression du noeud
		$this->manager->verifyNodeDeletion($id,$data->name());
    }
    
    public function addNode($name, $ip, $sched) {
        $this->manager->addNode($name, $ip, $sched);
    }
    
    public function addLink($src, $dest) {
        $this->manager->addLink($src, $dest);	
    }
    
    public function editLink($nodeSrc, $nodeDest) {
        $id1=$this->manager->displayNodeByName($nodeSrc);
		$id2=$this->manager->displayNodeByName($nodeDest);
		if ($id1 != null && $id2 != null){
			$this->manager->updateLink($_POST['id'],$id1->id(),$id2->id());
		}else {
			echo "/!\ Impossible Link, you need to create the corresponding nodes. /!\ ";
		}
		$donnees1     = $this->manager->displayListNode_();	
		$list_links   = $this->manager->displayListLink_();	
		$donnees3     = $this->manager->displayListMessage_();
        
		$tabNames =[];
		foreach ($list_links as $element){
			$name1 = $this->manager->displayNode($element->node1());
			$name2 = $this->manager->displayNode($element->node2());				
			array_push($tabNames,$name1->name(), $name2->name());
		}
		include('./Views/links.php');
    }
    
    public function deleteLink($id, $src, $dest) {
        $this->manager->deleteLink($id);	
		$this->manager->verifyLinkDeletion($src, $dest);
        
		$list_links= $this->manager->displayListLink($this->simuKey);	
		$tabNames =[];
        
		foreach ($list_links as $element){
			$name1 = $this->manager->displayNode($element->node1());
			$name2 = $this->manager->displayNode($element->node2());				
			array_push($tabNames, $name1->name(), $name2->name());
		}				
		include('./Views/links.php');
    }
    
    public function addMessage($path, $offset, $period, $color, $wcetStr) {
		$message = new Message();
		
		$newpath = $this->manager->verrifyPath($path);
		if ($newpath != ""){
			$insertedId = $this->manager->addMessage($newpath,$period,$offset,$color);
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

    }
    
    public function editMessage($id, $period, $offset, $wcetStr, $path, $color) {		
		/* Verify network path */
		$newpath = $this->manager->verrifyPath($path);	
			
		if ($newpath != ""){
            $this->manager->updateMessage($id,$newpath,$period,$offset,$color);	
			
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
		$donnees1=  $this->manager->displayListNode($this->simuKey);	
		$donnees2=  $this->manager->displayListLink($this->simuKey);	
		$donnees3=  $this->manager->displayListMessage($this->simuKey);
		$tabNames =[];
		
		foreach ($donnees2 as $element){
			$name1 =  $this->manager->displayNode($element->node1());
			$name2 =  $this->manager->displayNode($element->node2());				
			array_push($tabNames,$name1->name(),$name2->name());
		}
		include('./Views/messages.php');	
    }
    
    public function deleteMessage($idMsg) {
        $this->manager->deleteMessage($idMsg);	
		Message::deleteMessage($idMsg);
		
		$donnees1= $this->manager->displayListNode_();	
		$donnees2= $this->manager->displayListLink_();	
		$donnees3= $this->manager->displayListMessage_();
		$tabNames =[];
		foreach ($donnees2 as $element){
			$name1 = $this->manager->displayNode($element->node1());
			$name2 = $this->manager->displayNode($element->node2());				
			array_push($tabNames,$name1->name(),$name2->name());
		}
		include('./Views/messages.php');
    }
}

?>