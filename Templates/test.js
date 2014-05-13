function addMessages(){

var formMessage = document.createElement('form');
formMessage.id = 'formMess';

var titleForm = document.createElement('p');
titleForm.appendChild(document.createTextNode('Messages'));

var pathText = document.createElement('p');
pathText.appendChild(document.createTextNode('Path :'));
var path = document.createElement('input');

var periodText = document.createElement('p');
periodText.appendChild(document.createTextNode('Period :'));
var period = document.createElement('input');

var offsetText =document.createElement('p');
offsetText.appendChild(document.createTextNode('Offset :'));
var offset = document.createElement('input');

var wcetText =document.createElement('p');
wcetText.appendChild(document.createTextNode('WCET :'));
var wcet = document.createElement('input');

formMessage.appendChild(titleForm);
formMessage.appendChild(pathText);
formMessage.appendChild(path);
formMessage.appendChild(periodText);
formMessage.appendChild(period);
formMessage.appendChild(offsetText);
formMessage.appendChild(offset);
formMessage.appendChild(wcetText);
formMessage.appendChild(wcet);

document.getElementById('graph-popUp').appendChild(formMessage);
}
