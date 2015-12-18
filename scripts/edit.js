function editValue(element) {
	if(!isNaN(parseFloat(element.innerHTML)) && isFinite(element.innerHTML)) {
		element.innerHTML = "<td><input id='' type='text'  value="+(element.innerHTML)+" /></td>";
	}
}

function editWcet(element, wcetId) {
	editValue(element);
}

function saveEditedMessage(id, wcetIdTable) {
	var pathElement 	= document.getElementById('path_'+id);
	var periodElement 	= document.getElementById('peri_'+id);
	var offsetElement	= document.getElementById('offs_'+id);
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
		url:"./Controller.php",
		type:"post",
		data:'action='+'editMessage'+'&id='+id+'&path='+path+'&period='+period+'&offset='+offset+"&wcetStr="+wcetStr,
		success:function(data){
			loadContent('messages');
		}
	});
}

function saveSettings() {	
	var time 	= $("#timelimit")[0].value;
	var latency = $("#elatency")[0].value;
    var wcttcompute = $("#wcttcompute")[0].value;
    
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:'action='+'saveSettings'+'&time='+time+'&elatency='+latency+"&wcttcompute="+wcttcompute,
		success:function(data){
			loadContent('messages');
		},
		fail:function(jqXHR, textStatus){
			//alert(textStatus);
		}
	});
}