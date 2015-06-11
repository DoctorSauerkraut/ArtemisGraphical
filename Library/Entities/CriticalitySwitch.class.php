<?php
include("../../functions.php");

class CriticalitySwitch {
	private $time;
	private $level;
	private $simuKey;
	
	public function __construct($time_, $lvl_, $simuKey_) {
		$this->time = $time_;
		$this->level = $lvl_;		
		$this->simuKey = $simuKey_;
	}
	
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
		$sql = 	"INSERT INTO critswitches(id_simu, time, level)";
		$sql .= "VALUES(\"".$this->simuKey."\",\"".$this->time."\", \"".$this->level."\")";
		
		$bdd = connectBDD();
		$bdd->query($sql);
	}
	
	public static function load($idSimu) {
		$sql = 	"SELECT time, level FROM critswitches WHERE id_simu=\"".$idSimu."\"";
		
		$bdd = connectBDD();

		$req = $bdd->query($sql) or die(print_r($bdd->errorInfo()));
			
		return $req;
	}
	
		public static function delete($time, $idSimu) {
		$sql = 	"DELETE FROM critswitches WHERE time=\"$time\" AND id_simu=\"".$idSimu."\"";
		
		$bdd = connectBDD();

		$req = $bdd->query($sql) or die(print_r($bdd->errorInfo()));

		return $req;
	}
}

?>