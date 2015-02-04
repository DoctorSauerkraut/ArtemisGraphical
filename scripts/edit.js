function editValue(element) {
	if(!isNaN(parseFloat(element.innerHTML)) && isFinite(element.innerHTML)) {
		element.innerHTML = "<td><input type='text'  value="+(element.innerHTML)+"</td>";
	}
}