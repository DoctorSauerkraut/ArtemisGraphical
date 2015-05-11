<?php

include("../../functions.php");

class Settings {
	
	public static function save($key, $value) {
		//Unicity check
		$sql = "SELECT * FROM config where config.key = \"$key\"";
	
		$cptRst = 0;
		
		$bdd = connectBDD();
		$result = $bdd->query($sql);

		echo "::".$sql;
		
		while($query=$result->fetch()) {$cptRst++;}

		if($cptRst== 0) {
			$sql 	= "INSERT INTO config (`key`, `value`)";
			$sql 	.= " VALUES (\"$key\", \"$value\")";
		}
		else {
			$sql 	= "UPDATE config";
			$sql 	.= " SET value = \"$value\"";
			$sql 	.= " WHERE config.key = \"$key\"";
		}
		
		echo "::".$sql;
		
		$bdd = connectBDD();
		$result = $bdd->query($sql);
			
	}
	
	public static function getParameter($key) {
		$sql = "SELECT * FROM config where config.key = \"$key\"";
		
		$bdd = connectBDD();
		$result = $bdd->query($sql);

		
		while($tuples=$result->fetch()) {
			return $tuples["value"];	
		}
	}
	
}

?>