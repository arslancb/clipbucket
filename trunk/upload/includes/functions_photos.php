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
    return get_image_file( $params );
    //global $cbphoto;
    //return $cbphoto->getFileSmarty($params);
}

//Photo Upload BUtton
function upload_photo_button($params)
{
       global $cbphoto;
       return $cbphoto->upload_photo_button($params);
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

/**
 * This function returns the untouched/original file
 * of photo
 * 
 * @global OBJECT $cbphoto
 * @param ARRAY $photo
 * @param BOOL $with_path
 * @return STRING
 */
function get_original_photo( $photo, $with_path = false ) {
	global $cbphoto;
	if ( !is_array($photo) ) {
		$ph = $cbphoto->get_photo($photo);	
	} else {
		$ph = $photo;	
	}
	
	if ( is_array($ph) ) {
        $files = $cbphoto->get_image_file( $ph, 't', true, false, $with_path, true );
        $orig = $ph['filename'].'.'.$ph['ext'];
        $file = array_find( $orig, $files );
        return $file;			
	}
}

/**
 * This will extract the most common colors from photo.
 * Record it's HEX code, rgb code and percent found in photo,
 * than before entering database, it runs through json_encode.
 * 
 * @global OBJECT $db
 * @global OBJECT $cbphoto
 * @param ARRAY $photo
 * @return type
 */
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

/**
 * This function inserts EXIF data embedded by cameras.
 * 
 * @global OBJECT $db
 * @global OBJECT $cbphoto
 * @param ARRAY $photo
 * @return INT
 */
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
					$db->update( tbl($cbphoto->p_tbl), array('has_exif'), array('yes'), " photo_id = '".$ph['photo_id']."' " );
					
					return $insert_id;
				}
			}
		}
	}
}

/**
 * This function takes EXIF data stored in database and make
 * required indexes that are most important or user usually
 * looks for these details.
 * 
 * @param ARRAY $exif
 * @param INT $photo
 * @return ARRAY
 */
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

/**
 * This converts camelCase of EXIF index into
 * readable string.
 * 
 * @param STRING $str
 * @return STRING
 */
function format_exif_camelCase( $str ) {
	if ( !$str ) {
		return false;	
	}
	
	$re = '/(?<=[a-z])(?=[A-Z])/';
	$str = preg_split( $re, $str );
	return implode(' ', $str );
}

/**
 * This outputs an img HTML tag. $src is requred.
 * $attrs is array contains ['attribute name' => attribute value]
 * @param STRING $src
 * @param ARRAY $attrs
 * @return STRING
 */
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
                $attributes .= ( $value );
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
function add_custom_photo_size( $name, $code, $width = 0, $height = 0, $crop = 4, $watermark = false, $sharpit = false ) {
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
        'name' => $name,
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

/**
 * This gets default photo dimensions. If $filter is true
 * We'll run photo_dimensions filter to all available dimensions.
 * 
 * @global OBJECT $cbphoto
 * @param BOOL $filter
 * @return ARRAY
 */
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
	
	if ( ( $photo['has_exif'] == 'yes' && $photo['view_exif'] == 'yes' ) || ( $photo['userid'] == userid() && $photo['has_exif'] == 'yes' ) ) {
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
 * You can use photo_action_links filter to new links.
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
	
	$download = photo_download_button( array('details' => $photo, 'return_url' => true) );	
	if ( $download ) {
		$links[] = array(
                'href' => $download,
                'text' => lang('download_photo'),
                'icon' => 'circle-arrow-down'
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

	if ( userid() && $photo['userid'] == userid() ) {
		$links[] = array(
                'href' => BASEURL.'/edit_photo.php?photo='.$photo['photo_id'],
                'text' => lang('Edit Photo'),
                'target' => '_blank',
                'icon' => 'pencil'
		);
		
		$links[] = array(
                'href' => '#',
                'text' => lang('Delete Photo'),
                'icon' => 'remove',
				'tags' => array(
					'onclick' => 'displayConfirm("delete_photo_'.$photo['photo_id'].'","'.lang('Are you sure you want to delete this photo ? This action will delete photo permanently.').'", delete_photo_ajax,"'.lang('Delete This Photo').'"); return false;',
					'data-toggle' => 'modal',
					'data-target' => '#delete_photo_'.$photo['photo_id']
				)
		);
	}
  
    return $links;
}

function display_photo_actions( $icon = true, $white = false ) {
    $links = load_photo_actions();
    if ( $links ) {
        $output='';
        foreach( $links as $link ) {
            if ( $link['href'] ) {
                $output .= '<li>';
                $output .= "<a href='".$link['href']."' ";
                if ( $link['target'] ) {
                    $output .= "target='".$link['target']."' ";
                }
                
                if ( $link['style'] ) {
                    $output .= "style='".$link['style']."' ";
                }
                
                // Add attributes
                if ( $link['tags'] && is_array($link['tags']) ) {
                    foreach ( $link['tags'] as $attribute => $value ) {
                        $output .= $attribute."='".$value."'";
                    }
                }
                
                $output .= ">";
                // Add icon
                if ( $link['icon'] && $icon !== false ) {
                    if ( $white === true ) {
                        $white_icon = ' icon-white';
                    }
                    $output .= "<i class='icon-".$link['icon']."$white_icon'></i> ";
                }
                
                $output .= $link['text'];
                $output .= "</a>";
                $output .= '</li>';
            }
        }
    }
    
    return $output;
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
    //$alias = ", $p.userid as userid, $p.views as views, $p.allow_comments as allow_comments, $p.allow_rating as allow_rating, $p.total_comments as total_comments, $p.date_added as date_added, $p.rating as rating, $p.rated_by as rated_by, $p.voters as voters, $p.featured as featured, $p.broadcast as broadcast, $p.active as active";
    //$alias .= ", $c.collection_name as collection_name, $c.userid as cuserid, $c.views as cviews, $c.allow_comments as callow_comments, $c.allow_rating as callow_rating, $c.total_comments as ctotal_comments, $c.date_added as cdate_added, $c.rating as crating, $c.rated_by as crated_by, $c.voters as cvoters, $c.featured as cfeatured, $c.broadcast as cbroadcast, $c.active as cactive, $c.cover_photo";
    $alias = ", $c.collection_name, $c.cover_photo, $c.category, $c.broadcast";
    return array( $join, $alias );
}

/**
 * This function creates LEFT for meta data
 */
function join_meta_details() {
    
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
        assign( 'object', $photo );
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
 * Function gets the date folder for the current photo. If file_directory index is found
 * in photo details, we'll extract time from it's filename and format it to check if files
 * exists in desired folder. If not found false will return, confirming that photo exists
 * at files/photos dir.
 * 
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
	if( $photo['file_directory'] ) {
		$date_dir = $photo['file_directory'];	
	}
	
	if ( !$date_dir ) {
		// OK, no db value found. Create structure from filename.
		$random_string = substr( $photo['filename'], -6, 6 );
		$time = str_replace( $random_string, '', $photo['filename'] );
		$date_dir = date('Y/m/d', $time );
		// Make sure the file exists @PHOTOS_DIR.'/'.$date_dir.'/'.$photo['filename'].'.'.$photo['ext']
		if ( !file_exists( PHOTOS_DIR.'/'.$date_dir.'/'.$photo['filename'].'.'.$photo['ext'] ) ) {
			$date_dir = false;	
		} else {
            // Update the db value
            $db->update( tbl('photos'), array('file_directory'), array($date_dir), " photo_id = '".$photo['photo_id']."' " );
        }		
	}
	
	return $date_dir;
}

/**
 * Alias function to create view photo link
 * 
 * @global OBJECT $cbphoto
 * @param ARRAY $photo
 * @param STRING $type
 * @return STRING
 */
function view_photo_link( $photo, $type='view_item' ) {
    global $cbphoto;
    return $cbphoto->collection->collection_links( $photo, $type);
}

/**
 * This function adds a new link in options for each at photos manager.
 * Callback is still in not working. I want to register both link and it's callback
 * using same function. 
 * 
 * PROBLEM:-
 * Problem is calling the callback function for certain link. Even if i figure out
 * a way to determine which callback to call, it will not be called because these
 * new links are being added inside display_manager_links() which is being called
 * inside smarty file.
 * 
 * SOLUTION:- ( to me, for the time being )
 * We don't use filters to add new links. Just calling add_photo_manager_link will
 * add new link inside $cbphoto->manager_links, so at admin_area/photo_manager.php
 * every link exists inside manager_links.
 * 
 * FINAL:-
 * Like my proposed solution. calling this function will do following
 *  |- Add new item in manager_links array
 *  |- Create a callback_id if callback exists using $callback.$link.$title and hashing with md5
 *  |- Add it's callback_id if exists into manager_link_callbacks array
 * 
 * To get details of each photo for link, you must use a function to create the link. Pass any
 * argument to function and it will hold details of each photo, e.g
 * 
 * function create_manager_link( $p ) {
 *  return '?_key'.$p['photo_key'];
 * }
 * 
 * @global OBJECT $cbphoto
 * @param STRING $title
 * @param STRING $link
 * @param STRING $callback
 * @param BOOLEAN $front_end
 * @return MIX
 */
function add_photo_manager_link( $title, $link, $callback = false, $front_end = false ) {
    global $cbphoto;
        
    if ( !$title || !$link ) {
        return false;
    }
    
    /* Check which end we are adding this link */
    $which_end = ( $front_end === true ) ? 'front_end' : 'back_end';
    
    // Create callback id
    if ( $callback ) {
        $callback_id = md5( $callback.$link.$which_end.SEO(strtolower($title)) );
    }
    
    $cbphoto->manager_links[$which_end][] = array(
        'title' => $title,
        'id' => SEO(strtolower($title)).'-'.time(),
        'link' => $link,
        'args' => $args,
        'callback' => $callback,
        'callback_id' => $callback_id
    );
    
    // Add callback
    if ( $callback ) {
        $cbphoto->manager_link_callbacks[ $callback_id ] = $callback;
    }
    
    return $cbphoto->manager_links;
}

/**
 * Function displays the photo manager links. Here we will check if link
 * is function or simple string. If function, call it and return the result.
 * Than if callback exists, append it's id at end of returned result.
 * 
 * filter used: photo_manager_links
 * 
 * @global OBJECT $cbphoto
 * @param type $photo
 * @return string
 */
function display_manager_links( $photo, $front_end = false ) {
    global $cbphoto;
    $which_end = ( $front_end === true || FRONT_END ) ? 'front_end' : 'back_end';
    $links = $cbphoto->manager_links[$which_end];
    $links = apply_filters($links, 'photo_manager_links');
    
    if ( $links ) {
        foreach( $links as $link ) {
            // Creating link. Either function or string
            if ( function_exists($link['link']) && is_string($link['link']) ) {
                $url = $link['link'] ( $photo );
            } else {
                $url = $link['link'];
            }
            
            if ( !$url ) {
                continue; // Skip this index
            }
            
            if ( is_array( $url ) ) {
                /* Sometimes we need to change title according to conditions. Return array with title and link 
                 * indexes in your function to do so.  For example return result should be like following:
                 * 
                 *      function name() {
                 *          ..........
                 *          ........
                 *          ....
                 *          ..
                 *          .
                 *          return array( 'title' => 'New title', 'link' => 'your_link' );
                 *      }
                 * 
                 */
                $link['title'] = $url['title'] ? $url['title'] : $link['title'];
                $url = $url['link'] ? $url['link'] : $link['link'];
            }
            
            if ( $_SERVER['QUERY_STRING'] && strpos( $url, '.php') === false && strpos($url,'?') !== false ) {
                // QUERY_STRING exists and $url does not have .php
                // append QUERY_STRING before $url
                $url = ltrim( $url, '?' ); // removing the ? from start of string
                parse_str( $url, $variables ); // changing $url query string to array
                parse_str( $_SERVER['QUERY_STRING'], $server_variables );
                if ( $variables ) {
                  $query_string_variables = array_keys( $variables ); // extracting only names of variables
                  $server_string_variables = array_keys( $server_variables );

                  if ( $query_string_variables ) {
                    $query_string_variables = ( array_merge( $query_string_variables, $server_string_variables ) );
                    $query_string_variables = array_unique( $query_string_variables );
                    $query_string_variables = array_diff( $query_string_variables, array('omo') );
                    //pr( $query_string_variables, true );
                    $query_string = queryString( null, $query_string_variables );
                  }
                  //echo $query_string."<br/>";
                  $url = $query_string.$url;
                }
            }
            
            // Appeding callback_id if callback exists
            if ( $link['callback'] ) {
                if ( strpos( $url, '?' ) !== false ) {
                    $url .= '&callback_id='.$link['callback_id'];
                } else {
                    $url .= '?callback_id='.$link['callback_id'];
                }
            }
            
            if ( $link['args'] && is_array( $link['args']) ) {
                $attributes = '';
                foreach( $link['args'] as $attribute => $value ) {
                    $attributes .= $attribute."='".$value."' ";
                }
            }
            
            $output .= '<li><a id="'.$link['id'].'" href="'.$url.'" '.$attributes.'>'.$link['title'].'</a></li>';
        }
       
        return $output;
    }
}

/**
 * Function calls the provided callback_id callback function. Using callback_id
 * insures that no fasool calls are made. Only callacbk is called whos id is provided.
 * callback_id is required to make to call.
 * 
 * @global OBJECT $cbphoto
 */
function photo_manager_link_callbacks() {
    global $cbphoto;
    $callbacks = $cbphoto->manager_link_callbacks;
    if ( $callbacks ) {
        // Check if callback id is exists
        if ( $_GET['callback_id'] ) {
            $callback_id = mysql_clean( $_GET['callback_id'] );
            // Get current function callback function
            if ( !empty( $cbphoto->manager_link_callbacks[ $callback_id ] ) ) {
                if ( function_exists( $cbphoto->manager_link_callbacks[ $callback_id ] ) ) {
                    $cbphoto->manager_link_callbacks[$callback_id]();
                }
            }
        }
    }
}

/**
 * Fetchs photo_plupload template
 * @return STRING
 */
function load_photo_plupload_block() {
    $template = fetch(MODULES_DIR.'/uploader/photo_pluploader.html',false);
    return $template;
}

/**
 * Function will be all embed types registered for photos
 * 
 * @global OBJECT $cbphoto
 * @return ARRAY
 */
function get_photo_embeds() {
    global $cbphoto;
    return $cbphoto->embed_types;
}

/**
 * Function will add a new embed type in photo embed_types array.
 * $name, $id and $callback is required to register successfully. $id
 * needs to be a unique one. If your embed code is not showing there
 * are two possibilities. Either $id already exists or $callback function
 * does not exist.
 * 
 * In your callback function you will format your embed code. You
 * can use few embed code templates given below, related to image:
 * %IMAGE_URL% - this will be replaced with image url path
 * %IMAGE_WIDTH% - this will be replaced with image width
 * %IMAGE_HEIGHT% - this will be replaced with image height.
 * 
 * Example of callback:
 * return "<a href='view_photo_link($photo)' target='_blank'><img src='%IMAGE_URL%' width='%IMAGE_WIDTH%' height='%IMAGE_HEIGHT%' /></a>";
 * 
 * @global OBJECT $cbphoto
 * @param STRING $name
 * @param STRING $id
 * @param STRING $callback
 * @param STRING $description
 * @return ARRAY
 */
function add_photo_embed_type( $name, $id, $callback, $description=null ) {
    global $cbphoto;
    $embeds = $cbphoto->embed_types;
    
    if ( $embeds[ $id ] || !function_exists( $callback ) ) {
        //Embed code already exists with given id or
        // callback function provided does not exist
        return;
    }
    
    $cbphoto->embed_types [$id ] = array(
        'name' => $name,
        'callback' => $callback,
        'id' => $id,
        'description' => $description
    );
    
    return $cbphoto->embed_types;
}

/**
 * Previous function has been replaced with this one.
 * ---------------------------------------------------------
 * This function will be construct any array of embed codes with
 * there repesctive embed codes. It will also check if photo details
 * are given, any embed code has registered or not and if photo
 * has allowed photo embedding and display message accordingly.
 * 
 * This will also add embed code in photo_embed_templates array.
 * These will be used in javascript to change to image url, width 
 * and height.
 * 
 * @global OBJECT $cbphoto
 * @param ARRAY $params
 * @return ARRAY
 */
function photo_embed_codes( $params=null ) {
    global $cbphoto;
    if ( is_array( $params ) ) {
        $photo = $params['details'];
    } else {
        $photo = $params;
    }
    $params['display_error'] = $params['display_error'] ? $params['display_error'] : 'yes';
    
    $embeds = get_photo_embeds();
    
    if ( empty($photo) ) {
        if ( $params['display_error'] == 'yes' ) {
            echo '<p>'.lang("Unable to create embed codes. Photo details not available").'</p>';
        }
        return;
    } else if ( empty( $embeds ) ) {
        if ( $params['display_error'] == 'yes' ) {
            echo '<p>'.lang("Unable to create embed codes. No photo embed code registered").'</p>';
        }
        return;
    } else if ( $photo['allow_embedding'] == 'no' ) {
        if ( $params['display_error'] == 'yes' ) {
            echo '<p>'.lang("Photo embedding is disabled by user.").'</p>';
        }
        return;
    } else {
        $remove = $params['remove'];
        if ( $remove ) {
            $remove = explode( ',', $remove ); // Separate them using ','
            $remove = array_map( 'trim', $remove ); // Remove any space around string
            foreach( $remove as $R ) {
                if ( $embeds[ $R ] ) {
                    unset( $embeds[ $R ] );
                }
            }
        }
        
        foreach ( $embeds as $id => $embed ) {
            $code = $embed['callback']( $photo );
            $embeds[ $id ]['code'] = $code;
            // Setting up default size.
            $size = $params['size'] ? $params['size'] : 'm';
            $pd = json_decode( $photo['photo_details'], true );
            $size_details = $pd[ $size ];
            $embeds[ $id ]['code'] = str_replace( array('%IMAGE_URL%','%IMAGE_WIDTH%','%IMAGE_HEIGHT%'), array( $cbphoto->get_image_file( $photo, $size ), $size_details['width'], $size_details['height'] ), $code );
            $cbphoto->photo_embed_templates[ $embed['id'] ] = $code;
        }
               
        return $embeds;
    }
}

/**
 * This will list avaiable photo sizes. By default following are created:
 * Thumb | code - t
 * Medium | code - m
 * Large | code - l
 * Original | code - p
 * ---------------------------------
 * If from plugin or @startup.php more thumbs are added and current
 * photo has custom thumbs, those dimensions will also be displayed here.
 * 
 * If photo has custom thumb but it's has been removed from thumb_dimensions
 * array ( e.g plugin is deactivate ), only it's dimensions will be displayed.
 * 
 * @global OBJECT $cbphoto
 * @param ARRAY $photo
 * @return ARRAY
 */
function display_photo_embed_sizes( $photo ) {
    global $cbphoto;
    $dimensions = $cbphoto->thumb_dimensions;
    $pd = json_decode($photo['photo_details'],true);
    
    foreach ( $pd as $code => $size ) {
        $sort[ $code ] = $size['width'];
    }
    
    asort( $sort, SORT_NUMERIC );
    
    foreach( $sort as $code => $width ) {
        $_pd = $pd[ $code ];
        $output .= '<li data-url="'.$cbphoto->get_image_file($photo, $code).'" data-code="'.$code.'" data-width="'.$_pd['width'].'" data-height="'.$_pd['height'].'" data-size="'.$_pd['size']['bytes'].'" class="toggle-photo-size"><a href="javascript:void(0)">'.$_pd['width'].' x '.$_pd['height'].'</a></li>';
    }
        
    return $output;
}

/**
 * HTML Embed code callback
 * @param ARRAY $photo
 * @return string
 */
function photo_html_code( $photo ){
    $output = "<a href='".  view_photo_link( $photo ) ."' target='_blank'><img src='%IMAGE_URL%' border='0' width='%IMAGE_WIDTH%' height='%IMAGE_HEIGHT%' alt='".$photo['photo_title']." by ".$photo['username']." on ".TITLE."' /></a>";
    return $output;
}

/**
 * BB Embed code callback
 * @param ARRAY $photo
 * @return string
 */
function photo_bb_code( $photo ) {
    return "[IMG=%IMAGE_URL%]";
}

/**
 * Email Embed code callback
 * @param ARRAY $photo
 * @return string
 */
function photo_email_code( $photo ) {
    return view_photo_link( $photo );
}

/**
 * BB Linked Embed code callback
 * @param ARRAY $photo
 * @return string
 */
function photo_bb_code_linked( $photo ) {
    return "[URL=".  view_photo_link( $photo ) ."][IMG=%IMAGE_URL%][/URL]";
}

/**
 * BB Alt Embed code callback
 * @param ARRAY $photo
 * @return string
 */
function photo_bb_code_alt ( $photo ) {
    return "[IMG]%IMAGE_URL%[/IMG]";
}

/**
 * BB Alt Linked Embed code callback
 * @param ARRAY $photo
 * @return string
 */
function photo_bb_code_alt_linked ( $photo ) {
    return "[URL=".  view_photo_link( $photo ). "][IMG]%IMAGE_URL%[/IMG][/URL]";
}

function photo_direct_link( $photo ) {
    return '%IMAGE_URL%';
}

/**
 * Gets photo manager orders
 * 
 * @return ARRAY
 */
function get_photo_manager_orders() {
    return object_manager_orders('photo');
}

/**
 * Adds photo manager order
 * 
 * @param STRING $title Title of order
 * @param STRING $order mySQL order
 * @param STRING $id Optional
 * @return MIX
 */
function add_photo_manager_order( $title, $order, $id =  false ) {
    return add_object_manager_order( $title, $order, 'photo', $id );
}

/**
 * Displays photo manager order
 * 
 * @param STRING $display
 * @return MIX
 */
function display_photo_manger_orders( $display='unselected' ) {
    return display_manager_orders('photo',$display);
}

/**
 * Displays current photo manager order
 * 
 * @return STRING
 */
function current_photo_order () {
    return current_object_order('photo');
}


/**
 * This function makes sure that no photo file exists. Sometimes
 * new thumb dimensions are added using function add_custom_photo_size().
 * Newly uploaded photo will have this dimension, but previous one will one.
 * This checks if provided size/code does not exist in cb default thumb dimensions
 * we'll get url of 'm' ( a default thumb dimension ) size. If exists, we'll show that else
 * default thumb will be displayed.
 * 
 * @param string $photo_url
 * @param array $params
 * @return string
 */
function _recheck_photo_code( $params ) {
    global $cbphoto;
    $sizes = $params['size'] ? $params['size'] : ( $params['code'] ? $params['code'] : 't' );
    $sizes = explode( ",",$sizes );
    $sizes = array_map( "trim", $sizes );
    
    $cbsizes = $cbphoto->default_thumb_dimensions;

    foreach ( $sizes as $size ) {
        if ( !in_array( $size, $cbsizes ) ) {
            $thumb = get_image_url ( $params['photo'], 'm' );
        }
    }

    if ( $thumb ) {
        return array ( $thumb );
    } else {
        return false;
    }
}

/**
 * Updated version of getting photo file. In this function
 * we dont use glob() function to first get all thumbs and
 * then extract one thumb, instead now we only construct
 * the url of given code/size, checks if file_exists, if yes return
 * thumb else return default thumb.
 */
function get_image_file ( $params ) {
    global $cbphoto;
    $details = $params['details'];
    $output = $params['output'];
    $sizes = $params['size'] ? $params['size'] : ( $params['code'] ? $params['code'] : 't' );
        
    if ( empty( $details) ) {
        return $cbphoto->default_thumb( $size, $output );
    } else {
        // Call custom functions
         if ( count( $Cbucket->custom_get_photo_funcs ) > 0 ) {
            foreach ( $Cbucket->custom_get_photo_funcs as $funcs ) {
                if ( function_exists( $funcs ) ) {
                    $func_returned = $funcs( $params );
                    if ( $func_returned ) {
                        return $func_returned;
                    }
                }
            }
        }
        
        // Make sure photo exists
        if ( !is_array( $details ) ) {
            $photo = $cbphoto->get_photo( $details );
        } else {
            $photo = $details;
        }
        
        if ( empty( $photo['photo_id'] ) or empty( $photo['photo_key'] ) ) {
            return $cbphoto->default_thumb( $size, $output );
        } else {
            if ( empty( $photo['filename'] ) or empty( $photo['ext'] ) ) {
                return $cbphoto->default_thumb( $size, $output );
            } else {
                
                $params['photo'] = $photo;
                
                if ( $details['is_mature'] == 'yes' && !userid() ) {
				return get_mature_thumb( $details, $size, $output );
			}
                      
                $dir = PHOTOS_DIR;
                $file_directory = get_photo_date_folder( $photo );
                $with_path = $params['with_path'] = ( $params['with_path'] === false ) ? false : true;
                
                if ( $file_directory ) {
                    $file_directory .= '/';
                }
                
                $path = $dir.'/'.$file_directory;
                $file_name = $photo['filename'].'%s.'.$photo['ext'];
                                
                $sizes = explode( ",", $sizes );
                if ( $sizes[0] == 'all' and count( $sizes ) == 1 ) {
                    $sizes = get_photo_dimensions( true );
                    $sizes = array_keys( $sizes );
                }
                $sizes = array_map( "trim", $sizes );
                
                if ( phpversion < '5.2.0' ) {
                    global $json; $js = $json;
                }

                if ( !empty( $js ) ) {
                    $image_details = $js->json_decode( $photo['photo_details'], true );
                } else {
                    $image_details = json_decode( $photo['photo_details'], true );
                }
                
                foreach ( $sizes as $size ) {
                    $filename = sprintf( $file_name, "_".$size );
                    $full_path = $path.$filename;
                    
                    {
                        if ( file_exists( $full_path ) ) {
                            if ( $with_path ) {
                                $thumbs[] = PHOTOS_URL.'/'.$file_directory.$filename;
                            } else {
                                $thumbs[] = $filename;
                            }
                        }
                    }
                }
                
                if ( $params['with_orig'] === true ) {
                    if ( $with_path ) {
                        $thumbs[] = PHOTOS_URL.'/'.$file_directory.sprintf( $file_name, "");
                    } else {
                        $thumbs[] = sprintf( $file_name, "");
                    }
                }
                //pr ( $thumbs, true );
                if ( !$thumbs or count($thumbs) <= 0 ) {
                    $thumbs = _recheck_photo_code( $params );
                    if ( !$thumbs or !is_array( $thumbs ) ) {
                        return $cbphoto->default_thumb( $size, $output );  
                    }
                }
                
                if ( empty( $params['output']) or $params['output'] == 'non_html' ) {
                    if ( $params['assign'] ) {
                        assign( $params['assign'], $thumbs );
                    } else if ( $params['multi'] ) {
                        return $thumbs;
                    } else {
                        return $thumbs[0];
                    }
                } else {
                    $attrs = array();
                    $src = $thumbs[0];
                    $size = $cbphoto->get_image_type( $src );
                    if ( !file_exists( str_replace( PHOTOS_URL, PHOTOS_DIR, $src ) ) ) {
                        $src = $cbphoto->default_thumb( $size );
                    }

                    if ( empty( $image_details) or !isset( $image_details[$size] ) ) {
                        $dem = getimagesize( str_replace( PHOTOS_URL, PHOTOS_DIR, $src ) );
                        $width = $dem[0];
                        $height = $dem[1];
                        /* UPDATEING IMAGE DETAILS */
                        $cbphoto->update_image_details( $photo );
                    } else {
                        $width = $image_details[$size]['width'];
                        $height = $image_details[$size]['height'];
                    }
                    
                    if ( ( $params['width'] and is_numeric( $params['width'] ) ) and ( $params['height'] and is_numeric( $height  ) ) ) {
                        $width = $params['width'];
                        $height = $params['height'];
                    } else if ( ( $params['width'] and is_numeric( $params['width'] ) ) ) {
                        $height = round( $params['width'] / $width * $height );
                        $width = $params['width'];
                    } else if ( ( $params['height'] and is_numeric( $height  ) ) ) {
                        $width = round( $params['height'] * $width / $height );
                        $height = $p['height'];
                    }
                    $attrs['width'] = $width;
                    $attrs['height'] = $height;
                    
                    if ( USE_PHOTO_TAGGING && THIS_PAGE == 'view_item' ) {
                        $id = $cbphoto->get_selector_id()."_".$photo['photo_id'];
                    } else {
                        if ( $params['id'] ) {
                            $id = mysql_clean( $params['id'] )."_".$photo['photo_id'];
                        } else {
                            $id = $cbphoto->get_selector_id()."_".$photo['photo_id'];
                        }                               
                    }
                    $attrs['id'] = $id;
                    
                    if ( $params['class'] ) {
                        $attrs['class'] = mysql_clean( $params['class'] );
                    }
                    
                    if ( $params['align'] ) {
                        $attrs['align'] = mysql_clean( $params['align'] );
                    }
                    
                    $title = $params['title'] ? $params['title'] : $photo['title'];
                    $attrs['title'] = mysql_clean ($title );
                    
                    $alt = $params['alt'] ? $params['alt'] : TITLE.' - '.$photo['title'];
                    $attrs['alt'] = mysql_clean( $alt );
                    
                    $anchor_p = array( "place" => 'photo_thumb', "data" => $photo );
                    $params['extra'] = ANCHOR( $anchor_p );
                    
                    if ( $params['style'] ) {
                        $attrs['style'] = ( $params['style'] );
                    }
                    
                    if ( $params['extra'] ) {
                        $attrs['extra'] = ( $params['extra'] );
                    }
                    
                    $image = cb_output_img_tag( $src, $attrs );
                    
                    if ( $params['assign'] ) {
                        assign( $params['assign'], $image );
                    } else {
                        return $image;
                    }
                }
            }
        }
    }
}

/**
 * Function returns the url of photo file
 * @param Array|Int $photo
 * @param String $size
 * @param Bool $multi
 * @param String $assign
 * @param Bool $with_path
 * @param Bool $with_orig
 * @return String
 */
function get_image_url( $photo, $size='t', $multi = false, $assign = null, $with_path = true, $with_orig = false ) {
    $params = array( "details" => $photo, "size" => $size, "multi" => $multi, "assign" => $assign, "with_path" => $with_path, "with_orig" => $with_orig );
    return get_image_file( $params );
}
?>