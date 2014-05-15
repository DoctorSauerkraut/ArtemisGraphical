<?php	
	function chargerClasse($classe)
		{
		if ($classe == 'Manager' ){
		  require 'Library/Models/'.str_replace('\\', '/', $classe).'.class.php'; }
		  else{
			require 'Library/Entities/'.str_replace('\\', '/', $classe).'.class.php';}
		}
		spl_autoload_register('chargerClasse');
		
				try
				{
					$bdd = new PDO('mysql:host=localhost;dbname=artemis', 'root', '');
					$manager = new Manager($bdd);
				}
				catch (Exception $e)
				{
						die('Erreur : ' . $e->getMessage());
				}
				
		if (isset($_POST["action"]))
		{
		
			if ($_POST["action"]=="view"){
				$donnees1= $manager->displayListNode();	
				$donnees2= $manager->displayListLink();	
				$donnees3= $manager->displayListMessage();	
				include(dirname(__FILE__).'./Views/show.php');
			}
			else if ($_POST['action']=="results"){
				
				include(dirname(__FILE__).'Views/results.php');
				
			}else if ($_POST['action']=="deleteNode"){
			
			$id=$_POST['id'];
			$manager->deleteNode($id);
				$donnees1= $manager->displayListNode();	
				$donnees2= $manager->displayListLink();	
				$donnees3= $manager->displayListMessage();	
				include(dirname(__FILE__).'./Views/show.php');
			
			}else if($_POST['action']=="deleteLink"){
			
			$id=$_POST['id'];
			$manager->deleteLink($id);
			$donnees1= $manager->displayListNode();	
			$donnees2= $manager->displayListLink();	
			$donnees3= $manager->displayListMessage();	
			include(dirname(__FILE__).'./Views/show.php');
				
			}else if ($_POST['action']=="deleteMessage"){
			
			$id=$_POST['id'];
			$manager->deleteMessage($id);	
				$donnees1= $manager->displayListNode();	
				$donnees2= $manager->displayListLink();	
				$donnees3= $manager->displayListMessage();	
				include(dirname(__FILE__).'./Views/show.php');
				
			}else if ($_POST['action']=="generate"){
			
				$donnees1= $manager->displayListNode();	
				$donnees2= $manager->displayListLink();	
				$donnees3= $manager->displayListMessage();	
				include(dirname(__FILE__).'./Templates/network.php');
			
			}else if($_POST['action']=="editNode"){
				//echo ("Données :".$_POST['id'].$_POST['label'].$_POST['ipAddress'].$_POST['scheduling'].$_POST['criticality']);
				$manager->updateNode($_POST['id'],$_POST['label'],$_POST['ipAddress'],$_POST['scheduling'],$_POST['criticality']);
				$donnees1= $manager->displayListNode();	
				$donnees2= $manager->displayListLink();	
				$donnees3= $manager->displayListMessage();
				include(dirname(__FILE__).'./Views/show.php');			
			}else if($_POST['action']=="editLink"){
				include(dirname(__FILE__).'./Views/show.php');			
			}else if($_POST['action']=="editMessage"){
				include(dirname(__FILE__).'./Views/show.php');			
			}else{
					include(dirname(__FILE__).'./Views/accueil.php');
			}
		}else {
		
		//include('./Views/create.php');
		}
?>		