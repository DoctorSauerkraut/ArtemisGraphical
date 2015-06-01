<?php
		
	// classe node
	class Node{

		// attributs
		
		private $_id;
		private $_name;
		private $_ipAddress= 0;
		private $_scheduling = 'FIFO';
		private $_criticality= 0;
		private $_links;
		private $_displayed;
		
		const SCHEDULING_FIFO = 'FIFO';
		const SCHEDULING_FP = 'FP';
		const SCHEDULING_EDF = 'EDF';
		const SCHEDULING_RM = 'RM';

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
	if( $key =="ip_address"){
	$method = 'setIpAddress';
	}
        
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
		
		public function criticality(){
			return $this->_criticality;
		}
		
		public function name(){
			return $this->_name.trim();
		}
		public function scheduling(){
			return $this->_scheduling;
		}
		
		public function ipAddress(){
			return $this->_ipAddress;
		}
		//mutateur
		public function setCriticality($crit){
			$this->_criticality=$crit;
		}

		public function setId($id){
			$this->_id=$id;
		}
		
		public function setIpAddress($ip){

			$this->_ipAddress=$ip;
		}

		public function setScheduling($sched){

			if (in_array($sched, array(self::SCHEDULING_FP, self::SCHEDULING_FIFO, self::SCHEDULING_EDF, self::SCHEDULING_RM)))
			{
			  $this->_scheduling = $sched;
			}
		}

		public function setName($name){
			if(is_string($name))
			{
				$this->_name=$name;
			}
		}
		
		public function setDisplayed($displayed) {
			$this->_displayed = $displayed;
		}

		public function isDisplayed() {
			return $this->_displayed;
		}
	}

?>