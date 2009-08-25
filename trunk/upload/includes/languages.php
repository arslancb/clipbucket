<?php

if(!function_exists('scandir')) {
   function scandir($dir, $sortorder = 0) {
       if(is_dir($dir))        {
           $dirlist = opendir($dir);
           while( ($file = readdir($dirlist)) !== false) {
               if(!is_dir($file)) {
                   $files[] = $file;
               }
           }
           ($sortorder == 0) ? asort($files) : rsort($files);
           return $files;
       } else {
       return FALSE;
       break;
       }
   }
}

$dir    = dirname(__FILE__).'/../lang';
$langs = scandir($dir);

$langArray   = 
array(
'cn_ZH'	=>	'中文 (简体)',
'de'	=>	'Deutsch',
'en'	=>	'English (US)',
'es'	=>	'Español',
'fr'    =>  'Français',
'it'	=>	'Italiano',
'ja'	=>	'日本語',
'lt'	=>	'Lietuvių',
'lv'	=>	'Latviešu',
'pt_BR' =>  'Português (Brasil)',
'pt_PT'	=>	'Português (Portugal)',
'ru'	=>	'Pyccĸий',
'nl'	=>	'Dutch',
'sk'    =>  'Slovenčina',
'tr'	=>	'Türkçe',
);

$total = count($langs);
foreach($langs as $lang){
if(!empty($langArray[$lang])) $languages[$lang] =$langArray[$lang] ;
}
asort($langArray);

?>