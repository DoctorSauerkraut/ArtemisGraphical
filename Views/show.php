<!--------------------------------------- Edit node PopUp which is normally not displayed ------------------------------------------------>

	<div style="display:none" id="popup-node-edit">
		<span id="edit-node-title">node</span> <br>
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
				<td>Load</td><td><input id="node-crit" type="number" value="1"> </td>
			</tr>
		</table>
		<input type="button" value="SAVE" onclick="editNode();" id="saveButton"></button>
		<input type="button" value="CANCEL" onclick="hideNode();" id="cancelButton"></button>
	</div>
	
	<!--------------------------------------- Edit Link PopUp which is normally not displayed ------------------------------------------------>
	
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
	
	<!--------------------------------------- Edit Message PopUp which is normally not displayed ------------------------------------------------>
	
	<div style="display:none" id="popup-message-edit">
		<span id="edit-message-title">Message</span> <br>
		<table id="table-edit-message" >
			<tr style="display:none;">
				<td>ID</td><td><input id="message-id"> </td>
			</tr>
			<tr>
				<td>Path</td><td><input id="path" value="new value"> </td>
			</tr>
			<tr>
				<td>Period</td><td><input id="period" value="0"> </td>
			</tr>
			<tr>
				<td>Offset</td><td><input id="offset" value="0"> </td>
			</tr>
			<tr>
				<td>Wcet</td><td><input id="wcet" value="0"> </td>
			</tr>
		</table>
		<input type="button" value="SAVE" onclick="editMessage();" id="saveButton"></button>
		<input type="button" value="CANCEL" onclick="hideMessage();" id="cancelButton"></button>
	</div>
	
	<!------------------------------------- Tables containing information coming from the database ---------------------------------------------->
	
	<!---------- Nodes Table ---------->
	<?php
		/* Load computation */
		//$loadArray = new Array();
		foreach($donnees3 as $message) {
			$path = explode(",", $message->path());
			
			foreach($path as $machineName) {
				$tempPeriod = $message->period();
				if($tempPeriod == 0) {
					$tempPeriod = 80;
				}
				$currentLoad = $message->wcet()/$tempPeriod;
				
				$machineName = trim($machineName);
				$loadArray[$machineName] += $currentLoad;	
			}
		}
	?>
	<div id="tabledetailsdiv">
		<table class="tableShow">
			<caption> Nodes Table </caption>
			<tr>
				<th>ID</th><th>Name</th><th>IP Address</th><th>Scheduling</th><th>Load</th><th>Edit</th><th>Delete</th>
			</tr>
			
			<?php foreach($donnees1 as $element){ ?>
				<tr>
					<td><?php echo $element->id(); ?> </td>
					<td><?php echo $element->name(); ?> </td>
					<td><?php echo $element->ipAddress(); ?> </td>
					<td><?php echo $element->scheduling(); ?> </td>
					<?php 
						$name = trim($element->name());
						$load = $loadArray[$name];?>
					<td <?php if($loadArray[$name] > 1.0) {echo "class=\"redcase\"";}?>><?php echo number_format($loadArray[$name], 4); ?></td>
					<!-- <td><?php echo $element->criticality(); ?> </td> -->
					<td style="text-align:center;"><a href="#" onclick="popupNode('<?php echo $element->id(); ?>','<?php echo $element->name(); ?>','<?php echo $element->ipAddress(); ?>','<?php echo $element->scheduling(); ?>','<?php echo $element->criticality(); ?>')"><img src="Templates/edit.png"></a></td>
					<td style="text-align:center;"><a href="#" onclick="deleteNode('<?php echo $element->id(); ?>')"><img src="Templates/delete.png"></a></td>
				</tr>		
			<?php } ?>	 
		</table>	
	
	

	</div>