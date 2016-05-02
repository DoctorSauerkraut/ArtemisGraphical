<?php
	$simulations_list = Simulation::loadSimulationsForSession(getSessionId());
?>

<div class="tabledetailsdiv" id="tabledetailsmessages">
	<table class="tableShow">
		<caption> Simulations Table </caption>
		<tr><th>ID</th><th></th><th></th></tr>
		<?php
		while($simulation = $simulations_list->fetch()) {
		
			echo "<td>".$simulation["id_simu"]."</td>";
			if($simulation['id_simu']==$simuKey){
				echo "<td><a class=\"button green\" onclick=\"select_simu(".$simulation["id_simu"].");\" >Select</a></td>";
			}else{echo "<td><a class=\"button blue\" onclick=\"select_simu(".$simulation["id_simu"].");\" >Select</a></td>";}
			if($simulation['id_simu']==$simuKey){
				echo "<td><a class=\"button blue\" style=\"display:none\">Delete</a></td>";
			}else{echo "<td><a class=\"button red\" onclick=\"delete_simu(".$simulation["id_simu"].");\" >Delete</a></td>";}
			echo "</tr>";
			$lastSimu=$simulation['id_simu'];
		}
		?>
		<tr><td></td><td></td><td><a class="button blue" onclick="new_simu(<?php echo $lastSimu+1; ?>)";>New</a></th></tr>
	</table>
</div>