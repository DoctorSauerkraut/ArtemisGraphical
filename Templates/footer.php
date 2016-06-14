<div id="footer">
    
    <!-- 
        Codification du numéro de version :
            1er chiffre : version fonctionnelle majeure
            2e chiffre : modification du noyau mineure
            3e chiffre : modification de la GUI mineure
            4e chiffre : Correction de bugs
    -->

	<p>Artemis v1.14.6.1 - Feedback : cros@ece.fr - <a onclick="loadContent('credits')">Credits</a></p>
	<p id="numSimu">
	<?php 
		if($_SESSION['simuid']!=0){
			echo ' - Simulation n° '.$_SESSION['simuid'];
		}
	?></p> 
</div>
