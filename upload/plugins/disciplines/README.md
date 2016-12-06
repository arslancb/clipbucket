# Plugin ClipBucket - discipline
This plugin is used to add informations in relation with the videos. For each video you can add the discipline concerned by this video.

# Install
To activate this plugin, go to the plugin manager and add click on the "install button" on the "discipline" plugin. 
This will create 1 table in CB database. It will also add locales for the plugin. In this version English and French are supported.

## Requirements
This plugin is based on the following plugins :

- **Common Library** (Required) : Used in this plugin for localisation, and admin access permissions. 
- **Extended Search** (Optional) : Used to perform search with CB search CORE but into videos and discipline data. If not installed the search will not be active.

# Uninstall
Uninstalling the plugin in the plugin manager will remove the database table and clean up the locales and permissions.
	
# Use
The plugin has two parts : one in the site administration and the other in the front office. Nothing has been done in video editing in to the front office.

## Use in the administration :
in the administration part go to "video Addon/Discipline manager" to add, edit or delete disciplines.
Go to "Video manager" and in each video "Action" button you'll find a new command "Link discipline" to connect a discipline to the selected video. 

## Use in the front office :

### Add en menu entry in the front office
In header.html front office template page you can add   the following anchor to display a formatted list of all disciplines that have their flag in_menu set to 1 :

	{ANCHOR place="{ANCHOR place="disciplinesMenuOutput"}" data=$video}

This will write something like :

	\<li>\<a href="$url">$disciplinename\</a>\\</li>
	\<li>\<a href="$url">$disciplinename\</a>\\</li>
	...
	\<li>\<a href="$url">$disciplinename\</a>\\</li>

where :

- $url is a link to the search engine that can retrieve all videos from this particular discipline.
- $disciplinename is the name of the discipline.

### Add a link to the searche engine for a specific discipline
In the front office use the following anchor to display a link to the discipline that have their flag in_menu set to 1 :
	
	{ANCHOR place="disciplineThumbOutput" data=$video.videoid}
	
This will write  something like :

	<a href="$url" style="color:$color" ; "border-color:$color;">$disciplinename</a>

where :

- $url is a link to the search engine that can retrieve all videos from the discipline of the sprcified video id.
- $color is the color of the discipline
- $disciplinename is the name of the discipline

