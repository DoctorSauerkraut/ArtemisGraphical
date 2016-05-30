<?php

include("../../functions.php");

class Settings {
	
	public static function createSimulation($session_id) {
		$key = 0;
		$bdd = connectBDD();
		$sql = "SELECT * FROM simulations WHERE id_session = \"$session_id\"";
		$result = $bdd->query($sql);
		$resu =$bdd->query($sql);
		$exist=0;
		$exist = $result->fetch();
		

			if($exist!=0){
			if($_SESSION['id_sel']<>null){
				$id_sel=$_SESSION['id_sel'];
				if($id_sel==0){
					// cas 1: création d'une nouvelle simulation suite au clique sur 'new'
					$sql 	= "SELECT id_simu FROM simulations";
					$result = $bdd->query($sql);			
					$idSimu = 0;
					while($data=$result->fetch()){
						if($data["id_simu"] > $idSimu) {
							$idSimu = $data["id_simu"];
						}
					}
					$idSimu++;
					$sql 	= "INSERT INTO simulations (`id_simu`, `id_session`)";
					$sql 	.= " VALUES (\"$idSimu\", \"$session_id\")";
					$result = $bdd->query($sql);
					$_SESSION['id_sel']=NULL;			
					$_SESSION["simuid"] = $idSimu;
					$simuKey = $_SESSION["simuid"];
				}
				else{
					// cas2: attribution de la simulation selon le choix de l'utilisateur, bouton 'select'
					$sql = "SELECT * FROM simulations WHERE id_session = \"$session_id\" AND id_simu=\"$id_sel\"";
					$result = $bdd->query($sql);
					$donne=$result->fetch();
					$_SESSION["simuid"] = $_SESSION['id_sel'];
					$simuKey = $_SESSION["simuid"];
				}
			}
			else{		
				// cas 3: l'utilisateur à déja une session mais n'a pas encore de simu attibuée
				while($data=$resu->fetch()){
					if($data["id_simu"] > $idSimu) {
						$idSimu = $data["id_simu"];
					}
				}		
				$_SESSION["simuid"] = $idSimu;
				$exist=null;
				$_SESSION['id_sel']=null;
				$simuKey = $_SESSION["simuid"];
			}
		}
		else {
			// cas 4: l'utilisateur n'a pas de session, on lui attribue une simulation avec pour id le max de la base +1
			$sql 	= "SELECT id_simu FROM simulations";
				
			$result = $bdd->query($sql);
				
			$idSimu = 0;
			while($data = $result->fetch()){
				if($data["id_simu"] > $idSimu) {
					$idSimu = $data["id_simu"];
				}
			}
			$idSimu++;
				
			$sql  = "INSERT INTO simulations (`id_simu`, `id_session`)";
			$sql .= " VALUES (\"$idSimu\", \"$session_id\")";
				
			$result = $bdd->query($sql);
			$_SESSION['id_sel']=null;				
			$_SESSION["simuid"] = $idSimu;
			$simuKey = $_SESSION["simuid"];
		}
		return $result;
	}

	
	/* Save a config parameter */
	public static function save($key, $value, $simuKey) {
		//Unicity check
		$sql = "SELECT * FROM config where config.key = \"$key\" AND id_simu=\"$simuKey\"";
		$cptRst = 0;
		$result = 0;
		
		$bdd = connectBDD();
		$result = $bdd->query($sql);
		
		while($query=$result->fetch()) {$cptRst++;}
		
		if($cptRst== 0) {
			$sql 	= "INSERT INTO config (`id_simu`,`key`, `value`)";
			$sql 	.= " VALUES (\"$simuKey\", \"$key\", \"$value\")";
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
        
        /* If here : no default value */
        return "";
	}
	
}

?>