/************************************************************************ 
Author: Eric Simmons
Contact: info AT jswitch DOT com
Website: http://www.jswitch.com
Script featured and available at Dynamic Drive: http://www.dynamicdrive.com
Version: 2.1 05/2006       
Browser Target: Mozilla 6+/FireFox Netscape 6+, IE 5.0+
Type: XP Style Menus ver 2.1

DISCLAIMER:
THIS SOFTWARE IS PROVIDED "AS IS" AND WITHOUT
ANY EXPRESS OR IMPLIED WARRANTIES, JSWITCH.COM
IS NOT RESPONSIBLE FOR ANY ADVERSE AFFECTS TO
YOUR COMPUTER OR SYSTEMS RUNNING THIS SCRIPT.

LICENSE:
YOU ARE GRANTED THE RIGHT TO USE THE SCRIPT
PERSONALLY OR COMMERCIALLY. THE AUTHOR, WEBSITE LINKS 
AND LICENSE INFORMATION IN THE HEADER OF THIS SCRIPT
MUST NOT BE MODIFIED OR REMOVED. IF PORTIONS OF THE 
CODE ARE TRANSFERED TO ANOTHER SCRIT THE AUTHORS NAME
AND CONTACT INFORMATION MUST BE STATED IN THE NEW SCRIPT
BY THE PORTIONS CODE THAT HAVE BEEN TRANSFERED.


v2.2
-fixed cookie bug

v2.1
-Mouse events states for title bar
-object index bug fixes
-redesigned to look like XP :)

v 2.0
-W3C HTML 4.01 Transitional compliant
-Support for initially open menu
-New fading capability
-Cookie menu persistence
-Redesigned code


Notes:

If you want a menu to be open initially set the style display to
"inline" in the subMenu class div like this...
<div class="subMenu" style="display:inline;">


The fading effect can be disabled by setting the 
doFading var to false. I have only tested this feature on 
ie 6, Netscape 1.7, firefox 1 & 1.5

If the user has cookies enabled the script will bake a cookie ;) on
the browser to track the state of the menus. This is good news for those
of you who don't use frames, you can now have persistent menu states

Make sure you put the java script include tag after all the menus otherwise
it will not be able find and initialize them. If you have questions, or comments
about the XpStyle Menu v2 send me an email.

And lastly a plug for myself, If you need some professional help with technical
web development please drop me an email, I do contracts for a competitive price. :)



v 1.0
XP style sliding Menu Bar

***********************************************************************/


var menuObjArray = new Array();
menuObjArray[0] = new Array();
menuObjArray[1] = new Array();
menuObjArray[2] = new Array();
menuObjArray[3] = new Array();
menuObjArray[4] = new Array();
menuObjArray[5] = new Array();
menuObjArray[6] = new Array();
menuObjArray[7] = new Array();


var timerSlide = null;
var numMenuItem = 0;
var slideDelay = 5;
var divHeight = 26; 
var moveSlidePix = 7;
var isLocked = null;
var doFading = true;


InitAll();

function InitAll()
{
	var divs = document.getElementsByTagName("DIV");
	menuStateAry = GetUserCookie("xpMenuCookv2").split(",");

	aryNum = 0;
	for(dn=0; dn < divs.length;dn++)
	{
		if(String(divs.item(dn).className).substring(0,7) == "topItem")
		{	
			mainMenuDiv = divs.item(dn).parentNode;
			menuContainerDiv= mainMenuDiv.getElementsByTagName("DIV").item(1);
			itemContainerDiv= menuContainerDiv.getElementsByTagName("DIV").item(0);
			
			
			try //to apply cookies settings
			{
				if(menuStateAry != 0)
					itemContainerDiv.style.height = parseInt(menuStateAry[aryNum]) + "px";
				
				if(!doFading)
				{
					
					if (menuContainerDiv.filters)
						menuContainerDiv.filters.alpha.opacity = 100;
					else
						menuContainerDiv.opacity = 1;
				}
					
				if(menuStateAry != 0 )
				{
					if( parseInt(menuStateAry[aryNum]) == 0)
						itemContainerDiv.style.display = 'none';
					else
						itemContainerDiv.style.display = 'inline';
				}
			}
			catch(e)
			{
				e= null; //cookie may not exist yet
			}

			Init(divs.item(dn));
			aryNum++;

		}
	}	
}

function Init(objDiv)
{
    
    if (isLocked)
        return;

    var mainMenuDiv, subMenuDiv, menuContainerDiv, itemContainerDiv,styleRules;

	
	for(r=0;r < document.styleSheets.length; r++)
	{	
		if( -1 != String(document.styleSheets[r].href).indexOf("slidemenu.css") )	
			break;
	}
	if(!document.styleSheets[r].rules)
		styleRules = document.styleSheets[r].cssRules;
	else
		styleRules = document.styleSheets[r].rules;
		
    numMenuItem = 0;
    mainMenuDiv = objDiv.parentNode;
    subMenuDiv =  mainMenuDiv.getElementsByTagName("DIV").item(0);
    

    menuContainerDiv= mainMenuDiv.getElementsByTagName("DIV").item(1);
    itemContainerDiv= menuContainerDiv.getElementsByTagName("DIV").item(0);
    

    aLen = menuObjArray[0].length;
    for (i=0 ;i < aLen ; i++)
    {
        if (menuObjArray[0][i] == menuContainerDiv)
        {
            break;
        }
    }
    
    if (i == aLen)
    {
        menuObjArray[0][i]  = menuContainerDiv;
        menuObjArray[1][i] = itemContainerDiv;
        menuObjArray[7][i] = subMenuDiv;
        menuObjArray[7][i].onmouseover = ChangeStyle;
        menuObjArray[7][i].onmouseout = ChangeStyle;
        subMenuDiv.onclick = SetSlide;

        
		lastmenuNum = -1;
        for (b=0;b<itemContainerDiv.childNodes.length;b++)
        {
            if (itemContainerDiv.childNodes.item(b).tagName == "DIV")
            { 
                numMenuItem ++;
                itemContainerDiv.childNodes.item(b).onmouseover= ChangeStyle;
                itemContainerDiv.childNodes.item(b).onmouseout= ChangeStyle;
                lastmenuNum = b;
            }
        }  
        
        
		for(r=0;r < styleRules.length; r++)
		{
			tmpStr1 = String(styleRules[r].selectorText);
			tmpStr2 = String("." + itemContainerDiv.childNodes.item(lastmenuNum).className);
			if(tmpStr1 == tmpStr2)
			{
				if(NaN != parseInt(styleRules[r].style.height))
				{
					divHeight = parseInt(styleRules[r].style.height) + 2;
					break;
				}
				
			}
		}
				
        menuObjArray[2][i] = numMenuItem;
        menuObjArray[3][i] = mainMenuDiv;

        if (itemContainerDiv.style.display == "inline")
        {
            menuObjArray[4][i] = numMenuItem * divHeight;
            menuObjArray[0][i].style.height = numMenuItem * divHeight + "px";
            menuObjArray[6][i] = true;
			
            if(doFading)
			{
				if (menuObjArray[0][i].filters)
					menuObjArray[0][i].filters.alpha.opacity = 100;
				else
					menuObjArray[0][i].style.opacity = 1;
			}
            
            
        } else
        {
			menuObjArray[7][i].className = menuObjArray[7][i].className + "Close";
            menuObjArray[4][i] = 0;
            menuObjArray[0][i].style.height = 0 + "px";
            menuObjArray[6][i] = false;
            if(doFading)
			{
				if (menuObjArray[0][i].filters)
					menuObjArray[0][i].filters.alpha.opacity = 0;
				else
					menuObjArray[0][i].style.opacity = .0;
			}
        }

    }//end if

    mainMenuDiv = null;
    subMenuDiv =  null;
    menuContainerDiv= null;
    itemContainerDiv= null;
    
}

function SetSlide()
{   
    if (isLocked)
        return;
    else
        isLocked = this.parentNode;          
    for (i=0 ;i < menuObjArray[0].length; i++)
    {
        if (menuObjArray[3][i] == this.parentNode)
        {
            if (menuObjArray[5][i] == null)
                menuObjArray[5][i] = setInterval("RunSlide(" + i + ")", slideDelay);
            break;
        }
    }

}



function UpdateUserCookie(aryIndex)
{
    date = new Date();
    date.setTime(date.getTime() + (1000 * 60 * 60 * 24 * 30)); 
    document.cookie = "xpMenuCookv2" + "=" + escape(menuObjArray[4].toString()) + "; expires=" + date.toGMTString();  
   
}

function GetUserCookie(crumbName)
{
    colCookie = document.cookie.split("; ");
    
    for (a=0; a < colCookie.length; a++)
    {
        colCrumb = colCookie[a].split("=");                    
        if(colCrumb[0] == crumbName)
            return unescape(colCrumb[1]);
    }

    return "";

}


function RunSlide(objIndex)  
{

    if (menuObjArray[6][objIndex])
    {
		if(doFading)
		{
			if(menuObjArray[0][objIndex].filters)
				menuObjArray[0][objIndex].filters.alpha.opacity -= 100/ ( ( (menuObjArray[2][objIndex] * divHeight) / moveSlidePix) +1);
			else
				menuObjArray[0][objIndex].style.opacity -= .9/(((menuObjArray[2][objIndex] * divHeight) / moveSlidePix)+1);
		}
        menuObjArray[1][objIndex].style.display = 'none';
        menuObjArray[4][objIndex] -=  moveSlidePix;
        if (menuObjArray[4][objIndex] > 0)
            menuObjArray[0][objIndex].style.height = menuObjArray[4][objIndex] + "px";
        else
        {
            if(doFading)
			{
				if(menuObjArray[0][objIndex].filters)
					menuObjArray[0][objIndex].filters.alpha.opacity = 0;
				else
					menuObjArray[0][objIndex].style.opacity = 0;
			}
		
		
			cName = String(menuObjArray[7][objIndex].className);
			//alert(cName);

			if (cName.substring(cName.length - 4, cName.length) == "Item")
			{
				menuObjArray[7][objIndex].className = menuObjArray[7][objIndex].className+"Close";
			}
			
			if (cName.substring(cName.length - 4, cName.length) == "Over")
			{
				menuObjArray[7][objIndex].className = cName.substring(0,cName.length - 4);
				menuObjArray[7][objIndex].className = menuObjArray[7][objIndex].className+"CloseOver";
			}
			
			if (cName.substring(cName.length - 5, cName.length) == "Close")
			{
				menuObjArray[7][objIndex].className = cName.substring(0,cName.length - 5);
				menuObjArray[7][objIndex].className = menuObjArray[7][objIndex].className+"CloseOver";
			}
			
			//cName = String(menuObjArray[7][objIndex].className);
			//alert(cName);
			
			
            menuObjArray[4][objIndex] = 0;
            menuObjArray[0][objIndex].style.height = 0 + "px";
            clearInterval(menuObjArray[5][objIndex]);
            menuObjArray[5][objIndex] = null;
            menuObjArray[6][objIndex] = false;
            isLocked = null;
            UpdateUserCookie(objIndex);
            return 0;
        }
        
        return 0;
        
    }

    if (!menuObjArray[6][objIndex])
    {
		if(doFading)
		{
			if(menuObjArray[0][objIndex].filters)
				menuObjArray[0][objIndex].filters.alpha.opacity += 100/ ( ( (menuObjArray[2][objIndex] * divHeight) / moveSlidePix) +1);
			else
			{
				opcVal = parseFloat(menuObjArray[0][objIndex].style.opacity);
				opcVal += .9/((menuObjArray[2][objIndex] * divHeight) / moveSlidePix);
				menuObjArray[0][objIndex].style.opacity = opcVal;
			}
		}
        menuObjArray[4][objIndex] +=  moveSlidePix;
        if (menuObjArray[4][objIndex] < (menuObjArray[2][objIndex] * divHeight))
            menuObjArray[0][objIndex].style.height = menuObjArray[4][objIndex] + "px";
        else
        {
			
			
			if(doFading)
			{
				if(menuObjArray[0][objIndex].filters)
					menuObjArray[0][objIndex].filters.alpha.opacity = 100;
				else
					menuObjArray[0][objIndex].style.opacity = 1;
			}
			strClassName = String(menuObjArray[7][objIndex].className);
			menuObjArray[4][objIndex] = (menuObjArray[2][objIndex] * divHeight);
			menuObjArray[0][objIndex].style.height = (menuObjArray[2][objIndex] * divHeight)+ "px";			     
            menuObjArray[1][objIndex].style.display = 'inline';
            clearInterval(menuObjArray[5][objIndex]);
            menuObjArray[5][objIndex] = null;
            menuObjArray[6][objIndex] = true;
            
            
            cName = String(menuObjArray[7][objIndex].className);
			//alert(cName);
			if (cName.substring(cName.length - 4, cName.length) == "Over")
			{
				menuObjArray[7][objIndex].className = cName.substring(0,cName.length - 9);
				menuObjArray[7][objIndex].className = menuObjArray[7][objIndex].className+"Over";
			}
			
			if (cName.substring(cName.length - 5, cName.length) == "Close")
			{
				menuObjArray[7][i].className = cName.substring(0,cName.length - 5);
			}
			
			//cName = String(menuObjArray[7][objIndex].className);
			//alert(cName);
			

            isLocked = null;
             UpdateUserCookie(objIndex);
            return 0;
        }       
        return 0;
        
    }


}

function ChangeStyle()
{
    className = String(this.className);
   
    if (className.substring(className.length - 4, className.length) == "Over")
        this.className = className.substring(0,className.length - 4);
    else
        this.className = this.className + "Over";
   //  alert(this.className);
}
