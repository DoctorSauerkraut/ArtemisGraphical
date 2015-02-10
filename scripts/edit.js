function editValue(element) {
	if(!isNaN(parseFloat(element.innerHTML)) && isFinite(element.innerHTML)) {
		element.innerHTML = "<td><input id='' type='text'  value="+(element.innerHTML)+" /></td>";
	}
}

function saveEditedMessage(id) {
	var id 		= id;
	
	var pathElement 	= document.getElementById('path_'+id);
	var periodElement 	= document.getElementById('peri_'+id);
	var offsetElement	= document.getElementById('offs_'+id);
	var wcetElement		= document.getElementById('wcet_'+id);
	
	var path 	= pathElement.childNodes[0].value;
	var period 	= periodElement.childNodes[0].value;
	var offset 	= offsetElement.childNodes[0].value;
	var wcet 	= wcetElement.childNodes[0].value;
	
	if(path == undefined) {
		path = pathElement.innerHTML;
	}
	
	if(period == undefined) {
		period = periodElement.innerHTML;
	}
	
	if(offset == undefined) {
		offset = offsetElement.innerHTML;
	}
	
	if(wcet == undefined) {
		wcet = wcetElement.innerHTML;
	}
	
	
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:'action='+'editMessage'+'&id='+id+'&path='+path+'&period='+period+'&offset='+offset+'&wcet='+wcet,
		success:function(data){
			loadContent('messages');
		}
	});
}

function saveSettings() {	
	var time 	= $("#timelimit")[0].value;
	var latency = $("#elatency")[0].value;
	
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:'action='+'saveSettings'+'&time='+time+'&elatency='+latency,
		success:function(data){
			loadContent('settings');
		}
	});
}