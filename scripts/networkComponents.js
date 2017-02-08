function editNode() {
	$.ajax({
		url:"./Controllers/ElementsController.php",
		type:"post",
		data:'action='+'editNode'+'&id='+document.getElementById('node-id').value+'&label='+document.getElementById('node-label').value+'&ipAddress=0'+'&scheduling=FIFO'+'&speed='+document.getElementById('node-speed').value,
		success:function(data){
			document.getElementById("corps").innerHTML = data;
		}
	});
}

function getTopo(){
	$.ajax({
		url:"./Controllers/ElementsController.php",
		type:"post",
		data:"action=getTopo",
		success:function(data){
			drawTopo(data);		
		}
	});
}

function deleteNode($id) {
	$.ajax({
		url:"./Controllers/ElementsController.php",
		type:"post",
		data:"action=getTopo",
		success:function(data){
			obj=JSON.parse(data);	
			for(node in obj.topo){
				var idnode = obj.topo[node].id;
				var shape = obj.topo[node].shape;
				if($id==idnode){
					if(shape=='square'){
						alert('You can\'t delete a switch point, if you really want to delete it, you\'ll have to delete all the endpoints which are linked to this switch.');
					}else{
						$.ajax({
						url:"./Controllers/ElementsController.php",
						type:"post",
						data:'action='+'deleteNode'+'&id='+$id,
							success:function(data){
								loadContent("details");
							}
						});
					}
				}
			}
		}
	});
}

function editNodeSchema(){
	$.ajax({
		url:"./Controllers/ElementsController.php",
		type:"post",
		data:'action='+'editNodeSchema'+'&id='+document.getElementById('nodeSchema-id').value+'&label='+document.getElementById('nodeSchema-label').value,
		success:function(data){
			document.getElementById("corps").innerHTML = data;
			createSchema();
		}
	});
}

function addLink(id1,id2){
	$.ajax({
		url:"./Controllers/ElementsController.php",
		type:"post",
		data:'action='+'addLink'+'&id1='+id1+'&id2='+id2
	});
}

function editLink($id) {
	$.ajax({
		url:"./Controllers/ElementsController.php",
		type:"post",
		data:'action='+'editLink'+'&id='+document.getElementById('link-id').value+'&node1='+document.getElementById('node1-label').value+'&node2='+document.getElementById('node2-label').value,
		success:function(data){
			document.getElementById("corps").innerHTML = data;
		}
	});
}


function deleteLink($id,$node1,$node2) {
  
	$.ajax({
		url:"./Controllers/ElementsController.php",
		type:"post",
		data:'action='+'deleteLink'+'&id='+$id+'&source='+$node1+'&destination='+$node2,
		success:function(data){
			document.getElementById("corps").innerHTML = data;
		}
	});
}

function addNode(name, ip, sched, crit){
	$.ajax({
		url:"./Controllers/ElementsController.php",
		type:"post",
		data:'action='+'addNode'+'&name='+name+'&ip='+ip+'&sched='+sched+'&crit='+crit,
		success:function(data){
			createSchema();
		}
	});
}


function saveEditedMessage(id, wcetIdTable) {
	var pathElement 	= document.getElementById('path_'+id);
	var periodElement 	= document.getElementById('peri_'+id);
	var offsetElement	= document.getElementById('offs_'+id);
	var colorElement	= document.getElementById('inputColor'+id);
	var wcetStr = "";
	
	var cptId = 0;
	
	/* Computing all the WCET values */
	while(wcetIdTable[cptId] != undefined) {
		var wcetElt = "wcet_"+wcetIdTable[cptId];
		var idElt = wcetElt+"_"+id;
		
		var valueWcet = document.getElementById(idElt).childNodes[0].value;
		if (valueWcet == undefined) {
			valueWcet = document.getElementById(idElt).innerHTML;	
		}
		wcetStr += wcetIdTable[cptId]+"="+valueWcet+":";
		
		cptId++;
	}
	
	var path 	= pathElement.childNodes[0].value;
	var period 	= periodElement.childNodes[0].value;
	var offset 	= offsetElement.childNodes[0].value;
	var color 	= colorElement.value;
	
	if(path == undefined) {
		path = pathElement.innerHTML;
	}
	
	if(period == undefined) {
		period = periodElement.innerHTML;
	}
	
	if(offset == undefined) {
		offset = offsetElement.innerHTML;
	}
	
	$.ajax({
		url:"./Controllers/ElementsController.php",
		type:"post",
		data:'action='+'editMessage'+'&id='+id+'&path='+path+'&period='+period+'&offset='+offset+"&wcetStr="+wcetStr+"&color="+color,
		success:function(data){
			loadContent('messages');
		}
	});
}

function editMessage($id) {
	$.ajax({
		url:"./Controllers/ElementsController.php",
		type:"post",
		data:'action='+'editMessage'+'&id='+document.getElementById('message-id').value+'&path='+document.getElementById('path').value+'&period='+document.getElementById('period').value+'&offset='+document.getElementById('offset').value+'&wcet='+document.getElementById('wcet').value,
		success:function(data){
			document.getElementById("corps").innerHTML = data;
		}
	});
}

function deleteMessage($id) {
	$.ajax({
		url:"./Controllers/ElementsController.php",
		type:"post",
		data:'action='+'deleteMessage'+'&id='+$id,
		success:function(data){
			//document.getElementById("corps").innerHTML = data;
			
			loadContent('messages');
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
		url:"./Controllers/ElementsController.php",
		type:"post",	data:'action='+'addMessage'+'&path='+document.getElementById('path').value.trim()+'&period='+document.getElementById('period').value+'&offset='+document.getElementById('offset').value+'&wcetStr='+wcetStr+'&color='+document.getElementById('inputColor').value,
		success:function(data){
			data=data.trim();
			/* Reload page */
			loadContent('messages');
		}
	});
}

function ajaxSaveMessage(){
	$.ajax({
		url:"./Controllers/ElementsController.php",
		type:"post",
		data:'action='+'addMessage'+'&path='+document.getElementById('path').value+'&period='+document.getElementById('period').value+'&offset='+document.getElementById('offset').value+'&wcet='+document.getElementById('wcet').value,
		success:function(data){
			data=data.trim();
			if(data!=''){
			}
		}
	});
}




