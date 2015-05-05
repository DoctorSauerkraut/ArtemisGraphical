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
		url:"Controller.php",
		type:"post",
		data:"action="+action,
		success:function(data){
			document.getElementById("corps").innerHTML = data;
			updateMenuStyle("link-"+action);
		}
	});
}

function loadCreate() {
	$.ajax({
		url:"./Views/create.php",
		type:"post",
		data:"",
		success:function(data){
			document.getElementById("corps").innerHTML = data;
			recupDatabase();	
			updateMenuStyle("link-create");
			
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
				}
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
		data:'action='+'editNode'+'&id='+document.getElementById('node-id').value+'&label='+document.getElementById('node-label').value+'&ipAddress='+document.getElementById('node-ip').value+'&scheduling='+document.getElementById('node-sched').value+'&criticality='+document.getElementById('node-crit').value,
		success:function(data){
			document.getElementById("corps").innerHTML = data;
		}
	});
}

function hideNode(){
	var div = document.getElementById('popup-node-edit');
	div.style.display = 'none';
}

function popupNode($id, $name, $ip, $sched, $crit) {
	var span = document.getElementById('edit-node-title');
	var id = document.getElementById('node-id');
	var name = document.getElementById('node-label');
	var ip = document.getElementById('node-ip');
	var sched = document.getElementById('node-sched');
	var crit = document.getElementById('node-crit');
	var div = document.getElementById('popup-node-edit');
	span.innerHTML = "Edit Node n."+$id;
	id.value = $id;
	name.value=$name;
	ip.value=$ip;
	sched.value=$sched;
	crit.value=$crit;
	div.style.display = 'block';
}

function deleteNode($id) {
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:'action='+'deleteNode'+'&id='+$id,
		success:function(data){
			document.getElementById("corps").innerHTML = data;
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
		data:'action='+'addMessage'+'&path='+document.getElementById('path').value+'&period='+document.getElementById('period').value+'&offset='+document.getElementById('offset').value+'&wcetStr='+wcetStr,
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
				alert(data);
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
			alert("done:"+data);
		}
	});	
}

