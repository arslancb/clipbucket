function virtualpaginate(className, chunksize, elementType){
var elementType=(typeof elementType=="undefined")? "div" : elementType //The type of element used to divide up content into pieces. Defaults to "div"
this.pieces=virtualpaginate.collectElementbyClass(className, elementType) //get total number of divs matching class name
//Set this.chunksize: 1 if "chunksize" param is undefined, "chunksize" if it's less than total pieces available, or simply total pieces avail (show all)
this.chunksize=(typeof chunksize=="undefined")? 1 : (chunksize>0 && chunksize <this.pieces.length)? chunksize : this.pieces.length
this.pagecount=Math.ceil(this.pieces.length/this.chunksize) //calculate number of "pages" needed to show the divs
this.showpage(-1) //show no pages (aka hide all)
this.currentpage=0 //Having hidden all pages, set currently visible page to 1st page
this.showpage(this.currentpage) //Show first page
}

// -------------------------------------------------------------------
// PRIVATE: collectElementbyClass(classname)- Returns an array containing DIVs with the specified classname
// -------------------------------------------------------------------

virtualpaginate.collectElementbyClass=function(classname, element){ //Returns an array containing DIVs with specified classname
var classnameRE=new RegExp("(^|\\s+)"+classname+"($|\\s+)", "i") //regular expression to screen for classname within element
var pieces=[]
var alltags=document.getElementsByTagName(element)
for (var i=0; i<alltags.length; i++){
if (typeof alltags[i].className=="string" && alltags[i].className.search(classnameRE)!=-1)
pieces[pieces.length]=alltags[i]
}
return pieces
}

// -------------------------------------------------------------------
// PUBLIC: showpage(pagenumber)- Shows a page based on parameter passed (0=page1, 1=page2 etc)
// -------------------------------------------------------------------

virtualpaginate.prototype.showpage=function(pagenumber){
var totalitems=this.pieces.length //total number of broken up divs
var showstartindex=pagenumber*this.chunksize //array index of div to start showing per pagenumber setting
var showendindex=showstartindex+this.chunksize-1 //array index of div to stop showing after per pagenumber setting
for (var i=0; i<totalitems; i++){
if (i>=showstartindex && i<=showendindex)
this.pieces[i].style.display="block"
else
this.pieces[i].style.display="none"
}
this.currentpage=parseInt(pagenumber)
if (this.cpspan) //if <span class="paginateinfo> element is present, update it with the most current info (ie: Page 3/4)
this.cpspan.innerHTML='Page '+(this.currentpage+1)+'/'+this.pagecount
}

// -------------------------------------------------------------------
// PRIVATE: paginate_build_() methods- Various methods to create pagination interfaces
// paginate_build_selectmenu(paginatedropdown)- Accepts an empty SELECT element and turns it into pagination menu
// paginate_build_regularlinks(paginatelinks)- Accepts a collection of links and screens out/ creates pagination out of ones with specific "rel" attr
// paginate_build_flatview(flatviewcontainer)- Accepts <span class="flatview"> element and replaces it with sequential pagination links
// paginate_build_cpinfo(cpspan)- Accepts <span class="paginateinfo"> element and displays current page info (ie: Page 1/4)
// -------------------------------------------------------------------

virtualpaginate.prototype.paginate_build_selectmenu=function(paginatedropdown, anchortext){
var instanceOfBox=this
var anchortext=anchortext || new Array()
this.selectmenupresent=1
for (var i=0; i<this.pagecount; i++){
if (typeof anchortext[i]!="undefined") //if custom anchor text for this link exists, use anchor text as each OPTION's text
paginatedropdown.options[i]=new Option(anchortext[i], i)
else //else, use auto incremented, sequential numbers
paginatedropdown.options[i]=new Option("Page "+(i+1)+" of "+this.pagecount, i)
}
paginatedropdown.selectedIndex=this.currentpage
paginatedropdown.onchange=function(){
instanceOfBox.showpage(this.selectedIndex)
}
}

virtualpaginate.prototype.paginate_build_regularlinks=function(paginatelinks){
var instanceOfBox=this
for (var i=0; i<paginatelinks.length; i++){
var currentpagerel=paginatelinks[i].getAttribute("rel")
if (currentpagerel=="previous" || currentpagerel=="next" || currentpagerel=="first" || currentpagerel=="last") //screen for these "rel" values
paginatelinks[i].onclick=function(){
instanceOfBox.navigate(this.getAttribute("rel"))
return false
}
}
}

virtualpaginate.prototype.paginate_build_flatview=function(flatviewcontainer, anchortext){
var instanceOfBox=this
var flatviewhtml=""
var anchortext=anchortext || new Array()
for (var i=0; i<this.pagecount; i++){
if (typeof anchortext[i]!="undefined") //if custom anchor text for this link exists
flatviewhtml+='<a href="#flatview" rel="'+i+'">'+anchortext[i]+'</a> ' //build pagination link using custom anchor text
else
flatviewhtml+='<a href="#flatview" rel="'+i+'">'+(i+1)+'</a> ' //build  pagination link using auto incremented sequential number instead
}
flatviewcontainer.innerHTML=flatviewhtml
this.flatviewlinks=flatviewcontainer.getElementsByTagName("a")
for (var i=0; i<this.flatviewlinks.length; i++){
this.flatviewlinks[i].onclick=function(){
instanceOfBox.flatviewlinks[instanceOfBox.currentpage].className="" //"Unhighlight" last flatview link clicked on...
this.className="selected" //while "highlighting" currently clicked on flatview link (setting its class name to "selected"
instanceOfBox.showpage(this.getAttribute("rel"))
return false
}
}
this.flatviewlinks[this.currentpage].className="selected" //"Highlight" current page
this.flatviewpresent=true //indicate flat view links are present
}

virtualpaginate.prototype.paginate_build_cpinfo=function(cpspan){
this.cpspan=cpspan
cpspan.innerHTML='Page '+(this.currentpage+1)+'/'+this.pagecount
}


// -------------------------------------------------------------------
// PRIVATE: buildpagination()- Create pagination interface by calling one or more of the paginate_build_() functions
// -------------------------------------------------------------------

virtualpaginate.prototype.buildpagination=function(divid, optnavtext){
var instanceOfBox=this
var paginatediv=document.getElementById(divid)
if (this.chunksize==this.pieces.length){ //if user has set to display all pieces at once, no point in creating pagination div
paginatediv.style.display="none"
return
}
var paginationcode=paginatediv.innerHTML //Get user defined, "unprocessed" HTML within paginate div
if (paginatediv.getElementsByTagName("select").length>0) //if there's a select menu in div
this.paginate_build_selectmenu(paginatediv.getElementsByTagName("select")[0], optnavtext)
if (paginatediv.getElementsByTagName("a").length>0) //if there are links defined in div
this.paginate_build_regularlinks(paginatediv.getElementsByTagName("a"))
var allspans=paginatediv.getElementsByTagName("span") //Look for span tags within passed div
for (var i=0; i<allspans.length; i++){
if (allspans[i].className=="flatview")
this.paginate_build_flatview(allspans[i], optnavtext)
else if (allspans[i].className=="paginateinfo")
this.paginate_build_cpinfo(allspans[i])
}
this.paginatediv=paginatediv
}

// -------------------------------------------------------------------
// PRIVATE: navigate(keyword)- Calls this.showpage() with the currentpage property preset based on entered keyword
// -------------------------------------------------------------------

virtualpaginate.prototype.navigate=function(keyword){
if (this.flatviewpresent)
this.flatviewlinks[this.currentpage].className="" //"Unhighlight" previous page (before this.currentpage increments)
if (keyword=="previous")
this.currentpage=(this.currentpage>0)? this.currentpage-1 : (this.currentpage==0)? this.pagecount-1 : 0
else if (keyword=="next")
this.currentpage=(this.currentpage<this.pagecount-1)? this.currentpage+1 : 0
else if (keyword=="first")
this.currentpage=0
else if (keyword=="last")
this.currentpage=this.pieces.length-1
this.showpage(this.currentpage)
if (this.selectmenupresent)
this.paginatediv.getElementsByTagName("select")[0].selectedIndex=this.currentpage
if (this.flatviewpresent)
this.flatviewlinks[this.currentpage].className="selected" //"Highlight" current page
}