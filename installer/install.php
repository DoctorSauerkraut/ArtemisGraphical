<?php

	if ($_POST['user']!='' && $_POST['pass']!='' ){
	
	// Donnees
		$user = $_POST['userinit'];
		$password = $_POST['passinit'];
		$host = "localhost";
		$db=$_POST['db'];

	// Connection au server
		mysql_connect($host,$user,$password);
	
	//Création de la base de données
		$requetes="";
		$sql=file("artemis.sql"); // on charge le fichier SQL
		foreach($sql as $l){ // on le lit
			if (substr(trim($l),0,2)!="--"){ // suppression des commentaires
				$requetes .= $l;
			}
		}
		 
		$reqs = explode(";",$requetes);// on sépare les requêtes
		foreach($reqs as $req){	// et on les éxécute
			if (!mysql_query($req) && trim($req)!=""){
				die("ERROR : ".$req); // stop si erreur 
			}
		}
	
	//Creation de l'utilisateur
		$add_user = "CREATE USER `".$_POST['user']."`@'localhost' IDENTIFIED BY '".$_POST['pass']."'";
		$query = mysql_query($add_user);		
		//$privileges = "GRANT ALL PRIVILEGES ON *.* TO '".$_POST['user']."'@'%' IDENTIFIED BY '".$_POST['pass']."'";
		//mysql_query($privileges);
		$privileges = "GRANT ALL PRIVILEGES ON *.* TO '".$_POST['user']."'@'localhost' IDENTIFIED BY '".$_POST['pass']."'";
		mysql_query($privileges);

		// création du fichier config.php
		$file=fopen("../config.php","r+");
		fwrite($file,'<?php $db_name="'.$db.'"; $db_user="'.$_POST['user'].'"; $db_pass="'.$_POST['pass'].'"; $db_host="localhost"; ?>');
		fclose($file);
		
		// Vérification finale et suppression de l'installer
		if(mysql_select_db($db) && $query == TRUE ) {
		echo "<p> Installation Done.</p>";
		echo"<a href='../index.php'>Start ARTEMIS</a>";
		unlink("artemis.sql");
		unlink("index.php");
		unlink("install.php");
		rmdir("../installer");
		}
		
	}else {
		echo "<p> You need to fill all the fields to go to the next step !</p>";
		echo"<a href='index.php'>Return to the form</a>";
	}
?>