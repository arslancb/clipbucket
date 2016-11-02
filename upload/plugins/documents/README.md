# Plugin ClipBucket - documents
This plugin is used to add informations in relation with the videos. For each video you can add the documents associated to this video.

# Install
To activate this plugin, go to the plugin manager and add click on the "install button" on the "Documents" plugin. 
This will create 1 table in CB database. It will also add locales for the plugin. In this version English and French are supported.

## Requirements
This plugin is based on the following plugins :

- **Common Library** (Required) : Used in this plugin for localisation, and admin access permissions. 

# Uninstall
Uninstalling the plugin in the plugin manager will remove the database table and clean up the locales and permissions.
	
# Use
The plugin has two parts : one in the site administration and the other in the front office. Nothing has been done in video editing in to the front office.

## Use in the administration :
in the administration part go to "video Addon/Document manager" to add, edit or delete documents.
Go to "Video manager" and in each video "Action" button you'll find a new command "Link document" to connect a document to the selected video. 

## Use in the front office :

In the front office use the following anchor to display a formatted list of all disciplines that have their flag in_menu set to 1 :

	{ANCHOR place="externalDocumentList" data=$video}

This will write something like :
	\<li>\<a target="_blank" href="$url">$documentname\</a>\\</li>
	\<li>\<a target="_blank" href="$url">$documentname\</a>\\</li>
	...
	\<li>\<a href="$url">$disciplinename\</a>\\</li>

where :

- $url is a link to the specified document.
- $documentname is the name of the document as it was previously uploaded.

