	
		<?php

include_once('./Templates/header.php'); ?>

<div id="corps">
<table class="tableShow">
<caption> Nodes Table </caption>
<tr>
<th> Node ID</th><th>Name</th><th>IP Address</th><th>Scheduling</th><th>Criticality</th><th>Delete</th>
</tr>
		<?php foreach($donnees1 as $element){
	?>	
		<tr><td><?php echo $element->id(); ?> </td>
		<td><?php echo $element->name(); ?> </td>
		<td><?php echo $element->ipAddress(); ?> </td>
		<td><?php echo $element->scheduling(); ?> </td>
		<td><?php echo $element->criticality(); ?> </td>
		<td style="text-align:center;"><a href="./Controller.php?action=deleteNode&id=<?php echo $element->id();?>"><img src="Templates/delete.png"></a></td></tr>
		
				<?php
		}
		 ?>
		 
	 
</table>	

<table class="tableShow">
<caption> Links Table </caption>
<tr>
<th> Link ID</th><th>Node 1</th><th>Node 2</th><th>Delete</th>
</tr>
		<?php foreach($donnees2 as $element){
	?>	
		<tr><td><?php echo $element->id(); ?> </td>
		<td><?php echo $element->node1(); ?> </td>
		<td><?php echo $element->node2(); ?> </td>
		<td style="text-align:center;"><a href="./Controller.php?action=deleteLink&id=<?php echo $element->id(); ?>"><img src="Templates/delete.png" ></a></td></tr>	
				<?php
		}
		 ?>
		 
	 
</table>	

<table class="tableShow">
<caption> Messages Table </caption>
<tr>
<th> Message ID</th><th>Path</th><th>Period</th><th>Offset</th><th>WCET</th><th>Delete</th>
</tr>
		<?php foreach($donnees3 as $element){
	?>	
		<tr><td><?php echo $element->id(); ?> </td>
		<td><?php echo $element->path(); ?> </td>
		<td><?php echo $element->period(); ?> </td>
		<td><?php echo $element->offset(); ?> </td>
		<td><?php echo $element->wcet(); ?> </td>
		<td style="text-align:center;"><a href="./Controller.php?action=deleteMessage&id=<?php echo $element->id(); ?>"><img src="Templates/delete.png"></a></td></tr>		
				<?php
		}
		 ?>
		 
	 
</table> 
<p style="text-align:center;"><a href="./Controller.php?action=generate">Click here to simulate and generate an xml file.</a></p>
</div>
		<?php

 include_once('./Templates/footer.php'); 


?>
