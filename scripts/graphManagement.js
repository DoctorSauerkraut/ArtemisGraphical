function reloadGraph() {
	var startTimeGraph = $("#starttimegraph")[0].value;
	var endTimeGraph = $("#endtimegraph")[0].value;
    
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:"action=reloadGraph"+"&starttimegraph="+startTimeGraph+"&endtimegraph="+endTimeGraph,
		success:function(data){
            alert(data);
			viewGanttChart();
		}
	});	
}

/* Sets / Unsets a node for graph displaying */
function loadNodeOnCheck(nodeId, checked) {
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:"action=loadNodeForGraph"+"&nodeId="+nodeId+"&checked="+checked,
		success:function(data){
			viewGanttChart();
		}
	});	
}

function viewGanttChart() {
    popup('loadingGanttChart');
    
	$.ajax({
		url:"Controller.php",
		type:"post",
		data:'action='+'viewGanttChart',
		success:function(data){
			document.getElementById("corps").innerHTML = data;
			document.getElementById("link-results").innerHTML = "Simulate";
			closePopup();
		}
	});
}