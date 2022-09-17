<?php

global $lang_obj;
require_once 'GTvendor/autoload.php';
use Stichoza\GoogleTranslate\TranslateClient;
$transClient = new TranslateClient(); // Default is from 'auto' to 'en'

class GoogleTranslator
{
    /**
     * Get access token
     *
     * @return string 
     */
    public function get_access_token() {    
        return false;
    }
    /**
     * Translate text to a specified language
     *
     * @param string $text
      * @param string $to    language to be translated to
     * @param string $from  language of text   
     * @return string       Translated text
     */
    public function translate($text, $to, $from = 'en') {
        global $transClient;
        $transClient->setSource($from); // Translate from English
        $transClient->setTarget($to); // Translate to Georgian
        $transClient->setUrlBase('http://translate.google.cn/translate_a/single'); // Set Google Translate URL base (This is not necessary, only for some countries)
        // checkmate
        $text=str_replace("%s","_c_b_",$text);
        $phraseTranslated = $transClient->translate($text);
        $phraseTranslated=str_replace("_c_b_","%s",$phraseTranslated);
        // checkmate
        return $phraseTranslated;
    }
            
    /**
     * Translate text to specified languages
     *
     * @param string $text
     * @param string $from  language of text
     * @param array $tos     languages to be translated to
     * @return array        Translations of the text to given languages
     */
    public function multiTranslate($text, $from, $tos) {        
       return false;
    }

    /**
     * Detect the language of a given text
     *
     * @param String $text 
     * @return String           Language of text
     */
    public function detectLang($text) {
        return false;
    }
}

?>