	//////////////////////////////////////////////////// Header links ///////////////////////////////////////////////////////////

/* Change the style of the menu items on click */
function updateMenuStyle(id) {
	var menuitems = document.getElementsByClassName('menuitem');
	
	for(var cpt = 0; cpt < menuitems.length; cpt++) {
		menuitems[cpt].className = "menuitem";
	}
	
	document.getElementById(id).className += " linkselected";
}

/* Load different pages linked to the menu */
function loadContent(action) {
	$.ajax({
		url:"menu.php",
		type:"post",
		data:"action="+action,
		success:function(data){
			document.getElementById("corps").innerHTML = data;
			updateMenuStyle("link-"+action);
		}
	});
}

function loadCreate(topo) {
	$.ajax({
		url:"./Views/create.php",
		type:"post",
		data:"",
		success:function(data){
			document.getElementById("corps").innerHTML = data;
			document.getElementById("mygraph").innerHTML = topo;
			getTopo();
			getLinks();	
			updateMenuStyle("link-create");
			
		}
	});
}
function getLinks(){
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:"action=getLinks",
		success:function(data){
			drawLinks(data);		
		}
	});
}
function getTopo(){
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:"action=getTopo",
		success:function(data){
			drawTopo(data);		
		}
	});
}

function createSchema(){
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:"action=createSchema",
		success:function(data){
			loadCreate(data);
			// document.getElementById("canvas").innerHTML = data;		
		}
	});
}

function recupDatabase(){
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:"action=create",
		success:function(data){
			draw(data.trim());		
		}
	});
}

function popup(popupFunction) {
		closePopup();
		
		$.ajax({
			url:"popup.php",
			type:"post",
			data:'popupFunction='+popupFunction,
			success:function(data){
				if(data == 'ok') generate();
				else {
					openPopup();
					document.getElementById("popup").innerHTML = data;
					updateMenuStyle("link-results");
				}
			}
		});
}

function confirmDelSimu(id_sel) {
		closePopup();
		
		$.ajax({
			url:"popup.php",
			type:"post",
			data:'popupFunction=confirmDelSimu&id_sel='+id_sel+'&titre=Delete the simulation',
			success:function(data){
				openPopup();
				document.getElementById("popup").innerHTML = data;
			}
		});
}

function showSimulationResults() {
		$.ajax({
		url:"Controller.php",
		type:"post",
		data:"action=generateSimu",
		success:function(data){
			document.getElementById("corps").innerHTML = data;
			closePopup();
		}
	});
}

function openPopup() {
	document.getElementById("grayer").style.visibility = 'visible';
	document.getElementById("popup").style.visibility = 'visible';
}

function closePopup() {
	document.getElementById("grayer").style.visibility = 'hidden';
	document.getElementById("popup").style.visibility = 'hidden';
}

//////////////////////////////////////////////////// Generate link
function generate() {
	popup('loadingSimu');

	$.ajax({
		url:"Controller.php",
		type:"post",
		data:'action='+'generate',
		success:function(data){
			showSimulationResults();
			document.getElementById("corps").innerHTML = data;
			document.getElementById("link-results").innerHTML = "Simulate";
			closePopup();
		}
	});
}
//////////////////////////////////////////////////// Details Links ///////////////////////////////////////////////////////////

//////////////////////////////////////////////////// Node table links


function editNode() {
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:'action='+'editNode'+'&id='+document.getElementById('node-id').value+'&label='+document.getElementById('node-label').value+'&ipAddress=0'+'&scheduling=FIFO'+'&speed='+document.getElementById('node-speed').value,
		success:function(data){
			document.getElementById("corps").innerHTML = data;
		}
	});
}

function hideNode(){
	var div = document.getElementById('popup-node-edit');
    if(div == null) {
        div = document.getElementById('newNodePopup');
    }
	div.style.display = 'none';
}

function popupNode($id, $name, $ip, $sched, $crit) {
	var span = document.getElementById('edit-node-title');
	var id = document.getElementById('node-id');
	var name = document.getElementById('node-label');
	//var ip = document.getElementById('node-ip');
	//var sched = document.getElementById('node-sched');
	var crit = document.getElementById('node-crit');
	var div = document.getElementById('popup-node-edit');
	span.innerHTML = "Edit Node n."+$id;
	
	id.value = $id;
	name.value=$name;
	//ip.value=$ip;
	//sched.value=$sched;
	
	
	crit.value=$crit;
	
	div.style.display = 'block';
}

function popupNewNode(){
	var name = document.getElementById('newNodeName');
	var div = document.getElementById('newNodePopup');
	name.value='NodeX';
	div.style.display = 'block';
}

function deleteNode($id) {
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:'action='+'deleteNode'+'&id='+$id,
		success:function(data){
			loadContent("details");
		}
	});
}

//////////////////////////////////////////////////// Link table links
function editLink($id) {
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:'action='+'editLink'+'&id='+document.getElementById('link-id').value+'&node1='+document.getElementById('node1-label').value+'&node2='+document.getElementById('node2-label').value,
		success:function(data){
			document.getElementById("corps").innerHTML = data;
		}
	});
}

function hideLink(){
	var div = document.getElementById('popup-link-edit');
	div.style.display = 'none';
}

function popupLink($id, $name1, $name2) {
	var span = document.getElementById('edit-link-title');
	var id = document.getElementById('link-id');
	var node1 = document.getElementById('node1-label');
	var node2 = document.getElementById('node2-label');
	var div = document.getElementById('popup-link-edit');
	
	span.innerHTML = "Edit Link n."+$id;
	id.value = $id;
	node1.value = $name1;
	node2.value = $name2;
	div.style.display = 'block';
}

function deleteLink($id,$node1,$node2) {
  
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:'action='+'deleteLink'+'&id='+$id+'&source='+$node1+'&destination='+$node2,
		success:function(data){
			document.getElementById("corps").innerHTML = data;
		}
	});
}

//////////////////////////////////////////////////// Message table links
function editMessage($id) {
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:'action='+'editMessage'+'&id='+document.getElementById('message-id').value+'&path='+document.getElementById('path').value+'&period='+document.getElementById('period').value+'&offset='+document.getElementById('offset').value+'&wcet='+document.getElementById('wcet').value,
		success:function(data){
			document.getElementById("corps").innerHTML = data;
		}
	});
}

function hideMessage(){
	var div = document.getElementById('popup-message-edit');
	div.style.display = 'none';
}

function popupMessage($id, $path, $period, $offset, $wcet) {
	var span = document.getElementById('edit-message-title');
	var id = document.getElementById('message-id');
	var path = document.getElementById('path');
	var period = document.getElementById('period');
	var offset = document.getElementById('offset');	
	var wcet = document.getElementById('wcet');
	var div = document.getElementById('popup-message-edit');
	span.innerHTML = "Edit Msg n."+$id;
	id.value = $id;
	path.value = $path;
	period.value = $period;
	offset.value = $offset;
	wcet.value = $wcet;
	div.style.display = 'block';
}

function deleteMessage($id) {
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:'action='+'deleteMessage'+'&id='+$id,
		success:function(data){
			//document.getElementById("corps").innerHTML = data;
			
			loadContent('messages');
		}
	});
}


//////////////////////////////////////////////////// "Create your topology" links ///////////////////////////////////////////////////////////

function addMessage() {
	var span = document.getElementById('popUp-adds-title');
	var label = document.getElementById('node-label');
	var path = document.getElementById('path');
	var div = document.getElementById('graph-popUp-adds');
	
	span.innerHTML = "Create a Message";
	path.value=label.value+",Node X,Destination Node";
	div.style.display = 'block';
}

function hideGraphPopUpAdds(){
	var div = document.getElementById('graph-popUp-adds');
	div.style.display = 'none';
}

function fillPopUp(){
	 return $.ajax({
		url:"./Controller.php",
		type:"post",
		data:'action='+'fillPopUp'+'&id='+document.getElementById('node-id').value
	});
}

function addLink(id1,id2){
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:'action='+'addLink'+'&id1='+id1+'&id2='+id2
	});


}

function addNode(name, ip, sched, crit){
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:'action='+'addNode'+'&name='+name+'&ip='+ip+'&sched='+sched+'&crit='+crit,
		success:function(data){
			recupDatabase();
		}
	});
}

function addNodeToTopo(){
	var newNodeName=document.getElementById('newNodeName').value;
	var nodeToLink=document.getElementById('nodeToLink').value;
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:'action='+'addNodeTopo'+'&name='+newNodeName+'&ip=0'+'&sched=FIFO'+'&crit=0'+'&id1='+newNodeName+'&id2='+nodeToLink,
		success:function(data){
			// alert("on");
			createSchema();
		}
	});
	
}

function updateNode(id,name, ip, sched){
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:'action='+'updateNode'+'&id='+id+'&name='+name+'&ip='+ip+'&sched='+sched
	});
}

function getInformationAndDeleteLink(id){
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:'action='+'recupInfoAndDeleteLink'+'&id='+id
	});

}

function getInformationAndDeleteNode(id){
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:'action='+'recupInfoAndDeleteNode'+'&id='+id
	});

}

function clearGraph(name){
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:'action='+'clearGraph',
				success:function(data){
				recupDatabase();
				}
	});
}

function saveMessage() {
	ajaxSaveMessage();
	
	var divAdd = document.getElementById('graph-popUp-adds');
	divAdd.style.display = 'none';
}


function getMessage(){
	if(document.getElementById('path').value!=null){
		var path = document.getElementById('path').value;
	}else{
		var path='';
	}
	var newMess = document.getElementById('newMess').value;
	if(path!=''){
		path=path+','+newMess;
	}else{
		path=newMess;
	}
	
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:'action='+'getMessage'+'&nodeSel='+newMess+'&path='+path,
		success: function(data){
			document.getElementById('path').value=path;
			document.getElementById('path').style.display='block';
			document.getElementById('newMess').innerHTML=data;
		}
	});	
}

function addMessageTable(idArray) {
	/* Building wcet list */
	var wcetStr = "";
	
	var cptId = 0;
	
	/* Computing all the WCET values */
	while(idArray[cptId] != undefined) {
		var wcetElt = "wcet_"+idArray[cptId];
		
		var valueWcet =  document.getElementById(wcetElt).value;	

		wcetStr += idArray[cptId]+"="+valueWcet+":";
		
		cptId++;
	}
	
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:'action='+'addMessage'+'&path='+document.getElementById('path').value.trim()+'&period='+document.getElementById('period').value+'&offset='+document.getElementById('offset').value+'&wcetStr='+wcetStr+'&color='+document.getElementById('thecolor').value+document.getElementById('inputColor').value,
		success:function(data){
			data=data.trim();

			/* Reload page */
			loadContent('messages');
		}
	});
}

function ajaxSaveMessage(){
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:'action='+'addMessage'+'&path='+document.getElementById('path').value+'&period='+document.getElementById('period').value+'&offset='+document.getElementById('offset').value+'&wcet='+document.getElementById('wcet').value,
		success:function(data){
			data=data.trim();
			if(data!=''){
			}
		}
	});
}


function displayCriticalityTable() {
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:'action='+'displayCritTable',
		success:function(data){
			document.getElementById("critTableDiv").innerHTML = data;
		}
	});	
}

/* Call the server to launch java-simulation core */
function launchSimulation() {
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:"action=launchSimulation",
		success:function(data){
		}
	});	
}

function reloadGraph() {
	var startTimeGraph = $("#starttimegraph")[0].value;
	var endTimeGraph = $("#endtimegraph")[0].value;
	popup('loadingSimu');
	
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:"action=reloadGraph"+"&starttimegraph="+startTimeGraph+"&endtimegraph="+endTimeGraph,
		success:function(data){
			showSimulationResults();
			closePopup();
		}
	});	
}

/* Auto-activate/desactivate text fields for tasks autogeneration on click */
function activateGenerateTextFields() {
	switchStateTextField(false);
}

function desactivateGenerateTextFields() {
	switchStateTextField(true);
}

function switchStateTextField(state) {
	var textFields = document.getElementsByClassName("autogenTextField");
	var i;
	for (i = 0; i < textFields.length; i++) {
		textFields[i].disabled = state;
	}
}

/* Sets / Unsets a node for graph displaying */
function loadNodeOnCheck(nodeId, checked) {
	
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:"action=loadNodeForGraph"+"&nodeId="+nodeId+"&checked="+checked,
		success:function(data){
			showSimulationResults();
		}
	});	
}

function generateTopology() {
	var depth = document.getElementById("topodepth").value;
	
    openPopup();
    document.getElementById("popup").innerHTML = "Generating topology...";
    
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:"action=generateTopology"+"&topoDepth="+depth, 
		success:function(data){
            closePopup();
            loadCreate();
		}
	});	
}

function generateMessagesSet() {
   openPopup();
    
    var tasks 	= $("#autotasks")[0].value;
	var hwcet	= $("#highestwcet")[0].value;
	var autogen = $("input[name=radiotask]:checked")[0];
	var autoload= $("#autoload")[0].value;
    
    if(autogen != undefined) {
        autogen = autogen.value;
    }
    
    $.ajax({
		url:"./Controller.php",
		type:"post",
		data:"action=generateMessagesSet"+"&autogen="+autogen+"&highestwcet="+hwcet+"&autoload="+autoload+"&autotasks="+tasks,
		success:function(data){
            closePopup();
            loadContent("messages");
		}

	});	
}

// attribution d'une nouvelle simulation, et actualisation du numéro de simulation dans le footer
function select_simu(id_sel){
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:"action=select_simu"+"&id_sel="+id_sel,
		success:function(data){
			document.getElementById("numSimu").innerHTML = ' - Simulation n° '+id_sel;
			loadContent('simus');
		}
	});
}

// création d'une nouvelle simulation, et actualisation du numéro de simulation dans le footer
function new_simu(id_simu){
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:"action=select_simu&id_sel=0",
		success:function(data){
			document.getElementById("numSimu").innerHTML = ' - Simulation n° '+id_simu;
			loadContent('simus');
		}
	});
}

// suppression d'une simulation et des éléments associés
function delete_simu(id_sel){
	closePopup();
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:"action=delete_simu&id_sel="+id_sel,
		success:function(data){
			loadContent('simus');
		}
	});
}

function export_simu(id_sel){
	closePopup();
	$.ajax({
			url:"popup.php",
			type:"post",
			data:'popupFunction=confirmExportSimu&titre=Export a simulation&id_sel='+id_sel,
			success:function(data){
				openPopup();
				document.getElementById("popup").innerHTML = data;
			}
		});
}

function activeInputFiles(){
	var monImport = document.getElementById('importSimu');
	monImport.click();
	$.ajax({
			url:"popup.php",
			type:"post",
			data:'popupFunction=confirmImportSimu&titre=Import a simulation',
			success:function(data){
				openPopup();
				document.getElementById("popup").innerHTML = data;
			}
		});;
}

function import_simu(){
	closePopup();
	var monImport = document.getElementById('importSimu');
    
	if(monImport.value != ""){
		document.getElementById('submitImport').click();
	}else{
		import_simu();
	}
}

function verifyFile(){
	var monImport = document.getElementById('importSimu').value;
	if (monImport.substr(monImport.lastIndexOf('\\')+1,11)!="export_simu"){
		$.ajax({
			url:"popup.php",
			type:"post",
			data:'popupFunction=wrongFile&titre=Wrong File',
			success:function(data){
				openPopup();
				document.getElementById("popup").innerHTML = data;
			}
		});
	}else{
		import_simu();
	}
}


function notAllowed(){
	if(document.getElementById('protocol').value=='Decentralized' && document.getElementById('switch').value=='S') {
		(document.getElementById('switch').value='D');
		alert('You can\'t choose both \"Switch: Static\" and \"Protocole: Decentralized\"');
	}
}


function correction(){
	if(document.getElementById('switch').value=='S'){
		document.getElementById('protocol').value='Centralized';
	}
}

function activeColorBox(curColor,id){
	var color=document.getElementById('colorChoice'+id);
	if(color.style.display=="none"){
		color.style.display='block';
		if(document.getElementById('thecolor'+id).value==''){
			if(document.getElementById('inputColor'+id).value.substr(0,1)!="#"){
				document.getElementById('inputColor'+id).value="#"+document.getElementById('inputColor'+id).value;
			}else{
				document.getElementById('inputColor'+id).value=document.getElementById('inputColor'+id).value;
			}
		}else{
			if(document.getElementById('thecolor').value.substr(0,1)!="#"){
				document.getElementById('inputColor').value="#"+document.getElementById('thecolor'+id).value;
			}else{
				document.getElementById('inputColor'+id).value=document.getElementById('thecolor'+id).value;
			}
			
		}
		// document.getElementById('thecolor'+id).value=0;	
	}
	else{
		color.style.display='none';
		document.getElementById('activeColorBox'+id).style.backgroundColor='#'+curColor;
		document.getElementById('inputColor'+id).value='#'+curColor;
		document.getElementById('thecolor'+id).value='';
	}
}

function deactivateRadio(id){
	var color=document.getElementById('inputColor'+id);
	document.getElementById('activeColorBox'+id).style.backgroundColor=color.value;
	document.getElementById('thecolor'+id).value='';
	document.getElementById('thecolor'+id).checked='false';
}

function valideColor(id){
	var color = document.getElementById('inputColor'+id).value;
	if(color.length!=7 || color.substr(0,1)!="#"){
		alert('You choose a wrong color code, please check it before continue.');
	}
	else if(color.search('g')!=-1 || color.search('l')!=-1 || color.search('q')!=-1 || color.search('v')!=-1 ||
		color.search('h')!=-1 || color.search('m')!=-1 || color.search('r')!=-1 || color.search('w')!=-1 ||
		color.search('i')!=-1 || color.search('n')!=-1 || color.search('s')!=-1 || color.search('x')!=-1 ||
		color.search('j')!=-1 || color.search('o')!=-1 || color.search('t')!=-1 || color.search('y')!=-1 ||
		color.search('k')!=-1 || color.search('p')!=-1 || color.search('u')!=-1 || color.search('z')!=-1){
		alert('Please enter some hexadecimal color code to continue (0-9/A-F).');
	}
	else{
		document.getElementById('colorChoice'+id).style.display="none";
		document.getElementById('activeColorBox'+id).style.backgroundColor=color;
		document.getElementById('thecolor'+id).value='';
	}
}