function headerFooter(){
	var headerheight = "";
	var footerheight = "";
	var search_hight = "";
	var cont_height = $("#container").height();

	headerheight = $("#header").outerHeight();
	footerheight = $("#footer").outerHeight();
	
	cont_height = cont_height - headerheight - footerheight;
	
	$("#container").css('padding-top',headerheight+'px');
	$("#container").css('padding-bottom',footerheight+'px');

	$(".account-container").css('height',cont_height+'px');
	//console.log($(window).height());
	//console.log(cont_height);
}
var flag = 0;
function responsiveFixes(){
	var WinWidth = $(window).width();
	console.log(WinWidth);
	var SearchHtml = $("#header .menu-holder .user_menu").html();
	var navseach = $('#header .navbar-header');
	var menuLinks = $("#header .menu-holder");

	if (WinWidth <992)
	{
		var length1 = navseach.find('.user_menu').length;
		if(length1==0)
		{
			$(navseach).append('<div class="col btn-holder user_menu text-right logged-out">'+SearchHtml+"</div>");
		}
		$('.menu-holder').find('.user_menu').remove();
	}
	else
	{
		var searchBtns = navseach.find('.user_menu').html();
		var length2 = menuLinks.find('.user_menu').length;

		if(length2==0)
		{
			menuLinks.append('<div class="col btn-holder user_menu text-right logged-out">'+searchBtns+"</div>");
		}
		navseach.find('.user_menu').remove();

	}
	if( WinWidth <1280 )
	{
		$(".btn-newacc").html("Signup");
	}
	else
	{
		$(".btn-newacc").html("Create new account");
	}

	if(userid)
	{
		$(".user_menu").addClass('logged-in');
		$(".user_menu").removeClass('logged-out');
	}
	else{
		$(".user_menu").removeClass('logged-in');
		$(".user_menu").addClass('logged-out');
	}

	if( WinWidth <768 )
	{
		var length3 = $('.menu-holder').find('.newuser-links').length;
		if(length3==0)
		{
			var rightLinkHtml = $('.navbar-right').html();
			$('.menu-holder').prepend("<ul class='newuser-links'>"+rightLinkHtml+"</ul>");
			$('.navbar-right').remove();
		}
	}
	else{
		var length4 = $('.user_menu').find('.right-menu').length;
		if(length4==0)
		{	
			var newLinkHtml = $('.newuser-links').html();
			$('.user_menu').append("<ul class='nav navbar-nav navbar-right right-menu'>"+newLinkHtml+"</ul>");
			$('.newuser-links').remove();
		}
	}
}
function preLoadingBlock(){
	//two videos in a row
	var ftthumbWidth = $('.featured-videos .thumb-video').width();
	var	ftthumbHeight = ftthumbWidth * (10/16);
	$(".featured-videos .thumb-video").css('height', ftthumbHeight+'px');
	//three videos in a row
	var thumbWidth = $('.videos .thumb-video').width();
	var	thumbHeight = thumbWidth * (10/16);
	$(".videos .thumb-video").css('height', thumbHeight+'px');
}
function loginHeight(){
	var loginHeight = $("#login_form").outerHeight();
	loginHeight = loginHeight - 40;
	$(".account-holder .side-box").css('height', loginHeight+'px');
}
$(document).ready(function(){
	//footer at bototm
	headerFooter();
	if(userid)
	{
		$(".user_menu").addClass('logged-in');
		$(".user_menu").removeClass('logged-out');
	}
	else{
		$(".user_menu").removeClass('logged-in');
		$(".user_menu").addClass('logged-out');
	}
	responsiveFixes();

	$(".navbar-sm-login-links a").click(function(){
		$("body").removeClass('sideactive');
	});

	var havechild = $('.adbox-holder').children().length;

	if (havechild == 0){
		$('.adbox-holder').hide();
	}

	$(".btn-search-toggle").click(function() {
		$(".navbar-header").toggleClass('show-search');
	});
	loginHeight();
});


function homePageVideos(qlist_items) {
	$('#container').on("click","#recent_load_more, #featured_load_more",function(){
		var loadLink = baseurl + '/ajax/home.php',
		main_object = $(this),
		sendType = 'post',
		dataType = 'html',
		loadType = $(main_object).attr('loadtype'),
		loadMode = $(main_object).attr('loadmode'),
		loadLimit = $(main_object).attr('loadlimit'),
		loadHit = $(main_object).attr('loadhit'),
		newloadHit = parseInt(loadHit) + 1;
		moreRecent = true;
		moreFeatured = true;

		featuredFound = '';
		if (loadHit == 1) {
			recentFound = 6;
			featuredFound = 2;
		} else {
			featuredSect = $('#container').find('#total_videos_featured').text();
			recentSect = $('#container').find('#total_videos_recent').text();

			totalFeaturedVids = featuredSect;
			totalRecentVids = recentSect;

			featuredShown = loadHit * 2 - 2;
			recentShown = loadHit * 6 - 6;

			gotMoreFeatured = parseInt(totalFeaturedVids) - parseInt(featuredShown);
			gotMoreRecent = parseInt(totalRecentVids) - parseInt(recentShown);
			/*console.log("LOAD HIT " + loadHit);
			console.log("SHOWN " + shownVideos);
			console.log("To fetch" + vidsToFetch);*/
			console.log(gotMoreFeatured);
			if (gotMoreFeatured > 2) {
				featuredFound = 2;
			} else {
				moreFeatured = false;
				featuredFound = gotMoreFeatured;
			}

			if (gotMoreRecent > 6) {
				recentFound = 6;
			} else {
				moreRecent = false;
				recentFound = gotMoreRecent;
			}
		}

		/*$.ajax({
			url: loadLink,
			type: sendType,
			dataType: dataType,
			data: {
				"load_type":'count',
				"load_mode":loadMode,
				"load_limit":loadLimit,
				"load_hit": parseInt(loadHit) + 1
			},

			success: function(data) {
				var jsonData = $.parseJSON(data);
				num = jsonData.more_vids;
				if (loadMode == 'recent') {
					if (num > 6) {
						recentFound = 6;
					} else {
						recentFound = 53;
					}
				} else {
					if (num > 2) {
						featuredFound = 2;
					} else {
						featuredFound = 45;
					}
				}

				if (num == 'none') {
					if (loadMode == 'recent') {
						moreRecent = false;
					} else {
						moreFeatured = false;
					}
				}
			}
		});*/

		$.ajax({
			url: loadLink,
			type: sendType,
			dataType: dataType,
			data: {
				"load_type":loadType,
				"load_mode":loadMode,
				"load_limit":loadLimit,
				"load_hit":loadHit
			},

			beforeSend: function() {
				// setting a timeout
				$(main_object).attr('disabled','disabled');
				$(main_object).text("Loading..");
				if (loadType != 'count') {
					if (loadMode == 'featured') {
						for (var i = 0; i < featuredFound; i++) {
							$(document).find('#featured_pre').append('<div class="item-video col-lg-6 col-md-6 col-sm-6 col-xs-12"><div style="height:200px" class="thumb-video background-masker clearfix"></div></div>');
						}
					} else {
						for (var i = 0; i < recentFound; i++) {
							$(document).find('#recent_pre').append('<div class="item-video col-lg-4 col-md-4 col-sm-4 col-xs-6"><div class="thumb-video background-masker clearfix"></div><div class="loadingInfo video-info relative clearfix"><div class="background-masker heading clearfix"></div><div class="background-masker paragraph clearfix"></div><div class="background-masker clearfix views-date"></div></div></div>');
						}
						preLoadingBlock();
					}
				}
			},

			success: function(data) {
				$(main_object).removeAttr('disabled');
				$(main_object).text("Load More");
				if (data.length < 10) {
					$(main_object).remove();
					if (loadHit == 1) {
						if (loadMode = 'featured') {
							$('#featured_load_more').hide();
							$('#featured_pre').hide();
							$("#featured_vid_sec").html('<div class="break2"></div><span class="well well-info btn-block">No featured videos found</span>');
							return false;
						} else if (loadMode == 'recent') {
							$('#recent_load_more').remove();
							$('#recent_pre').remove();
							$("#recent_vids_sec").html('<div class="break2"></div><span class="well well-info btn-block">No recent videos found</span>');
							return false;
						}
					}
					return true;
				}
				if (loadType == 'video') {
					if (loadMode == 'recent') {
						$('#recent_load_more').remove();
						$('#recent_pre').html('');
						$(data).appendTo('#recent_vids_sec').fadeIn('slow');
						recentSect = $('#container').find('#total_videos_recent').text();
						if (loadHit == 1 && recentSect <= 2) {
							moreRecent = false;
						}
						if (moreRecent == true) {
							$(document).find('#recent-loadmore').append('<div class="clearfix text-center"><button id="recent_load_more" class="btn btn-loadmore" loadtype="video" loadmode="recent" loadlimit="'+loadLimit+'" loadhit="'+newloadHit+'">Load More</button></div>');
						}
					} else {
						$('#featured_load_more').remove();
						$('#featured_pre').html('');
						$(data).appendTo('#featured_vid_sec').fadeIn('slow');
						featuredSect = $('#container').find('#total_videos_featured').text();
						if (loadHit == 1 && featuredSect <= 2) {
							moreFeatured = false;
						}

						if (moreFeatured == true) {
							$(document).find('#featured-loadmore').append('<div class="clearfix text-center"><button id="featured_load_more" class="btn btn-loadmore" loadtype="video" loadmode="featured" loadlimit="'+loadLimit+'" loadhit="'+newloadHit+'">Load More</button></div>');
						}
					}
				} 
				$('#container').find('#total_videos_recent').hide();
				$('#container').find('#total_videos_featured').hide();
			}
		});
	});

	// trigger clicks on doc load to get
	// initial videos

	$(document).ready(function(){

		$('#featured_load_more').trigger("click");
		$('#featured_load_more').hide();
		$('#recent_load_more').trigger("click");
		$('#recent_load_more').hide();

		// var windowWith = $(window).width();
		// alert(windowWith);

	});
}

//on resize functions
$(window).resize(function(){
 	headerFooter();
 	preLoadingBlock();
 	responsiveFixes();
 	loginHeight();
});