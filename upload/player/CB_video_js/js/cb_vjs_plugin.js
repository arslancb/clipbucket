//Starting CB logo custom elements class
var cbvjslogo = function(player,options){
	var cbvjslogo = this;
	
	cbvjslogo.path = options.branding_logo;
	cbvjslogo.link = options.product_link;
	cbvjslogo.show = options.show_logo;
	cbvjslogo.player = player;
	
	cbvjslogo.init();
}

cbvjslogo.prototype.init = function(){
	var cbvjslogo = this;
	var CbLogoBrand = document.createElement("div");
	CbLogoBrand.id = "vjs-cb-logo";
	CbLogoBrand.className = "vjs-cblogo-brand";
	CbLogoBrand.className += " vjs-menu-button";
	CbLogoBrand.className += " vjs-control";
	CbLogoBrand.className += " vjs-button";
	CbLogoBrand.innerHTML = '<img style="display:block !important; cursor : pointer;margin:5px 0 0 4px;" src="data:image/png;base64, '+cbvjslogo.path+'" alt="">';

	var FullScreenToggle = cbvjslogo.player.controlBar.getChild('fullscreenToggle').el_;
	cbvjslogo.player.controlBar.el_.insertBefore(CbLogoBrand, FullScreenToggle);

	cbvjslogo.el = CbLogoBrand;
	cbvjslogo.onclick(); 
}

cbvjslogo.prototype.onclick = function(){
	var cbvjslogo = this;
	cbvjslogo.el.addEventListener('click',function(){
		window.open(cbvjslogo.link, '_blank');
	});
}

//Starting Captions Menu Holder Class
var cbvjsheader = function(player,options){
	var cbvjsheader = this;
	
	cbvjsheader.title = options.videotitle;
	cbvjsheader.uploader = options.uploader;
	cbvjsheader.player = player;
	
	cbvjsheader.init();
}

cbvjsheader.prototype.init = function(){
	var cbvjsheader = this;
	var CbVjsHeader = document.createElement("div");
	CbVjsHeader.id = "vjs-cb-header";
	CbVjsHeader.className = "vjs-cb-header-caption";
	CbVjsHeader.innerHTML = "<div class='captionBlock'><div class='vidTitle col'><p>"+cbvjsheader.title+"</p></div><div class='uploaderName col'>by "+cbvjsheader.uploader+"</div></div>";

	var BigPlayButton = cbvjsheader.player.getChild('bigPlayButton').el_;
	cbvjsheader.player.el_.insertBefore(CbVjsHeader, BigPlayButton);
}


function cb_vjs_elements(settings){

	var logo_settings = settings.logo;
	var header_settings = settings.header;

	CbVjsLogo = new cbvjslogo(this,logo_settings);
	CbVjsHeader = new cbvjsheader(this,header_settings);
}

videojs.plugin('cb_vjs_elements', cb_vjs_elements);

    