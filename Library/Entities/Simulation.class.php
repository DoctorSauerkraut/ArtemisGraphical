<?php
include ("../../functions.php");

class Simulation {
	private $id;
	private $session;
	
	public static function loadSimulationsForSession($idSession) {
		$sql = 	"SELECT id_simu FROM simulations WHERE id_session=\"".$idSession."\"";
		
		$bdd = connectBDD();
		
		$req = $bdd->query($sql) or die(print_r($bdd->errorInfo()));
			
		return $req;
	}
}