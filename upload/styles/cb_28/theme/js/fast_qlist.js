
	$(document).find('#qlist_main').hide();		
	//alert(fastQitems);
	if (fastQitems == 'yes') {
		//alert("HERE");
		$(document).find('#qlist_main').show();
	}
	var notInList = false;
	function pushToQlist(obj, id) {
		id = parseInt(id);
		$.cookie("btn-q-"+id, "yes", { expires : 10 });
		currentList = $.cookie("fast_qlist");
		cleanList = currentList;
		//console.log(cleanList);
		if (cleanList != null) {
			notInList = true;
			index = cleanList.indexOf(id);
			if (index == '-1') {
				cleaned = cleanList.replace(/\[/g, '');
				cleaned = cleaned.replace(/\]/g, '');	
				newCookie = "[" + cleaned + ',' + id + ']';
			} else {
				return false;
			}
		} else {
			//console.log("Really");
			notInList = true;
			newCookie = "[" + id + "]";
		}

		$.cookie("fast_qlist", newCookie, { expires : 10 });
		var vtitle = $(obj).attr("vtitle"),
		vtitle = vtitle.split(0,10);
		thevid = $(obj).attr("v-id"),
		vlink = $(obj).attr("vlink"),
		vthumb = $(obj).attr("vthumb"),
		vduration = $(obj).attr("vduration");
		
		if (notInList == true) {
			$('<div style="display:none" class="qlist_item clearfix" style="background-color:#fff; "  id="quicklist_playlist_cont_'+thevid+'"><div class="pl_num"></div><div class="pl_thumb"><a href="'+obj.attr("vlink")+'" ><img src="'+vthumb+'" class="img-responsive" ><img src="'+baseurl+'/styles/cb_28/theme/images/thumb-ratio.png" alt="" class="thumb-ratio"></a><span class="pl_duration">'+vduration+'</span></div><div class="pl_details" "><p><a href="'+vlink+'" >'+vtitle+'</a></p></div><button todel="'+thevid+'" class="ql_delete glyphicon glyphicon-trash btn btn-danger btn-sm"  title="remove '+vtitle+' from qucklist" alt="quicklist"></button></div>').appendTo('#my_quicklist').fadeIn('slow');
		}

		$.cookie("quick_list_box", "show", { expires : 10 });
		$('#qlist_main').show();
		$('.quicklist_cont').css("display","block");
	}

	$(document).ready(function(){
		$(".ql_show-hide1").click(function(){
			$(this).toggleClass('glyphicon-minus glyphicon-plus');
		});
	});

	$(document).on("click",".cb_quickie",function(){
		obj = $(this);
		$(this).addClass('icon-tick');
		id = $(this).attr('v-id');
		title = $(this).attr('vtitle');
		thumb = $(this).attr('vthumb');
		link = $(this).attr('vlink');
		vdur = $(this).attr('vduration');
		pushToQlist(obj, id);
	});

	$(document).on("click",".ql_delete",function(e){
		e.preventDefault();
		vid = $(this).attr('todel');
		//$(document).find('quick-1046').removeClass("check_icon");
		$(".cb_quickie[v-id="+vid+"]").removeClass('icon-tick');
		currentList = $.cookie("fast_qlist");
		cleaned = currentList.replace(vid, '');
		console.log(cleaned);
		$.cookie("fast_qlist", cleaned, { expires : 10 });
		$(this).closest('.qlist_item').fadeOut('slow');
	});

	$(document).on("click",".ql_rem",function(e){
		e.preventDefault();
		$.cookie("fast_qlist", null, { expires : 10 });
		$('.qlist_item').fadeOut('slow');
		$('#qlist_main').fadeOut('slow');
		$('.cb_quickie').removeClass('icon-tick');
	});
	