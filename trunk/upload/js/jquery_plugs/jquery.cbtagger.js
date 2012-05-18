(function ($) {
	
	$.clipbucket = $.clipbucket || {
		'prefix' : 'cb-',
		'version' : '1.0'	
	};
	
	$.clipbucket.tagger = {
		version : '1.0',
		// Prefix
		prefix : 'tagger-',
		_this : null,
		options : {
			allowTagging : 'yes',
			// Default, min, max Widths & Heights
			defaultWidth : 100,
			defaultHeight : 100,
			minWidth : 100,
			minHeight : 100,
			maxWidth : 100,
			maxHeight : 100,
			
			// isResizeable
			resizeable : false,
			resizeableOptions : {},
			
			//isDraggable
			draggable : false,
			draggableOptions : {},
			
			//AutoComplete
			autoComplete : false,
			autoCompleteOptions : {},
			
			// Label of tag
			showLabels : true,
			labelsWrapper : null,
			labelsLinkNew : true,
			makeString : true,
			makeStringCSS : false,
			
			//Tags
			defaultTags : null,
			wrapDeleteLinks : true,
			use_percentage : true,
			use_arrows : true,
			arrow_class : 'tagger-up-arrow border',
			
			// Button to start and stop
			addButton : true,
			buttonWrapper : null,
			buttonID : null,
			buttonClass : 'tagger-button tagger-tagging-button',
			addIcon : true,
			
			// page where we will send request for adding/removing tag
			page : baseurl+'/actions/photo_tagger.php',

			//Phrases
			phrases : {
				'tagging_disabled' : 'Tagging is disabled',
				'start_tagging' : 'Tag Photo',
				'stop_tagging' : 'Done',
				'cancel_tag' : 'Cancel',
				'save_tag' : 'Save Tag',
				'saving_tag' : 'Saving Tag',
				'empty_tag' : 'Please type your friend name',
				'remove_tag' : 'Remove Tag',
				'confirm_tag_remove' : 'Confirm Tag Removal',
				'pending_tag' : 'Pending',
				'pending_desc' : 'User has enabled tag modration. Once approved, your tag will be visible publicly'	
			}
		}
	};
		
	function CBTagger( element, options ) {
				
		if ( element.get(0).tagName.toLowerCase() != 'img' ) {
			throw("CB Tagger only works on images. Wrong element was supplied: "+element.get(0).tagName.toLowerCase() );	
		}
				
		$( window ).load(function(e){
			var _this = this;
			TaggerSelf( _this );
			
			var fire = element.add(_this), orgParent = element.parent(), orgParentTag = orgParent.get(0).tagName.toLowerCase();
			element.addClass('tagger-has-this-image'); element.attr('data-tagger-photo-id',element.attr('id').split('_')[1] );
			if ( orgParentTag == 'a' ) {
				element.addClass('tagger-image-has-link');	
			}
			var container = element.wrap("<div id='"+getID('wrapper')+"' class='tagger-image-wrapper' />").addClass('tagger-image').parent(),
					imgContainer = container.find("img[id="+element.attr('id')+"]").wrap("<div style='width:"+element.outerWidth()+"px; height:"+element.outerHeight()+"px; position:relative; margin:auto;' />").parent(),
					tagContainer = $("<div />").attr({
						id : getID('tags-wrapper')	
					}).addClass('tagger-tags-wrapper').appendTo( imgContainer ),
					labelContainer = $("<div/>").attr({
						id : getID('label-wrapper')	
					}).addClass('tagger-label-wrapper');
					
			if ( ( element.hasClass('tagger-image-has-link') || orgParentTag == 'a' ) && ( options.labelWrapper == null )  ) {
				labelContainer.insertAfter( orgParent );
			} else {
				labelContainer.appendTo( document.getElementById(options.labelWrapper) ? $('#'+options.labelWrapper) : orgParentTag == 'a' ? orgParent.parent() : container );
			}

			if ( options.addButton == true ) {
				options.buttonID = options.buttonID ? option.buttonID : getID('tagging');
				if ( options.allowTagging == 'yes' ) {
					if ( orgParentTag == 'a' && options.buttonWrapper == null ) {
						buttonWrapper = orgParent.parent();
					} else {
						buttonWrapper = document.getElementById(options.buttonWrapper) ? $('#'+options.buttonWrapper) : orgParentTag == 'a' ? orgParent.parent() : container;
					}

					/* Let's make sure buttonWrapper has position:relative */
					buttonWrapper.css('position','relative');
					button = $('<button></button>').attr('id',options.buttonID).addClass(options.buttonClass).text(options.phrases.start_tagging).appendTo(buttonWrapper);
					if ( options.addIcon == true ) {
						button.addClass('tagger-button-has-icon');
						tagIcon = $("<img />").attr({
							id : getID('tag-icon'),
							src : baseurl+'/js/jquery_plugs/images/tag.png'
						}).addClass('tagger-button-icon').prependTo(button);
						options.phrases.start_tagging = tagIcon.get(0).outerHTML+options.phrases.start_tagging;
					}

					if ( options.buttonWrapper && document.getElementById(options.buttonWrapper) ) {
						button.addClass('tagger-button-custom-wrapper '+options.buttonWrapper);
					}
					
					// Event: start taggign
					button.on('click',{ options: options }, function(e){
						_this.start(e);
					});
				} 
			}	

			// Events: show|hide tag
			bindingContainer = container.add( labelContainer );
			bindingContainer.on('mouseenter', '.tagger-tag, .tagger-tag-label',{ options: options }, showTag );
			bindingContainer.on('mouseleave', '.tagger-tag, .tagger-tag-label',{ options: options }, hideTag );
			// Event: remove tag
			labelContainer.on('click','.tagger-remove-tag-link',{ options: options }, removeTag );

			if ( orgParentTag == 'a' ) {
				orgParent.click(function(e){
					if ( imgContainer.hasClass('tagger-tagging-active')) {
						return false;
					}
				})  
			}
						
			$.extend(true, _this, {
				addTag : function( width, height, top, left, label , id) {
					if ( typeof(width) != 'object' ) {
						object = {
							width : width,
							height : height,
							top : top,
							left : left,
							id : id,
							label : label	
						};
					} else {
						object = width	
					}
															 
					/* Adding Tag */
					var _d = getTagDimensions( object ),
					
					thisTag = $("<div />").attr({
						id : getID('tag-'+object.id),
						'data-tagger-label' : object.label.toLowerCase()
					}).addClass('tagger-tag').css({
						width : _d.width+_d.unit,
						height : _d.height+_d.unit,
						top : _d.top+_d.unit,
						left : _d.left+_d.unit	,
						opacity : 0
					});
					
					if ( object.pending ) {
						thisTag.addClass('tagger-tag-pending');
					}

					thisTagBox = $("<div />").attr({
						id : getID('tag-box-'+object.id)
					}).addClass('tagger-tag-box').appendTo(thisTag);
					
					thisTagDetails = $("<span />").attr({
						id : getID( 'tag-details-'+object.id )	
					}).addClass('tagger-tag-details').html("<i>"+object.label+"</i>").appendTo(thisTag);
					
					if ( options.use_arrows ) {
						$("<b></b>").addClass( options.arrow_class ).appendTo(thisTagDetails.find('i'));	
					}
					
					tagContainer.prepend(thisTag);
							
					/* Adding Label */
					thisLabel = $("<span />").attr({
						id : getID('tag-label-'+object.id),
						rel : getID('tag-'+object.id),
						'data-tag-id' : object.id
					}).addClass('tagger-tag-label');
					label = object.label;
					
					if ( object.link ) {
						if ( object.link.search('(http|https)://') != -1 ) {
							label = $('<a />').attr({
								href : object.link,
								id : getID('tag-link-'+object.id)
							}).addClass('tagger-tag-label-link').html(label);
							
							if ( options.labelsLinkNew == true ) {
								label.attr('target','_blank');	
							}
						}
					}

					thisLabel.html(label);
					
					if ( object.pending ) {
						$('<i id="'+getID('pending-tag-label-')+object.id+'" title="'+options.phrases.pending_desc+'">'+options.phrases.pending_tag+'</i>').addClass('tagger-pending-tag-label').appendTo(thisLabel);
					}

					if ( object.canDelete == true ) {
						thisLabel.html( thisLabel.html()+' ( ' );
						
						deleteLabel = $("<a />").attr({
							id : getID('remove-tag-link-'+object.id),
							rel : getID('tag-'+object.id),
							href : "#"
						}).addClass('tagger-remove-tag-link').html(options.phrases.remove_tag);
						// I know there is a better way to wrap A with ( ), but for now this will work
						thisLabel.append( deleteLabel );
						thisLabel.append(' )');
					}

					if ( options.makeStringCSS == true && options.makeString == true ) {
						thisLabel.addClass('tagger-tag-label-css-string');	
					}
					
					$('#'+getID('label-wrapper')).append( thisLabel );
					
					if ( options.makeStringCSS == false && options.makeString == true ) {
						makeStringJS( $('#'+getID('label-wrapper')) );
					}
										
					_this.hideTagger(e, _this.getImage().parent() );

					return thisTag;
				},
				getImage : function() {
					return element;
				},
				getOptions : function() {
					return options;
				},
				start : function(e) {

					if ( _this.getOptions().allowTagging == 'no' ) {
						return; // Return if tagging is not allowed.
					}

					var image = _this.getImage(), overlayContainer = image.parent();
					e.target.innerHTML = _this.getOptions().phrases.stop_tagging;
					if ( overlayContainer.hasClass('tagger-tagging-active') ) {
						_this.stop();
						e.target.innerHTML = _this.getOptions().phrases.start_tagging;
						return;	
					}
					
					overlayContainer.addClass('tagger-tagging-active');
					overlayContainer.on('click',function(e){
						_this.showTagger(e, overlayContainer);
					});
				},
				stop : function(e) {
					var image = _this.getImage(), overlayContainer = image.parent();
					overlayContainer.removeClass('tagger-tagging-active');	
					_this.hideTagger(e, overlayContainer);
					overlayContainer.off('click');
				},
				showTagger : function(e, parent) {
					var options = _this.getOptions();
					/*  Return if event is called from Save or Cancel button*/					
					if ( e.target.id == getID('cancel-button') || e.target.id == getID('save-button') ) {
						return;
					}
					/* Return if event is called from inside the Tagger */
					if ( document.getElementById(getID('tagger')) ) {
						postTAGGER = $('#'+getID('tagger') ); targetID = e.target.id;
						if ( postTAGGER.attr('id') != targetID ) {
							if ( postTAGGER.has(e.target).length == 1 ) {
								return;	
							} else {
								_this.hideTagger(e, parent);
							}
						} else {
							return;	
						}
					}
					
					parent.addClass('tagger-overlay');
					var TAGGER = $("<div />").attr({
						id : getID('tagger')	
					}).addClass('tagger-container').css({
						width: options.defaultWidth+"px",
						height: options.defaultHeight+"px",
						opacity: 1	
					});
					
					var positions = getTagPosition(e, parent, TAGGER );
					TAGGER.css({
						'top' : positions[1]+"px",
						'left' : positions[0]+"px"	
					});
					
					input_details = $("<div />").attr({
						id : getID('tagger-input-details')	
					}).addClass("tagger-input-details").appendTo( TAGGER );
					
					$("<div id='"+getID('tagger-input')+"' class='tagger-input'><input name='name' autocomplete='off' type='text' id='"+getID('input')+"' /><button class='tagger-save-button tagger-button' id='"+getID('save-button')+"'>"+options.phrases.save_tag+"</button><button class='tagger-cancel-button tagger-button' id='"+getID('cancel-button')+"'>"+options.phrases.cancel_tag+"</button></div>").appendTo( input_details );
					
					if ( options.use_arrows ) {
						$("<b></b>").addClass( options.arrow_class ).appendTo( TAGGER.find('#'+getID('tagger-input')) );
					}
					
					parent.prepend( TAGGER );
					
					if ( options.autoComplete == true ) {
						if ( $('#'+getID('input')).is(':visible') ) {
							$('#'+getID('input')).attr({
								'data-provide' : 'typeahead'	
							}).typeahead( options.autoCompleteOptions );
						}
					}
					
					//Event save|cancel tag
					$('.tagger-cancel-button').on('click',function(e){
						_this.hideTagger( e,_this.getImage().parent() );	
					});
					
					$('.tagger-save-button').on('click',function(e){
						var self = $(this);
						if ( self.prev().get(0).tagName.toLowerCase() == 'input' ) {
							label = self.prev().val();	
						} else {
							label = $('#'+getID('input')).val();	
						}
						
						if ( label == '' || label == 'undefined' || !label) {
							alert(options.phrases.empty_tag);
							return;	
						}
						
						$.ajax(options.page,{
							type : 'POST',
							dataType : 'json',
							data : {
								width : options.defaultWidth,
								height : options.defaultHeight,
								left : positions[0],
								top : positions[1],
								label : label,
								pid : _this.getImage().attr('data-tagger-photo-id'),
								mode : 'a'
							},
							beforeSend : function() {
								self.html( options.phrases.saving_tag ).attr('disabled','disabled');
								$( '#'+getID('cancel-button') ).hide();
								$('#'+getID('input')).attr('disabled','disabled');	
							},
							success : function( d ) {
								if ( d['error'] == true ) {
									alert(d['error_message']);
									_this.hideTagger(e, parent);
									return;	
								} else if ( d['success'] ) {
									_this.addTag(d);	
								}
							}
						});
					});
				},
				hideTagger : function(e, parent) {
					$('#'+getID('tagger')).remove();
					parent.removeClass('tagger-overlay');
				}
			});
								
			if ( options.defaultTags != null ) {
				$.each( options.defaultTags, function(index, value){
					_this.addTag( value )
				});
			}
					
			return _this;
		});
	}
	
	function getTagDimensions (object) {
		var options = TaggerSelf().getOptions(), image = TaggerSelf().getImage(),
		_return = {
			width : object.width,
			height : object.height,
			top : object.top,
			left : object.left,
			unit : 'px'	
		};
		
		if ( options.use_percentage == true ) {
			_return.width = ( object.width / image.parent().width() )*100;
			_return.height = ( object.height / image.parent().height() )*100;
			_return.top = ( object.top / image.parent().height() )*100;
			_return.left = ( object.left / image.parent().width() )*100;
			_return.unit = '%';
		} 
		
		return _return;
	}
	
	function getTagPosition( event, relative, tagger ) {
		var x,y, options = TaggerSelf().getOptions(), image = TaggerSelf().getImage();
		
		x = Math.max(0, event.pageX - relative.offset().left - (options.defaultWidth/2) );
		y = Math.max(0, event.pageY - relative.offset().top - (options.defaultHeight/2) );
		
		/*  Making sure that tagger stays inside image*/
		if( x + tagger.width() > image.width() ) {
			x = image.width() - tagger.width() - 2; // Removing two pixles of borders	
		}
		
		if ( y + tagger.height() > image.height() ) {
			y = image.height() - tagger.height() - 2; // Removing two pixles of borders	
		}
		
		return [x,y]
	}
	
	function getPrefix() {
		return $.clipbucket.prefix+$.clipbucket.tagger.prefix;
	}
	
	function getID(id) {
		return getPrefix()+id;	
	}
	
	function showTag () {
		var tag = $(this);
		if ( tag.hasClass('tagger-tag') ) {
			tag.addClass('tagger-tag-active-hover');	
		} else if ( tag.hasClass('tagger-tag-label') ) {
			labelTag = tag = $('#'+tag.attr('rel'));
			labelTag.addClass('tagger-tag-active-label');
		}
		tag.css('opacity',1);	
	}
	
	function hideTag() {
		var tag = $(this);
		if ( tag.hasClass('tagger-tag') ) {
			tag.removeClass('tagger-tag-active-hover')		
		} else if ( tag.hasClass('tagger-tag-label') ) {
			labelTag = tag = $('#'+tag.attr('rel'));
			labelTag.removeClass('tagger-tag-active-label');
		}
		
		tag.css('opacity',0);		
	}
	
	function removeTag (e) {
		e.preventDefault();
		
		var link = $(this), options = e.data.options;
		if ( link.parent().hasClass('tagger-removing-tag') || $('#'+getID('label-wrapper')).hasClass('tagger-removing-tag-in-process') ) {
			return;
		}
		
		if ( typeof window.confirm_it == 'function ') {
			action = confirm_it( options.phrases.confirm_tag_remove );	
		} else {
			action = confirm( options.phrases.confirm_tag_remove )	
		}

		if ( action ) {
			var labelParent = link.parent(), tagID = labelParent.attr('data-tag-id')
			
			$.ajax( options.page,{
				type : 'POST',
				dataType : 'json',
				data : {
					mode : 'r',
					id : tagID	
				},
				beforeSend : function() {
					labelParent.addClass('tagger-removing-tag');
					$('#'+getID('label-wrapper')).addClass('tagger-removing-tag-in-process')	;
				},
				success : function(d) {
						$('#'+getID('label-wrapper')).removeClass('tagger-removing-tag-in-process')	;
						if ( d['error'] == true ) {
							alert(d['error_message']);
							return;	
						} else if ( d['success'] ) {
							// Remove Tag
							$('#'+getID('tag-'+tagID)).remove();
							// Remove Label
							$('#'+getID('tag-label-'+tagID)).remove();
							
							/*
								Rewrite labels string if options.makeString == true and make sure
								we are using JS to achieve this task
							*/
							if ( options.makeString == true && options.makeStringCSS == false ){
								makeStringJS( $('#'+getID('label-wrapper')) );
							}
						}
				}
			})
			
		} else {
			return false;	
		}
	}
	
	function makeStringJS( wrapper ) {
		var pattern =  /<span([^<>+]*)>([\w\W]*?)<\/span>/g;
		if (  _childs = wrapper.html().match(pattern) ) {
			var _totalChilds = _childs.length, SecondLastIndex = _totalChilds - 2, LastIndex = _totalChilds - 1,
			formattedOutput = ''
			for(index=0;index<_totalChilds;index++){
				formattedOutput += _childs[index];
				if ( SecondLastIndex == index ) {
					formattedOutput += ' and ';	
				} else {
					if ( index != LastIndex) {
						formattedOutput += ', ';	
					}
				}	
			}

			wrapper.empty().html( formattedOutput );
		}		
	}
	
	function TaggerSelf( set ) {
		
		if ( set ) {
			$.clipbucket.tagger._this = set;	
		}
		
		return $.clipbucket.tagger._this;	
	}
	
	// jQuery
	$.fn.cbtag = function(options) {
		
		var tagger = $(this).data("cbtagger");
		if ( tagger ) {
			return tagger;	
		}
		options = $.extend( true, {}, $.clipbucket.tagger.options, options );
		this.each(function(){
			tagger = new CBTagger( $(this), options );	
			$(this).data("cbtagger",tagger);
		});
		return options.tagger ? tagger : this;
	}
})(jQuery);