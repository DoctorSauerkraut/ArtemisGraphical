<?php
	session_start();
	include('../functions.php');
	
	spl_autoload_register('chargerClasse');
	
	/* Session initialization */
	$manager = initManager();
	
	$id = getSessionId();

	$ret = create_session($id);
	$manager->setSimuId($_SESSION["simuid"]);
	$simuKey = $_SESSION["simuid"];
	$donnees1= $manager->displayListNode($simuKey);
	// print_r($donnees1);
?>
	<!------------------ GRAPH POP UP  -------------->
	<table class="tableShow">
        <tr>
            <td>Automatic topology generation</td>
            <td><input id="topodepth" type="text" value="0" />entry points</td>
        <td><a class="button blue" onclick="generateTopology()">Generate</a></td>
        <td><a class="button red" onclick="clearGraph()">Delete all nodes</a></td></tr>
    </table>

	<div id="mygraph" style="width:100%;height:auto;">
	</div>

<div id="newNodePopup" style="display:none;">
	<span id="newNode">New node</span> <br>
	<table id="tableNewNode" >
		<tr>
			<td>Name:</td><td><input id="newNodeName" value="new value"> </td>
		</tr>
		<tr>
			<td>Linked to:</td><td><select id="nodeToLink" >
			<?php
			foreach ($donnees1 as $node) {
				$nodes[]=$node->name();
			}
			asort($nodes);
			foreach ($nodes as $node) {
				echo '<option value="'.$node.'">'.$node.'</option>';
			}
			?>
			</select></td>
		</tr>
		<!--<tr>
			<td>IP Address:</td><td><input id="node-ip" value="0"> </td>
		</tr>
		<tr>
			<td>Scheduling:</td><td id="liste"><select id="node-sched">
			<option value="FIFO" selected >FIFO</option>
			<option value="FP">FP</option>
			<option value="EDF">EDF</option>
			<option value="RM">RM</option></select></td>
		</tr>-->
	</table>
		<input type="button" onclick="addNodeToTopo()" value="SAVE" id="saveButton"></button>
		<input type="button" onclick="hideNode();" value="CANCEL" id="cancelButton"></button>
</div>
<?php
	if($donnees1!=array()){
		echo '<a class="button myst addNodeToTopo" onclick="popupNewNode()">Add node</a>';
	}
?>
</br></br></br></br></br></br></br></br></br>



