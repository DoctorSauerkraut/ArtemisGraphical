<?php	
			function chargerClasse($classe)
		{
		if ($classe == 'Manager' ){
		  require 'Library/Models/'.str_replace('\\', '/', $classe).'.class.php'; }
		  else{
			require 'Library/Entities/'.str_replace('\\', '/', $classe).'.class.php';}
		}
		spl_autoload_register('chargerClasse');
		
		//if (!isset($_SESSION["manager"])){
				try
				{
					$bdd = new PDO('mysql:host=localhost;dbname=artemis', 'root', '');
					$manager = new Manager($bdd);
					//$_SESSION["manager"]=$manager;
				}
				catch (Exception $e)
				{
						die('Erreur : ' . $e->getMessage());
				}
				
		if (isset($_GET["action"]))
		{
		
			if ($_GET["action"]=="view"){
				$donnees1= $manager->displayListNode();	
				$donnees2= $manager->displayListLink();	
				$donnees3= $manager->displayListMessage();	
				include(dirname(__FILE__).'./Views/show.php');
			}
			else if ($_GET['action']=="results"){
				
				include(dirname(__FILE__).'./Views/results.php');
				
			}else if ($_GET['action']=="deleteNode"){
			
			$id=$_GET['id'];
			$manager->deleteNode($id);
				$donnees1= $manager->displayListNode();	
				$donnees2= $manager->displayListLink();	
				$donnees3= $manager->displayListMessage();	
				include(dirname(__FILE__).'./Views/show.php');
			
			}else if($_GET['action']=="deleteLink"){
			
			$id=$_GET['id'];
			$manager->deleteLink($id);
			$donnees1= $manager->displayListNode();	
			$donnees2= $manager->displayListLink();	
			$donnees3= $manager->displayListMessage();	
			include(dirname(__FILE__).'./Views/show.php');
				
			}else if ($_GET['action']=="deleteMessage"){
			
			$id=$_GET['id'];
			$manager->deleteMessage($id);	
				$donnees1= $manager->displayListNode();	
				$donnees2= $manager->displayListLink();	
				$donnees3= $manager->displayListMessage();	
				include(dirname(__FILE__).'./Views/show.php');
				
			}else if ($_GET['action']=="generate"){
			
				$donnees1= $manager->displayListNode();	
				$donnees2= $manager->displayListLink();	
				$donnees3= $manager->displayListMessage();	
				include(dirname(__FILE__).'./Templates/network.php');
			
			}else {
					include(dirname(__FILE__).'./Views/accueil.php');
			}
		}else {
		
		include(dirname(__FILE__).'./Views/accueil.php');
		}
?>		