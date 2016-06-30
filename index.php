<?php
	// if(file_exists("installer/index.php")) {
	// 	header("Location: installer/index.php"); 
	// }
	if(!file_exists("config.php")){
		header("Location: installer/formDBCreation.php");
	}
	session_start();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <title>ARTEMIS</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF8" />
		<link type="text/css" href="styles/artemis.css" rel="stylesheet"/>
		<link type="text/css" href="styles/menu.css" rel="stylesheet"/>
		<link type="text/css" href="styles/topology.css" rel="stylesheet"/>
		<link type="text/css" rel="stylesheet" href="./vis/dist/vis.css"/>
		
		<script type="text/javascript" src="./scripts/jquery.js" ></script>
		
		<script type="text/javascript" src="./scripts/script.js"></script>
		<!--  Vis files -->
		<script type="text/javascript" src="./vis/dist/vis.js"></script>
		
		<script type="text/javascript" src="./scripts/create.js"></script>
		<script type="text/javascript" src="./scripts/edit.js"></script>
		<script type="text/javascript" src="./scripts/settings.js"></script>
		<link rel="icon" href="./Templates/icon2.png" />
    </head>
    <body onload="loadContent('simus')"> 
		<div id="header">
			<div id="titre" >
				<h3>ARTEMIS</h3> 
			</div>
		</div>
		<div id="menu"> 
					<ul id="menuitems">
						<li id="link-simus" class="menuitem" onclick="loadContent('simus')">
							Simulations
						</li>
						<li id="link-create" class="menuitem" onclick="createSchema()">
							Network
						</li>
						<li id="link-details" class="menuitem" onclick="loadContent('details')">
							Nodes
						</li>
					<!-- 	<li id="link-links" class="menuitem" onclick="loadContent('links')">
							Links
						</li> -->
						<li id="link-messages" class="menuitem" onclick="loadContent('messages')">
							Messages
						</li>
						<li id="link-mixedc" class="menuitem" onclick="loadContent('mixedc')">
							Criticality
						</li>
						<li id="link-results" class="menuitem" onclick="popup('popupConfirmSimu')">
							Run
						</li>
					</ul>
			</div>
		<div id="grayer" onclick="closePopup()"></div>
		<div id="popup"></div>
		<div id="corps">
		</div>
		<?php  
		include('functions.php');
		if($_FILES['import']['name']!=''){
			print_r($_FILES);
			import();
		}else{}

        $_FILES=array();
		$_FILES['import']['name']='';
	
		include_once('./Templates/footer.php'); 
		?>
	</body>
</html>