 
function draw(info) {
var nodes = null;
var edges = null;
var graph = null;
	
	if(info != ""){
		infos=info.split(";");
        
           // alert(infos);
            infoNode= infos[0].split(",");
            if(infos[1] != undefined) {
                infoEdge= infos[1].split(",");
        
                nodes=[];
                edges=[];
                countNode=0;
                countEdge=0;			
                while (countNode < infoNode.length){
                    var currentGroup;
                    var name = infoNode[countNode+1];
                    name=name.trim();
                    if(name.indexOf("S") == 0) {
                        currentGroup = 'switches';
                    }
                    else {
                        currentGroup = 'endpoints';
                    }
                    nodes.push({"id":parseInt(infoNode[countNode]),"label":infoNode[countNode+1], "group":currentGroup});
                    countNode=countNode+2;
                }  
            }
            if (infos[1] != "" && infos[1] != undefined){
                while (countEdge < infoEdge.length) {
                    edges.push({"from":parseInt(infoEdge[countEdge]),"to":parseInt(infoEdge[countEdge+1]),"id":parseInt(infoEdge[countEdge+2])});
                    countEdge=countEdge+3;
                }
            }
	} 
	// create a graph
	var container = document.getElementById('mygraph');
	var options = {	  
		nodes: {
			allowedToMoveX:false,
		},
			
		edges: {
			color: "rgb(20,20,30)",
			length: 50
		},
			
		groups : {
			switches:{
				shape:"circle",
				fontColor: "rgb(0,0,0)",	
				fontFace: "Verdana",
				fontSize: 15,
				color: {		
					background: "rgb(220,220,230)",
					border: "rgb(210,210,230)",
					highlight:{
						background:"rgb(210,210,230)",
						font: "rgb(20,20,30)",
						border: "rgb(20,20,30)"
					}
				},
			}, 
			endpoints : {
				shape:"square",
				fontColor: "rgb(0,0,0)",	
				fontFace: "Verdana",
				fontSize: 8,
				color: {		
					background: "rgb(20,20,30)",
					border: "rgb(210,210,230)",
					highlight:{
						background:"rgb(210,210,230)",
						font: "rgb(20,20,30)",
						border: "rgb(20,20,30)"
					}
				},
			}
		},	
		physics: {
	        barnesHut: {
	            enabled: false,
	            gravitationalConstant: -2000,
	            centralGravity: 1.0,
	            springLength: 95,
	            springConstant: 0.50,
	            damping: 0.09
	        },
	        repulsion: {
	            centralGravity: 1.0,
	            springLength: 50,
	            springConstant: 0.05,
	            nodeDistance: 100,
	            damping: 0.09
	        },
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
				/*var ip = document.getElementById('node-ip');
				ip.value=parseInt(outputs[0]);
				var sched = document.getElementById('node-sched');
				sched.value=outputs[1];*/
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
	var data = {
			nodes: nodes,
			edges: edges
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
	}

	function saveData(data,callback) {
		var idInput = document.getElementById('node-id');
		var labelInput = document.getElementById('node-label');
		var ip = 0;//document.getElementById('node-ip');
		var sched = 'FIFO';//document.getElementById('node-sched');
		var div = document.getElementById('graph-popUp');
		data.id = idInput.value;
		data.label = labelInput.value;
		updateNode(idInput.value,labelInput.value,ip.value,sched.value);
		
		clearPopUp();
		callback(data);
	}
	

}
		
		