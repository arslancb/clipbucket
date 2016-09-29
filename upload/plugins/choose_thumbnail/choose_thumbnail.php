<?php
/*
Plugin Name: Choose Thumbnail
Description: Add a button to choose the thumbnail from a time in the video
Author: Adrien Ponchelet
Author Website: https://www.u-picardie.fr
ClipBucket Version: 2.8.1 rc1
Version: 1.0
*/


//  define("SITE_MODE",'/admin_area');

if (!function_exists('choose_thumb_in_video')){
	function choose_thumb_in_video(){
//		echo '<div style="background-color:#F7F7F7; border:1px solid #999; padding:5px; margin:5px; text-align:center">';
//		echo "My Test Announcement Goes here...";
//		echo '</div>';
    }

    $Cbucket->add_admin_header(PLUG_DIR . '/choose_thumbnail/admin/header.html');	// *** Ajoute le JS pour récupérer le temps de lecture

}

	$btn = '<div class="row">';
	$btn .= '<a class="btn btn-primary btn-sm dropdown-toggle pull-right has-spinner" style="margin:30px;" onclick="getTimeFromVideo(\'cb_video_js\');" title="Génére une vignette à partir de l\'emplacement lu dans la vidéo.">Générer la vignette</a>';
	$btn .= '<input type="hidden" name="time_for_thumbnail" id="time_for_thumbnail" value="">';
	$btn .= '<input type="hidden" name="video_id_for_thumnail" id="video_id_for_thumnail" value="'.$_GET['video'].'">';
	$btn .= '<div id="choose_thumbnail_info"></div>';
	$btn .= '</div>';

	Assign('choosethumb', $btn);

    // Load this on every page
//    register_anchor_function('choose_thumb_in_video', 'after_compose_box');		// Ne fonctionne pas dans l'admin
?>