	<!---------- Messages Table ---------->
	<?php
		/* Get criticality levels and corresponding WCETS */
		$levels = CriticalityLevel::load();
		$cptLevels = 0;
		
		while($rst = $levels->fetch()) {
			$levelsTab[$cptLevels] = new CriticalityLevel($rst["name"], $rst["code"]);
			$cptLevels++;
		}
		
		if ($cptLevels == 0) {
			$newCritLvl = new CriticalityLevel("Non critical", "NC");
			$newCritLvl->save();
		}
		
	?>
	
		<div id="tabledetailsmessages">
		<table class="tableShow">
			<caption> Messages Table </caption>
			<tr>
				<th>ID</th><th>Path</th><th>Period</th><th>Offset</th>
				<?php
					for($cptTd=0;$cptTd<$cptLevels;$cptTd++) {	
						echo "<th>WCTT ".$levelsTab[$cptTd]->getName()."</th>";
					}	
				?>
				<th>Edit</th><th>Add</th><th>Delete</th>
			</tr>
			<?php foreach($donnees3 as $element){ 	
				echo "<tr>";
					echo "<td>".$element->id()."</td>";
					echo "<td id=\"path_".$element->id()."\" >".$element->path()."</td>";
					echo "<td id=\"peri_".$element->id()."\" onclick=\"editValue(this)\">".$element->period()."</td>";
					echo "<td id=\"offs_".$element->id()."\" onclick=\"editValue(this)\">".$element->offset()."</td>";
					
					/* Criticality levels */
					
					for($cptTd=0;$cptTd<$cptLevels;$cptTd++) {	
						$code = $levelsTab[$cptTd]->getCode();
						$wcet = ($element->wcet_($code) != "") ? $element->wcet_($code):"-";
						
						echo "<td id=\"wcet_$code_".$element->id()."\" >".$wcet."</td>";
					}
					
					echo "<td>";
					echo "<img src=\"Templates/edit.png\" onclick=\"saveEditedMessage('".$element->id()."')\" />";
					echo "</td>";
			
			
					echo "<td>-</td>";
					echo "<td><img src=\"Templates/delete.png\" onclick=\"deleteMessage(".$element->id().")\"/></td>";
				echo "</tr>";		
			 } 
			
				echo "<tr>";
				echo "<td>-</td>";
				echo "<td><input type=\"text\" id=\"path\" /></td>";
				echo "<td><input type=\"text\" id=\"period\" /></td>";
				echo "<td><input type=\"text\" id=\"offset\" /></td>";
				for($cptTd=0;$cptTd<$cptLevels;$cptTd++) {	
					echo "<td><input type=\"text\" id=\"wcet_$cptTd\" /></td>";
				}
				echo "<td>-</td>";
				echo "<td><img src=\"Templates/add.png\" onclick=\"addMessageTable()\" /></td>";
				echo "<td><img src=\"Templates/delete.png\" onclick=\"deleteMessage(".$element->id().")\" /></td>";
				echo "</tr>";
			?> 
				
		</table> 
		
		<input type="button" value="Add a criticality level" onclick="popup('critButton')" />
		
		</div>