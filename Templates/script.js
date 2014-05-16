

function loadCreate() {
	$.ajax({
		url:"./Views/create.php",
		type:"post",
		data:"",
		success:function(data){
			//alert(data);
			document.getElementById("corps").innerHTML = data;
			draw();
		}
	});
}
 
 function loadDetails() {
	$.ajax({
		url:"Controller.php",
		type:"post",
		data:"action=view",
		success:function(data){
			document.getElementById("corps").innerHTML = data;
		}
	});
}

function loadResults() {
	$.ajax({
		url:"./Views/results.php",
		type:"post",
		data:"",
		success:function(data){
			document.getElementById("corps").innerHTML = data;
		}
	});
}

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

function popupNode($id) {
	var span = document.getElementById('edit-node-title');
	var id = document.getElementById('node-id');
	var div = document.getElementById('popup-node-edit');
	span.innerHTML = "Edit Node n."+$id;
	id.value = $id;
	div.style.display = 'block';
}

function deleteNode($id, $name) {
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:'action='+'deleteNode'+'&id='+$id+'&name='+$name,
		success:function(data){
			document.getElementById("corps").innerHTML = data;
		}
	});
}

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

function popupLink($id) {
	var span = document.getElementById('edit-link-title');
	var id = document.getElementById('link-id');
	var div = document.getElementById('popup-link-edit');
	span.innerHTML = "Edit Link n."+$id;
	id.value = $id;
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

function popupMessage($id) {
	var span = document.getElementById('edit-message-title');
	var id = document.getElementById('message-id');
	var div = document.getElementById('popup-message-edit');
	span.innerHTML = "Edit Message n."+$id;
	id.value = $id;
	div.style.display = 'block';
}

function deleteMessage($id) {
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:'action='+'deleteMessage'+'&id='+$id,
		success:function(data){
			document.getElementById("corps").innerHTML = data;
		}
	});
}

function generate() {
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:'action='+'generate',
		success:function(data){
			document.getElementById("corps").innerHTML = data;
		}
	});
}