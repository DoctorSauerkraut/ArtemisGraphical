<?php
	include("../../functions.php");
	
	// classe message
	class Message{

		// attributs
		
		private $_id;
		private $_path;
		private $_period;
		private $_offset;
		private $_wcet;
		private $_color;

		private static $_idnb=0;

		// Methodes

		public function __construct(){
		}
		
		public function hydrate(array $donnees)
		{
			foreach ($donnees as $key => $value)
			{
				// On récupère le nom du setter correspondant à l'attribut.
				$method = 'set'.ucfirst($key);
					
				// Si le setter correspondant existe.
				if (method_exists($this, $method))
				{
				  // On appelle le setter.
				  $this->$method($value);
				}
			}
		}

		//accesseur 
		public function id(){
			return $this->_id;
		}
		public function path(){
			return $this->_path;
		}
		public function period(){
			return $this->_period;
		}
		public function offset(){
			return $this->_offset;
		}
		
		public function wcet() {	
			return $this->wcet_("NC");
		}
		
		public function color() {	
			return $this->_color;
		}

		public function wcet_($critLvl){
			
			$critLvl = CriticalityLevel::getIdFromLevel($critLvl);	
			
			$sql = "SELECT value FROM wcets WHERE id_msg=\"".$this->_id."\" AND id_clvl=\"$critLvl\"";
	
			$bdd = connectBDD();
			$req = $bdd->query($sql);
			
			if($rst = $req->fetch()){
				return $rst["value"];	
			}
			
			return -1;
		}
		
		public function setWcet($wcet) {
			$this->_setWcet($wcet, "NC");
		}

		public function setColor($colorP) {
			$this->_color = $colorP;
		}
		
		public function getAllWcet() {
			$sql 	= "SELECT wcets.value, critlevels.code FROM wcets, critlevels ";
			$sql   .= "WHERE id_msg=\"".$this->_id."\" AND critlevels.id = wcets.id_clvl";

			$bdd = connectBDD();
			$req = $bdd->query($sql);
			
			return $req;
		}
		
		public function _setWcet($wcet, $critLvl){      
			/* Selecting existing message */
			$wcetBDD = $this->wcet_($critLvl);
			
			$critLvl = CriticalityLevel::getIdFromLevel($critLvl);		
			
			if($wcet == -1 && $wcetBDD == -1) {
				// No message previously created, and no message to create;
				return;	
			}
			
			if($wcet == -1 && $wcetBDD != -1) {
				// Message previously created, to delete
				$sql = "DELETE FROM wcets WHERE id_msg=\"".$this->_id."\" AND id_clvl=\"$critLvl\"";
			}
			
			if($wcet != - 1 && $wcetBDD == -1) {
				// No message previously created, to create
				$sql 	= "INSERT INTO wcets(id_msg, id_clvl, value)";
				$sql 	.= " VALUES(".$this->_id.",".$critLvl.",".$wcet.")";
			}
			
			if($wcet != -1 && $wcetBDD != -1) {
				// Message previously created, to update
				$sql = "UPDATE wcets SET value=\"$wcet\" WHERE id_msg=\"".$this->_id."\" AND id_clvl=\"$critLvl\"";
			}

			$bdd = connectBDD();
			$bdd->query($sql);
		}
		
		//mutateur
		
		public static function deleteMessage($id) {
			$sql 	= "DELETE FROM wcets ";
			$sql 	.= "WHERE id_msg=\"$id\"";
			
			$bdd = connectBDD();
			$bdd->query($sql);
		}
		
		public function setId($id){
			$this->_id=$id;
		}
		
		public function setPath($path){
			if (is_string($path)){
					$this->_path=$path;
				}
		}
		
		public function setPeriod($period){
			$this->_period=$period;
		}

		public function setOffset($offset){
			$this->_offset = $offset;
		}

		

	}

?>