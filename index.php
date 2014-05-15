<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
    <head>
        <title>ARTEMIS</title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF8" />
		<link type="text/css" href="./Templates/artemis.css" rel="stylesheet"/>
		<link type="text/css" rel="stylesheet" href="./vis/dist/vis.css"/>
		<script type="text/javascript" src="./Templates/jquery.js" ></script>
		<script type="text/javascript" src="./Templates/script.js"></script>
		<script type="text/javascript" src="./vis/dist/vis.js"></script>
		<script type="text/javascript" src="./Templates/create.js"></script>


    </head>
	
    <body>
	<header>
	<div id="header">
	
		<a href="./index.php"><img id="logo" src="./Templates/artemis.png" alt="logo artemis"/></a>
		<div id="titre" >
			<h3>"Another Real Time Entertainment for Message Issued Simulation"</h3> 
		</div>
		 
		<nav>
			<ul>
				<li><a href="#" onclick="loadCreate()"> Create your topology</a></li>
				<li><a href="#" onclick="loadDetails()"> Details</a></li>
				<li><a href="#" onclick="loadResults()"> See the results</a></li>
			</ul>
		</nav> 
	</div>
	</header>
	<body onload="draw();">
	<div id="corps">
		<?php
			include('./Views/create.php');
		?>
	</div>
	<?php  
		include_once('./Templates/footer.php'); 
	?>
	</body>
</html>