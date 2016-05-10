
var Ads = function(player,settings){

	var ads = this;
	//console.log(ads);
	ads.ad_id = settings.ad_id;
	ads.ad_code = settings.ad_code;
	ads.autoplay = settings.autoplay;
	ads.player = player;
	ads.init();
}

Ads.prototype.init = function (){
	var ads = this;
	console.log("Fuck : "+navigator.userAgent);
	var startEvent = 'click';
	if (navigator.userAgent.match(/iPhone/i) ||  navigator.userAgent.match(/iPad/i) ||navigator.userAgent.match(/Android/i)) 
	{
	    console.log("iphone/Android");
	    //startEvent = 'tap';
	}

	if (!ads.autoplay){
		ads.player.one(startEvent, ads.bind(ads, ads.initialize));
	}

	ads.events = [
	    google.ima.AdEvent.Type.ALL_ADS_COMPLETED,
	    google.ima.AdEvent.Type.CLICK,
	    google.ima.AdEvent.Type.COMPLETE,
	    google.ima.AdEvent.Type.FIRST_QUARTILE,
	    google.ima.AdEvent.Type.LOADED,
	    google.ima.AdEvent.Type.MIDPOINT,
	    google.ima.AdEvent.Type.PAUSED,
	    google.ima.AdEvent.Type.STARTED,
	    google.ima.AdEvent.Type.THIRD_QUARTILE
  	];

  	ads.options = {
    	id: ads.player.id_,
    	nativeControlsForTouch: false
  	};
  	
  	ads.player.ima(ads.options,this.bind(this, this.adsManagerLoadedCallback));
  	if (ads.autoplay){
		ads.initialize();
	}
}

Ads.prototype.initialize = function(){
	var ads = this;
	ads.player.ima.initializeAdDisplayContainer();
    ads.player.ima.setContent(null, ads.ad_code, true);
    ads.player.ima.requestAds();
    ads.player.play();
}

Ads.prototype.adsManagerLoadedCallback = function() {
	var ads = this;
	ads.player.ima.addEventListener('start',function(){
		ads.update_impressions(ads.ad_id);
	});
	for (var index = 0; index < ads.events.length; index++) {
		ads.player.ima.addEventListener(
	        ads.events[index],
	        ads.bind(ads, ads.onAdEvent));
	}
  	ads.player.ima.start();
}

Ads.prototype.onAdEvent = function(event) {
    console.log('Ad event: ' + event.type);
}

Ads.prototype.update_impressions = function(ad_id){
	update_ad_imp(ad_id);
}

Ads.prototype.bind = function(thisObj, fn) {
  	return function() {
    fn.apply(thisObj, arguments);
};
}

var cb_ultimate_ads = function(settings){
	
    var ultimate_ads =  new Ads(this,settings);
}
videojs.plugin('cb_ultimate_ads',cb_ultimate_ads);
