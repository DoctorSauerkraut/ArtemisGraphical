<?php
	$simulations_list = Simulation::loadSimulationsForSession(getSessionId());
?>

<div class="tabledetailsdiv" id="tabledetailsmessages">
	<table class="tableShow">
		<caption> Simulations Table </caption>
		<tr><th>ID</th><th></th><th></th><th></th></tr>
		<tr>
		<?php
		while($simulation = $simulations_list->fetch()) {
		
			echo "<td>".$simulation["id_simu"]."</td>";
			if($simulation['id_simu']==$simuKey){
				echo "<td><a class=\"button green\" onclick=\"select_simu(".$simulation["id_simu"].");\" >Select</a></td>";
			}else{echo "<td><a class=\"button grey\" onclick=\"select_simu(".$simulation["id_simu"].");\" >Select</a></td>";}
			if($simulation['id_simu']==$simuKey){
				echo "<td><a class=\"button blue\" style=\"display:none\">Delete</a></td>";
			}else{echo "<td><a class=\"button red\" onclick=\"confirmDelSimu(".$simulation["id_simu"].");\" >Delete</a></td>";}
			echo "<td><a class=\"button purple\" onclick=\"export_simu(".$simulation["id_simu"].");\" >Export</a></td>";
			echo "</tr>";
			$lastSimu=$simulation['id_simu'];

		}
		// echo 'done dans simu: '.$_SESSION['done'];
		?>
		<tr><td></td><td></td>
		<td><a class="button blue" onclick="new_simu(<?php echo $lastSimu+1; ?>)";>New</a></td>
		<td><a class="button myst" onclick="activeInputFiles()";>Import</a></td>
		</tr>
	</table>
</div>
<form method="post" action="" style="display: none" enctype= "multipart/form-data">
	<input type="file" name="import" id="importSimu">
	<input type="hidden" name="MAX_FILE_SIZE" value="2097152" />
	<input type="submit" name="submit" id="submitImport" value="Envoyer" />
</form>

