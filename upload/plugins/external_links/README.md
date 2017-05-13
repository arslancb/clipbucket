# Plugin ClipBucket - External links
This plugin is used to add informations in relation with the videos. For each video you can add some external urls associated to this video.

# Install
To activate this plugin, go to the plugin manager and add click on the "install button" on the "External links" plugin. 
This will create 2 tables in CB database. It will also add locales for the plugin. In this version English and French are supported.

## Requirements
This plugin is based on the following plugins :

- **Common Library** (Required) : Used in this plugin for localisation, and admin access permissions. 

# Uninstall
Uninstalling the plugin in the plugin manager will remove the database tables and clean up the locales and permissions.
	
# Use
The plugin has two parts : one in the site administration and the other in the front office. Nothing has been done in video editing in to the front office.

## Use in the administration :
in the administration part go to "video Addon/External links manager" to add, edit or delete Links.
Go to "Video manager" and in each video "Action" button you'll find a new command "Link external link" to connect a document to the selected video. 

## Use in the front office :

In the front office use the following anchor to display a formatted list of all disciplines that have their flag in_menu set to 1 :

	{ANCHOR place="externalLinkList" data=$video}

This will write something like :
	\<li>\<a target="_blank" href="$url">$linkname\</a>\\</li>
	\<li>\<a target="_blank" href="$url">$linkname\</a>\\</li>
	...
	\<li>\<a target="_blank" href="$url">$linkname\</a>\\</li>

where :

- $url is a link to the specified url.
- $linkname is the name of the link as it was previously edited.

