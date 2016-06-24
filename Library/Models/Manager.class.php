<?php
class Manager{

	private $_db;
	private $counter=0;
	private $simulationId;
	
	 public function __construct($db){
		 $this->setDb($db);
	 }
	 public function setDb(PDO $db){
		 $this->_db = $db;
	 }
 
  	public function setSimuId($idP) {
  		$this->simulationId = $idP;
  	}
  	
  	public function getSimuId() {
  		return $this->simulationId;
  	}
 	
  public function clearAll(){
  	$this->_db->exec("DELETE FROM config WHERE id_simu = ".$this->simulationId);
   $this->_db->exec("DELETE FROM message WHERE id_simu = ".$this->simulationId);
   $this->_db->exec("DELETE FROM link WHERE id_simu = ".$this->simulationId);
   $this->_db->exec("DELETE FROM node WHERE id_simu = ".$this->simulationId);
   $this->_db->exec("DELETE FROM critswitches WHERE id_simu = ".$this->simulationId);
   // $this->_db->exec("DELETE FROM wcets WHERE id_simu = ".$this->simulationId); 
   //$this->_db->exec('TRUNCATE TABLE critlevels');  
  }
  
 ////////////////////////////////////////////////    PART NODE     ///////////////////////////////////////////////////

 public function nbNodes(){
 	$q= $this->_db->query('SELECT count(id) FROM node ')or die(print_r($_db->errorInfo()));
 	$donnees = $q->fetch(PDO::FETCH_ASSOC);
	
 	return $donnees['count(id)'];
 }
 
 
 public function addNode($name, $ip, $sched){
	 if($name == ''){
	 $name = "Unnamed ".$counter;
		 $counter++;
	 }
	 $sql = 'INSERT INTO node SET name = :name, id_simu = :id_simu, ip_address = :ip_address, scheduling = :scheduling';
	 $q = $this->_db->prepare($sql)or die(print_r($_db->errorInfo()));
	 
	 $q->bindValue(':name',$name);
	 $q->bindValue(':id_simu',$this->simulationId);
	  $q->bindValue(':ip_address',$ip);
	 $q->bindValue(':scheduling', $sched);
	 
	 $q->execute();
 }
 
 public function deleteNode($id){
 	$this->_db->exec('DELETE FROM node WHERE id = '.$id);
 }
 
 public function displayNode($id){
	 $id = (int) $id; 
	 
	 $request='SELECT id, name, ip_address, scheduling, displayed, speed, shape FROM node WHERE id = '.$id;

	 $q= $this->_db->query($request)or die(print_r($_db->errorInfo()));
	 $donnees = $q->fetch(PDO::FETCH_ASSOC);
     
	 $tmp =new node();
	 $tmp->hydrate($donnees);
	 return $tmp;
 }
 
  public function displayNodeByName($name){
	$sql  = 'SELECT id, name, ip_address, scheduling, displayed, speed, shape FROM node WHERE name = "'.$name.'"';
	$sql .= " AND id_simu = \"".$this->simulationId."\""; 
	 $q= $this->_db->query($sql)or die (print_r($_db->errorInfo()));
	 	 
	 $donnees = $q->fetch(PDO::FETCH_ASSOC);
	 $tmp = new node();
	 
	 if($donnees != null){
	 	$tmp->hydrate($donnees);
		return $tmp;
	 }else {
	 	return null;
	 }

 } 
   
 public function displayListNode($simuId){
 	$this->simulationId = $simuId;
 	return $this->displayListNode_();
 }
 
 
 public function displayListNode_(){
	 $nodes = array();
	 $sql  = 'SELECT id, name, ip_address, scheduling, displayed, speed, shape FROM node ';
	 $sql .= "WHERE id_simu = \"".$this->simulationId."\""; 

	 $q = $this->_db->query($sql) or die(print_r($_db->errorInfo()));
	 while ($donnees = $q->fetch(PDO::FETCH_ASSOC)){
		 $tmp = new node();
		 $tmp->hydrate($donnees);
		 $nodes[]=$tmp;
	  }
 return $nodes;
 }
 
 
public function updateNodeC($id, $name, $ip, $sched, $disp, $speed) {
	$sql  = 'UPDATE node SET name = :name, ip_address = :ip_address, scheduling = :scheduling, displayed = :displayed, speed = :speed';
	$sql .= ' WHERE id = :id';
			
	$q=$this->_db->prepare($sql)or die(print_r($_db->errorInfo()));
	
	$q->bindValue(':name',$name);
	$q->bindValue(':ip_address', $ip, PDO::PARAM_INT);
	$q->bindValue(':scheduling', $sched);
	$q->bindValue(':id', $id, PDO::PARAM_INT);
	$q->bindValue(':displayed', $disp, PDO::PARAM_INT);
	$q->bindValue(':speed', $speed, PDO::PARAM_INT);

	$q->execute();
}

public function updateNodeS($id, $name, $ip, $sched, $speed){
	$this->updateNodeC($id, $name, $ip, $sched, 0, $speed);
}

 public function updateNode($id, $name, $ip, $sched){
	$this->updateNodeC($id, $name, $ip, $sched, 0, 1);
 }

 public function insertShape($id, $shape) {
 	echo $shape.'   ';
	$sql  = 'UPDATE node SET shape = :shape';
	$sql .= ' WHERE id = :id';
			
	$q=$this->_db->prepare($sql)or die(print_r($_db->errorInfo()));
	
	$q->bindValue(':id', $id, PDO::PARAM_INT);
	$q->bindValue(':shape', $shape);

	$q->execute();
}
 
 public function verifyNodeDeletion($idnode,$name){
 // We check in the link database if we need to delete some links 
	$q=$this->_db->query('SELECT id FROM link WHERE node1 ='.$idnode.' OR node2 = '.$idnode);
	while ($donnees = $q->fetch(PDO::FETCH_ASSOC)){
			$this->deleteLink($donnees['id']);
	}  
// We now check in the Message database if some messages have to be deleted.
	$donnees=$this->displayListMessage();
	$nametested=trim($name);
	foreach($donnees as $element){
		$path=explode(",", $element->path(), 100);
		foreach($path as $apath){
				print_r (" actual name ".$apath);
			if ($apath==$nametested){
				$this->deleteMessage($element->id());
				break;
			}
		}
	}
 }
 
////////////////////////////////////////////////////////     PART LINK    /////////////////////////////////////////////////////////

 public function addLink($node1, $node2){
	 echo ($node1.$node2);
	 $q = $this->_db->prepare('INSERT INTO link SET id_simu = :id_simu, node1 = :node1, node2 = :node2')or die(print_r($_db->errorInfo()));
	 $q->bindValue(':id_simu',$this->simulationId);
	 $q->bindValue(':node1',$node1);
	 $q->bindValue(':node2', $node2);
	 $q->execute();
 }

  public function deleteLink($id){
	 $this->_db->exec('DELETE FROM link WHERE id = '.$id);
 }
 
  public function displayLink($id){
	 $id = (int) $id;
	 $q= $this->_db->query('SELECT id, node1, node2 FROM link WHERE id = '.$id)or die(print_r($_db->errorInfo()));
	 $donnees = $q->fetch(PDO::FETCH_ASSOC);
	 $tmp = new link();
	 $tmp->hydrate($donnees);
	 return $tmp;
 }
 
 public function displayListLink($simuId){
 	$this->simulationId = $simuId;
 	return $this->displayListLink_();
 }
 
  public function displayListLink_(){
	$links = array();
	$sql  = 'SELECT id, node1, node2 FROM link ';
	$sql .= "WHERE id_simu = \"".$this->simulationId."\""; 
	
	$q = $this->_db->query($sql)or die(print_r($_db->errorInfo()));
		 while ($donnees = $q->fetch(PDO::FETCH_ASSOC)){
			 $tmp = new link();
			 $tmp->hydrate($donnees);
			 $links[]=$tmp;
		 }
	return $links;
 }
 
  public function updateLink($id, $node1, $node2){
	 $q=$this->_db->prepare('UPDATE link SET node1 = :node1, node2 = :node2 WHERE id = :id')or die(print_r($_db->errorInfo()));
	 
	 $q->bindValue(':node1',$node1);
	 $q->bindValue(':node2', $node2);
	 $q->bindValue(':id', $id, PDO::PARAM_INT);
	 $q->execute();
 }
 
  public function verifyLinkDeletion($node1, $node2){
  // We check in the Message database if some messages have to be deleted.
	$donnees=$this->displayListMessage();
	foreach($donnees as $element){
		$path=explode(",", $element->path(), $this->nbNodes());
		$prev="";
		$next="";
		foreach($path as $apath){
		$prev=$next;
		$next=$apath;
			if (($prev==trim($node1) && $next==trim($node2))||($prev==trim($node2) && $next==trim($node1))){
				$this->deleteMessage($element->id());
				break;
			}
		}
	}
 }
////////////////////////////////////////////////////////     PART MESSAGES   //////////////////////////////////////////////////////

 public function addMessage($path, $period, $offset, $color){
	$sql 	= "INSERT INTO message(id_simu, path, period, offset, color)";
	$sql 	.= "VALUES(\"".$this->simulationId."\", \"$path\",\"$period\", \"$offset\",\"$color\")";

	$this->_db->exec($sql);
	 
	$id = $this->_db->lastInsertId();

	return $id;
	 }

  public function deleteMessage($id){
 	$this->_db->exec('DELETE FROM message WHERE id = '.$id);
  }
 
  public function displayMessage($id){
 $id = (int) $id;
 $sql  = 'SELECT id, path, period, offset, color FROM message WHERE id = '.$id;
 $sql .= ' WHERE id_simu = \"'.$this->simulationId.'\"'; 
 		
 $q= $this->_db->query($sql)or die(print_r($_db->errorInfo()));
 $donnees = $q->fetch(PDO::FETCH_ASSOC);
 return new message($donnees);
 }
 
 public function displayListMessage($simuId){
 	
 	$this->simulationId = $simuId;
 	return $this->displayListMessage_();
 }

  public function displayListMessage_(){
	$messages = array();
	$sql  = 'SELECT id, path, period, offset, color FROM message ';
	$sql .= 'WHERE id_simu = "'.$this->simulationId.'"';
      
	$q = $this->_db->query($sql)or die(print_r($_db->errorInfo()));
		
		 while ($donnees = $q->fetch(PDO::FETCH_ASSOC)){
			 $tmp = new Message();
			 $tmp->hydrate($donnees);
			 $messages[]=$tmp;
		 }
	return $messages;
 }
    
    public function getMessageID($path, $period, $offset) {
        $sql = "SELECT id FROM message ";
        $sql .= "WHERE path=\"$path\" ";
        $sql .= "AND period=\"$period\" ";
        $sql .= "AND offset=\"$offset\" ";
        $sql .= 'AND id_simu = "'.$this ->simulationId.' "';
        $sql .= "ORDER BY id, id_simu";

        $q= $this->_db->query($sql)or die (print_r($_db->errorInfo()));
	 	 
        $data = $q->fetch(PDO::FETCH_ASSOC);

        $tmp = new Message($data);
        
         if($data != null){
            $tmp->hydrate($data);
             
            return $tmp->id();
         }else {
            return null;
         }
    }

  public function updateMessage($id, $path, $period, $offset, $color){
  
   $nodes = explode(",",$path);
		$newpath="";
	foreach ($nodes as $element){
		 $newpath = $newpath.trim($element).",";  
	}
	$newpath=substr($newpath,0,-1);
	 $sql 	= "UPDATE message ";
	 $sql 	.= "SET path=\"$newpath\",";
	 $sql 	.= "period = \"$period\",";
	 $sql 	.= "offset = \"$offset\",";
	 $sql 	.= "color = \"$color\" ";
	 $sql	.= "WHERE id=\"$id\"";
	 $q = $this->_db->query($sql)or die(print_r($_db->errorInfo()));
}
 
/* Check if path is correct */
 public function verrifyPath($path){
 
 	 $nodes = explode(",",$path);
 	 
	 $newpath="";
	 
	 foreach ($nodes as $element){
		 $newpath = $newpath.trim($element).",";  
		}
	$newpath=substr($newpath,0,-1);
	  $nodes = explode(",",$newpath);
	  $nodesid=[];
	  
	  foreach ($nodes as $element ){
	  	$tmp=$this->displayNodeByName($element);
	  	
	  	if($tmp == null){
	  		return "";
	  	}
	  
	  	array_push($nodesid,$tmp->id());
	  }
	$donnees = $this->displayListLink_();

	$counter = 0;

	foreach ( $donnees as $element ){
		for($i = 0, $size = count($nodesid)-1;$i<$size; $i++){
			if ($nodesid[$i] == $element->node1() && $nodesid[$i+1] == $element->node2() ||
					 $nodesid[$i] == $element->node2() && $nodesid[$i+1] == $element->node1()){
				$counter++;
			break;
		} 
	}
	}
	if($counter !=  count($nodesid)-1){
		return '';
	}
	return $newpath;
 }
 

}



?>

