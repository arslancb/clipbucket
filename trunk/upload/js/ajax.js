    var AjaxObject = false;
	if(num){
	}else{
	var num = 1;
	}
	
	if(window.XMLHttpRequest){
		AjaxObject = new XMLHttpRequest();
	}else if(window.ActiveXObject){
		AjaxObject = new ActiveXObject('Microsoft.XMLHTTP');
	}
		
		function GetPageResults(URL,DIV){
			var ResultConatiner = document.getElementById(DIV);
			AjaxObject.open("GET",URL);
			AjaxObject.onreadystatechange = function(){
				if(AjaxObject.readyState == 4 && AjaxObject.status == 200){
					ResultConatiner.innerHTML = AjaxObject.responseText;
				}else{
					ResultConatiner.innerHTML = "<font size=\"3\"><b>Loading...</b></font> <img src=\""+baseurl+"/images/icons/progIndicator.gif\" border=\"0\">";
				}
			}

			AjaxObject.send(null);
		}