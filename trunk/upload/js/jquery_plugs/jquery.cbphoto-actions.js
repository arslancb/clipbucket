(function($){
	$.clipbucket = $.clipbucket || {
		'prefix' : 'cb-',
		'version' : '1.0'	
	};
	
	$.clipbucket.photo_actions = {
		version : '1.0',
		prefix : 'photo-actions-',
		_this : null,
		options : {
			menu_items : null	,
			menu_wrapper : "<ul></ul>",
			menu_item : "<li></li>"
		}
	}
	
	function CBPhotoActions ( element, options ) {
		var _this = this; 
		ActionsSelf( _this );

			if ( options.menu_items == null ) {
				return false; // No menu items provided return	
			}
		
		menu = $( options.menu_wrapper).attr({
			id : getID('wrapper')	
		}).addClass('cbphoto-actions-wrapper nav nav-list');
		
		// Empty the element
		element.html('');
		
		$.each( options.menu_items, function( index, val ) {
			if ( val.href && val.text ) {
				item = $( options.menu_item ).attr({
					id : getID('item-'+index)	
				}).addClass('cbphoto-actions-item');
				        
				link = $("<a>").attr({
					href : val.href,
					target : val.target || null,
					style : val.style || null
				}).html( val.text );

                        if ( val.icon ) {
                            link.html(' '+link.html());
                            icon = $("<i>").addClass('icon-'+val.icon).prependTo( link );
                        }
                        
                        if ( val.tags && typeof val.tags == 'object' ) {
                            link.attr( val.tags );
                        }
        
				link.appendTo( item );
				item.appendTo( menu ); 
			}
		});
		
		menu.appendTo( element );
		
		/*
			href, target, style, name, class
		*/	
	}
	
	function getPrefix() {
		return $.clipbucket.prefix+$.clipbucket.photo_actions.prefix;
	}
	
	function getID(id) {
		return getPrefix()+id;	
	}
	
	function ActionsSelf( set ) {
		if ( set ) {
			$.clipbucket.photo_actions._this = set;	
		}
		return $.clipbucket.photo_actions._this;
	}
	
	// jQuery
	$.fn.photoactions = function(options) {
		
		var actions = $(this).data("photoactions");
		if ( actions ) {
			return actions;	
		}
		options = $.extend( true, {}, $.clipbucket.photo_actions.options, options );
		this.each(function(){
			actions = new CBPhotoActions( $(this), options );	
			$(this).data("photoactions",actions);
		});
		return options.actions ? actions : this;
	}
})(jQuery);