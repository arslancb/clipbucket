<?php

/*
Plugin Name: Comment Censor
Description: This plugin will remove bullshit words from ClipBucket
Author: Arslan Hassan
Author Website: http://clip-bucket.com/
ClipBucket Version: 2
Version: 1.0
Website: http://labguru.com/
Plugin Type: global
*/
													   
													   

if(!function_exists('censor_words'))
{
	function censor_words($comment,$show_something=null)
	{
		
	 $words = array
	 (
	  '/shit/','/sex/','/fuck/','/asshole/'
	  );
	 
	 //Partial Hide The Word
	 foreach($words as $word)
	 {
		 $word = preg_replace('/\//','',$word);
		 $word_len = strlen($word);
		 $new_word = substr($word,0,1);
		 for($i=0;$i<$word_len-2;$i++)
		 $new_word .= '*';
		 $new_word .= substr($word,$word_len-1,1);
		 $word_censoredp[] = $new_word;
	 }
	 $coded_comment = preg_replace($words,$word_censoredp,$comment);
	 return $coded_comment.$show_something;
	}
}

register_action('censor_words','comment');

?>