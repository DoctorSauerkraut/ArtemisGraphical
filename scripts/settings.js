/* Criticality switch management */


function addCritSwitch() {
	var critTime = document.getElementById("critTimeText").value;
	var critLvl = document.getElementById("critLvlText").value;
	
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:'action=addCritSwitch'+"&critTime="+critTime+"&critLvl="+critLvl,
		success:function(data){
			loadContent('mixedc');
		}
	});
}

function deleteCritSwitch(time) {
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:'action=delCritSwitch'+"&time="+time,
		success:function(data){
			closePopup();
			loadContent('mixedc');
		}
	});
}

/* Criticality levels management */
function addCritState() {
	var critName = document.getElementById("nameNCL").value;
	var critCode = document.getElementById("codeNCL").value;
	//alert(critName+","+critCode);
	
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:'action=addCritState'+"&critName="+critName+"&critCode="+critCode,
		success:function(data){
			closePopup();
			loadContent('mixedc');
		}
	});
}

function deleteCritLevel(time) {}