<?php
##################################################################
###                                                            ###
##                                                              ##
#       this class was writtend by Arslan Hassan                 #
#       it will be used to create new language pack              #
#       add, edit or delete phrases of existing language packs   #
#       easy to manipulate and easy to use                       #
#       made for ClipBucket                                      #
##                                                              ##
###                                                            ###
##################################################################

#
# @ Author :Arslan Hassan
# @ software : ClipBucket
# @ license : cbla
# @ file : language.class.php
#



class language
{
	
	var $lang = 'en';
	var $lang_iso = 'en';
	
	/** 
	 * __Constructor
	 */
	function language()
	{
		$this->lang = $this->lang_iso = 'en';
	}
	 
	 
	/**
	 * Function used add new phrase
	 */
	function add_phrase($name,$text,$lang_code='en')
	{
		global $db;
		//First checking if phrase already exists or not
		if(empty($name))
			e("Phrase code was empty");
		elseif(empty($text))
			e("Phrase text was empty");
		elseif(!$this->lang_exists($lang_code))
			e("Language does not exist");
		elseif(!$this->get_phrase($name,$lang_code))
		{
			e("'$name' has been added",m);
			$db->insert("phrases",array('lang_iso','varname','text'),array($lang_code,$name,$text));
		}else{
			e("'$name' alread exists",m);
		}
	}
	 
	/**
	 * Function used to get language phrase
	 * @param STRING name
	 * @param STRING lang_code
	 */
	function get_phrase($name,$lang_code=NULL)
	{
		global $db;
		
		if($lang_code!='')
		{
			$lang_query = "AND lang_iso = '".mysql_clean($lang_code)."'";
		}
		
		$results = $db->select("phrases",'*'," id = '".mysql_clean($name)."' OR varname = '".mysql_clean($name)."' $lang_query ");
		if($db->num_rows > 0 )
			return $results[0];
		else
			return false;
	}
	
	
	/**
	 * Function used to modify phrase
	 */
	function update_phrase($id,$text,$lang_code='en')
	{
		global $db;
		//First checking if phrase already exists or not
		if($this->get_phrase($id,$lang_code))
			$db->update("phrases",array('text'),array(mysql_real_escape_string($text))," id = '".mysql_real_escape_string($id)."' ");
	}
	
	/**
	 * Function used to get all phrases of particular language
	 */
	function get_phrases($lang=NULL,$fields="varname,text",$limit=NULL,$extra_param=NULL)
	{
		global $db;
		$lang_details = $this->lang_exists($lang);
		$lang_code = $lang_details['lang_code'];
		if(empty($lang_code))
			$lang_code = $this->lang;
		return $db->select("phrases",$fields," lang_iso = '".$lang_code."' $extra_param",$limit," id ");
		
	}
	
	
	/**
	 * Function used to count phrases
	 */
	function count_phrases($lang=NULL,$extra_param=NULL)
	{
		global $db;
		$lang_details = $this->lang_exists($lang);
		$lang_code = $lang_details['lang_code'];
		if(empty($lang_code))
			$lang_code = $this->lang;
		
		$results = $db->select("phrases","COUNT(id)"," lang_iso = '".$lang_code."' $extra_param");
		if($db->num_rows>0)
			return $results[0][0];
		else
			return 0;
		
	}
	
	/**
	 * Function used to assign phrases as an array
	 */
	function lang_phrases()
	{
		$phrases = $this->get_phrases();
		foreach($phrases as $phrase)
		{
			$lang[$phrase['varname']] = $phrase['text'];
		}
		return $lang;
	}
	
	
	/**
	 * Function used to get list of languages installed
	 */
	function get_langs()
	{
		global $db;
		$results = $db->select("languages","*");
		return $results;
	}
	
	
	
	/**
	 * Function used to check 
	 * weather language existsor not
	 * using iso_code or its lang_id
	 */
	function lang_exists($id)
	{
		global $db;
		$results = $db->select("languages","*"," language_code ='$id' OR language_id = '$id'");
		if($db->num_rows>0)
			return $results[0];
		else
			return false;
	}
	
	
	/**
	 * Make Language Default
	 */
	function make_default($lid)
	{
		global $db;
		if($this->lang_exists($lid))
		{
			$db->update("languages",array("language_default"),array("no")," language_default='yes'");
			$db->update("languages",array("language_default"),array("yes")," language_id='$lid'");
		}
	}
	
	/**
	 * Funcion used to get language detilas
	 */
	function get_lang($id)
	{
		return $this->lang_exists($id);
	}
}

?>