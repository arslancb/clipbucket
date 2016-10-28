# Plugin ClipBucket - Video Speaker
This plugin is used to add add informations in relation with the videos. For each video you can add which speaker is concerned by the video and what was the role of the speaker in this particular video. Each speaker may have multiple roles.

# Install
To activate this plugin, go to the plugin manager and add click on the "install button" on the "Video Speaker" plugin. 
This will create 3 tables in CB database. It will also add locales for the plugin. In this version English and French are supported.

## Requirements
This plugin is based on the following plugins :

- **Extended Search** (Optional) : Used to perform search with CB search CORE but into speakers data. If not installed the search will not be active.
- **Common Library** (Required) : Used in this plugin for localisation, and admin access permissions. 
- **php5-intl** The plugin need this php package to be installed. a function uses iconv function to convert some text in utf8.

# Uninstall
Uninstalling the plugin in the plugin manager will remove the 3 database tables and clean up the locales and permissions.
	
# Use
The plugin has two parts : one ine the site administration and the other in the front office. Nothing has been done in video editing in to the front office.

## Use in the administration :
in the administration part go to "video Addon/Speaker manager" to add, edit or delete speakers and speaker's roles.
Go to "Video manager" and in each video "Action" button you'll find a new command "Link speakers" to connect speakers to the selected video. 

## Use in the front office :
In the front office use the following anchor to display a formatted list of all speakers :

	{ANCHOR place="speaker_list" data=$video}

This will return something like :

	\<li>\<a href="$url">$firstname $lastname\</a>\<span>$description\</span>\</li>
	\<li>\<a href="$url">$firstname $lastname\</a>\<span>$description\</span>\</li>
	...
	\<li>\<a href="$url">$firstname $lastname\</a>\<span>$description\</span>\</li>

where :

- $url is a link to the search engine that can retrieve all videos from this particular speaker.
- $firstname, $lastname are the speaker first and last name.
- $description is the role of the speaker in this video 