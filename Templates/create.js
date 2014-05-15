	 var nodes = null;
		var edges = null;
		var graph = null;

		function draw() {
		 /* nodes = [{"id":0,"label":"Athens"},
				   {"id":1,"label":"Rome"},
				   {"id":2,"label":"Carthage"},
				   {"id":3,"label":"Alexandria"},
				   {"id":4,"label":"Constantinople"},
				   {"id":5,"label":"Lyon"},
				   {"id":6,"label":"Marseille"}];
		  edges = [{"from":1,"to":0,"id":"19313624-3af5-653d-90fd-d475f59346"}];*/
		  
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
			stabilize: false,
			dataManipulation: true,
		   /* onAdd: function(data,callback) {
			  var span = document.getElementById('operation');
			  var idInput = document.getElementById('node-id');
			  var labelInput = document.getElementById('node-label');
			  var saveButton = document.getElementById('saveButton');
			  var cancelButton = document.getElementById('cancelButton');
			  var div = document.getElementById('graph-popUp');
			  span.innerHTML = "Add Node";
			  idInput.value = data.id;
			  labelInput.value = data.label;
			  saveButton.onclick = saveData.bind(this,data,callback);
			  cancelButton.onclick = clearPopUp.bind();
			  div.style.display = 'block';
			},*/
			onEdit: function(data,callback) {
			  var span = document.getElementById('operation');
			  var idInput = document.getElementById('node-id');
			  var labelInput = document.getElementById('node-label');
			  var saveButton = document.getElementById('saveButton');
			  var cancelButton = document.getElementById('cancelButton');
			  var div = document.getElementById('graph-popUp');
			  span.innerHTML = "Edit Node";
			  idInput.value = data.id;
			  labelInput.value = data.label;
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
			  }
			  else {

				callback(data);
			  }
			}
		  };
		  graph = new vis.Graph(container, data, options);

		  // add event listeners
		  /*graph.on('select', function(params) {
			document.getElementById('selection').innerHTML = 'Selection: ' + params.nodes;
		  });*/

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
			var div = document.getElementById('graph-popUp');
			data.id = idInput.value;
			data.label = labelInput.value;
			clearPopUp();
			callback(data);

		  }
		}