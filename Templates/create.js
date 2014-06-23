var nodes = null;
var edges = null;
var graph = null;

function draw(info) {

	if(info != ""){
		infos=info.split(";");
		infoNode= infos[0].split(",");
		infoEdge= infos[1].split(",");
		nodes=[];
		edges=[];
		countNode=0;
		countEdge=0;			
		while (countNode < infoNode.length){
			nodes.push({"id":parseInt(infoNode[countNode]),"label":infoNode[countNode+1]});
			countNode=countNode+2;
		}  
		if (infos[1] != ""){
			while (countEdge < infoEdge.length) {
				edges.push({"from":parseInt(infoEdge[countEdge]),"to":parseInt(infoEdge[countEdge+1]),"id":parseInt(infoEdge[countEdge+2])});
				countEdge=countEdge+3;
			}
		}

	} 
	// create a graph
	var container = document.getElementById('mygraph');
	var data = {
		nodes: nodes,
		edges: edges
	};
	var options = {	  
		nodes: {
			fontColor: "rgb(255,255,255)",	
			fontFace: "Verdana",
			fontSize: 15,
			color: {		
				background: "rgb(20,20,30)",
				border: "rgb(210,210,230)",
				highlight:{
					background:"rgb(210,210,230)",
					font: "rgb(20,20,30)",
					border: "rgb(20,20,30)"
				}
			}
		},
			
		edges: {
			color: "rgb(20,20,30)",
			length: 50
		},
			
		stabilize: true,
		dataManipulation: true,
		onAdd: function(data,callback) {
			addNode("",0,"FIFO",0);
			saveData.bind(this,data,callback);  
			},
			
		onEdit: function(data,callback) {	
			var span = document.getElementById('operation');
			var idInput = document.getElementById('node-id');
			var labelInput = document.getElementById('node-label');
			var saveButton = document.getElementById('saveButton');
			var cancelButton = document.getElementById('cancelButton');
			var div = document.getElementById('graph-popUp');
			span.innerHTML = "Edit Node n°"+data.id;
			idInput.value = data.id;
			labelInput.value = data.label;

			var donnees = fillPopUp();
			  
			donnees.success(function (data) {
				var output = data;
				outputs=output.split(",");
				var ip = document.getElementById('node-ip');
				ip.value=parseInt(outputs[0]);
				var sched = document.getElementById('node-sched');
				sched.value=outputs[1];
				var crit = document.getElementById('node-crit');
				crit.value=parseInt(outputs[2]);
			});
			 			  			  
			saveButton.onclick = saveData.bind(this,data,callback);
			cancelButton.onclick = clearPopUp.bind();
			div.style.display = 'block';
			  
		},
		
		onConnect: function(data,callback) {
			if (data.from == data.to) {
				var r=confirm("Do you want to connect the node to itself?");
				if (r==true) {
					callback(data);
				}
			}else {
				callback(data);
			}
			addLink(data.from, data.to);
		}
	};
	
	graph = new vis.Graph(container, data, options);

	graph.on("resize", function(params) {
		console.log(params.width,params.height)});

	function clearPopUp() {
		var saveButton = document.getElementById('saveButton');
		var cancelButton = document.getElementById('cancelButton');
		saveButton.onclick = null;
		cancelButton.onclick = null;
		var div = document.getElementById('graph-popUp');
		div.style.display = 'none';
		var divAdd = document.getElementById('graph-popUp-adds');
		divAdd.style.display = 'none';
	}

	function saveData(data,callback) {
		var idInput = document.getElementById('node-id');
		var labelInput = document.getElementById('node-label');
		var ip = document.getElementById('node-ip');
		var sched = document.getElementById('node-sched');
		var crit = document.getElementById('node-crit');
		var div = document.getElementById('graph-popUp');
		data.id = idInput.value;
		data.label = labelInput.value;
		updateNode(idInput.value,labelInput.value,ip.value,sched.value,crit.value);
		clearPopUp();
		callback(data);

	}
}
		
		