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
		
		public function wcet_($critLvl){
			
			$critLvl = CriticalityLevel::getIdFromLevel($critLvl);	
			
			$sql = "SELECT value FROM wcets WHERE id_msg=\"".$this->_id."\" AND id_clvl=\"$critLvl\"";
	
			$bdd = connectBDD();
			$req = $bdd->query($sql);
			
			if($rst = $req->fetch()){
				return $rst["value"];	
			}
			
			return 0;
		}
		
		public function setWcet($wcet) {
			$this->_setWcet($wcet, CriticalityLevel::getIdFromLevel("NC"));
		}
		
		public function _setWcet($wcet, $critLvl){
			$sql = "UPDATE wcets SET value=\"$wcet\" WHERE id_msg=\"".$this->_id."\" AND id_clvl=\"$critLvl\"";
			echo "::".$sql;
			
			$bdd = connectBDD();
			$bdd->query($sql);
		}
		
		//mutateur

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