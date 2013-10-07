var p = 'ajax.php';
var img = '<img src="images/loading.gif" />';


function dbconnect()
{
	$('#loading').html(img);
	var formData = $('#installation').serialize();
	$.post(p,formData,function(data)
	{
		if(data.err)
		{
			$('#dbresult').show().html(data.err)
			$('#loading').html('');
		}else
		{
			$('#installation').submit();
		}
	},"json");
}


function dodatabase(step)
{
	var formData = $('#installation').serialize();
	
	formData += '&step='+step;
	$.post(p,formData,function(data)
	{
		
		if(data.msg)
			$('#resultsDiv').before(data.msg);
		if(data.err)
			$('#resultsDiv').before(data.err);
		if(data.status)
			$('#current').html(data.status);
					
		if(data.step=='forward')
		{
			$('#installation').submit();
		}		
		if(data.step && data.step!='forward')
			dodatabase(data.step)
	},"json");
}

function password(length, special) {
  var iteration = 0;
  var password = "";
  var randomNumber;
  if(special == undefined){
      var special = false;
  }
  while(iteration < length){
    randomNumber = (Math.floor((Math.random() * 100)) % 94) + 33;
    if(!special){
      if ((randomNumber >=33) && (randomNumber <=47)) { continue; }
      if ((randomNumber >=58) && (randomNumber <=64)) { continue; }
      if ((randomNumber >=91) && (randomNumber <=96)) { continue; }
      if ((randomNumber >=123) && (randomNumber <=126)) { continue; }
    }
    iteration++;
    password += String.fromCharCode(randomNumber);
  }
  return password;
}

function register(email,website)
{
	$('#loadingReg').html(img);
	$.ajax({
		url  : p,
		type : 'POST',
		data : ({mode:'register',website:website,email:email}),
		dataType: "html",
		success:function(data){$('#installation').submit()}
		
	})
	

}