<?php
		
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
		public function wcet(){
		return $this->_wcet;
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

		public function setWcet($wcet){
				$this->_wcet=$wcet;
		}

	}

?>