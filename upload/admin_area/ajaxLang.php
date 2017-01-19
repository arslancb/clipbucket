<?php

    /**
    * File : ajaxLang
    * Description : Handles language translation in Languages area of admin section
    */
    
    require('../includes/config.inc.php');
    
    /**
    * Creates initial logs file to store translation progress related data
    * @param : { string } { $langName } { iso lang code }
    * @param : { integer } { $total } { total number of phrases that are to be translated }
    
*    * @return : { string / boolean } { full path to log file else false }
    * @since : 8th October, 2016 ClipBucket 2.8.1
    * @author : Saqib Razzaq
    */

    function initLog($langName, $total) {
        $date = date("d/M/y");
        $string = "Starting language translation \n";
        $string .= "======================================== \n";
        $string .= "Translation details \n";
        $string .= "======================================== \n";
        $string .= "Started @ ".$date."\n";
        $string .= "From English to ".$langName."\n";
        $string .= "Total phrases to translate : ".$total;
        $fullPath = FILES_DIR.'/langLog_'.$langName.'.log';
        $check = file_put_contents($fullPath, $string);
        if ($check) {
            return $fullPath;
        } else {
            return false;
        }
    }

    /**
    * Translates a given phrase and stores translation to file immediately
    * @param : { string } { $phrase } { Phrase to be translated }
    * @param : { string } { $phrase_code } { lang code of phrase to be translated }
    * @param : { string } { $to } { iso code of language that phrase is to be translated to }
    *
    * @return : { string  } { $translation } { translated string }
    * @since : 8th October, 2016 ClipBucket 2.8.1
    * @author : Saqib Razzaq
    */

    function translate_phrase($phrase, $phrase_code, $to, $total, $current) {
        global $MrsTranslator;
        /**
        * There is no point in starting with empty phrase
        * or invalid language code
        */

        if (!empty($phrase) && strlen($to) == 2)  {
            # Feed file to Bing API for translation
            $translation = $MrsTranslator->translate($phrase,$to,'en',"text/html");

            # In case, it failed in translation, lets give it another try
            if (!$translation) {
               $translation = $MrsTranslator->translate($phrase,$to,'en',"text/html"); 
            }

            if (!empty($translation)) {
                # build default translation log path
                $transFile = BASEDIR.'/includes/langs/'.$to.'.lang';
                $logFile = FILES_DIR.'/translation_'.$to.'.log';

                # check if file exists or not because handling will matter 
                if (file_exists($transFile)) {
                    
                    # read data of file to ease up appending
                    $existingData = file_get_contents($transFile);

                    # Make data readable by converting in Json
                    $data = json_decode($existingData,true);

                    # considering file is empty, lets create an array
                    if (!is_array($data)) {
                        $data = array();
                    }
                    
                    $data[$phrase_code] = $translation;
                } else {
                    $data = array();
                    $data[$phrase_code] = $translation;
                }

                if (!empty($data)) {
                    if (file_exists($logFile)) {
                        $langLogData = file_get_contents($logFile);
                    } else {
                        $langLogData = "";
                    }
                    $progressPercent = ($current/$total)*100;
                    $progressPercent = intval($progressPercent); 
                    
                    file_put_contents($logFile, $langLogData."\n"."[DONE] ".$current." / ".$total." [$progressPercent %] Translated phrase : $phrase");

                    file_put_contents(BASEDIR."/files/percent.lang", $progressPercent."\n");

                    $data = json_encode($data);
                    if (!file_put_contents($transFile, $data)) {
                        return false;
                    }
                }

                return $translation;
            } else {
                return false;
            }
        }
    }

    // When there is data in _POST, run the process
    if(isset($_POST['selectFieldValue'])) {
        #sleep(2);
        global $lang_obj;
        $output = array();
        $iso_code = $_POST['selectFieldValue'];
        $language_detact = $_POST['langDetect'];
        $phrase = $_POST['phrase'];
        $phrase_code = $_POST['phrase_code'];
        
        $totalPhrases = $_POST['totalPhrases'];
        $phraseNum = $_POST['phraseNum'];
        $translation = translate_phrase($phrase,$phrase_code,$iso_code, $totalPhrases, $phraseNum);

        if (!empty($translation)) {
            # translation was success, lets proceed
            
            $progressPercent = ($phraseNum/$totalPhrases)*100;
            $progressPercent = intval($progressPercent); 

            $output['status'] = 'success';
            $output['phrase_code'] = $phrase_code;
            $output['phrase'] = $phrase;
            $output['translation'] = $translation;
            $output['progress'] = $progressPercent;
            if ($phrase == 'aye') {
                $lang_obj->import_packlang($iso_code,$iso_code,$language_detact);
                header("refresh: 2;");
            }
            echo json_encode($output);
        } else {
            # unable to translate, throw error
            $output['status'] = 'error';
            return false;
        }
    }
