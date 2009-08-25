//include('popup_image.js');

function ShowHint(text){
	return fixedtooltip('<table bgcolor="#f1f1f1" width="400px" class="des_table"><tr><td>'+text+'</td></tr></table>', this, event, '0px');
}