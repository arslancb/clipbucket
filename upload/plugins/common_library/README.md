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
 
