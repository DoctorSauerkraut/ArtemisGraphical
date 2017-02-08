 <?php		
 	session_start();

	/* We get the action sent by the client */
	$action_server = isset($_POST["action"]) ? $_POST["action"]	: "";
	
	include('../functions.php');
	
	spl_autoload_register('chargerClasse');
	
	/* Session initialization */
	$manager = initManager();
	
	$id = getSessionId();

	$ret = create_session($id);
	$manager->setSimuId($_SESSION["simuid"]);
	$simuKey = $_SESSION["simuid"];

     $elements = new ElementsEditor($manager, $simuKey);

	/* Selecting actions */
	if($action_server == "") {
		return;	
	}
    else if ($action_server == "editNode"){
        $elements->editNode($_POST['id'],$_POST['label'],$_POST['ipAddress'],$_POST['scheduling'],$_POST['speed']);
	}
    /* Edit network components */
    else if ($action_server == "deleteNode"){
        $elements->deleteNode($_POST['id']);
	}
	else if ($action_server == "editNodeSchema"){
        $elements->editNodeSchema($_POST['id'],$_POST['label']);
	}
    else if ($action_server == "addNode"){
		$elements->addNode($_POST["name"],$_POST["ip"],$_POST["sched"]);
	}
    else if ($action_server == "deleteLink"){
        $elements->deleteLink($_POST['id'], $_POST['source'], $_POST['destination']);
	}
    else if ($action_server == "addLink"){
        $elements->addLink($_POST["id1"],$_POST["id2"]);	
	}
    else if ($action_server == "editLink"){
        $elements->editLink($_POST['node1'], $_POST['node2']);	
	}
    else if ($action_server == "editMessage"){
        $elements->editMessage($_POST["id"], $_POST["period"], $_POST["offset"], $_POST["wcetStr"], $_POST["path"], $_POST["color"]);
	}
    else if ($action_server == "deleteMessage"){
        $elements->deleteMessage($_POST["id"]);	
	}
    else if ($action_server == "addMessage"){
        $wcetStr = (isset($_POST["wcetStr"]) && $_POST["wcetStr"] != "NC=:") ? $_POST["wcetStr"]:"NC=-1:";
        $elements->addMessage($_POST["path"], $_POST["offset"], $_POST["period"], $_POST["color"], $wcetStr, 0);		
	}
    else if ($action_server == "getTopo"){
		$topo=$_SESSION['topo'];
		$i=0;
		foreach ($topo as $node) {
			$topoToDraw['topo'][$i]['id']=$node['id'];
			$topoToDraw['topo'][$i]['name']=$node['name'];
			$topoToDraw['topo'][$i]['shape']=$node['shape'];
			$topoToDraw['topo'][$i]['posX']=$node['posX'];
			$topoToDraw['topo'][$i]['posY']=$node['posY'];
			if(!isset($node['disp'])){
				$topoToDraw['topo'][$i]['disp']='true';
			}else{
				$topoToDraw['topo'][$i]['disp']=$node['disp'];
			}
			
			$i++;
		}
		$topoToDraw=json_encode($topoToDraw);
		print_r($topoToDraw);
	}

?>