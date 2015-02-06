<?php
include("../../functions.php");

class CriticalitySwitch {
	private $time;
	private $level;
	
	public function setTime($time_) {
		$this->time = $time_;	
	}
	
	public function getTime() {
		return $this->time;	
	}
	
	public function setLevel($level_) {
		$this->level = $level;	
	}
	
	public function getLevel() {
		return $this->level;	
	}
	
	public function save() {
		$sql = 	"INSERT INTO critswitches(time, level)";
		$sql .= "VALUE('$time', \"$level\")";
		
		$bdd = connectBDD();
		$bdd->query($sql);
	}
	
	public static function load() {
		$sql = 	"SELECT time, level FROM critswitches";
		
		$bdd = connectBDD();

		$req = $bdd->query($sql) or die(print_r($bdd->errorInfo()));
			
		return $req;
	}
}

?>