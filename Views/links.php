<!---------- Links Table ---------->
		<div class="tabledetailsdiv" id="tabledetailslinks">
		<table class="tableShow">
		<caption> Links Table </caption>
			<tr>
				<th>ID</th><th>Node 1</th><th>Node 2</th><th>Edit</th><th>Delete</th>
			</tr>
			<?php $i=0;
			foreach($donnees2 as $element){ ?>	
				<tr>
					<td><?php echo $element->id(); ?> </td>
					<td><?php echo $tabNames[$i]; ?> </td> 
					<td><?php echo $tabNames[$i+1]; ?> </td>
					<td style="text-align:center;"><a href="#" onclick="popupLink('<?php echo $element->id(); ?>','<?php echo $tabNames[$i]; ?>','<?php echo $tabNames[$i+1]; ?>')"><a class="button green" >Edit</a></td>
					<td style="text-align:center;"><a href="#" onclick="deleteLink('<?php echo $element->id(); ?>','<?php echo $tabNames[$i]; ?>','<?php echo $tabNames[$i+1]; ?>')"><a class="button red" >Delete</a></td>
				</tr>	
				<?php $i=$i+2;
			} ?> 
		</table>	
		</div>