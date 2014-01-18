<?php /* Smarty version 2.6.18, created on 2014-01-17 12:40:27
         compiled from /var/www/clipbucket/styles/cbv2new/layout/blocks/category_list.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'lang', '/var/www/clipbucket/styles/cbv2new/layout/blocks/category_list.html', 2, false),array('function', 'cbCategories', '/var/www/clipbucket/styles/cbv2new/layout/blocks/category_list.html', 5, false),)), $this); ?>
<div class="category_list">
<span class="cat_heading"><?php echo smarty_lang(array('code' => 'categories'), $this);?>
</span>
<div class="categories">
    <ul>
    	<?php echo getSmartyCategoryList(array('type' => $this->_tpl_vars['type'],'echo' => 'TRUE','list_style' => 'collapsed','output' => 'list','with_all' => 'TRUE'), $this);?>

    </ul>
</div>
</div>