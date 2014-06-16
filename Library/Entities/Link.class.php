<?php
		
	// classe link
	class Link{

		// attributs
		
		private $_id;
		private $_node1;
		private $_node2;

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
		public function node1(){
		return $this->_node1;
		}
		public function node2(){
		return $this->_node2;
		}

		//mutateur

		public function setId($id){
		//if(is_int($id)){
			$this->_id=$id;
			//}
		}
		
		public function setNode1($id){
			$this->_node1=$id;
		}

		public function setNode2($id){
			  $this->_node2 = $id;
		}



	}

?>