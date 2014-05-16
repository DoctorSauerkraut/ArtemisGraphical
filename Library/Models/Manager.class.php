<?php
class Manager{

private $_db;

 public function __construct($db){
 $this->setDb($db);
 }
 public function setDb(PDO $db){
 $this->_db = $db;
 }
  
 ////////////////////////////////////////////////    PART NODE     ///////////////////////////////////////////////////
 
 public function nbNodes(){
 $q= $this->_db->query('SELECT count(id) FROM node ')or die(print_r($_db->errorInfo()));
 $donnees = $q->fetch(PDO::FETCH_ASSOC);
return $donnees['count(id)'];

 }
 
 
 public function addNode(Node $node){
 $q = $this->_db->prepare('INSERT INTO node SET name = :name, ip_address = :ip_address, scheduling = :scheduling, criticality = :criticality')or die(print_r($_db->errorInfo()));
 
 $q->bindValue(':name',$node->name());
  $q->bindValue(':ip_address',$node->ipAddress());
 $q->bindValue(':scheduling', $node->scheduling());
 $q->bindValue(':criticality', $node->criticality(),PDO::PARAM_INT);
 
 $q->execute();
 }
 
 public function deleteNode($id){
 $this->_db->exec('DELETE FROM node WHERE id = '.$id);
 }
 
 public function displayNode($id){
 $id = (int) $id;
 
 $q= $this->_db->query('SELECT id, name, ip_address, scheduling, criticality FROM node WHERE id = '.$id)or die(print_r($_db->errorInfo()));
 $donnees = $q->fetch(PDO::FETCH_ASSOC);
 
 return new node($donnees);
 }
 
 public function displayListNode(){
 $nodes = array();
 
 $q = $this->_db->query('SELECT id, name, ip_address, scheduling, criticality FROM node')or die(print_r($_db->errorInfo()));
 while ($donnees = $q->fetch(PDO::FETCH_ASSOC)){
 $tmp = new node();
 $tmp->hydrate($donnees);
 $nodes[]=$tmp;

 }
 return $nodes;
 }
 
 public function updateNode($id, $name, $ip, $sched, $crit){
 $q=$this->_db->prepare('UPDATE node SET name = :name, ip_address = :ip_address, scheduling = :scheduling, criticality = :criticality WHERE id = :id')or die(print_r($_db->errorInfo()));
 
 $q->bindValue(':name',$name);
 $q->bindValue(':ip_address', $ip, PDO::PARAM_INT);
 $q->bindValue(':scheduling', $sched);
 $q->bindValue(':criticality', $crit, PDO::PARAM_INT);
 $q->bindValue(':id', $id, PDO::PARAM_INT);
 $q->execute();
 }
 
 public function verifyNodeDeletion($name){
 // We check in the link database if we need to delete some links 
	$q=$this->_db->query('SELECT id FROM link WHERE node1 = "'.$name.'" OR node2 = "'.$name.'"');
	while ($donnees = $q->fetch(PDO::FETCH_ASSOC)){
			$this->deleteLink($donnees['id']);
	}  
// We now check in the Message database if some messages have to be deleted.
	$donnees=$this->displayListMessage();
	foreach($donnees as $element){
		$path=explode(",", $element->path(), $this->nbNodes());
		foreach($path as $apath){
			if ($apath==$name){
				$this->deleteMessage($element->id());
				break;
			}
		}
	}
 }
 
////////////////////////////////////////////////////////     PART LINK    /////////////////////////////////////////////////////////

 public function addLink(Link $link){
 $q = $this->_db->prepare('INSERT INTO link SET node1 = :node1, node2 = :node2')or die(print_r($_db->errorInfo()));
 $q->bindValue(':node1',$link->node1());
 $q->bindValue(':node2', $node->node2());
 $q->execute();
 }

  public function deleteLink($id){
 $this->_db->exec('DELETE FROM link WHERE id = '.$id);
 }
 
  public function displayLink($id){
 $id = (int) $id;
 $q= $this->_db->query('SELECT id, node1, node2 FROM link WHERE id = '.$id)or die(print_r($_db->errorInfo()));
 $donnees = $q->fetch(PDO::FETCH_ASSOC);
 return new link($donnees);
 }
 
  public function displayListLink(){
	$links = array();
	$q = $this->_db->query('SELECT id, node1, node2 FROM link')or die(print_r($_db->errorInfo()));
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
			if (($prev==$node1 && $next==$node2)||($prev==$node2 && $next==$node1)){
				$this->deleteMessage($element->id());
				break;
			}
		}
	}
 }
////////////////////////////////////////////////////////     PART MESSAGES   //////////////////////////////////////////////////////

 public function addMessage(Message $message){
 $q = $this->_db->prepare('INSERT INTO message SET path = :path, period = :period , offset = :offset, wcet = :wcet')or die(print_r($_db->errorInfo()));
 $q->bindValue(':path',$link->path());
 $q->bindValue(':period', $node->period());
  $q->bindValue(':offset',$link->offset());
 $q->bindValue(':wcet', $node->wcet());
 $q->execute();
 }

  public function deleteMessage($id){
 $this->_db->exec('DELETE FROM message WHERE id = '.$id);
 }
 
  public function displayMessage($id){
 $id = (int) $id;
 $q= $this->_db->query('SELECT id, path, period, offset, wcet FROM message WHERE id = '.$id)or die(print_r($_db->errorInfo()));
 $donnees = $q->fetch(PDO::FETCH_ASSOC);
 return new message($donnees);
 }
 
  public function displayListMessage(){
	$messages = array();
	$q = $this->_db->query('SELECT id, path, period, offset, wcet FROM message')or die(print_r($_db->errorInfo()));
		 while ($donnees = $q->fetch(PDO::FETCH_ASSOC)){

		 $tmp = new message();
		 $tmp->hydrate($donnees);
		 $messages[]=$tmp;
		 }
	return $messages;
 }
 
  public function updateMessage($id, $path, $period, $offset, $wcet){
 $q=$this->_db->prepare('UPDATE message SET path = :path , period = :period, offset = :offset, wcet = :wcet WHERE id = :id')or die(print_r($_db->errorInfo()));
 
 $q->bindValue(':path',$path);
 $q->bindValue(':period', $period);
 $q->bindValue(':offset',$offset);
 $q->bindValue(':wcet', $wcet);
  $q->bindValue(':id', $id);
 $q->execute();
 }
}

?>

