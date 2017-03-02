# Plugin ClipBucket - Video Extensions
This plugin add multiple functionalities to video managment in administration pages. You can create new empty video, duplicate an existing video (only data and not the video files), link a video to external encoded video files dropped into a specific "pending_video" folder. 

# Install
To activate this plugin, go to the plugin manager and add click on the "install button" on the "video_extensions" plugin. 
This will create 3 tables in CB database. These tables are used to communicate with an external encoder that can be installed in one or many server. The goal of this external encoding is to reduce activity on the front clipbucket application. The installation will also add locales for the plugin. In this version English and French are supported.

## Requirements
This plugin is based on the following plugins :

- **Common Library** (Required) : Used in this plugin for localisation, and admin access permissions. 

It also manage data comming from the following plugins :

- **Video Speaker** (Optional)  
- **Documents** (Optional)  
- **External Links** (Optional)  
- **Video Grouping** (Optional)  
- **Discipline** (Optional)

If any of these plugins is installed then when duplicating a video in Video Extension plugin, it will also associate th same data coming from the other plugin to the new video.


# Uninstall
Uninstalling the plugin in the plugin manager will remove the database tables and clean up the locales and permissions.
	
# Use
The plugin is only used in the site administration.

TODO add use case...