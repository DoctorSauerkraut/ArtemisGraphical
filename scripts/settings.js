/* Criticality level management */
function addCritLevel() {
	var critTime = document.getElementById("critTimeText").value;
	var critLvl = document.getElementById("critLvlText").value;
	
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:'action=addCritLevel'+"&critTime="+critTime+"&critLvl="+critLvl,
		success:function(data){
			loadContent('settings');
		}
	});
}

function addCriticalityState() {
	var critName = document.getElementById("nameNCL").value;
	var critCode = document.getElementById("codeNCL").value;
	
	$.ajax({
		url:"./Controller.php",
		type:"post",
		data:'action=addCritState'+"&critName="+critName+"&critCode="+critCode,
		success:function(data){
			closePopup();
			loadContent('messages');
		}
	});
}