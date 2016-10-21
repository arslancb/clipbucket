function buildPhotoUrl(xhr,display) {
	var collectionId = xhr.collection_id,
	photo_key = xhr.photo_key,
	photo_title = xhr.photo['0'].photo_title,
	nonSeoUrl = "view_item.php?item="+photo_key+"&type=photos&collection="+collectionId,
	theSeoUrl = "item/photos/"+collectionId+"/"+photo_key+"/"+photo_title,
	mainUrl = nonSeoUrl;

	if (seoUrls == 'yes' && display == 'aho') {
		mainUrl = theSeoUrl;
	} else if (display == 'aho') {
		mainUrl = nonSeoUrl;
	} else {
		mainUrl += "&moto=ajax";
	}
	
	return mainUrl;
}

function galleryDimension(){
	var galleryHeight = $("#gallery").height();
	$('#gallery .inner-holder').css("height",galleryHeight+"px");
	if($('#gallery img').width() < $('#gallery img').height()){
		$('#gallery img').addClass('tall_img');
	}
	else{
		$('#gallery img').removeClass('tall_img');
	}
}

function overlayDimension(){
	var screenHeight = $(window).height();
	$('#myNav .overlay-content').css("height",screenHeight+"px");
}

$("#gallery img").load(function() {
	galleryDimension();
});


// event which brings previous image from the collection 	
$(document).on("click", "#mainNextBtn,#mainPrevBtn", function () {
//	var curr_photo = d;
	var user_admin = user;
	var check = $(this).data('check');
	if(check == "prev") {
		var pre_next = "prev";
	}
	if(check == "next"){
		var pre_next = "next";
	}
	data = {
		'mode': mode,
		'photo_pre' : curr_photo,
		'user': user_admin,
		'item': pre_next,
	}

	$.ajax({
		url: baseurl+'/ajax.php'+'?moto=ajax', 
		type: "post",
		data: data,
		dataType: 'json',
		beforeSend: function() {
			$('.photo-view').append(r);
			$('#gallery').fadeTo( "slow", 0.33 );
			$('.overlay-content').append(b);
			$('#gallery-exd').fadeTo( "slow", 0.33 );
			$('.view-photo').prop("disabled",true);
		},
		success:function(xhr) {	
			var getUrl = baseurl + "/" + buildPhotoUrl(xhr);
			console.log(getUrl);
			$.get( getUrl, function( data ) {
			  	$('#main').html(data);
			  	$('.view-photo').prop("disabled",false);
				$("#gallery").fadeTo("normal",0.99);
				$('#gallery-exd').fadeTo( "normal", 0.99 );
				//d = xhr.photo[0];
				$('#gallery-exd').attr("src",key_globel);	
				$("#gallery").attr("src",key_globel);
				window.history.pushState("", "", baseurl+"/"+buildPhotoUrl(xhr,"aho"));
				$('.dropdown-toggle').dropdown();
				$('.pic-glyp').remove();
				galleryDimension();
			});
		}

	})

});


// Event which enlarge the image size in model. 

$(document).on("click", ".en-large", function () {
	var curr_photo = d;
	var check = $(this).data('check');
	if(check == "prev") {
		var pre_next = "prev";	
	}
	if(check == "next"){
		var pre_next = "next";
	}	
		data = {
		'mode': mode,
		'photo_pre' : curr_photo,
		'item': pre_next,
	}

	$.ajax({
		url: baseurl+'/ajax.php',
		type: "post",
		data: data,
		dataType: 'json',
		beforeSend: function() {
			$('.overlay-content').append(b);
			$('.en-large').prop("disabled",true);
		},
		success:function(xhr) {	
			$('.en-large').prop("disabled",false);
			$("#gallery").fadeTo("normal",0.99);
			d = xhr.photo[0];
			key_globel = xhr.src_string;
			console.log(key_globel);
			collection_id = xhr.collection_id;
			$('#gallery-exd').attr("src",key_globel);
			$("#btn-close").attr("data-check",key);
			$('.pic-glyp').remove();
		}
	});
});

$(document).ready(function(){
	galleryDimension();
});

$(window).resize(function(){
	 galleryDimension();
});

$(document).on("click", "#enlarge", function () {
	$('body').addClass('image-zoom');
	$('#myNav').attr("style","width:100%;left:0;");
	$('#myNav').addClass('maximized');
	$('#gallery-exd').attr("src",srcFirst);
	d=curr_photo;
	overlayDimension();
});

// var imgwd = $("#gallery-exd").width();
// 	alert(imgwd);
// 	// if($("#gallery-exd").width() < $("#gallery-exd").height()){
// 	// 	alert("tall");
// 	// }
function sample(){
	var imgwd = $("#gallery-exd").width();
	var imght = $("#gallery-exd").height();
	if(imgwd < imght){
		$("#gallery-exd").addClass('tallest');
	}
	else{
		$("#gallery-exd").removeClass('tallest');
	}
}
$("#gallery-exd").load(function() {
	t = setTimeout("sample()",100);
});

// On closing modal update image source..
$(document).on("click", "#btn-close", function () {
	$('body').removeClass('image-zoom');
	 $('#myNav').attr("style","left:-100%;");
	 $('#myNav').removeClass('maximized');

});

$( document ).on( 'keydown', function ( e ) {
    if ( e.keyCode === 27 ) { // ESC
       	var myNav = document.getElementById("myNav");
       	if (myNav != null){
       	 	myNav.style.left = "-100%";
       	}
    }
});