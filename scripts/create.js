 
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
		
function drawLinks(json){

	var canvas = document.getElementById('canvas');
	// canvas.width=parseInt(canvas.style.width);
	// canvas.height=parseInt(canvas.style.height);
	// var topologie= document.getElementById('topologie');
	// canvas.style.width=topologie.width;
    var context1 = canvas.getContext('2d');
    context1.globalCompositeOperation = "destination-over";
  
	obj=JSON.parse(json);
	
	for(link in obj.links){
		var top1=parseInt(document.getElementById(obj.links[link].node1).style.top);
 		var top2=parseInt(document.getElementById(obj.links[link].node2).style.top);
 		var left1=parseInt(document.getElementById(obj.links[link].node1).style.left);
		var left2=parseInt(document.getElementById(obj.links[link].node2).style.left);

		// alert('de '+left1+', '+top1+' à '+left2+', '+top2);
		// alert('et maintenant tracez vous');

		context1.beginPath();
		context1.lineWidth='1';
		context1.moveTo(left1+25,top1+25);
		context1.lineTo(left2+25,top2+25);
		context1.stroke();
		context1.closePath();
	}
}

function drawTopo(json){
	var canvas = document.getElementById('canvas');
	canvas.width=parseInt(canvas.style.width);
	canvas.height=parseInt(canvas.style.height);
	// var topologie= document.getElementById('topologie');
	// canvas.style.width=topologie.width;
    var context = canvas.getContext('2d');
    // context1.globalCompositeOperation = "destination-over";
  
	obj=JSON.parse(json);
	// alert(obj.topo);
	for(node in obj.topo){
		var name = obj.topo[node].name;
		var id = obj.topo[node].id;
		var shape = obj.topo[node].shape;
		var posX = obj.topo[node].posX;
		var posY = obj.topo[node].posY;
		var rank = obj.topo[node].rank;
		var parent = obj.topo[node].parent;
		
		// alert('et maintenant tracez vous');
		if(shape=='round'){
			// alert('node : '+name+' posX : '+posX+' posY : '+posY+' rank : '+rank+' parent : '+parent);
			var backgroundRound = context.createRadialGradient(posX+25,posY+25,1,posX+25,posY+25,18);
			backgroundRound.addColorStop(0,"#FFAAAA");
			backgroundRound.addColorStop(1,"#AA2222");
			context.fillStyle = backgroundRound;
			context.moveTo(posX+25,posY+25);
			context.beginPath();
			context.lineWidth='2';
			context.arc(posX+25,posY+25,18,0,2*Math.PI);
			context.fill();
			context.stroke();
			context.fillStyle = "black";
			
			context.textAlign = "center";
			context.textBaseline = "middle";
			if(name.length>3){
				context.font = "11pt Arial, Times";
			}
			else{
				context.font = "13pt Arial, Times";
			}
   			context.fillText (name,posX+25, posY+25); 
			context.closePath();
		}
		else{
			// alert('node : '+name+' posX : '+posX+' posY'+posY+' rank : '+rank+' parent : '+parent);
			var backgroundSquare = context.createLinearGradient(posX+5,posY+5,posX+43,posY+43);
			backgroundSquare.addColorStop(0,"#AAFFAA");
			backgroundSquare.addColorStop(1,"#99AA99");
			context.fillStyle = backgroundSquare;
			context.moveTo(posX+5,posY+5);
			context.beginPath();
			context.lineWidth='2';
			context.rect(posX+5,posY+5,38,38);
			context.fill();
			context.stroke();
			context.fillStyle = "black";
			context.font = "14pt Arial, Times";
			context.textAlign = "center";
			context.textBaseline = "middle";
			if(name.length>3){
				context.font = "11pt Arial, Times";
			}
			else{
				context.font = "13pt Arial, Times";
			}
   			context.fillText(name, posX+24, posY+24); 
			context.closePath();
		}
	}
}
function zoomplus(){
	var canvas = document.getElementById('canvas');
	var trans = window.getComputedStyle(canvas, null);
	tr=trans.getPropertyValue("transform");
	var values = tr.split('(')[1].split(')')[0].split(',');
	var a = values[0];
	var b = values[1];
	var c = values[2];
	var d = values[3];
	var scale = Math.sqrt(a*a + b*b);
	// canvas.style.transform='scale(0.75)';
	
	switch (scale){
		case 1:
			break;
		case 0.75:
			canvas.style.left='0';
			canvas.style.transform='scale(1)';
			break;
		case 0.5:
			canvas.style.left='0';
			canvas.style.transform='scale(0.75)';
			canvas.style.left='-'+((canvas.width)/8)+'px';
			break;
		case 0.35:
			canvas.style.left='-'+((canvas.width)/4)+'px';
			canvas.style.transform='scale(0.5)';
			break;
		default:
			canvas.style.transform='scale(1)';
	}
	// canvas.style.transform='scale(1)';
	// canvas.style.transform='scale:1.5';
}
function zoomminus(){
	var canvas = document.getElementById('canvas');
	var trans = window.getComputedStyle(canvas, null);
	tr=trans.getPropertyValue("transform");
	var values = tr.split('(')[1].split(')')[0].split(',');
	var a = values[0];
	var b = values[1];
	var c = values[2];
	var d = values[3];
	var scale = Math.sqrt(a*a + b*b);
	// alert(scale);
	// canvas.style.transform='scale:1.5';
	switch (scale){
		case 1:
			canvas.style.transform='scale(0.75)';
			canvas.style.left='-'+((canvas.width)/8)+'px';
			break;
		case 0.75:
			canvas.style.transform='scale(0.5)';
			canvas.style.left='-'+((canvas.width)/4)+'px';
			break;
		case 0.5:
			canvas.style.transform='scale(0.35)';
			canvas.style.left='-'+(((canvas.width)*32.5)/100)+'px';
			break;
		case 0.35:
			break;
		default:
			canvas.style.transform='scale(1)';
	}
}
		