	<!---------- Messages Table ---------->
	<?php
		/* Get criticality levels and corresponding WCETS */
		$cptLevels = 0;
			$levels = CriticalityLevel::load();	
			
			while($rst = $levels->fetch()) {
				$levelsTab[$cptLevels] = new CriticalityLevel($rst["name"], $rst["code"]);
				$cptLevels++;
			}
			
			if ($cptLevels == 0) {
				$newCritLvl = new CriticalityLevel("Non critical", "NC");
				$newCritLvl->save();
				
				$levels = CriticalityLevel::load();	
			
				while($rst = $levels->fetch()) {
					$levelsTab[$cptLevels] = new CriticalityLevel($rst["name"], $rst["code"]);
					$cptLevels++;
				}
			}
			
					
	?>
	
		<div class="tabledetailsdiv" id="tabledetailsmessages">
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
			<?php 
			$codeStr = "";
			
			foreach($donnees3 as $element){ 	
				echo "<tr>";
					echo "<td>".$element->id()."</td>";
					echo "<td id=\"path_".$element->id()."\" >".$element->path()."</td>";
					echo "<td id=\"peri_".$element->id()."\" onclick=\"editValue(this)\" >".$element->period()."</td>";
					echo "<td id=\"offs_".$element->id()."\" onclick=\"editValue(this)\" >".$element->offset()."</td>";
					
					/* Criticality levels */
					$codeStr ="";
					for($cptTd=0;$cptTd<$cptLevels;$cptTd++) {	
						$code = $levelsTab[$cptTd]->getCode();
						$wcet = ($element->wcet_($code) != "") ? $element->wcet_($code):"0";
						
						echo "<td id=\"wcet_".$code."_".$element->id()."\" onclick=\"editWcet(this, '$code')\" >".$wcet."</td>";
						
						$codeStr .= "'".$code."'";
						if($cptTd<$cptLevels-1)
							$codeStr .= ",";
					}
					
					echo "<td>";
					
					/* Building js table containing all wcet-critical codes */
					echo "<a class=\"button green\" onclick=\"saveEditedMessage('".$element->id()."', new Array(".$codeStr;
					echo "))\" />Edit</a>";
					echo "</td>";
			
			
					echo "<td>-</td>";
					echo "<td><a class=\"button red\" onclick=\"deleteMessage(".$element->id().")\"/>Delete</a></td>";
				echo "</tr>";		
			 } 
			
				echo "<tr>";
				echo "<td>-</td>";
				echo "<td><input type=\"text\" id=\"path\" /></td>";
				echo "<td><input type=\"text\" id=\"period\" /></td>";
				echo "<td><input type=\"text\" id=\"offset\" /></td>";
				
				$codeStr ="";
				for($cptTd=0;$cptTd<$cptLevels;$cptTd++) {	
					$critLvl = $levelsTab[$cptTd]->getCode();
					
					echo "<td><input type=\"text\" id=\"wcet_".$critLvl."\" /></td>";
					
					$codeStr .= "'".$critLvl."'";
					if($cptTd<$cptLevels-1)
							$codeStr .= ",";
				}
				
				echo "<td>-</td>";
				echo "<td><a class=\"button blue\" onclick=\"addMessageTable(new Array($codeStr))\" />Add</a></td>";
				echo "<td>-</td>";
				echo "</tr></table> ";
			?> 
				
		
	<br />	<!-- <a class="button blue" id="buttonAddCriticality" onclick="popup('critButton')">Add a criticality level</a> -->
		
		</div>