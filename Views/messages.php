<?php
		/* Load computation */
		//$loadArray = new Array();

	?>	
	<!---------- Messages Table ---------->
		<div id="tabledetailsmessages">
		<table class="tableShow">
			<caption> Messages Table </caption>
			<tr>
				<th>ID</th><th>Path</th><th>Period</th><th>Offset</th><th>WCET</th><th>Edit</th><th>Delete</th>
			</tr>
			<?php foreach($donnees3 as $element){ ?>	
				<tr>
					<td><?php echo $element->id(); ?> </td>
					<td><?php echo $element->path(); ?> </td>
					<td onclick="editValue(this)"><?php echo $element->period(); ?> </td>
					<td onclick="editValue(this)"><?php echo $element->offset(); ?> </td>
					<td onclick="editValue(this)"><?php echo $element->wcet(); ?> </td>
					<td><a href="#" onclick="popupMessage('<?php echo $element->id(); ?>','<?php echo $element->path(); ?>','<?php echo $element->period(); ?>','<?php echo $element->offset(); ?>','<?php echo $element->wcet(); ?>')"><img src="Templates/edit.png"></a></td>
					<td><a href="#" onclick="deleteMessage(<?php echo $element->id(); ?>)"><img src="Templates/delete.png"></a></td>
				</tr>		
			<?php } ?> 
		</table> 
		
		
		<input type="button" value="Add a criticality level" />
		</div>