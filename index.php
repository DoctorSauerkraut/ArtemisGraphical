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
		<link type="text/css" href="./Templates/artemis.css" rel="stylesheet"/>
		<link type="text/css" href="styles/menu.css" rel="stylesheet"/>
		<link type="text/css" rel="stylesheet" href="./vis/dist/vis.css"/>
		
		<script type="text/javascript" src="./Templates/jquery.js" ></script>
		<script type="text/javascript" src="./Templates/script.js"></script>
		<script type="text/javascript" src="./vis/dist/vis.js"></script>
		<script type="text/javascript" src="./Templates/create.js"></script>
    </head>
	<header>
		<div id="header">
			<a href="./index.php"><img id="logo" src="./Templates/artemis.png" alt="logo artemis"/></a>
			<div id="titre" >
				<h3>"Another Real-Time Engine for Message-Issued Simulation"</h3> 
			</div>
			 
			<nav>
				<ul>
					<li id="link-create" class="menuitem" onclick="loadCreate()">
						Network
					</li>
					<li id="link-details" class="menuitem" onclick="loadContent('details')">
						Details
					</li>
					<li id="link-settings" class="menuitem" onclick="loadContent('settings')">
						Settings
					</li>
					<li id="link-results" class="menuitem" onclick="generate()">
						Simulate
					</li>
				</ul>
			</nav> 
		</div>
		
	</header>
	<body onload="loadCreate()"> 
		<div id="corps">
		</div>
		<?php  
			include_once('./Templates/footer.php'); 
		?>
	</body>
</html>