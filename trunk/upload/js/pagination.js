var ajaxpageclass=new Object()
//1) HTML to show while requested page is being fetched:
ajaxpageclass.loadstatustext="<img src='./styles/clipbucketblue/images/loading.gif' /> Loading content..."

//2) Bust cache when fetching pages?
ajaxpageclass.ajaxbustcache=false

ajaxpageclass.connect=function(pageurl, divId){
	var page_request = false
	var bustcacheparameter=""
	if (window.XMLHttpRequest) // if Mozilla, IE7, Safari etc
		page_request = new XMLHttpRequest()
	else if (window.ActiveXObject){ // if IE6 or below
		try {
		page_request = new ActiveXObject("Msxml2.XMLHTTP")
		} 
		catch (e){
			try{
			page_request = new ActiveXObject("Microsoft.XMLHTTP")
			}
			catch (e){}
		}
	}
	else
		return false
	page_request.onreadystatechange=function(){ajaxpageclass.loadpage(page_request, divId)}
	if (this.ajaxbustcache) //if bust caching of external page
		bustcacheparameter=(pageurl.indexOf("?")!=-1)? "&"+new Date().getTime() : "?"+new Date().getTime()
	page_request.open('GET', pageurl+bustcacheparameter, true)
	page_request.send(null)
}

ajaxpageclass.loadpage=function(page_request, divId){
	document.getElementById(divId).innerHTML=this.loadstatustext //Display "fetching page message"
	if (page_request.readyState == 4 && (page_request.status==200 || window.location.href.indexOf("http")==-1)){
		document.getElementById(divId).innerHTML=page_request.responseText
	}
}

ajaxpageclass.bindpages=function(pageinfo, divId, paginateIds){ //Main Constructor function
	this.pageinfo=pageinfo //store object containing URLs of pages to fetch, selected page number etc
	this.divId=divId
	this.paginateIds=paginateIds //array of ids corresponding to the pagination DIVs defined for this pageinstance
	var initialpage=(pageinfo.selectedpage<pageinfo.page.length)? pageinfo.selectedpage : 0 //set initial page shown
	this.buildpagination(initialpage)
	this.selectpage(initialpage)
}

ajaxpageclass.bindpages.prototype={

	buildpagination:function(selectedpage){
		if (this.pageinfo.page.length==1)
			var paginateHTML="Page 1 of 1" //Pagination HTML to show when there's only 1 page (no pagination needed)
		else{ //construct pagimation interface
			var paginateHTML='<div class="pagination"><ul>\n'
			paginateHTML+='<li><a href="#previous" rel="'+(selectedpage-1)+'">«</a></li>\n'
			for (var i=0; i<this.pageinfo.page.length; i++){
				paginateHTML+='<li><a href="#page'+(i+1)+'" rel="'+i+'">'+(i+1)+'</a></li>\n'
			}
			paginateHTML+='<li><a href="#next" rel="'+(selectedpage+1)+'">next »</a></li>\n'
			paginateHTML+='</ul></div>'
		}// end construction
		for (var i=0; i<this.paginateIds.length; i++){ //loop through # of pagination DIVs specified
			var paginatediv=document.getElementById(this.paginateIds[i]) //reference pagination DIV
			paginatediv._currentpage=selectedpage //remember current page selected (which will become previous page selected after each page turn)
			paginatediv.innerHTML=paginateHTML
			var pageinstance=this
			paginatediv.onclick=function(e){
				var targetobj=window.event? window.event.srcElement : e.target
				if (targetobj.tagName=="A" && targetobj.getAttribute("rel")!=""){
					if (!/disabled/i.test(targetobj.className)){ //if this pagination link isn't disabled (CSS classname "disabled")
						pageinstance.selectpage(parseInt(targetobj.getAttribute("rel")))
					}
				}
				return false
			}
		}
	},

	selectpage:function(selectedpage){
		//replace URL's root domain with dynamic root domain (with or without "www"), for ajax security sake:
		var modifiedurl=this.pageinfo.page[selectedpage].replace(/^http:\/\/[^\/]+\//i, "http://"+window.location.hostname+"/")
		ajaxpageclass.connect(modifiedurl, this.divId) //fetch requested page and display it inside DIV
		if (this.pageinfo.page.length==1) //if this book only containe 1 page
			return
		var prevlinkoffset=1
		for (var i=0; i<this.paginateIds.length; i++){ //loop through # of pagination DIVs specified
			var paginatediv=document.getElementById(this.paginateIds[i])
			var paginatelinks=paginatediv.getElementsByTagName("a")
			paginatelinks[0].className=(selectedpage==0)? "prevnext disabled" : "prevnext" //if current page is 1st page, disable "prev" button
			paginatelinks[0].setAttribute("rel", selectedpage-1) //update rel attr of "prev" button with page # to go to when clicked on
			paginatelinks[paginatelinks.length-1].className=(selectedpage==this.pageinfo.page.length-1)? "prevnext disabled" : "prevnext"
			paginatelinks[paginatelinks.length-1].setAttribute("rel", selectedpage+1)
			paginatelinks[paginatediv._currentpage+prevlinkoffset].className="" //deselect last clicked on pagination link (previous)
			paginatelinks[selectedpage+prevlinkoffset].className="currentpage" //select current pagination link
			paginatediv._currentpage=selectedpage //Update last clicked on link
		}
	},

	refresh:function(pageinfo){
	this.pageinfo=pageinfo
	var initialpage=(pageinfo.selectedpage<pageinfo.page.length)? pageinfo.selectedpage : 0
	this.buildpagination(initialpage)
	this.selectpage(initialpage)
	}
}