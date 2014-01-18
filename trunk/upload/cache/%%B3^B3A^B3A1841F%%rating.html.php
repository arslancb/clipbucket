<?php /* Smarty version 2.6.18, created on 2014-01-17 12:33:36
         compiled from /var/www/clipbucket/styles/cbv2new/layout/blocks/rating.html */ ?>
<!-- <div id="rating_container">
<ul class="big_stars <?php echo $this->_tpl_vars['disable']; ?>
" id="rating_container_<?php echo $this->_tpl_vars['id']; ?>
">
<li class="current-rating" style="width:<?php echo $this->_tpl_vars['perc']; ?>
"></li>
<li>

<a href="javascript:void(0)" class="one-star" <?php if (! $this->_tpl_vars['disable']): ?>onclick="rate('<?php echo $this->_tpl_vars['id']; ?>
','1','<?php echo $this->_tpl_vars['type']; ?>
')"<?php endif; ?> onMouseOver="rating_over('Poor','<?php echo $this->_tpl_vars['disable']; ?>
')" onmouseout="rating_out('Rate this <?php echo $this->_tpl_vars['type']; ?>
','<?php echo $this->_tpl_vars['disable']; ?>
')"> </a>
</li>
<li>
<a href="javascript:void(0)" class="two-stars" <?php if (! $this->_tpl_vars['disable']): ?>onclick="rate('<?php echo $this->_tpl_vars['id']; ?>
','2','<?php echo $this->_tpl_vars['type']; ?>
')"<?php endif; ?> onMouseOver="rating_over('Bad','<?php echo $this->_tpl_vars['disable']; ?>
')" onmouseout="rating_out('Rate this <?php echo $this->_tpl_vars['type']; ?>
','<?php echo $this->_tpl_vars['disable']; ?>
')"> </a>
</li>
<li>
<a href="javascript:void(0)" class="three-stars" <?php if (! $this->_tpl_vars['disable']): ?>onclick="rate('<?php echo $this->_tpl_vars['id']; ?>
','3','<?php echo $this->_tpl_vars['type']; ?>
')"<?php endif; ?> onMouseOver="rating_over('Average','<?php echo $this->_tpl_vars['disable']; ?>
')" onmouseout="rating_out('Rate this <?php echo $this->_tpl_vars['type']; ?>
','<?php echo $this->_tpl_vars['disable']; ?>
')"> </a>
</li>
<li>
<a href="javascript:void(0)" class="four-stars" <?php if (! $this->_tpl_vars['disable']): ?>onclick="rate('<?php echo $this->_tpl_vars['id']; ?>
','4','<?php echo $this->_tpl_vars['type']; ?>
')"<?php endif; ?> onMouseOver="rating_over('Good','<?php echo $this->_tpl_vars['disable']; ?>
')" onmouseout="rating_out('Rate this <?php echo $this->_tpl_vars['type']; ?>
','<?php echo $this->_tpl_vars['disable']; ?>
')"> </a>
</li>
<li>
<a href="javascript:void(0)" class="five-stars" <?php if (! $this->_tpl_vars['disable']): ?>onclick="rate('<?php echo $this->_tpl_vars['id']; ?>
','5','<?php echo $this->_tpl_vars['type']; ?>
')"<?php endif; ?> onMouseOver="rating_over('Excellent','<?php echo $this->_tpl_vars['disable']; ?>
')" onmouseout="rating_out('Rate this <?php echo $this->_tpl_vars['type']; ?>
','<?php echo $this->_tpl_vars['disable']; ?>
')"> </a>
</li>
</ul>
<div id="rating_result_container" align="center"><?php if ($this->_tpl_vars['rating_msg']): ?><?php echo $this->_tpl_vars['rating_msg']; ?>
<?php else: ?>Rate this <?php echo $this->_tpl_vars['type']; ?>
<?php endif; ?></div>
</div> -->

<div id="rating_container">
<div class="newRating clearfix">
    <div<?php if (! $this->_tpl_vars['disable']): ?> onclick="rate('<?php echo $this->_tpl_vars['id']; ?>
','5','<?php echo $this->_tpl_vars['type']; ?>
')"<?php endif; ?> onMouseOver="rating_over('Like this <?php echo $this->_tpl_vars['type']; ?>
','<?php echo $this->_tpl_vars['disable']; ?>
')" onmouseout="rating_out('<?php echo $this->_tpl_vars['likes']; ?>
 Likes, <?php echo $this->_tpl_vars['dislikes']; ?>
 Dislikes','<?php echo $this->_tpl_vars['disable']; ?>
')" class="newLike">Like</div>
    <div style="width:88px; float:left; height:14px; margin-top:8px">
    	<div class="greenBar" style="width:<?php echo $this->_tpl_vars['perc']; ?>
"></div>
        <div class="redBar" style="width:<?php echo $this->_tpl_vars['disperc']; ?>
"></div>
    </div>
    <div<?php if (! $this->_tpl_vars['disable']): ?> onclick="rate('<?php echo $this->_tpl_vars['id']; ?>
','1','<?php echo $this->_tpl_vars['type']; ?>
')"<?php endif; ?> onMouseOver="rating_over('Dislike this <?php echo $this->_tpl_vars['type']; ?>
','<?php echo $this->_tpl_vars['disable']; ?>
')" onmouseout="rating_out('<?php echo $this->_tpl_vars['likes']; ?>
 Likes, <?php echo $this->_tpl_vars['dislikes']; ?>
 Dislikes','<?php echo $this->_tpl_vars['disable']; ?>
')" class="newDislike">Dislike</div>
</div>
<div id="rating_result_container" style="margin-top:4px;" align="center"><?php if ($this->_tpl_vars['rating_msg']): ?><?php echo $this->_tpl_vars['rating_msg']; ?>
<?php else: ?><?php echo $this->_tpl_vars['likes']; ?>
 Likes, <?php echo $this->_tpl_vars['dislikes']; ?>
 Dislikes<?php endif; ?></div>
</div>