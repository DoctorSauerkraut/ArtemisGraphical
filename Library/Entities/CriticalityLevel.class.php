<?php
include("../../functions.php");

class CriticalityLevel {
	private $code;
	private $name;
	
	public function __construct($name_, $code_) {
		$this->setCode($code_);
		$this->setName($name_);	
	}
	
	public function setCode($code_) {
		$this->code = $code_;	
	}	
	
	public function setName($name_) {
		$this->name = $name_;	
	}	
	
	public function getName() {
		return $this->name;	
	}
	
	public function getCode() {
		return $this->code;	
	}
	
	public static function getIdFromLevel($level) {
		$sql = 	"SELECT id FROM critlevels WHERE code=\"$level\"";
		
		$bdd = connectBDD();
		$req = $bdd->query($sql) or die(print_r($bdd->errorInfo()));
		$result = $req->fetch();
		
		return $result["id"];
	}
	
	
	public function save() {
		$sql = 	"INSERT INTO critlevels(code, name)";
		$sql .= "VALUES(\"".$this->code."\", \"".$this->name."\")";
		
		echo "::".$sql;
		
		$bdd = connectBDD();
		$bdd->query($sql);
	}
	
	public static function load() {
		$sql = 	"SELECT code, name FROM critlevels";
		
		$bdd = connectBDD();

		$req = $bdd->query($sql) or die(print_r($bdd->errorInfo()));

		return $req;
	}
}

?>