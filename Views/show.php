	<div style="display:none" id="popup-node-edit">
	  <span id="edit-title">node</span> <br>
	  <table id="table-edit-node" >
	  		<tr style="display:none;">
		  <td>ID</td><td><input id="node-id"> </td>
		</tr>
		<tr>
		  <td>Name</td><td><input id="node-label" value="new value"> </td>
		</tr>
			 <tr>
		  <td>IP Address</td><td><input id="node-ip" value="0"> </td>
		</tr>
		 <tr>
		  <td>Scheduling</td><td id="liste"><select id="node-sched">
		  <option value="FIFO" selected >FIFO</option>
		  <option value="FP">FP</option>
		  <option value="EDF">EDF</option>
		  <option value="RM">RM</option></select></td>
		</tr>
		<tr>
		  <td>Criticality</td><td><input id="node-crit" type="number" value="1"> </td>
		</tr></table>
	  <input type="button" value="save" onclick="editNode();" id="saveButton"></button>
	  <input type="button" value="cancel" onclick="hideNode();" id="cancelButton"></button>
	</div>

<table class="tableShow">
<caption> Nodes Table </caption>
<tr>
<th> Node ID</th><th>Name</th><th>IP Address</th><th>Scheduling</th><th>Criticality</th><th>Edit</th><th>Delete</th>
</tr>
		<?php foreach($donnees1 as $element){
		?>
		<tr><td><?php echo $element->id(); ?> </td>
		<td><?php echo $element->name(); ?> </td>
		<td><?php echo $element->ipAddress(); ?> </td>
		<td><?php echo $element->scheduling(); ?> </td>
		<td><?php echo $element->criticality(); ?> </td>
		<td style="text-align:center;"><a href="#" onclick="popupNode(<?php echo $element->id(); ?>)"><img src="Templates/edit.png"></a></td>
		<td style="text-align:center;"><a href="#" onclick="deleteNode(<?php echo $element->id(); ?>)"><img src="Templates/delete.png"></a></td></tr>
		
				<?php
		}
		 ?>
		 
	 
</table>	

<table class="tableShow">
<caption> Links Table </caption>
<tr>
<th> Link ID</th><th>Node 1</th><th>Node 2</th><th>Edit</th><th>Delete</th>
</tr>
		<?php foreach($donnees2 as $element){
	?>	
		<tr><td><?php echo $element->id(); ?> </td>
		<td><?php echo $element->node1(); ?> </td>
		<td><?php echo $element->node2(); ?> </td>
		<td style="text-align:center;"><a href="#" onclick="editLink(<?php echo $element->id(); ?>)"><img src="Templates/edit.png"></a></td>
		<td style="text-align:center;"><a href="#" onclick="deleteLink(<?php echo $element->id(); ?>)"><img src="Templates/delete.png" ></a></td></tr>	
				<?php
		}
		 ?>
		 
	 
</table>	

<table class="tableShow">
<caption> Messages Table </caption>
<tr>
<th> Message ID</th><th>Path</th><th>Period</th><th>Offset</th><th>WCET</th><th>Edit</th><th>Delete</th>
</tr>
		<?php foreach($donnees3 as $element){
	?>	
		<tr><td><?php echo $element->id(); ?> </td>
		<td><?php echo $element->path(); ?> </td>
		<td><?php echo $element->period(); ?> </td>
		<td><?php echo $element->offset(); ?> </td>
		<td><?php echo $element->wcet(); ?> </td>
		<td style="text-align:center;"><a href="#" onclick="editMessage(<?php echo $element->id(); ?>)"><img src="Templates/edit.png"></a></td>
		<td style="text-align:center;"><a href="#" onclick="deleteMessage(<?php echo $element->id(); ?>)"><img src="Templates/delete.png"></a></td></tr>		
				<?php
		}
		 ?>
		 
	 
</table> 
<p style="text-align:center;"><a href="#" onclick="generate()">Click here to simulate and generate an xml file.</a></p>

