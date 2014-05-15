

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
	var span = document.getElementById('edit-title');
	var id = document.getElementById('node-id');
	var div = document.getElementById('popup-node-edit');
	span.innerHTML = "Edit Node n."+$id;
	id.value = $id;
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

function editLink($id) {
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:'action='+'editLink'+'&id='+$id,
		success:function(data){
			document.getElementById("corps").innerHTML = data;
		}
	});
}

function deleteLink($id) {
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:'action='+'deleteLink'+'&id='+$id,
		success:function(data){
			document.getElementById("corps").innerHTML = data;
		}
	});
}

function editMessage($id) {
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:'action='+'editMessage'+'&id='+$id,
		success:function(data){
			document.getElementById("corps").innerHTML = data;
		}
	});
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