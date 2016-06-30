function drawLinks(json){

	var canvas = document.getElementById('canvas');
    var context1 = canvas.getContext('2d');
    context1.globalCompositeOperation = "destination-over";
    var rect = canvas.getBoundingClientRect();
  
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
			if(canvas.width>=1250){
				canvas.style.left='-'+((canvas.width)/8)+'px';
			}
			canvas.style.transform='scale(0.75)';
			break;
		case 0.35:
			if(canvas.width>=1250){
				canvas.style.left='-'+((canvas.width)/4)+'px';
			}
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
			if(canvas.width>=1250){
				canvas.style.left='-'+((canvas.width)/8)+'px';
			}
			
			break;
		case 0.75:
			canvas.style.transform='scale(0.5)';
			if(canvas.width>=1250){
				canvas.style.left='-'+((canvas.width)/4)+'px';
			}
			break;
		case 0.5:
			canvas.style.transform='scale(0.35)';
			if(canvas.width>=1250){
				canvas.style.left='-'+(((canvas.width)*32.5)/100)+'px';
			}
			break;
		case 0.35:
			break;
		default:
			canvas.style.transform='scale(1)';
	}
}

function drawTopo(json){
	// alert(json);
	var canvas = document.getElementById('canvas');
	var context = canvas.getContext('2d');
	
	canvas.width=parseInt(canvas.style.width);
	canvas.height=parseInt(canvas.style.height);

	var elements = [];

	obj=JSON.parse(json);
	// alert(obj.topo[1][disp]);
	for(node in obj.topo){
		var name = obj.topo[node].name;
		var id = obj.topo[node].id;
		var shape = obj.topo[node].shape;
		var posX = obj.topo[node].posX;
		var posY = obj.topo[node].posY;
		var disp = obj.topo[node].disp;
		// alert(disp);

		elements.push({
		    name: name,
		    shape: shape,
		    posX: posX,
		    posY: posY,
		    disp: disp
		});
	}

	// alert(elements[0].disp);
	elements.forEach(function(element) {
		// alert(element.disp);
		// alert(element.disp);
		if(element.shape=='round'){
			if(element.disp=='sel'){
				var backgroundRound = context.createRadialGradient(element.posX+25,element.posY+25,1,element.posX+25,element.posY+25,18);
				backgroundRound.addColorStop(0,"#FDD9A8");
				backgroundRound.addColorStop(1,"#DAA520");
			}else if(element.disp=='false'){
				var backgroundRound = context.createRadialGradient(element.posX+25,element.posY+25,1,element.posX+25,element.posY+25,18);
				backgroundRound.addColorStop(0,"#EEEEEE");
				backgroundRound.addColorStop(1,"#999999");
			}else if(element.disp==''){
				var backgroundRound = context.createRadialGradient(element.posX+25,element.posY+25,1,element.posX+25,element.posY+25,18);
				backgroundRound.addColorStop(0,"#FFAAAA");
				backgroundRound.addColorStop(1,"#AA2222");
			}
			else if(element.disp=='true'){
				var backgroundRound = context.createRadialGradient(element.posX+25,element.posY+25,1,element.posX+25,element.posY+25,18);
				backgroundRound.addColorStop(0,"#FFAAAA");
				backgroundRound.addColorStop(1,"#AA5555");
			}
			// alert(element.name);
			
			context.fillStyle = backgroundRound;
			context.moveTo(element.posX+25,element.posY+25);
			context.beginPath();
    		context.lineWidth='2';
			context.arc(element.posX+25,element.posY+25,20,0,2*Math.PI);
			context.fill();
			context.stroke();
			context.fillStyle = "black";
			context.textAlign = "center";
			context.textBaseline = "middle";
			if(element.name.length>3){
				context.font = "11pt Arial, Times";
			}
			else{
				context.font = "13pt Arial, Times";
			}
   			context.fillText (element.name,element.posX+25, element.posY+25);
   			context.closePath();
		}
		if(element.shape=='square'){
			if(element.disp=='sel'){
				var backgroundSquare = context.createLinearGradient(element.posX+5,element.posY+5,element.posX+43,element.posY+43);
				backgroundSquare.addColorStop(0,"#FDD9A8");
				backgroundSquare.addColorStop(1,"#DAA520");
			}else if(element.disp=='false'){
				var backgroundSquare = context.createLinearGradient(element.posX+5,element.posY+5,element.posX+43,element.posY+43);
				backgroundSquare.addColorStop(0,"#EEEEEE");
				backgroundSquare.addColorStop(1,"#999999");
			}else if(element.disp==''){
				var backgroundSquare = context.createLinearGradient(element.posX+5,element.posY+5,element.posX+43,element.posY+43);
				backgroundSquare.addColorStop(0,"#AAFFAA");
				backgroundSquare.addColorStop(1,"#99AA99");
			}else if(element.disp=='true'){
				var backgroundSquare = context.createLinearGradient(element.posX+5,element.posY+5,element.posX+43,element.posY+43);
				backgroundSquare.addColorStop(0,"#AAFFAA");
				backgroundSquare.addColorStop(1,"#AABBAA");
			}

			context.fillStyle = backgroundSquare;
			context.moveTo(posX+5,posY+5);
			context.beginPath();
    		context.lineWidth='2';
			context.rect(element.posX+5,element.posY+5,40,40);
			context.fill();
			context.stroke();
			context.fillStyle = "black";
			context.textAlign = "center";
			context.textBaseline = "middle";
			if(element.name.length>3){
				context.font = "11pt Arial, Times";
			}
			else{
				context.font = "13pt Arial, Times";
			}
   			context.fillText(element.name, element.posX+24, element.posY+24); 
   			context.closePath();
		}
    	
	});


}

function clickoncanvas(json,event){
	// alert(json);
	var canvas = document.getElementById('canvas');
	var context = canvas.getContext('2d');
	obj=JSON.parse(json);
	var rect = canvas.getBoundingClientRect();
	var elemLeft = rect.left;
	var elemTop = rect.top;
    var x = event.clientX - elemLeft,
        y = event.clientY - elemTop;
    console.log(x, y);
    var elements = [];

    for(node in obj.topo){
		var name = obj.topo[node].name;
		var id = obj.topo[node].id;
		var shape = obj.topo[node].shape;
		var posX = obj.topo[node].posX;
		var posY = obj.topo[node].posY;
		var disp = obj.topo[node].disp;

		elements.push({
			id: id,
		    name: name,
		    shape: shape,
		    posX: posX,
		    posY: posY,
		    disp: disp
		});
	}
	// alert(document.getElementById('removeNodeFromTopo'));
	 elements.forEach(function(element) {

	 	if(document.getElementById('removeNodeFromTopo')!=null && document.getElementById('editNodeFromTopo')!=null){
	 		if(document.getElementById('removeNodeFromTopo').checked==true){
	 			if(y > element.posY + 5 && y < element.posY + 45 && x > element.posX + 5 && x < element.posX + 45){
	 				if(element.shape=='round'){
	 					deleteNodeByName(element.name);
	 				}else{
	 					alert('You can\'t delete a switch point, if you really want to delete it, you\'ll have to delete all the endpoints which are linked to this switch.');
	 				}
	    		}
	 		}else if(document.getElementById('editNodeFromTopo').checked==true){
	 			if(y > element.posY + 5 && y < element.posY + 45 && x > element.posX + 5 && x < element.posX + 45){
	 				popupNodeSchema(element.id,element.name);
	 			}
	 		}
	 	}
	 	else{
	 		if(y > element.posY + 5 && y < element.posY + 45 && x > element.posX + 5 && x < element.posX + 45){
	    		// alert('on a cliqué sur le noeud'+ element.name+' qui a la dispo : '+element.disp);
	    		 if (element.disp=='false' || element.disp=='sel') {

	        	}else{
	        		getMessage(element.name);
	        	}
	    	}
	 	}
	    	
	    });
}
