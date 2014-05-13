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
 
 public function addNode(Node $node){
 $q = $this->_db->prepare('INSERT INTO node SET name = :name, scheduling = :scheduling, criticality = :criticality')or die(print_r($_db->errorInfo()));
 
 $q->bindValue(':name',$node->name());
 $q->bindValue(':scheduling', $node->scheduling());
 $q->bindValue(':criticality', $node->criticality(),PDO::PARAM_INT);
 
 $q->execute();
 }
 
 public function deleteNode($id){
 $this->_db->exec('DELETE FROM node WHERE id = '.$id);
 }
 
 public function displayNode($id){
 $id = (int) $id;
 
 $q= $this->_db->query('SELECT id, name, scheduling, criticality FROM node WHERE id = '.$id)or die(print_r($_db->errorInfo()));
 $donnees = $q->fetch(PDO::FETCH_ASSOC);
 
 return new node($donnees);
 }
 
 public function displayListNode(){
 $nodes = array();
 
 $q = $this->_db->query('SELECT id, name, scheduling, criticality FROM node')or die(print_r($_db->errorInfo()));
 
 while ($donnees = $q->fetch(PDO::FETCH_ASSOC)){
 $tmp = new node();
 $tmp->hydrate($donnees);
 $nodes[]=$tmp;

 }
 return $nodes;
 }
 
 public function updateNode(Node $node){
 $q->$this->_db->prepare('UPDATE node SET name = :name, scheduling = :scheduling, criticality = :criticality WHERE id = :id')or die(print_r($_db->errorInfo()));
 
 $q->bindValue(':name',$node->name());
 $q->bindValue(':scheduling', $node->scheduling());
 $q->bindValue(':criticality', $node->criticality(),PDO::PARAM_INT);
 $q->bindValue(':id', $node->id(), PDO::PARAM_INT);
 
 $q->execute();
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
 
  public function updateLink(Link $link){
 $q->$this->_db->prepare('UPDATE link SET node1 = :node1, node2 = :node2 WHERE id = :id')or die(print_r($_db->errorInfo()));
 
 $q->bindValue(':node1',$link->node1());
 $q->bindValue(':scheduling', $link->node2());
 $q->bindValue(':id', $link->id(), PDO::PARAM_INT);
 $q->execute();
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
 
  public function updateMessage(Message $message){
 $q->$this->_db->prepare('UPDATE message SET path = :path , period = :period, offset = :offset, wcet = :wcet WHERE id = :id')or die(print_r($_db->errorInfo()));
 
 $q->bindValue(':path',$message->path());
 $q->bindValue(':period', $message->period());
 $q->bindValue(':offset',$message->offset());
 $q->bindValue(':wcet', $message->wcet());
 $q->execute();
 }
}

?>

