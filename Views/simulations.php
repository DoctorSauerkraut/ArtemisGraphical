<?php
	$simulations_list = Simulation::loadSimulationsForSession(getSessionId());
?>

<div class="tabledetailsdiv" id="tabledetailsmessages">
	<table class="tableShow">
		<caption> Simulations Table </caption>
		<tr><th>ID</th><th></th><th></th></tr>
		<?php 
		while($simulation = $simulations_list->fetch()) {
		
			echo "<td>".$simulation["id_simu"]."</td><td><a class=\"button green\" >Select</a></td>";
			echo "<td><a class=\"button red\">Delete</a></td>";
		}
		?>
	</table>
</div>