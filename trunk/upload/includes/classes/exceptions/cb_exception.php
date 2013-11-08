<?php


/**
 * @author : Arslan Hassan
 *
 * Making sense with error handling
 */

class CB_Exception extends Exception
{

    public function getError()
    {
        $errorMsg = 'Error on line '.$this->getLine().' in '.$this->getFile()
            .': <b>'.$this->getMessage();
        return $errorMsg;
    }
}

?>