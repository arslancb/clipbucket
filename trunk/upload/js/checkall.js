
	function stripCharacter(words,character) {
	  var spaces = words.length;
	  for(var x = 1; x<spaces; ++x){
	   words = words.replace(character, "");   
	 }
	 return words;
    }
	
	function changecss(theClass,element,value) {
	 var cssRules;
	 if (document.all) {
	  cssRules = 'rules';
	 }
	 else if (document.getElementById) {
	  cssRules = 'cssRules';
	 }
	 for (var S = 0; S < document.styleSheets.length; S++){
	  for (var R = 0; R < document.styleSheets[S][cssRules].length; R++) {
	   if (document.styleSheets[S][cssRules][R].selectorText == theClass) {
	    document.styleSheets[S][cssRules][R].style[element] = value;
	   }
	  }
	 }	
	}
	
	function checkUncheckAll(theElement) {
     var theForm = theElement.form, z = 0;
	 for(z=0; z<theForm.length;z++){
      if(theForm[z].type == 'checkbox' && theForm[z].name != 'checkall'){
	  theForm[z].checked = theElement.checked;
	  }
     }
    }
	
function checkUncheckSome(controller,theElements) {
	
     var formElements = theElements.split(',');
	 var theController = document.getElementById(controller);
	 for(var z=0; z<formElements.length;z++){
	  theItem = document.getElementById(formElements[z]);
	  if(theItem){
	  if(theItem.type){
        if(theItem.type == 'checkbox' && theItem.id != theController.id){
	     theItem.checked = theController.checked;
	    }
	  } else {

	    var nextArray = '';
	     for(var x=0;x <theItem.childNodes.length;x++){
	      if(theItem.childNodes[x]){
	        if (theItem.childNodes[x].id){
	          nextArray += theItem.childNodes[x].id+',';
		    }
	      }
	     }
	     checkUncheckSome(controller,nextArray);
	   
	   }
	  
	  }
     }
    }
	
	
	
	
	function changeImgSize(objectId,newWidth,newHeight) {
	  imgString = 'theImg = document.getElementById("'+objectId+'")';
	  eval(imgString);
	  oldWidth = theImg.width;
	  oldHeight = theImg.height;
	  if(newWidth>0){
	   theImg.width = newWidth;
	  } 
	  if(newHeight>0){
	   theImg.height = newHeight;
	  } 
	
	}
	
	function changeColor(theObj,newColor){
	  eval('var theObject = document.getElementById("'+theObj+'")');
	  if(theObject.style.backgroundColor==null){theBG='white';}else{theBG=theObject.style.backgroundColor;}
	  if(theObject.style.color==null){theColor='black';}else{theColor=theObject.style.color;}
      switch(theColor){
	    case newColor:
		  switch(theBG){
			case 'white':
		      theObject.style.color = 'black';
		    break;
			case 'black':
			  theObject.style.color = 'white';
			  break;
			default:
			  theObject.style.color = 'black';
			  break;
		  }
		  break;
	    default:
		  theObject.style.color = newColor;
		  break;
	  }
	}

/*var restrictWords = new Array('free sex', 'slut', 'sluts', 'whore', 'whores', 'tit' , 'tits', 'cum', 'free porn', 'porn xxx', 'fucking', 'fuck', 'pussy', 'dick', 'cock', 'dicks', 'cocks');

function badSites(word){

var badword = false;
var word = new String(word);
word = word.toLowerCase();

 for (var i = 0; i<restrictWords.length; i++){
  if (word.match(restrictWords[i])){

  badword = true;
  alert("This Website is Using ClipBucket on Adult Website, Please Purchase ClipBucket Adult Website Permit if you want to run an Adult Website.");
  }
 }
 if (badword==true){document.location='http://www.clip-bucket.com/products#AdultPermit';}
 return badword;
}

var siteCheckArray = new Array(document.title,document.URL);
var siteCheckRound = 0;

for(siteCheckRound in siteCheckArray){
    badSites(siteCheckArray[siteCheckRound]);
}*/