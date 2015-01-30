	<!------------------------ GRAPH POP UP  ------------------------->
	<div id="graph-popUp">
		<span id="operation">node</span> <br>
		<table id="tableEdit" >
			<tr style="display:none;">
				<td>ID</td><td><input id="node-id" value="new value"></td>
			</tr>
			<tr>
				<td>Name:</td><td><input id="node-label" value="new value"> </td>
			</tr>
			<tr>
				<td>IP Address:</td><td><input id="node-ip" value="0"> </td>
			</tr>
			<tr>
				<td>Scheduling:</td><td id="liste"><select id="node-sched">
				<option value="FIFO" selected >FIFO</option>
				<option value="FP">FP</option>
				<option value="EDF">EDF</option>
				<option value="RM">RM</option></select></td>
			</tr>
			<tr>
				<td>Criticality:</td><td><input id="node-crit" type="number" value="1"> </td>
			</tr>	
		</table>
			<p style="text-align:left;">-> Create & Add a Message<a id="addButton" href="#" onclick="addMessage();"><img src="./Templates/add.png"></a> </p>
			<input type="button" value="SAVE" id="saveButton"></button>
			<input type="button" value="CANCEL" id="cancelButton"></button>
	</div>
	
	<!------------------------ GRAPH POP UP ADD MESSAGE ------------------------->
	
	<div style="display:none" id="graph-popUp-adds"> 
		<span id="popUp-adds-title">Message</span> <br>
		<table id="popUp-adds-table" >
			<tr style="display:none;">
				<td class="label">ID:</td><td><input id="message-id"> </td>
			</tr>
			<tr>
				<td class="label">Path:</td><td><input id="path" value="ex:Node 1,Node 2,Node 3"> </td>
			</tr>
			<tr>
				<td class="label">Period:</td><td><input id="period" value="0"> </td>
			</tr>
			<tr>
				<td class="label">Offset:</td><td><input id="offset" value="0"> </td>
			</tr>
			<tr>
				<td class="label">Wcet:</td><td><input id="wcet" value="0"> </td>
			</tr>
		</table>
		<input type="button" value="SAVE" onclick="saveMessage();" id="saveButton"></button>
		<input type="button" value="CANCEL" onclick="hideGraphPopUpAdds();" id="cancelButton"></button>
	</div>
	
	<br />
	<div id="mygraph">
	</div>


