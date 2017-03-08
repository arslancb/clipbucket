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

## Action "add video"
In the video manager, a new button "add video" near "Video Manager" title is added. This button allow an administrator to add an empty video data (no video file is included)

This action is only possible if the user has the "Allow video donwload" permission defined in the "User Levels" administration page.

## Action "duplicate video"
This new action is added under the "action" button of each video. executing this action will create a copy of the selected video informations but without any video file linked. The goal is to add multiple video of the same event without being necessary to  edit all identical informations for each video added.   

This action is only possible if the user has the "Allow video donwload" permission defined in the "User Levels" administration page.

## Action "Link video"
This action is used to link a selected video to a pending vido file stored into  /files/pending_videos subfolder. We suppose here that an external video encoder code will drop the result of it's job into a folder inside pending_videos folder. Ths name of the folder must be identical ti one of the jobset value into the job table created by this plugin. The action "Link Video" will search in th pendiging_videos folder. If there is some subfolder we try to find if there is some job in the "job" table that have a identical jobset value and the idvideo value is NULL then the folder corresponds to a pending video. We then display it and the administator can select it and link it to the specified video. Doing that the idjob in the "job" table will be set to video id and the name of the video stored into that folder will be stored as the original video file name into the video table in a field called "original_videofilename"

This action does nothing more. This mean that before linking it's necessary that an external tool had encoded at most one of the video output format, created that sufolder and put the video encoded into that subfolder. This tool had to insert right informations into the "job" table. This tool may be on the same server or not.
And a second tool is necessary (run in a cron table) to move the encoded linked file into the folder corresponding to the video data linked to that video file.  
