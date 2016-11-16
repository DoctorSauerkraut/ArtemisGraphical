///////////// fonction de palcement des liens dans le canvas ////////////////////////////////////////////

function drawLinks(json){

	var canvas = document.getElementById('canvas'); // identification du canvas
    var context1 = canvas.getContext('2d'); // identification du contexte
    context1.globalCompositeOperation = "destination-over"; // on précise que tous ce qu'on va dessiner doit se trouver sous les dessins déjà présents
    var rect = canvas.getBoundingClientRect(); // on récupère la taille du canvas
  
	obj=JSON.parse(json); // on parse le tableau pour l'exploiter
	
	for(link in obj.links){ // pour tous les liens
		var top1=parseInt(document.getElementById(obj.links[link].node1).style.top); // on recupère la position en y du noeud1
 		var top2=parseInt(document.getElementById(obj.links[link].node2).style.top); // on recupère la position en y du noeud2
 		var left1=parseInt(document.getElementById(obj.links[link].node1).style.left); // on recupère la position en x du noeud1
		var left2=parseInt(document.getElementById(obj.links[link].node2).style.left); // on recupère la position en x du noeud2

		// alert('de '+left1+', '+top1+' à '+left2+', '+top2);
		// alert('et maintenant tracez vous');
		if(top2<top1){
			temp=top2;
			tempo=left2;
			top2=top1;
			left2=left1;
			top1=temp;
			left1=tempo;
		}

		context1.beginPath();
		context1.lineWidth='1';
		context1.moveTo(left1+25,top1+25);
		// context1.lineTo(left2+25,top2+25); //POUR LES LIENS EN LIGNES DROITES

		///////////////////// POUR LES LIENS EN ARCS /////////////////////////////////////
		if(left2<left1 && (left1-left2)>50){											//
			context1.bezierCurveTo(left2+75,top1+25,left2+25,top2,left2+25,top2+25);	//
		}																				//
		else if(left2>left1 && (left2-left1)>50){										//
			context1.bezierCurveTo(left2-25,top1+25,left2+25,top2,left2+25,top2+25);	//
		}																				//
		else if(left1==left2){															//
			context1.lineTo(left2+25,top2+25);											//
		}																				//
		else if(left2>left1 && (left2-left1)<=50){										//
			context1.bezierCurveTo(left2,top1+25,left2+25,top2,left2+25,top2+25);	//
		}	
		else if(left2<left1 && (left1-left2)<=50){										//
			context1.bezierCurveTo(left2+50,top1+25,left2+25,top2,left2+25,top2+25);	//
		}																					//
																						//
		//////////////////////////////////////////////////////////////////////////////////
		context1.stroke();
		context1.closePath();
	}	
}


/////////// fonction de zoom sur le canvas ////////////////////////////////////////////////

function zoomplus(){
	var canvas = document.getElementById('canvas'); // identification du canvas
	var trans = window.getComputedStyle(canvas, null); // on recupère les caractéristiques du canvas
	tr=trans.getPropertyValue("transform"); // on récupère les propriétés de la transformation du canvas
	var values = tr.split('(')[1].split(')')[0].split(','); // création d'un tableau contenant les propriétés
	var a = values[0];
	var b = values[1];
	var c = values[2];
	var d = values[3];
	var scale = Math.sqrt(a*a + b*b); // calcul de l'echelle du canvas
	// canvas.style.transform='scale(0.75)';
	
	switch (scale){
		case 1: // si l'echelle est de 1
			break;// on ne fais rien (ne peut pas ête plus grand)
		case 0.75: // si l'echelle est de 0.75
			canvas.style.left='0'; // on replace le canvas à gauche
			canvas.style.transform='scale(1)'; // on lui donne une echelle de 1 (taille normale)
			break;
		case 0.5: // si l'echelle est de 0.5
			if(canvas.width>=1250){ // si le canvas original est plus grand que 1250px de largeur, il va se décentrer
				canvas.style.left='-'+((canvas.width)/8)+'px';// on décale le canvas pour qu'il soit à la meilleure place possible
			}
			canvas.style.transform='scale(0.75)'; // on lui donne une echelle de 3/4
			break;
		case 0.35: // si l'echelle est de 0.35
			if(canvas.width>=1250){ // si le canvas original est plus grand que 1250px de largeur, il va se décentrer
				canvas.style.left='-'+((canvas.width)/4)+'px';// on décale le canvas pour qu'il soit à la meilleure place possible
			}
			canvas.style.transform='scale(0.5)'; // on lui donne une echelle de 1/2
			break;
		default: // sinon, on le laisse à une echelle de 1
			canvas.style.transform='scale(1)';
	}
	// canvas.style.transform='scale(1)';
	// canvas.style.transform='scale:1.5';
}

/////////// fonction de dézoom sur le canvas ////////////////////////////////////////////////

function zoomminus(){
	var canvas = document.getElementById('canvas'); // identification du canvas
	var trans = window.getComputedStyle(canvas, null); // on recupère les caractéristiques du canvas
	tr=trans.getPropertyValue("transform"); // on récupère les propriétés de la transformation du canvas
	var values = tr.split('(')[1].split(')')[0].split(','); // création d'un tableau contenant les propriétés
	var a = values[0];
	var b = values[1];
	var c = values[2];
	var d = values[3];
	var scale = Math.sqrt(a*a + b*b); // calcul de l'echelle du canvas
	// alert(scale);
	// canvas.style.transform='scale:1.5';
	switch (scale){
		case 1:// si l'echelle est de 1
			canvas.style.transform='scale(0.75)'; // on la passe à 3/4
			if(canvas.width>=1250){ // si le canvas original est plus grand que 1250px de largeur, il va se décentrer
				canvas.style.left='-'+((canvas.width)/8)+'px';// on décale le canvas pour qu'il soit à la meilleure place possible
			}
			
			break;
		case 0.75: // si l'echele est de 0.75
			canvas.style.transform='scale(0.5)'; // on la passe à 1/2
			if(canvas.width>=1250){// si le canvas original est plus grand que 1250px de largeur, il va se décentrer
				canvas.style.left='-'+((canvas.width)/4)+'px';// on décale le canvas pour qu'il soit à la meilleure place possible
			}
			break;
		case 0.5: // si l'echele est de 0.5
			canvas.style.transform='scale(0.35)'; // on la passe à 0.35
			if(canvas.width>=1250){// si le canvas original est plus grand que 1250px de largeur, il va se décentrer
				canvas.style.left='-'+(((canvas.width)*32.5)/100)+'px';// on décale le canvas pour qu'il soit à la meilleure place possible
			}
			break;
		case 0.35: // si l'echele est de 0.35
			break; // on ne fait rien (ne peut pas être plus petit)
		default:
			canvas.style.transform='scale(1)';
	}
}

////////////////////// fonction de dessin des noeuds qui forment la topologie ////////////////////////////////

function drawTopo(json){
	// alert(json);
	var canvas = document.getElementById('canvas'); // identification du canvas
	var context = canvas.getContext('2d'); // identification du contexte
	
	canvas.width=parseInt(canvas.style.width); // récupération de la largeur
	canvas.height=parseInt(canvas.style.height); // récupération de la hauteur

	var elements = []; // création d'un tableau d'éléments (en l'occurence des noeuds)

	obj=JSON.parse(json); // on parse le tableau
	// alert(obj.topo[1][disp]);
    if(obj != null) { // si le tableau n'est pas vide
        for(node in obj.topo){ // pour tous les noeuds, on recupère les infos du noeud
            var name = obj.topo[node].name;
            var id = obj.topo[node].id;
            var shape = obj.topo[node].shape;
            var posX = obj.topo[node].posX;
            var posY = obj.topo[node].posY;
            var disp = obj.topo[node].disp;
            // alert(disp);

            elements.push({ // on remplit le tableau
                name: name,
                shape: shape,
                posX: posX,
                posY: posY,
                disp: disp
            });
        }
    }
	// alert(elements[0].disp);
	elements.forEach(function(element) { // pour tous les élements, on place le noeud selon son posX et posY
		// alert(element.disp);
		// alert(element.disp);
		if(element.shape=='round'){ // cas d'un endpoint
			if(element.disp=='sel'){ // le noeud est sélectionné
				var backgroundRound = context.createRadialGradient(element.posX+25,element.posY+25,1,element.posX+25,element.posY+25,18);
				backgroundRound.addColorStop(0,"#FDD9A8");
				backgroundRound.addColorStop(1,"#DAA520");
			}else if(element.disp=='false'){ // le noeud ne peut pas être sélectionné
				var backgroundRound = context.createRadialGradient(element.posX+25,element.posY+25,1,element.posX+25,element.posY+25,18);
				backgroundRound.addColorStop(0,"#EEEEEE");
				backgroundRound.addColorStop(1,"#999999");
			}else if(element.disp==''){ // le noeud est disponible
				var backgroundRound = context.createRadialGradient(element.posX+25,element.posY+25,1,element.posX+25,element.posY+25,18);
				backgroundRound.addColorStop(0,"#FFAAAA");
				backgroundRound.addColorStop(1,"#AA2222");
			}
			else if(element.disp=='true'){ // le noeud prend une apparence normale
				var backgroundRound = context.createRadialGradient(element.posX+25,element.posY+25,1,element.posX+25,element.posY+25,18);
				backgroundRound.addColorStop(0,"#FFAAAA");
				backgroundRound.addColorStop(1,"#AA5555");
			}

			// dessin du noeud
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
			if(element.name.length>3){ // si le nom du noeud a plus de 3 caractère on l'écrit en plus petit
				context.font = "11pt Arial, Times";
			}
			else{
				context.font = "13pt Arial, Times";
			}
   			context.fillText (element.name,element.posX+25, element.posY+25);
   			context.closePath();
		}
		if(element.shape=='square'){ // cas d'un switch
			if(element.disp=='sel'){ // le noeud est sélectionné
				var backgroundSquare = context.createLinearGradient(element.posX+5,element.posY+5,element.posX+43,element.posY+43);
				backgroundSquare.addColorStop(0,"#FDD9A8");
				backgroundSquare.addColorStop(1,"#DAA520");
			}else if(element.disp=='false'){ // le noeud ne peut pa être sélectionné
				var backgroundSquare = context.createLinearGradient(element.posX+5,element.posY+5,element.posX+43,element.posY+43);
				backgroundSquare.addColorStop(0,"#EEEEEE");
				backgroundSquare.addColorStop(1,"#999999");
			}else if(element.disp==''){ // le noeud est disponible
				var backgroundSquare = context.createLinearGradient(element.posX+5,element.posY+5,element.posX+43,element.posY+43);
				backgroundSquare.addColorStop(0,"#AAFFAA");
				backgroundSquare.addColorStop(1,"#99AA99");
			}else if(element.disp=='true'){ // le noeud prend une apparence normale
				var backgroundSquare = context.createLinearGradient(element.posX+5,element.posY+5,element.posX+43,element.posY+43);
				backgroundSquare.addColorStop(0,"#AAFFAA");
				backgroundSquare.addColorStop(1,"#AABBAA");
			}

			// dessin du noeud
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
			if(element.name.length>3){ // si le nom du noeud a plus de 3 caractères, on l'écrit en plus petit
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

///////////// fonction de gestion des click sur le canvas
function clickoncanvas(json,event){
	// alert(json);
	var canvas = document.getElementById('canvas'); // identification du canvas
	var context = canvas.getContext('2d'); // identification du contexte
	obj=JSON.parse(json); // on parse le tableau
	var rect = canvas.getBoundingClientRect(); // récupère la taille du canvas
	var elemLeft = rect.left; // on récupère la position de départ du canvas sur la page
	var elemTop = rect.top;
    var x = event.clientX - elemLeft, // on calcule la position du click sur la page
        y = event.clientY - elemTop;
    console.log(x, y);
    var elements = []; // on crée un tableau d'élements (les noeuds)

    for(node in obj.topo){ // on récupère les infos des noeuds
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
	 elements.forEach(function(element) { // pour chaque noeud, en cas de click sur le canvas

	 	if(document.getElementById('removeNodeFromTopo')!=null && document.getElementById('editNodeFromTopo')!=null){ // si les boutons sont présents (alors on est sur la page create.php)
	 		if(document.getElementById('removeNodeFromTopo').checked==true){ // on veut supprimer un noeud
	 			if(y > element.posY + 5 && y < element.posY + 45 && x > element.posX + 5 && x < element.posX + 45){
	 				if(element.shape=='round'){ // si c'est un endpoint alors ok
	 					deleteNodeByName(element.name); // on supprime le noeud en identifiant son nom
	 				}else{ // sinon, une alerte previent que ce n'est pas possible
	 					alert('You can\'t delete a switch point, if you really want to delete it, you\'ll have to delete all the endpoints which are linked to this switch.');
	 				}
	    		}
	 		}else if(document.getElementById('editNodeFromTopo').checked==true){ // on veut renommer un noeud
	 			if(y > element.posY + 5 && y < element.posY + 45 && x > element.posX + 5 && x < element.posX + 45){
	 				popupNodeSchema(element.id,element.name); // on edit le noeud
	 			}
	 		}
	 	}
	 	else{ // sinon on est dans messages.php, et on crée le path d'un message
	 		if(y > element.posY + 5 && y < element.posY + 45 && x > element.posX + 5 && x < element.posX + 45){
	    		// alert('on a cliqué sur le noeud'+ element.name+' qui a la dispo : '+element.disp);
	    		 if (element.disp=='false' || element.disp=='sel') { // si le noeud est indispo ou bien déjà sélectionné
	    		 	// on ne fais rien
	        	}else{ // sinon on ajoute le nom du noeud dans le path du message à créer
	        		getMessage(element.name);
	        	}
	    	}
	 	}
	    	
	    });
}