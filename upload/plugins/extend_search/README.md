# Plugin ClipBucket - Extended Search
This plugin is used to add new functionalities to the CB search engine. 2 set of classes are included in the plugin :
The first one (in the extend_xxx.php files) are used to extend the cbvideo and cbsearch classes in order to be able to search in other fields than the predefined one (ie: in vides description). but ilt alos add the possibility to search into a table joined to the video table. (ie : search videos for a specific speaker by it's name, this is usesd in the "Video Speaker" plugin)
The second one (in multi_xxx.php files) add the possibility to aggregate search engines of many classes associated to the video table. So that you can either search in the video table but also in the speaker one using the same search form field. You don't have to specify what kind of data you want to search. This group of tools become necessary when adding some plugin that extend video. It could be generated to other core table (ie: photo) but as i'm just interrested in video plugins for the moment, i only have made it for videos.      

# Install
To activate this plugin, go to the plugin manager and add click on the "install button" on the "Extended Search" plugin. It also add some data in the config table.

# Uninstall
Uninstalling the plugin in the plugin manager will remove the database table and clean up the config table.
	
# Use
The plugin has two parts but no user interface. The first part is the extend video search and the second the aggreation search.

## Use the extend video search :
If you are writing a new video plugin that had fields which need to be searched (ie Video Speaker) you cann use this plugin.
	
	Add an init_search function into your plugin. 
	In this function you need instantiate a ExtendSearch object.
	Then set the values for some fields and particularly the 3 next arrays : reqTbls,reqTblsJoin and columns
	
	reqTbls specify wich tables are concerned by the search : 
	ie: 
		$myExtendSearch->reqTbls=array('video','users', 'speaker', 'speakerfunction', 'video_speaker');
	
	reqTblsJoin specyfy which table is connected to the other : 
	ie :
		$myExtendSearch->reqTblsJoin=array(array('table1'=>'users', 'field1'=>'userid','table2'=>'video','field2'=>'userid'),
			array('table1'=>'speaker', 'field1'=>'id','table2'=>'speakerfunction','field2'=>'speaker_id'),
			array('table1'=>'speakerfunction', 'field1'=>'id','table2'=>'video_speaker','field2'=>'speakerfunction_id'),
			array('table1'=>'video_speaker', 'field1'=>'video_id','table2'=>'video','field2'=>'videoid')
		);
	
	columns specify the fields to search, the SQL search type (==, <>, < > like...) and the boolean operation regarding to te others fields ('OR', 'AND') :
	ie :
		$myExtendSearch->columns =array(
			array('table'=>'speaker', 'field'=>'firstname','type'=>'LIKE','var'=>'%{KEY}%','op'=>'OR'),
			array('table'=>'speaker', 'field'=>'lastname','type'=>'LIKE','var'=>'%{KEY}%','op'=>'OR'),
			array('table'=>'speaker', 'field'=>'slug','type'=>'LIKE','var'=>'%{KEY}%','op'=>'OR'),
			array('field'=>'broadcast','type'=>'!=','var'=>'unlisted','op'=>'AND','value'=>'static'),
			array('field'=>'status','type'=>'=','var'=>'Successful','op'=>'AND','value'=>'static')
		);

You need to declare your new search tool :
ie : $Cbucket->search_types['speaker'] = "speakerquery";
In this example speakerquery is an instance of the class that implemented the init_search above.

Finally tou need to add in the installation of your plugin a new entry into the config database table corresponding to your search domain. The name must be the name of your declared search type with "Section" concatenated at the end.
ie : 	$db->insert(tbl("config"),array("name","value"),array("speakerSection","yes"));	

After that your search engine should be ok.
For a full functionnal example see the Video Speaker plugin

## Use the Aggregation search tool :
In your plugin youolny have to declare your previously class, with the init_search as described above into the $multicategories global variable.
ie :

	global $multicategories;
	$multicategories->addSearchObject("speakerquery");

Now in the header search field of the default CB front office, you can select "multisearch" and this particular search engine have the ability to retrieve data from all connected search engines.
ie : In the example with Video Speaker plugin the search engine can get data from, video title, tags, descriptions, speaker first name, speaker last name, speaker slug... 

