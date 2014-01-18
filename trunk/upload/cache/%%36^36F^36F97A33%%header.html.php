<?php /* Smarty version 2.6.18, created on 2014-01-15 12:18:18
         compiled from /var/www/clipbucket/admin_area/styles/cbv2/layout/header.html */ ?>
<div class="header_grey_bar">
	<img src="<?php echo $this->_tpl_vars['imageurl']; ?>
/dot.gif" class="cbicon" /><?php echo $this->_tpl_vars['title']; ?>

    <div class="welcome" style="float:right">
    	Hello <?php echo $this->_tpl_vars['userquery']->username; ?>
 <a href="logout.php"><img src="<?php echo $this->_tpl_vars['imageurl']; ?>
/dot.gif" border="0" class="logout_button" /></a>
	</div>
</div>

<div class="header_menu clearfix">
  <ul>
    <li><a href="<?php echo $this->_tpl_vars['baseurl']; ?>
/admin_area"><?php echo $this->_tpl_vars['userquery']->permission['user_level_name']; ?>
 Home</a></li>
    <li><a href="<?php echo $this->_tpl_vars['baseurl']; ?>
">Website Home</a></li>
  </ul>
  <div class="dp_opts"><a href="javascript:void(0)" onclick="$('#dp_opts').slideToggle()">Display Options</a></div>
</div>
<div class="header_menu_after">
<div id="dp_opts">
  <form id="display_opts" name="display_opts" method="post" action="">
  	Results Per Page : 
  	<input name="admin_pages" type="text" style="width:50px" value="<?php echo $this->_tpl_vars['Cbucket']->configs['admin_pages']; ?>
"/>
    <input type="submit" name="update_dp_options" id="button" value="Update"  class="button"/>
  </form>
</div>
</div>