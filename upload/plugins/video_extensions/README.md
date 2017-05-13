# Plugin ClipBucket - Video Extensions
This plugin add multiple functionalities to video managment in administration pages. You can create new empty video data, duplicate an existing one (only data and not the video files), link a video data to a jobset that determine what videos files will be encoded. The goal of this plugin es to separate creation of a video data to real encoding of video files and is uses with administration rights into CB. It'a part of a package which also contains an external encoder. Here is the workflow.

First option  :
You cas create an empty video data or duplicate one to prepare your video streaming and later when the original video file is ready you run one or many external encoders on it. The encoder developped is made to add a list of jods into CB job table before running any encoding. At that moment you can associate these jobs to the video data, waiting for the encoded fideo files. After that, when any of the encoded file is ready then the video_move_job.php file stored in this plugin can be run as cron action and download and push the encoded file into the righ folder using the right name

Second option :
You have the original video file and push it to the eoncoder server. When doing that it will create the corresponding jobs and run it. You can come later and create the video data in CB (an empty video data or a duplacated one) and link it to the already encoded videos files. When associated the encoded videos will be pushed to the right folder in CB.
 
No matter the order you whant to work but at the end you have to manualy activate the video in order to set it ready to play. 

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
This action is used to link a selected video to a job corresponding to a pending video file stored somewhere in an encoder server. The action "Link Video" will search in the job table for jobs where no video is still associated. We then display it and the administator can select it and link it to the specified video. Doing that the idjob in the "job" table will be set to video id and the name of the video stored into that folder will be stored as the original video file name into the video table in a field called "original_videofilename"

This action does nothing more. In particular it doesn't encode any video. This job is made externally on the encoder server which can be local or external. And a second tool is necessary (run in a cron table) to move the encoded linked file into the folder corresponding to the video data linked to that video file. This tool is video_move_job.php.  
