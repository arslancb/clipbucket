# Plugin ClipBucket - Common Library
This plugin add some cross-functionalities :
- *importLanguagePack* and *removeLanguagePack* are 2 functions used to manage locales for your plugins

# Install
To activate this plugin, go to the plugin manager and add click on the "install button" on the "common Library" plugin. 

## Requirements
No other plugins is required for this plugin

# Uninstall
to uninstall the plugin use plugin manager functionality.
	
# Use


## Use of locale functionalities :
In your plugin add a folder 'lang' in wich you put some file 'lang_xx.xml' where xx is the iso language code. To get an example see the lang folder content of this plugin.  

In your 'install_pluginName.php' file add a call to *importLanguagePack* for each language you wnat your plugin to work with.

In your 'unnstall_pluginName.php' file add a call to *removeLanguagePack* for each languange you previously added to cleanup the locale database table and locale json files.

## Use of administration plugin permissions functions :
some functions in this plugin are dedicated  to allow/disallow and check permission access to a sprcific plugin admin page.
Let's showhow to use in with the exemple of a called "myplugin" plugin.
 
1. Add a call to *installPluginAdminPermissions()* function into your install_myplugin.php file. 	
	ie: ```installPluginAdminPermissions("myplugin", "My plugin administration", "Allow myplugin management");``` 
	This will add the necessaray data into the database.
	
2. Add a call to $userquery->login_check *uninstallPluginAdminPermissions()* function into your uninstall_myplugin.php file
	ie: ```uninstallPluginAdminPermissions("myplugin");``` in order to cleanup  the database after unsintalling the plugin.
	
3. Use a call to *$userquery->login_check(getStoredPluginName())* function in top of each admin page of your plugin to prevent access to non autorized user. It will show a deny acces message if user is not allowed to connect to this page.
	ie : ```if ($cbplugin->is_installed('common_library.php'))	$userquery->login_check(getStoredPluginName());```

3. Add a test for each call the menu entries addition to prevent the the menu entry from beiing completed by your plugin call if user is not autorized.
	ie in myplugin.php : ```if (!$cbplugin->is_installed('common_library.php') || $userquery->permission[getStoredPluginName("myplugin")]=='yes') { add_admin_menu("mypluginmenu","myplugintitle",'manage_myplugin.php',MYPLUGIN_BASE.'/admin'); }```
	
When all this code is in place, when you install and activate your plugin, you'll have to set permission for the plugin goinig to the "User Levels" admin page, selecting the right levels and going to the "Administration Permission". Then you can modify the administration permission for your plugin.
 