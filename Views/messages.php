<?php
		/* Load computation */
		//$loadArray = new Array();

	?>	
	<!---------- Messages Table ---------->
		<div id="tabledetailsmessages">
		<table class="tableShow">
			<caption> Messages Table </caption>
			<tr>
				<th>ID</th><th>Path</th><th>Period</th><th>Offset</th><th>WCET</th><th>Edit</th><th>Add</th><th>Delete</th>
			</tr>
			<?php foreach($donnees3 as $element){ 	
				echo "<tr>";
					echo "<td>".$element->id()."</td>";
					echo "<td id=\"path_".$element->id()."\" >".$element->path()."</td>";
					echo "<td id=\"peri_".$element->id()."\" onclick=\"editValue(this)\">".$element->period()."</td>";
					echo "<td id=\"offs_".$element->id()."\" onclick=\"editValue(this)\">".$element->offset()."</td>";
					echo "<td id=\"wcet_".$element->id()."\" onclick=\"editValue(this)\">".$element->wcet()."</td>";
					
					echo "<td>";
					echo "<img src=\"Templates/edit.png\" onclick=\"saveEditedMessage('".$element->id()."')\" />";
					echo "</td>";
			
			
					echo "<td>-</td>";
					echo "<td><img src=\"Templates/delete.png\" /></td>";
				echo "</tr>";		
			 } 
			
				echo "<tr>";
				echo "<td>-</td>";
				echo "<td><input type=\"text\" id=\"path\" /></td>";
				echo "<td><input type=\"text\" id=\"period\" /></td>";
				echo "<td><input type=\"text\" id=\"offset\" /></td>";
				echo "<td><input type=\"text\" id=\"wcet\" /></td>";
				echo "<td>-</td>";
				echo "<td><img src=\"Templates/add.png\" onclick=\"addMessageTable()\" /></td>";
				echo "<td><img src=\"Templates/delete.png\" /></td>";
				echo "</tr>";
			
			
			?> 
				
		</table> 
		
		</div>