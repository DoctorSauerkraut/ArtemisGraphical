<div style="display:none" id="popup-link-edit">
		<span id="edit-link-title">link</span> <br>
		<table id="table-edit-link" >
			<tr style="display:none;">
				<td>ID</td><td><input id="link-id"> </td>
			</tr>
			<tr>
				<td>Node 1</td><td><input id="node1-label" value="new value"> </td>
			</tr>
			<tr>
				<td>Node 2</td><td><input id="node2-label" value="new value"> </td>
			</tr>
		</table>
		
		<input type="button" value="SAVE" onclick="editLink();" id="saveButton"></button>
		<input type="button" value="CANCEL" onclick="hideLink();" id="cancelButton"></button>
</div>


<!---------- Links Table ---------->
		<div class="tabledetailsdiv" id="tabledetailslinks">
		<table class="tableShow">
		<caption> Links Table </caption>
			<tr>
				<th>Node 1</th><th>Node 2</th><th>Edit</th><th>Delete</th>
			</tr>
			<?php $i=0;
			foreach($list_links as $element){ ?>	
				<tr>
					<td><?php echo $tabNames[$i]; ?> </td> 
					<td><?php echo $tabNames[$i+1]; ?> </td>
					<td style="text-align:center;"><a onclick="popupLink('<?php echo $element->id(); ?>','<?php echo $tabNames[$i]; ?>','<?php echo $tabNames[$i+1]; ?>')" class="button green" >Edit</a></td>
					<td style="text-align:center;"><a onclick="deleteLink('<?php echo $element->id(); ?>','<?php echo $tabNames[$i]; ?>','<?php echo $tabNames[$i+1]; ?>')" class="button red" >Delete</a></td>
				</tr>	
				<?php $i=$i+2;
			} ?> 
		</table>	
		</div>