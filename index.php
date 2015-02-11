<?php
	if(file_exists("installer/index.php")) {
		header("Location: installer/index.php");
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <title>ARTEMIS</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF8" />
		<link type="text/css" href="styles/artemis.css" rel="stylesheet"/>
		<link type="text/css" href="styles/menu.css" rel="stylesheet"/>
		<link type="text/css" rel="stylesheet" href="./vis/dist/vis.css"/>
		
		<script type="text/javascript" src="./scripts/jquery.js" ></script>
		<script type="text/javascript" src="./scripts/script.js"></script>
		<script type="text/javascript" src="./vis/dist/vis.js"></script>
		<script type="text/javascript" src="./scripts/create.js"></script>
		<script type="text/javascript" src="./scripts/edit.js"></script>
		<script type="text/javascript" src="./scripts/settings.js"></script>
    </head>
    <body onload="loadCreate()"> 
		<div id="header">
			<a href="./index.php"><img id="logo" src="./Templates/artemis.png" alt="logo artemis"/></a>
			<div id="titre" >
				<h3>"Another Real-Time Engine for Message-Issued Simulation"</h3> 
			</div>
			 
			<div id="menu"> 
					<ul id="menuitems">
						<li id="link-create" class="menuitem" onclick="loadCreate()">
							Network
						</li>
						<li id="link-details" class="menuitem" onclick="loadContent('details')">
							Nodes
						</li>
						<li id="link-links" class="menuitem" onclick="loadContent('links')">
							Links
						</li>
						<li id="link-messages" class="menuitem" onclick="loadContent('messages')">
							Messages
						</li>
						<li id="link-settings" class="menuitem" onclick="loadContent('settings')">
							Settings
						</li>
						<li id="link-results" class="menuitem" onclick="popup('popupConfirmSimu')">
							Simulate
						</li>
					</ul>
			</div>
		</div>
		<div id="grayer"></div>
		<div id="popup"></div>
	
		<div id="corps">
		</div>
		<?php  
			include_once('./Templates/footer.php'); 
		?>
	</body>
</html>