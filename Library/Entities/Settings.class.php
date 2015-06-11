<?php

include("../../functions.php");

class Settings {
	
	public static function createSimulation($session_id) {
		$key = 0;
		$bdd = connectBDD();
		
		$sql = "SELECT * FROM simulations WHERE id_session = \"$session_id\"";

		$result = $bdd->query($sql);

		if($result->rowCount() == 0) {
			
			/* Generate new id */
			$sql 	= "SELECT id_simu FROM simulations";
			
			$result = $bdd->query($sql);
			echo "::";
			
			$idSimu = 0;
			while($data = $result->fetch()){
				if($data["id_simu"] > $idSimu) {
					$idSimu = $data["id_simu"];
				}
			}
			$idSimu++;
			
			
			$sql 	= "INSERT INTO simulations (`id_simu`, `id_session`)";
			$sql 	.= " VALUES (\"$idSimu\", \"$session_id\")";
			
			$result = $bdd->query($sql);
			
			$_SESSION["simuid"] = $key;
		}
		else {
			$session = ($result->fetch());
			
			$_SESSION["simuid"] = $session["id_simu"];
		}


		return $result;
	}
	
	/* Save a config parameter */
	public static function save($key, $value, $simuKey = 1) {
		//Unicity check
		$sql = "SELECT * FROM config where config.key = \"$key\" AND id_simu=\"$simuKey\"";
	
		$cptRst = 0;
		
		$bdd = connectBDD();
		$result = $bdd->query($sql);
		
		while($query=$result->fetch()) {$cptRst++;}

		if($cptRst== 0) {
			$sql 	= "INSERT INTO config (`id_simu`,`key`, `value`)";
			$sql 	.= " VALUES (\"".$this->idSimu."\", \"$key\", \"$value\")";
		}
		else {
			$sql 	= "UPDATE config";
			$sql 	.= " SET value = \"$value\"";
			$sql 	.= " WHERE config.key = \"$key\"";
			$sql	.= " AND id_simu=\"$simuKey\"";
		}
		
		$bdd = connectBDD();
		$result = $bdd->query($sql);
			
	}
	
	public static function getParameter($key, $simuKey = 1) {
		$sql = "SELECT * FROM config WHERE config.key = \"$key\" AND id_simu=\"$simuKey\"";
		
		$bdd = connectBDD();
		$result = $bdd->query($sql);

		
		while($tuples=$result->fetch()) {
			return $tuples["value"];	
		}
	}
	
}

?>