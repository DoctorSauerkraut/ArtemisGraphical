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
					$bdd = new PDO('mysql:host=localhost;dbname=artemis', 'root', '',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
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
				
			}else if ($_POST["action"]=="create"){
			
				$donnees1= $manager->displayListNode();
				$donnees2= $manager->displayListLink();	
				
				$info="";
				foreach ($donnees1 as $element1) {
					$info=$info.$element1->id().','.$element1->name().',';
				}
				$info=substr($info,0,-1);
				$info=$info.";";

				foreach ($donnees2 as $element2) {
					$node1=$element2->node1();
					$node1bla=$manager->displayNodeByName($node1);
					$node2=$element2->node2();
					$node2bla=$manager->displayNodeByName($node2);
					$info=$info.$node1bla->id().','.$node2bla->id().','.$element2->id().',';
				}
				$info=substr($info,0,-1);
				$info=$info.";";

				echo ($info);
							
			}else if ($_POST['action']=="results"){
			
				// Pas utilisé pour le moment.
				include(dirname(__FILE__).'Views/results.php');
				
			}else if ($_POST['action']=="deleteNode"){
				$manager->deleteNode($_POST['id']);
				$manager->verifyNodeDeletion($_POST['name']);
				$donnees1= $manager->displayListNode();	
				$donnees2= $manager->displayListLink();	
				$donnees3= $manager->displayListMessage();	
				include(dirname(__FILE__).'./Views/show.php');
			
			}else if($_POST['action']=="deleteLink"){
				$manager->deleteLink($_POST['id']);
				$manager->verifyLinkDeletion($_POST['source'],$_POST['destination']);
				$donnees1= $manager->displayListNode();	
				$donnees2= $manager->displayListLink();	
				$donnees3= $manager->displayListMessage();	
				include(dirname(__FILE__).'./Views/show.php');
				
			}else if ($_POST['action']=="deleteMessage"){

			$manager->deleteMessage($_POST['id']);	
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
			
				$manager->updateNode($_POST['id'],$_POST['label'],$_POST['ipAddress'],$_POST['scheduling'],$_POST['criticality']);
				$donnees1= $manager->displayListNode();	
				$donnees2= $manager->displayListLink();	
				$donnees3= $manager->displayListMessage();
				include(dirname(__FILE__).'./Views/show.php');
				
			}else if($_POST['action']=="editLink"){
			
				$manager->updateLink($_POST['id'],$_POST['node1'],$_POST['node2']);
				$donnees1= $manager->displayListNode();	
				$donnees2= $manager->displayListLink();	
				$donnees3= $manager->displayListMessage();
				include(dirname(__FILE__).'./Views/show.php');
				
			}else if($_POST['action']=="editMessage"){
			
				$manager->updateMessage($_POST['id'],$_POST['path'],$_POST['period'],$_POST['offset'],$_POST['wcet']);
				$donnees1= $manager->displayListNode();	
				$donnees2= $manager->displayListLink();	
				$donnees3= $manager->displayListMessage();
				include(dirname(__FILE__).'./Views/show.php');			
	
			}else{
					include(dirname(__FILE__).'./Views/accueil.php');
			}
		}else {
		
		//include('./Views/create.php');
		}
?>		