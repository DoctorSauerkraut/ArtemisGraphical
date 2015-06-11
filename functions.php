<?php		
	 function connectBDD() {
	 	try
		{
			include('config.php');
			$bdd = new PDO("mysql:host=$db_host;dbname=$db_name", "$db_user", "$db_pass",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
			
		}
		catch (Exception $e)
		{
			die('Erreur : ' . $e->getMessage());
		}
		
		return $bdd;
	 }
		
	function chargerClasse($classe)
		{
		if ($classe == 'Manager' ){
		  require 'Library/Models/'.str_replace('\\', '/', $classe).'.class.php'; 
		  require 'Library/Entities/Node.class.php';
		  require 'Library/Entities/Link.class.php';
		  require 'Library/Entities/Message.class.php';
		  require 'Library/Entities/CriticalitySwitch.class.php';
		  require 'Library/Entities/CriticalityLevel.class.php';
		  require 'Library/Entities/Settings.class.php';
		  require 'Library/Entities/Simulation.class.php';
		  }
		}
		
	function initManager() {
		
		$bdd = connectBDD();
		
		$manager = new Manager($bdd);
		
		return $manager;
	}
 
 	function is_dir_empty($dir) {
	  if (!is_readable($dir)) return NULL; 
	  $handle = opendir($dir);
	  while (false !== ($entry = readdir($handle))) {
	    if ($entry != "." && $entry != "..") {
	      return FALSE;
	    }
	  }
	  return TRUE;
	}
	
	function getSessionId() {
		return session_id();
		
	}
	function create_session($session_id) {
		return Settings::createSimulation($session_id);
	}
?>