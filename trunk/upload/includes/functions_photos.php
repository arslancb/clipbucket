<?php

/**
 * function used to get photos
 */
function get_photos($param)
{
        global $cbphoto;
        return $cbphoto->get_photos($param);
}



//Simple Width Fetcher
function getWidth($file)
{
            $sizes = getimagesize($file);
            if($sizes)
                    return $sizes[0];   
}

//Simple Height Fetcher
function getHeight($file)
{
            $sizes = getimagesize($file);
            if($sizes)
                    return $sizes[1];   
}

//Load Photo Upload Form
function loadPhotoUploadForm($params)
{
            global $cbphoto;
            return $cbphoto->loadUploadForm($params);   
}
//Photo File Fetcher
function get_photo($params)
{
       global $cbphoto;
       return $cbphoto->getFileSmarty($params);
}

//Photo Upload BUtton
function upload_photo_button($params)
{
       global $cbphoto;
       return $cbphoto->upload_photo_button($params);
}

//Photo Embed Cides
function photo_embed_codes($params)
{
            global $cbphoto;
            return $cbphoto->photo_embed_codes($params);   
}

//Create download button

function photo_download_button($params)
{
            global $cbphoto;
            return $cbphoto->download_button($params);   
}

function load_photo_controls ( $args ) {
  global $cbphoto;
  if ( !empty($args['photo'])) {
    $controls = explode(',',$args['controls']);
    $controls = array_map('trim', $controls);
    foreach ($controls as $control) {
      $control_args = null;
      // Parameters for this controls
      $control_args = $args[$control];
      $method_to_call = 'load_'.$control;
      if ( method_exists($cbphoto, $method_to_call) ) {
        $cbphoto->$method_to_call( $args['photo'], $control_args); // Call the method
      }
    }
  }
}

function get_original_photo( $photo, $with_path = false ) {
	global $cbphoto;
	if ( !is_array($photo) ) {
		$ph = $cbphoto->get_photo($photo);	
	} else {
		$ph = $photo;	
	}
	
	if ( is_array($ph) ) {
		$files = $cbphoto->get_image_file( $ph, 'o', true, null, $with_path, true);
		$orig = $ph['filename'].'.'.$ph['ext'];
		$file = array_find( $orig, $files );
		return $file;			
	}
}

function insert_photo_colors( $photo ) {
	global $db, $cbphoto;	
	
	if ( !is_array($photo) ) {
		$ph = $cbphoto->get_photo( $photo );	
	} else {
		$ph = $photo;	
	}
		
	if ( is_array($ph) && isset($ph['photo_id']) ) {
		
		if ( $id =  $db->select( tbl('photosmeta'),'pmeta_id'," photo_id = '".$ph['photo_id']."' AND meta_name = 'colors' " ) ) {
			return $id;	
		}
		
		$dir = PHOTOS_DIR.'/'.get_photo_date_folder( $ph).'/';
		$file = get_original_photo( $ph );
		$path = $dir.$file;
		
		if ( file_exists($path) ) {
			$img = new CB_Resizer( $path );
			$colors = $img->color_palette();
			$img->_destroy(); // Free memory
			
			if ( $colors ) {
				$jcolors = json_encode( $colors );
				$insert_id = $db->insert( tbl('photosmeta'), array('photo_id','meta_name','meta_value'), array($ph['photo_id'],'colors','|no_mc|'.$jcolors) );	
				if ( $insert_id ) {
					return $insert_id;	
				}
			}
		}
	}
}

function insert_exif_data( $photo ) {
	global $db, $cbphoto;
	
	if ( !is_array($photo) ) {
		$ph = $cbphoto->get_photo( $photo );	
	} else {
		$ph = $photo;	
	}
	
	if ( is_array($ph) && isset($ph['photo_id']) ) {
		$dir = PHOTOS_DIR.'/'.get_photo_date_folder( $ph).'/';

		if ( strtolower($ph['ext']) != 'jpg' ) {
			/* return if photo is not jpg */
			return;	
		}
		
		$file = get_original_photo( $ph );
		$path = $dir.$file;
		if ( file_exists($path) ) {
			/* File exists. read the exif data. Thanks to CopperMine, Love you */
			$data = read_exif_data_raw( $path, 0);
			if ( isset($data['SubIFD']) ) {
				$exif_to_include = array('IFD0','SubIFD','IFD1','InteroperabilityIFD');
				foreach( $exif_to_include as $eti ) {
					if ( isset( $data[$eti]) )	 {
						$exif[$eti] = $data[$eti];	
					}
				}

				/* Removing unknown tags from exif data */
				foreach ( $exif as $parent => $tags ) {
					foreach ( $tags as $tag => $value ) {
						if ( $tag == 'MakerNote' ) {
							if ( is_array( $value ) ) {
								foreach( $value as $mtag => $mvalue ) {
									if ( strpos($mtag,'unknown') !== false ) {
										unset( $exif[$parent][$tag][$mtag] );	
									} else {
										$exif[$parent][$tag][$mtag] = trim( $mvalue );
									}						
								}
							}
						}
						
						if ( is_array( $value )) {
							continue;	
						}	
											
						if ( strpos($tag,'unknown') !== false ) {
							unset( $exif[$parent][$tag] );	
						} else {
							$exif[$parent][$tag] = trim( $value );		
						}
					}
				}
												
				$jexif = json_encode($exif);
				$jexif = preg_replace('/\\\(u000)(.){1}/','', $jexif);
				/* add new meta of exif_data for current photo */
				$insert_id = $db->insert( tbl('photosmeta'), array('photo_id','meta_name','meta_value'), array($ph['photo_id'],'exif_data',"|no_mc|$jexif") );
				if ( $insert_id ) {
					/* update photo has_exif to yes, so we know that this photo has exif data */
					$db->update( tbl($cbphoto->p_tbl), array('exif_data'), array('yes'), " photo_id = '".$ph['photo_id']."' " );
					
					return $insert_id;
				}
			}
		}
	}
}

function ready_exif_data ( $exif, $photo = null ) {
	if ( empty( $exif ) || !is_array($exif) ) {
		return false;	
	}
	
	$formatted['dates']['taken'] = $exif['IFD0']['DateTime'] ? $exif['IFD0']['DateTime'] : null;
	$formatted['dates']['uploaded'] = ( $photo ? $photo['date_added'] : null );
	
	$formatted['base']['camera'] = ( $exif['IFD0']['Model'] ? $exif['IFD0']['Model'] : null );
	$formatted['base']['exposure'] = ( $exif['SubIFD']['ExposureTime'] ? $exif['SubIFD']['ExposureTime'] : null );
	$formatted['base']['aperture'] = ( $exif['SubIFD']['ApertureValue'] ? $exif['SubIFD']['ApertureValue'] : null );
	$formatted['base']['focal length'] = ( $exif['SubIFD']['FocalLength'] ? $exif['SubIFD']['FocalLength'] : null );
	$all = array();
	$merge = array('IFD0','SubIFD');
	foreach( $merge as $tag ) {
		if ( isset($exif[$tag]) ) {
			$all = array_merge($all, $exif[$tag]);	
		}
	}
	
	if ( isset( $exif['SubIFD']['MakerNote']) ) {
		$all = array_merge( $all, $exif['SubIFD']['MakerNote'] );
		unset( $all['MakerNote'] );	
	}
	
	/* Remove Base indexes and DateTime */
	unset( $all['DateTime'] );
	unset( $all['Model'] );
	unset( $all['ExposureTime'] );
	unset( $all['ApertureValue'] );
	unset( $all['FocalLength'] );
	
	$formatted['rest'] = $all;
	
	return $formatted;
}

function format_exif_camelCase( $str ) {
	if ( !$str ) {
		return false;	
	}
	
	$re = '/(?<=[a-z])(?=[A-Z])/';
	$str = preg_split( $re, $str );
	return implode(' ', $str );
}

function cb_output_img_tag( $src, $attrs = null ) {
    
    if ( empty( $src ) ) {
        return false;
    }
    
    $start = "<img ";
    $close = " />";
    
    $attributes = ' src = "'.$src.'"';
    if ( is_array( $attrs) ) {
        /* We'll just loop through and add attrs in image */
        foreach ( $attrs as $attr => $value ) {
            if ( strtolower($attr) != 'extra' ) {
              $attributes .= ' '.$attr.' = "'.$value.'" ';  
            } else {
                $attributes .= $value;
            }
        }
    }
    // THIS IS MERGINGGGGGG
    return $start.$attributes.$close;
}

/**
 * Add a new thumb dimension in thumb_dimensions array. 
 * 
 * @global OBJECT $cbphoto
 * @param STRING $code
 * @param INT $width
 * @param INT $height
 * @param INT $crop
 * @param BOOL $watermark
 * @param BOOL $sharpit
 * @return array
 */
function add_custom_photo_size( $code, $width = 0, $height = 0, $crop = 4, $watermark = false, $sharpit = false ) {
	global $cbphoto;
	$sizes = $cbphoto->thumb_dimensions;
	$code = strtolower( $code );
	
	if ( $code == 't' || $code == 'm' || $code == 'l' || $code == 'o' || preg_match('/[^\w]/', $code, $matches ) ) {
		return false;	
	}
	
	if ( !is_numeric( $width )  || !is_numeric( $height ) ) {
		return false;	
	}
	
	$sizes [ $code ] = array(
		'width' => abs( $width ),
		'height' => abs( $height ),
		'crop' => $crop,
		'watermark' => $watermark,
		'sharpit' => $sharpit
	);
	
	return  $cbphoto->thumb_dimensions = $sizes;
}

function get_all_custom_size_photos( $id ) {
    global $cbphoto;
    if ( !is_array( $id ) ) {
        $photo = $cbphoto->get_photo( $id );
    } else {
        $photo = $id;
    }
    /* I'm unable to select only custom size photos using glob, so we'll
     * get all photos loop through exclude clipbucket sizes and save only
     * custom ones
     */
    $files = $cbphoto->get_image_file( $photo, null, true, null, false );
    $cbsize = $cbphoto->thumb_dimensions;
    if ( $files ) {
        foreach ( $files as $file ) {
            $code = $cbphoto->get_image_type ( $file );
            if ( !isset( $cbsize[ $code ]) ) {
                $custom_files[] = $file;
            }
        }
        
        if ( is_array( $custom_files ) ) {
            return $custom_files;
        } else {
            return false;
        }
    }
}

function get_photo_dimensions($filter=false) {
    global $cbphoto;
    
    if ( $filter === true ) {
        apply_filters( null, 'photo_dimensions' );
    }
    
    return $cbphoto->thumb_dimensions;
}

function clean_custom_photo_size( $id ) {
    global $cbphoto;
    $photos = get_all_custom_size_photos( $id );
    // Apply photo_dimension filters so get all custom sizes
    apply_filters( null, 'photo_dimensions' );
    $dimensions = get_photo_dimensions();
    if ( $photos ) {
        foreach ( $photos as $photo ) {
            $code = $cbphoto->get_image_type( $photo );
            /*
            * The following custom code does not exist in thumb_dimensions array
            */
            if ( !isset( $dimensions[ $code ] ) ) {
                // I'm still thinking what to do with these extra photos
                $path = PHOTOS_DIR.'/'.$photo;
                $month = 2678400; // month period
                $lastacc = fileatime( $path );
                if ( ( $lastacc ) && time() - $lastacc > $month ) {
                    // it's been over a month since this file was accessed, remove it
                    unlink( $photo );
                }
            }
        }  
    }
}

function get_photometa( $id, $name ) {
	global $db;
	
	if ( empty( $id ) || empty( $name ) ) {
		return false;	
	}
	
	$result = $db->select( tbl('photosmeta'), '*', " photo_id = '".$id."' AND meta_name = '".strtolower($name)."' " );
	if ( $result ) {
		return $result[0];	
	} else {
		return false;	
	}
}

function get_photo_meta_value( $id, $name ) {
	$meta = get_photometa($id, $name);
	if ( $meta ) {
		return $meta['meta_value'];	
	} else {
		return false;	
	}
}

/**
 * It returns an array for photo action link.
 * This should be used with photo_action_links
 * filter.
 * 
 * @param string $text
 * @param string $href
 * @param string $target
 * @return mix 
 */

function add_photo_action_link( $text, $href, $icon = null, $target = null, $tags = null ) {
    if ( !$text && !$href ) {
        return false;
    }
    
    if ( strlen( trim( $href ) ) == 1 && $href == '#' ) {
        $skip_href_check = true;
    }
    
    if ( !preg_match('/(http:\/\/|https:\/\/)/', $href, $matches ) && !$skip_href_check ) {
        return false;
    }
    
    return array( 'href' => $href, 'text' => $text,'icon' => $icon, 'target' => $target, 'tags' => ( !is_null($tags) && is_array($tags) ) ? $tags : null );
}

function cbphoto_pm_action_link_filter( $links ) {
    if ( userid() ) {
      $links[] = add_photo_action_link( lang('Send in private message'), '#' , 'envelope', null, array('id' => 'private_message_photo', 'data-toggle' => 'modal', 'data-target' => '#private_message_photo_form') );  
    }
    
	global $photo, $cbphoto;
	
	if ( ( $photo['exif_data'] == 'yes' && $photo['view_exif'] == 'yes' ) || ( $photo['userid'] == userid() && $photo['exif_data'] == 'yes' ) ) {
		$links[] = add_photo_action_link( lang('EXIF Data'), $cbphoto->photo_links( $photo, 'exif_data' ), 'camera' );	
	}
	
	/* Later we uncomment this, BAM something new to give >:D */
	//$links[] = add_photo_action_link( lang('View Colors'),'#','tasks', null, array('onclick' => 'show_colors(event)', 'data-photo-id' => $photo['photo_id']) );
	
    return $links;
}

function register_photo_private_message_field( $photo ) {
    global $cbpm;
    
    $field = array(
            'attach_photo' => array(
                'name' => 'attach_photo',
                'id' => 'attach_photo',
                'value' => $photo['photo_key'],
                'type' => 'hidden'
        )
    );
    
    $cbpm->add_custom_field( $field );
}

function attach_photo_pm_handlers() {
    global $cbpm;
    
    $cbpm->pm_attachments[] = 'attach_photo';
    $cbpm->pm_attachments_parse[] = 'parse_photo_attachment';
}

function attach_photo( $array ) {
    global $cbphoto;
    if ( $cbphoto->photo_exists( $array['attach_photo'] ) ) {
        return '{p:'.$array['attach_photo'].'}';
    }
}

function parse_photo_attachment( $att ) {
    global $cbphoto;
    preg_match('/{p:(.*)}/',$att,$matches);
    $key = $matches[1];
    if ( !empty( $key ) )  {
        $photo = $cbphoto->get_photo( $key, true );
        if ( $photo ) {
            assign( 'photo',$photo );
            assign( 'type','photo' );
            Template( STYLES_DIR.'/global/blocks/pm/attachments.html', false );
        }
    }
}

/**
 * This is registered at cb_head ANCHOR. This loads photo actions links.
 * You can use photo_action_links filter to new links and
 * photo_action_configs to provide custom configurations.
 * 
 * Right now it has only to configurations.
 * menu_wrapper => <ul></ul>, This one wraps menu_items
 * menu_item => <li></li>, This one wraps the anchor link
 * 
 * @global object $cbphoto
 * @global array $photo
 * @return none 
 */
function load_photo_actions() {
	global $cbphoto, $photo, $userquery;
	
	if ( empty($photo) || !$photo || !isset( $photo['ci_id'] ) ) {
		return false;	
	}
	$links = array();	
	$configs = array(
		'menu_wrapper' => '<ul></ul>',
		'menu_item' => '<li></li>'
	);
	
	$download = photo_download_button( array('details' => $photo, 'return_url' => true) );	
	if ( $download ) {
		$links[] = array(
                'href' => $download,
                'text' => lang('download_photo'),
                'icon' => 'circle-arrow-down'
		);
	}

	if ( userid() && $photo['userid'] == userid() ) {
		$links[] = array(
                'href' => BASEURL.'/edit_photo.php?photo='.$photo['photo_id'],
                'text' => lang('edit_photo'),
                'target' => '_blank',
                'icon' => 'pencil'
		);
		
		$links[] = array(
                'href' => '#',
                'text' => lang('Delete Photo'),
                'icon' => 'remove',
				'tags' => array(
					'onclick' => 'displayConfirm("delete_photo_'.$photo['photo_id'].'","'.lang('Please confirm the photo delete').'", delete_photo_ajax,"'.lang('Delete This Photo').'"); return false;',
					'data-toggle' => 'modal',
					'data-target' => '#delete_photo_'.$photo['photo_id']
				)
		);
	}

	if ( userid () ) {
		$user = $userquery->udetails;
		if ( $photo['collection_id'] != $user['avatar_collection'] ) {
			$links[] = array(
				'href' => $cbphoto->photo_links( $photo, 'ma' ),
				'text' => lang('Make avatar'),
				'icon' => 'user'
			);			
		} else {
			$links[] = array(
				'href' => $cbphoto->photo_links( $photo, 'ma' ),
				'text' => lang('Set as avatar'),
				'icon' => 'user'
			);				
		}
  }

		
	// Apply Filter to links
	$links = apply_filters( $links, 'photo_action_links');
	// Apply Filter to configs
	$configs = apply_filters( $configs, 'photo_action_configs' );
	$configs['menu_items'] = $links;
    
	assign('photo_action_configs', json_encode( $configs ) );
	assign('photo',$photo);
	Template(STYLES_DIR.'/global/photo_actions.html',false);
}

/**
 * This is registered at cb_head ANCHOR. This loads the photo tagging
 * plugin in clipbucket. You can use tagger_configurations filter to change
 * tagger configurations. Following is the list of configurations :
 * 
 *      |=  Show Tag labels =| BOOL
 *      showLabels => true
 * 
 *      |=  Provide an element ID and labels will loaded in them =| STRING
 *      labelWrapper => null
 * 
 *      |= Open labels links in new window =| BOOL
 *      labelLinksNew => false
 * 
 *      |= Make string like facebook: Tag1, Tag2 and Tag3 =| BOOL
 *      makeString => true
 * 
 *      |= We JS to create string. Set true, to create using CSS. Be warn CSS might not work in >IE9 =| BOOL
 *      makeStringCSS => false
 * 
 *      |= This wraps Remove Tag link in round brackets ( ) =| BOOL
 *      wrapDeleteLinks => true
 * 
 *      |= Show a little indicator arrow. Note: Arrow is created purely from CSS. Might not work in >IE9 =| BOOL
 *      use_arrows => true 
 *      
 *      |= To display Tag Photo elsewhere, provide an element ID  =| STRING
 *      buttonWrapper => null
 * 
 *      |= This will add a tag icon previous to Tag Photo text =| BOOL
 *      addIcon => true
 * 
 * @global object $db
 * @global object $cbphoto
 * @global array $photo
 * @global object $userquery
 * @return none 
 */
function load_tagging() {
	global $db, $cbphoto, $photo, $userquery;
	if ( USE_PHOTO_TAGGING != true ) {
		return false;	
	}
	
	if ( empty($photo) || !$photo || !isset( $photo['ci_id'] ) ) {
		return false;	
	}
	
	$options = $cbphoto->get_default_tagger_configs();
	$options['allowTagging'] = $photo['allow_tagging'];
      $phrases = $options['phrases'];
      /* User does not need phrases in apply_filters() function */
      unset( $options['phrases'] );

      $options = apply_filters( $options, 'tagger_configurations');
      /* Put back phrases in $options, over-wrtting JS Plugin Phrases */
      $options['phrases'] = $phrases;
	$tags = $cbphoto->get_photo_tags( $photo['photo_id'] );
	$autoComplete = $options['autoComplete'];
	$uid = userid();
	
	if ( $uid ) {
		$friends = $userquery->get_contacts( $uid, 0, 'yes');
	}
	
	if ( $friends ) {
		foreach ( $friends as $contact ) {
			$fa[$contact['contact_userid']] = $contact['username'];
			$typeahead[] = $contact['username'];
		}
	}
	
	if ( $tags ) {
		/* Tags exists */
		foreach ( $tags as $tag ) {
			$needs_update = false;
			/* Check if tag is active or not and if current user is not tagger or owner of photo or is guest, do not show tag */
			if ( ( !$uid && $tag['ptag_active'] == 'no' ) || ( $tag['ptag_active'] == 'no' && $uid && $tag['ptag_by_userid'] != $uid && $tag['photo_owner_userid'] != $uid ) ) {
				continue; 
			}
			$ta = array();
			$ta['id'] = $tag['ptag_id'];
			$ta['key'] = $tag['ptag_key'];
			$ta['width'] = $tag['ptag_width'];
			$ta['height'] = $tag['ptag_height'];
			$ta['left'] = $tag['ptag_left'];
			$ta['top'] = $tag['ptag_top'];
			$ta['label'] = $tag['username'] = $tag['ptag_username'];
			$ta['added_by'] = $tag['ptag_by_username'];
			$ta['date_added'] = nicetime( $tag['date_added'], true);

			if ( $tag['ptag_active'] == 'no' ) {
				$ta['pending'] = true;
			}

			/* Photo owner and User which has tagged */
			if ( $uid == $tag['photo_owner_userid'] || $uid == $tag['ptag_by_userid'] ) {
				$ta['canDelete'] = true;
			}

			/* 
				If make sure tag is a user
				See which person is online, tagger or tagged
				If Tagger is online, give him option to delete

				if Tagged is online, check if it's tagger's friend
				if true, give option to delete
			*/
			if ( $tag['ptag_isuser'] == 1 ) {
				if ( $uid == $tag['ptag_by_userid'] ){ // Tagger is online
					$ta['canDelete'] = true; // Grant him access to delete
					if ( is_array($friends) && $fa[ $tag['ptag_userid'] ] ) {
						$ta['link'] = $userquery->profile_link( $tag['ptag_userid'] );
						// Person tagged is in his contacts lists and already been tagged, remove it from typahead array
						unset( $typeahead[ end(array_keys($typeahead,$tag['ptag_username'])) ] );
					}
				} else if ( $uid == $tag['ptag_userid'] ) {
					if ( is_array($friends) && $fa[ $tag['ptag_by_userid'] ] ) {
						$ta['canDelete'] = true;
						$ta['link'] = $userquery->profile_link( $tag['ptag_userid'] );
					}
				}
                    
                    /* This basically checks, if tagger and tagged and logged-in user are same 
                     * create the profile link for logged-in user. This will be achieved if user tags
                     * itself on a photo
                     */
                    if ( $tag['ptag_by_userid'] == $tag['ptag_userid'] && $tag['ptag_userid'] == $uid ) {
                        $ta['link'] = $userquery->profile_link( $tag['ptag_userid'] );
                    }
			}

			$defaultTags[] = $ta;
		}

		$options['defaultTags'] = $defaultTags;
	}
	
	if ( $friends && $typeahead && $options['autoComplete'] == 1 ) {
		$options['autoCompleteOptions']['source'] = $typeahead;
	}
	
	assign('tagger_configs', json_encode($options));
	assign('selector_id',$cbphoto->get_selector_id());
	assign('photo',$photo);
	Template(STYLES_DIR.'/global/photo_tagger.html',false); 
}


/**
 *  This creates as array containing two index.
 * index 0 => contains the mySQL JOIN statement
 * index 1 => Alias for same columns in both tables
 * Easy using for this function will be:
 * ============================================
 * list( $join, $alias ) = join_collection_table();
 * $db->select( tbl('photos').$join, '*'.$alias, ....... );
 * ============================================
 * @global object $cbcollection
 * @global object $cbphoto
 * @return array 
 */
function join_collection_table() {
    global $cbcollection, $cbphoto;
    $c = tbl ($cbcollection->section_tbl ) ; $p = tbl('photos');
    $join = ' LEFT JOIN '.( $c ).' ON '.( $p.'.collection_id' ). ' = '.( $c.'.collection_id' );
    $alias = ", $p.userid as userid, $p.views as views, $p.allow_comments as allow_comments, $p.allow_rating as allow_rating, $p.total_comments as total_comments, $p.date_added as date_added, $p.rating as rating, $p.rated_by as rated_by, $p.voters as voters, $p.featured as featured, $p.broadcast as broadcast, $p.active as active";
    $alias .= ", $c.collection_name as collection_name, $c.userid as cuserid, $c.views as cviews, $c.allow_comments as callow_comments, $c.allow_rating as callow_rating, $c.total_comments as ctotal_comments, $c.date_added as cdate_added, $c.rating as crating, $c.rated_by as crated_by, $c.voters as cvoters, $c.featured as cfeatured, $c.broadcast as cbroadcast, $c.active as cactive";
    
    return array( $join, $alias );
}

/**
 * This function return all photos that are avatars
 * 
 * @global object $db
 * @global OBJECT $cbphoto
 * @param boolean $join
 * @param string $cond
 * @param string $order
 * @param int $limit
 * @return array
 */
function get_avatar_photos( $join=true, $cond=null,$order=null,$limit=null) {
    global $db, $cbphoto;
    
    if ( !is_null($cond) ) {
        $cond .= ' AND '.$cond;
    }
    
    if ( $join ) {
        list( $join, $alias ) = join_collection_table();
    }
    
    $results = $db->select( tbl('photos').$join, '*'.$alias, " is_avatar='yes' $cond ", $limit, $order );
    
    if ( $results ) {
        return $results;
    } else {
        return false;
    }
}


function is_photo_viewable( $pid ) {
	global $db, $cbphoto;
	
	if ( !is_array( $pid ) ) {
		$photo = $cbphoto->get_photo( $pid );
	} else {
		$photo = $pid;
	}
	
	if ( !$photo ) {
		e(lang('photo_not_exists'));
		if ( !has_access('admin_access', true) ) {
			return false;
		} else {
			return true;	
		}
	} else if ( $photo['active'] == 'no' ) {
		e(lang('Photo is not active. Please Admin for details'));
		if ( !has_access('admin_access', true) ) {
			return false;
		} else {
			return true;	
		}	
	} else if ( $photo['is_mature'] == 'yes' && !userid() ) {
		assign('title', $photo['photo_title']);
		template_files( STYLES_DIR.'/global/blocks/mature_content.html', false, false );
	} else {
		$funcs = cb_get_functions('view_photo');
		if ( is_array( $funcs ) ) {
			foreach( $funcs as $func ) {
				if ( function_exists( $func['func'] ) ) {
					$data = $func['func']( $photo );
					if ( $data ) {
						return $data;	
					}
				}
			}
		}
		return true;
	}
}

function delete_photo_tags( $photo ) {
	global $db;
	// Delete all tags of current photo
	$db->delete( tbl('photo_tags'), array('photo_id'), array( $photo['photo_id'] ) );
}

function delete_photo_meta( $photo ) {
	global $db;
	// Delete all meta of current photo
	$db->delete( tbl('photosmeta'), array('photo_id'), array( $photo['photo_id'] ) );	
}

/**
 * Function gets the date folder for the current photo.
 * @param INT|ARRAY $pid, This could either be a photo id or photo array
 * @return STRING 
 */
function get_photo_date_folder ( $pid ) {
	global $cbphoto, $db;
	if ( !is_array($pid) ) {
		$photo = $cbphoto->get_photo( $pid );	
	} else {
		$photo = $pid;	
	}
	
	if ( empty( $photo ) ) {
	 	return false;	
	}
	
	// First check if we have db date_dir column
	if( $photo['date_dir'] ) {
		$date_dir = $photo['date_dir'];	
	}
	
	if ( !$date_dir ) {
		// OK, no db value found. Create structure from filename.
		$random_string = substr( $photo['filename'], -6, 6 );
		$time = str_replace( $random_string, '', $photo['filename'] );
		$date_dir = date('Y/m/d', $time );
		// Make sure the fi	le exists @PHOTOS_DIR.'/'.$date_dir.'/'.$photo['filename'].'.'.$photo['ext']
		if ( !file_exists( PHOTOS_DIR.'/'.$date_dir.'/'.$photo['filename'].'.'.$photo['ext'] ) ) {
			$date_dir = false;	
		}		
	}
	
	return $date_dir;
}

function view_photo_link( $photo, $type='view_item' ) {
    global $cbphoto;
    return $cbphoto->collection->collection_links( $photo, $type);
}

function add_photo_manager_link( $title, $link, $callback = false, $args = false ) {
    global $cbphoto;
    $random_id = RandomString(8);
    
    if ( !$title || !$link ) {
        return false;
    }
    
    $links = array(
        'title' => $title,
        'id' => $random_id.'-'.SEO(strtolower($title)),
        'link' => $link,
        'args' => $args,
        'callback' => $callback
    );
         
    return $links;
}

function display_manager_links( $photo ) {
    global $cbphoto;
    $links = $cbphoto->manager_links;
    $cbphoto->photo = $photo;
    $links = apply_filters($links, 'photo_manager_links');
  
    if ( $links ) {
        foreach( $links as $link ) {
            $output .= '<li><a id="'.$link['id'].'" href="'.$link['link'].'">'.$link['title'].'</a></li>';
            $callback = $link['callback'];
            if ( $callback && is_string( $callback ) ) {
                $cbphoto->manager_link_callbacks[ $link['id'] ] = $callback;
            }
        }
       
        return $output;
    }
}

function cb_some_photo_plugin_links( $links ) {
    global $cbphoto;
    $photo = $cbphoto->photo;
    $link = 'recreate_thumbs.php?mode=single&photo='.$photo['photo_id'];
    $links[] = add_photo_manager_link( lang('Re-create Photo'), $link );
    if ( $photo['collection_id'] != 0 ) {
        $links[] = add_photo_manager_link( lang('Edit Collection ('. $photo['collection_name'].')'), 'edit_collection.php?collection='.$photo['collection_id'] );
    } else {
        $links[] = add_photo_manager_link(lang('Photo is orphan'),'javascript:void(0)');
    }
    return $links;
}


?>