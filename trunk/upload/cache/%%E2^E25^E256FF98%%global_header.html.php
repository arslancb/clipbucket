<?php /* Smarty version 2.6.18, created on 2014-01-17 13:17:09
         compiled from /var/www/clipbucket/styles/cbv2new/layout/global_header.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', '/var/www/clipbucket/styles/cbv2new/layout/global_header.html', 6, false),array('function', 'rss_feeds', '/var/www/clipbucket/styles/cbv2new/layout/global_header.html', 12, false),array('function', 'cbtitle', '/var/www/clipbucket/styles/cbv2new/layout/global_header.html', 19, false),array('function', 'include_header', '/var/www/clipbucket/styles/cbv2new/layout/global_header.html', 47, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- ClipBucket v2 -->
<meta name="copyright" content="ClipBucket - PHPBucket ClipBucket 2007 - <?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y") : smarty_modifier_date_format($_tmp, "%Y")); ?>
" />
<meta name="author" content="Arslan Hassan - http://clip-bucket.com/arslan-hassan" />
<link rel="shortcut icon" href="<?php echo $this->_tpl_vars['baseurl']; ?>
/favicon.ico">
<link rel="icon" type="image/ico" href="<?php echo $this->_tpl_vars['baseurl']; ?>
/favicon.ico" />

<!-- RSS FEEDS -->
<?php echo rss_feeds(array('link_tag' => true), $this);?>


<meta name="keywords" content="<?php echo $this->_tpl_vars['Cbucket']->configs['keywords']; ?>
" />
<meta name="description" content="<?php echo $this->_tpl_vars['Cbucket']->configs['description']; ?>
" />
<meta name="distribution" content="global" />


<title><?php echo cbtitle(array(), $this);?>
</title>

<link href="<?php echo $this->_tpl_vars['theme']; ?>
/main.css" rel="stylesheet" type="text/css" />

<!-- Setting Template Variables -->
<?php 
	if(!$_COOKIE['current_style'])
    	$_COOKIE['current_style'] = 'grid_view';
 ?>
<!-- Setting Template Variables -->


<script type="text/javascript">

<?php if ($this->_tpl_vars['upload_form_name'] != ''): ?>
	var upload_form_name = '<?php echo $this->_tpl_vars['upload_form_name']; ?>
';
	function submit_upload_form()
	<?php echo '
	{
	'; ?>

		document.<?php echo $this->_tpl_vars['upload_form_name']; ?>
.submit();
	<?php echo '
	}
	'; ?>

<?php endif; ?>
</script>
 <script src="http://code.jquery.com/jquery-latest.min.js"></script>

<?php echo include_header(array('file' => 'global_header'), $this);?>



<?php if (@THIS_PAGE == 'private_message' && $_GET['mid']): ?>
<script type="text/javascript">
var mid  = <?php echo $_GET['mid']; ?>
;
<?php echo '
		window.onload = function() {
			$(\'#messages_container\').scrollTo( \'#message-\'+mid, 800 );
		}
'; ?>

</script>
<?php endif; ?>

<!-- Including Common Js -->

<!-- End Including Common Js -->

<?php echo '
<script type="text/javascript">
	
	$(document).ready(function() {
	'; ?>

	<?php if ($_GET['query'] == ""): ?>
	<?php echo '
		$("#searchSelectWrapper ul li a").filter(\'#videos\').parent().addClass(\'selected\');	
		$(\'#selectedText\').text($("#searchSelectWrapper ul li a").filter(\'#videos\').text());
		$(\'#searchType\').val($("#searchSelectWrapper ul li a").filter(\'#videos\').attr(\'id\'));
	'; ?>

	<?php else: ?>
	<?php echo '
	
	'; ?>
	
	<?php endif; ?>
	<?php echo '						   
	$(\'.user_login\').hide();					   
	
	$(\'#user_login\').toggle(
		function() {
			$(\'.user_login\').slideDown(\'normal\');
		},
		function() {
			$(\'.user_login\').slideUp(\'normal\');
		}
								
	);
	$("#searchSelectWrapper ul li a").bind({
		click : function(event) {
			event.preventDefault();
			$("#searchType").val($(this).attr(\'id\'));
			$(\'#selectedText\').text($(this).text());
			$(this).parent().parent().children().removeClass(\'selected\');
			$(this).parent().addClass(\'selected\')
			$(\'#searchSelectWrapper\').hide();
		}
	});
	/*$(\'.tabs li\').click(
		function() {
			$(\'.tabs li\').removeClass(\'selected\')
			$(this).addClass(\'selected\');
		}
	);*/
	
	$(\'.tabs li\').each(function(index, value){
		$(value).click(function(){
			{	
				$(this).parent().children().filter(\'.selected\').removeClass(\'selected\');
				$(this).addClass(\'selected\');
			}
		})
	});
	$(\'#lang_selector\').click(function(){
  	//do redirection
	});
	
		$(\'#lang_selector\').change(function(){
		  var optionSelectedValue = $(\'#lang_selector option:selected\').val();
		  if(optionSelectedValue)
		  {
		  		var $currentLocation = window.location.href,
						matchedElement = $currentLocation.match(/\\?/);
						
				if(matchedElement == null)
				{
					window.location = "?set_site_lang="+optionSelectedValue;	
				} else {
					window.location = window.location+"&set_site_lang="+optionSelectedValue;	
				}
				
		  }
		});
		'; ?>

		<?php if (isSectionEnabled ( 'videos' ) && @THIS_PAGE == 'index'): ?>
			get_video('recent_viewed_vids','#index_vid_container');
		<?php endif; ?>
		<?php if (isSectionEnabled ( 'photos' ) && @THIS_PAGE == 'index'): ?>
			getAjaxPhoto('last_viewed','#index_pho_container');
		<?php endif; ?>
	
	
	<?php echo '		
	});
	
	function ToggleView(obj) {
		var obj = $(obj),
			obj_id = obj.attr(\'id\'),
			parent = obj.parent().attr(\'id\'),
			target = $("#"+parent).next().attr(\'id\');
			//alert(\'#\'+parent+\' #\'+target+\' .grid_view\');
			if(obj_id == "grid") {
				$(\'#\'+parent+\' + #\'+target+\' .grid_view\').removeClass(\'list_view\');
				$.cookie("current_style","grid_view")
				$(\'.vid_sp\').hide();		
				$(\'.truncatedtitle\').hide();	
				$(\'.fulltitle\').show();	
			} else {
				$(\'#\'+parent+\' + #\'+target+\' .grid_view\').addClass(\'list_view\');
				$.cookie("current_style","list_view")
				$(\'.vid_sp\').show();
				$(\'.truncatedtitle\').show();	
				$(\'.fulltitle\').hide();			
			}
	}

</script>
'; ?>

<?php echo '
<!--[if lte IE 6]>
<style type="text/css">
.clearfix { height: 1%; }
</style>
<![endif]-->
'; ?>

<!--[if IE 7]>
<link href="<?php echo $this->_tpl_vars['theme']; ?>
/ie7.css" rel="stylesheet" type="text/css" />
<![endif]-->
<?php echo '
<script type="text/javascript">
function bookmarkSite(title, url) {
	if (window.sidebar) { // firefox
		window.sidebar.addPanel(title, url,"");
		return false;
	} else if( document.all ) { //MSIE
		window.external.AddFavorite( url, title);
		return false;
	} else if(window.opera && window.print) {
		alert("Your using Opera. Press Shift+Ctrl+D to add \'"+title+"\' in bookmarks");
		return false;
	} else if(navigator.userAgent.toLowerCase().indexOf(\'webkit\') > -1) {
		alert("Your using either Chrome or Safari. Press Ctrl+D to add \'"+title+"\' in bookmarks.")	
	} else {
		alert("Sorry, your browser doesn\'t support this");	
	}
}
</script>
'; ?>


</head>

<!-- Global Header Ends Here -->