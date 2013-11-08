<?php


/**
 * @author : Arslan Hassan
 *
 * Making sense with error handling
 */

class DB_Exception extends mysqli_sql_exception
{

    public function getError()
    {
        $errorMsg = 'Error on line '.$this->getLine().' in '.$this->getFile()
            .': <b>'.$this->getMessage().'<br>While doing something with the database.';
        return $errorMsg;
    }
}

?>