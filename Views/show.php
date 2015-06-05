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
			<!--<tr>
				<td>IP Address</td><td><input id="node-ip" value="0"> </td>
			</tr>  
			<tr>
				<td>Scheduling</td>
				<td id="liste"><select id="node-sched">
				<option value="FIFO" selected >FIFO</option>
				<option value="FP">FP</option>
				<option value="EDF">EDF</option>
				<option value="RM">RM</option></select></td>
			</tr>-->
			<tr>
				<td>Load</td><td><input id="node-crit" type="number" value="1"> </td>
			</tr>
			<tr>
				<td>Speed</td><td>
					<select id="node-speed">
						<option value="1">100 Mo/s</option>
						<option value="10">1 Go/s</option>
						<option value="100">10 Go/s</option>
						<option value="1000">100 Go/s</option>
					</select>
				
				 </td>
			</tr>
		</table>
		
		<input type="button" value="SAVE" onclick="editNode();" id="saveButton"></button>
		<input type="button" value="CANCEL" onclick="hideNode();" id="cancelButton"></button>
	</div>
	
	<!------------------------------------- Tables containing information coming from the database ---------------------------------------------->
	
	<!---------- Nodes Table ---------->
	<?php
		/* Load computation */
		$loadArray = array();
		
		foreach($donnees3 as $message) {
			$path = explode(",", $message->path());
			
			/* Dynamic load computation */
			foreach($path as $machineName) {
				$machineName = trim($machineName);
				/* Computing speed */
				$machine = $manager->displayNodeByName($machineName);
				
				$tempPeriod = $message->period();
				
				if($tempPeriod == 0) {
					$tempPeriod = Settings::getParameter("timelimit", $simuKey);
				}
				
				$currentLoad = $message->wcet() / $tempPeriod;
				
				if($loadArray[$machineName] == "") {
					$loadArray[$machineName] = ($currentLoad/$machine->getSpeed());
				}
				else {
					$loadArray[$machineName] += ($currentLoad/$machine->getSpeed());
				}
			}
			 
		}
	?>
	<div class="tabledetailsdiv">
		<table class="tableShow">
			<caption> Nodes Table </caption>
			<tr>
				<th>ID</th><th>Name</th><!--<th>IP Address</th>--><th>Scheduling</th><th>Load</th><th>Speed</th><th>Edit</th><th>Delete</th>
			</tr>
			
			<?php foreach($donnees1 as $element){ ?>
				<tr>
					<td><?php echo $element->id(); ?> </td>
					<td><?php echo $element->name(); ?> </td>
				<!-- 	<td><?php echo $element->ipAddress(); ?> </td> -->
					<td><?php echo $element->scheduling(); ?> </td>
					<?php 
						$name = trim($element->name());
						$load = $loadArray[$name];?>
					<td <?php if($loadArray[$name] > 1.0) {echo "class=\"redcase\"";}?>><?php echo number_format($loadArray[$name], 4); ?></td>
					<td><?php echo $element->getSpeed()."x"; ?> </td>
					<td style="text-align:center;"><a href="#" class="button green" onclick="popupNode('<?php echo $element->id(); ?>','<?php echo $element->name(); ?>','<?php echo $element->ipAddress(); ?>','<?php echo $element->scheduling(); ?>','<?php echo $element->criticality(); ?>')">Edit</a></td>
					<td style="text-align:center;"><a href="#" class="button red" onclick="deleteNode('<?php echo $element->id(); ?>')">Delete</a></td>
				</tr>		
			<?php } ?>	 
		</table>	
	
	

	</div>