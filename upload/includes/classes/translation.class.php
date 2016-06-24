<?php

global $lang_obj;
$cl = $Cbucket->configs['clientid'];
$sc = $Cbucket->configs['secretId'];
define('BING_CLIENT_ID',$cl);
define('BING_CLIENT_SEC', $sc);

class MrsTranslator
{
    /**
     * Get access token
     *
     * @return string 
     */
    public function get_access_token() {	
     
        # Get a 10-minute access token for Microsoft Translator API.
        $url = 'https://datamarket.accesscontrol.windows.net/v2/OAuth2-13';
        $postParams = 'grant_type=client_credentials&client_id='.urlencode(BING_CLIENT_ID).
        '&client_secret='.urlencode(BING_CLIENT_SEC).'&scope=http://api.microsofttranslator.com';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postParams);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);  
        $rsp = curl_exec($ch); 
        $rsp = json_decode($rsp);
        $access_token = $rsp->access_token;
        
        return $access_token;
    }
    /**
     * Translate text to a specified language
     *
     * @param string $text
      * @param string $to    language to be translated to
     * @param string $from  language of text   
     * @return string       Translated text
     */
    public function translate($text, $to, $from = null) {
        $access_token = $this->get_access_token();

        if (is_null($from)) {
            $from = $this->detectLang($text);
        }

        $url = 'http://api.microsofttranslator.com/V2/Http.svc/Translate?text='.urlencode($text).'&from='.$from.'&to='.$to;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization:bearer '.$access_token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);  
        $rsp = curl_exec($ch); 
        
        preg_match_all('/<string (.*?)>(.*?)<\/string>/s', $rsp, $matches);
	
        return $matches[2][0];
        //unset($_COOKIE['bing_access_token']);
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
        $access_token = $this->get_access_token();
        $result = array();        
        $result[$from] = $text;
        
        foreach($tos as $to) {
            $url = 'http://api.microsofttranslator.com/V2/Http.svc/Translate?text='.urlencode($text).'&from='.$from.'&to='.$to;
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url); 
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization:bearer '.$access_token));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);  
            $rsp = curl_exec($ch); 
            
            preg_match_all('/<string (.*?)>(.*?)<\/string>/s', $rsp, $matches);

            $result[$to] = $matches[2][0];
        }
        
        return $result;
    }

    /**
     * Detect the language of a given text
     *
     * @param String $text 
     * @return String           Language of text
     */
    public function detectLang($text) {
        $access_token = $this->get_access_token();

        $url = "http://api.microsofttranslator.com/V2/Http.svc/Detect?text=".urlencode($text);

         $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization:bearer '.$access_token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);  
        $rsp = curl_exec($ch); 
        
        preg_match_all('/<string (.*?)>(.*?)<\/string>/s', $rsp, $matches);
        return $matches[2][0];
    }
}

?>