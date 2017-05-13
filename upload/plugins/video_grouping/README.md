# Plugin ClipBucket - Video Grouping
This plugin is used to add informations in relation with the videos. You can create Groupig types an for each ont as many of grouping as you want. Then you can add videos in one or many video grouping. This plugin semm's like the Disciplines plugin but is more generic, flexible and extendable.

# Install
To activate this plugin, go to the plugin manager and add click on the "install button" on the "Video Grouping" plugin. 
This will create 3 tables in CB database. It will also add locales for the plugin. In this version English and French are supported.

## Requirements
This plugin is based on the following plugins :

- **Common Library** (Required) : Used in this plugin for localisation, and admin access permissions. 
- **Extended Search** (Optional) : Used to perform search with CB search CORE but into Grouping data. If not installed the search will not be active.

# Uninstall
Uninstalling the plugin in the plugin manager will remove the 3 database tables and clean up the locales and permissions.
	
# Use
The plugin has two parts : one in the site administration and the other in the front office. Nothing has been done in video editing in to the front office. 

## Use in the administration :
in the administration part go to "video Addon/Manage video grouping" to add, edit or delete grouping types and groupings.
Go to "Video manager" and in each video "Action" button you'll find a new command "Link video grouping" to connect some groupings to the selected video. 

## Use in the front office :
In the front office use a code like this  :

	{if function_exists('groupingMenuOutput')}
		{foreach from=$videoGrouping->getAllGroupingTypes(true)  item=gt}
			<li class="dropdown"> <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{$gt.name} <span class="caret"></span></a>
				<ul class="dropdown-menu" role="menu">
					{ANCHOR place="groupingMenuOutput" data=$gt.id}
				</ul>
			</li>
		{/foreach}
	{/if}

This will add entries into the main menu for each grouping type and inside of it a list of links to search engine returning the selected grouping videos. Each grouping can be selected to be or not in the menu so the list for each grouping type can easily be managed and not too long. The code above also add a link to "All..." when some groupings of this grouping type is not in the menu. This link returns a search result that need a new template layout block that display a grouping thumb. You can get an example of this layout in the uLille template. The file is uLille/layout/blocks/grouping.html. It looks like the thumb.html layout block.

