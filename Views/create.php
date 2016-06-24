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

    <?php
	if($donnees1!=array()){
		echo '<div class="buttons">';
		echo '<a class="button myst addNodeToTopo" onclick="popupNewNode()">Add node</a>';
		echo '<div class="removeNodeFromTopo">';
            echo '<label>';
                    echo '<input type="checkbox" id="removeNodeFromTopo" class="removeNodeFromTopo" /><span>Delete node</span>';
        	echo '</label>';
		echo '</div>';
		echo '<a class="button myst saveCanvas" id="saveCanvas" onclick="saveTopo('.$simuKey.')">Save topology</a>';
		echo '</div>';

	}
	?>

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
	</table>
		<input type="button" onclick="addNodeToTopo()" value="SAVE" id="saveButton"></button>
		<input type="button" onclick="hidePopupNewNode();" value="CANCEL" id="cancelButton"></button>
</div>

</br></br></br></br></br></br></br></br></br>





