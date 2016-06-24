<?php
require('../includes/config.inc.php');

if(isset($_POST['selectFieldValue'])) {
    $select1 = $_POST['selectFieldValue'];
    $language_detact = $_POST['langDetect'];
    language_translate($select1,$language_detact);
}

/**
	* Function use for translating phrases.
	* @param : { string } { $iso } { Language code e.g "en" }
	* 		  : {string} {$language_detact} { Language name e.g "english"}
	* @return : { file } { save file }
	* @since : 17 may, 2016 ClipBucket 2.8.1
	* @author : Sikander Ali 
	*/

function language_translate($iso,$language_detact){
    global $lang_obj,$MrsTranslator;
    $language_name = $language_detact;
    $iso_code = $iso;
    $code_exists = $lang_obj->lang_exists($iso_code);
    if($code_exists == '' ){
        $percent_content = fopen(BASEDIR."/files/percent.lang","w");
        $counter = 1;
        $fileContent = $lang_obj->getPhrasesFromPack('en');
        $totalSize = count($fileContent);
          foreach ($fileContent as $key => $value) {
            $phrase_code = $key;
            $newLang[$key] = str_replace('', '+', $value);
            $newlang[$key] = $MrsTranslator->translate($value,$iso_code,'en',"text/html");
            $percentageCal = ($counter/$totalSize)*100;
            $interger_val = intval($percentageCal); 
            file_put_contents(BASEDIR."/files/percent.lang", $interger_val."\n");
            $counter++;
         }
        $lang_obj->import_packlang($iso_code,$newlang,$language_name);
        $lang_obj->createPack($iso_code);
        fclose($percent_content);
        $id = mysql_clean($_POST['make_default']);
        $lang_obj->make_default($id);
        $response['iso-code_exits'] = $code_exists;
        $response['set'] = false;
        echo json_encode($response);
    }else{
        $message = "Code Already Exists";
        $response['message'] = $message;
        echo json_encode($response);
    }
}