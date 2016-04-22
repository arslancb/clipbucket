var rowNum = 0;
function addRow(frm) {

rowNum ++;
fruit_text   = $("select[name='type'] :selected").text();
var row = '<br/><br/><p id="rowNum'+rowNum+'">Field Name:<input type="text" name="fieldname[]" size="20" value="'+frm.fieldname.value+'" onkeypress="return blockSpecialChar(event)"">Field Title(Lable):<input type="text" name="fieldtitle[]" size="20" value="'+frm.fieldtitle.value+'"> Type: <select  name="type[]" id="test"><option value="'+frm.type.value+'">'+fruit_text+'</option><option value="Textfield">Textfield</option><option value="password">Password</option><option value="textarea">Textarea</option><option value="checkbox">Checkbox</option><option value="radiobutton">Radio button</option><option value="dropdown">Dropdown</option></select>Default Values:<textarea name="default_value" cols="23" rows="3">'+frm.default_value.value+'</textarea>&nbsp;<br/>DB Field<input id="myTextArea" type="text" readonly="readonly" name="db_field[]" value="'+frm.db_field.value+'">&nbsp;<input type="button" value="Remove" onclick="removeRow('+rowNum+');" class="btn btn-primary btn-xs btn-danger"></p>';

jQuery('#itemRows').append(row);
frm.fieldname.value = '';
frm.fieldtitle.value = '';
frm.type.value = '';
frm.default_value.value = '';
frm.db_field.value = '';
$("#default_video").val('');
//frm.usethis.attr('checked', false);
$("#x").attr('checked', false);
$("#myTextArea").attr("disabled", "disabled"); 
}
function removeRow(rnum) {
jQuery('#rowNum'+rnum).remove();
}


var rowNum2 = 0;
function addRow2(frm2) {

rowNum2 ++;
fruit_text2   = $("select[name='type2'] :selected").text();

var row2 = '<br/><br/><p id="rowNum2'+rowNum2+'">Field Name:<input type="text" name="fieldname_signup[]" size="20" value="'+frm2.fieldname_signup.value+'">Field Title(Lable):<input type="text" name="fieldtitle_signup[]" size="20" value="'+frm2.fieldtitle_signup.value+'">Type: <select  name="type2[]" id="test"><option value="'+frm2.type2.value+'">'+fruit_text2+'</option><option value="textfield">Textfield</option><option value="password">Password</option><option value="textarea">Textarea</option><option value="checkbox">Checkbox</option><option value="radiobutton">Radio button</option><option value="dropdown">Dropdown</option></select>Default Values:<textarea name="default_value_signup" cols="23" rows="3">'+frm2.default_value_signup.value+'</textarea>&nbsp;<br/>&nbsp;<br/>DB Field<input id="myTextArea2" type="text" readonly="readonly" name="db_field2[]" value="'+frm2.db_field_signup.value+'">&nbsp;<input type="button" value="Remove" onclick="removeRow2('+rowNum2+');" class="btn btn-primary btn-xs btn-danger"></p>';
jQuery('#itemRows2').append(row2);
frm2.fieldname_signup.value = '';
frm2.fieldtitle_signup.value = '';
frm2.type2.value = '';
$("#default_signup").val('');
frm2.db_field_signup.value = '';
//frm.usethis.attr('checked', false);
$('#x2').attr('checked', false); // Unchecks it
$('#myTextArea2').attr("disabled", true);
}
function removeRow2(rnum2) {
jQuery('#rowNum2'+rnum2).remove();
}

$(document).ready(function(){ 
$(".fieldname").keydown(function(event) {
    if (event.keyCode == 32) {
        event.preventDefault();
    }
});
});
 function blockSpecialChar(e) {
            var k = e.keyCode;
            return ((k > 64 && k < 91) || (k > 96 && k < 123) || k == 8   || (k >= 48 && k <= 57));
        }
function enableText(checkBool, textID)
  {
    textFldObj = document.getElementById(textID);
    //Disable the text field
    textFldObj.disabled = !checkBool;
    //Clear value in the text field
    if (!checkBool) { textFldObj.value = ''; }
  }

function enableText2(checkBool, textID)
  {
    textFldObj = document.getElementById(textID);
    //Disable the text field
    textFldObj.disabled = !checkBool;
    //Clear value in the text field
    if (!checkBool) { textFldObj.value = ''; }
  }
