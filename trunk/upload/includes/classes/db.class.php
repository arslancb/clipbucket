<?php

/**
 * @Author : ARslan Hassan
 * @since : 2.7
 *
 * Simplies our database queries and methods
 * no magic, please!
 */

class Clipbucket_db
{
    var $db_link = "";
    var $db_name = "";
    var $db_uname = "";
    var $db_pwd = "";
    var $db_host = "";

    var $mysqli = "";

    var $num_rows = 0;

    /**
     * Connect to mysqli Database
     *
     * @param $host
     * @param $name
     * @param $uname
     * @param $pwd
     *
     * @return Boolean;
     */

    function connect($host=String,$name=String,$uname=String,$pwd=String)
    {

        try
        {
            if(!$host) $host = $this->db_host;
            if(!$name) $name = $this->db_name;
            if(!$uname) $uname = $this->db_uname;
            if(!$pwd) $pwd = $this->db_pwd;

            $this->mysqli = new mysqli($host,$uname, $pwd, $name);

            if($this->mysqli->connect_errno) return false;

        }catch(DB_Exception $e)
        {
            $e->getError();
        }
    }


    /**
     * @param $query
     * @return bool
     */
    function _select($query)
    {

        $result = $this->mysqli->query($query);

        $this->num_rows = $result->num_rows ;
        $data = array();


        #pr( $result, true );

        for ($row_no = 0; $row_no < $this->num_rows; $row_no++) {
            $result->data_seek($row_no);
            $data[] = $result->fetch_assoc();
        }

        if($result)
            $result->close();

        return $data;


    }


    /**
     * Fetch data from Database
     */
    function select($tbl,$fields='*',$cond=false,$limit=false,$order=false,$ep=false)
    {
        //return dbselect($tbl,$fields,$cond,$limit,$order);
        $query_params = '';
        //Making Condition possible
        if($cond)
            $where = " WHERE ";
        else
            $where = false;

        $query_params .= $where;
        if($where)
        {
            $query_params .= $cond;
        }

        if($order)
            $query_params .= " ORDER BY $order ";
        if($limit)
            $query_params .= " LIMIT $limit ";

        $query = " SELECT $fields FROM $tbl $query_params $ep ";


        return $this->_select($query);
        /*
        //Finally Executing
        $data = $this->Execute($query);
        $this->num_rows = $data->_numOfRows;
        $this->total_queries++;
        $this->total_queries_sql[] = $query;

        //Now Get Rows and return that data
        if($this->num_rows > 0)
            return $data->getrows();
        else
            return false;*/
    }


    function count($tbl,$fields='*',$cond=false)
    {
        global $db;
        if ($cond)
            $condition = " Where $cond ";
        $query = "Select Count($fields) From $tbl $condition";

        $result = $this->_select($query);

        $fields = $result[0];
        //$db->total_queries++;
        //$db->total_queries_sql[] = $query;
        //$fields = $result->fields;

        if ($fields)
        {
            foreach ($fields as $field)
                return $field;
        }

        return false;
    }





    /**
     * Get row
     *
     */
    function GetRow($query)
    {
        $result = $this->_select($query);

        if($result) return $result[0];
    }


    /**
     * Execute query
     */
    function Execute($query)
    {

        try
        {
            return $this->mysqli->query($query);
        }
        catch(DB_Exception $e)
        {
            $e->getError();
        }

    }

    /**
     * @param $tbl
     * @param $flds
     * @param $vls
     * @param $cond
     * @param null $ep
     * @return string
     */

    function update($tbl,$flds,$vls,$cond,$ep=NULL)
    {
        $total_fields = count($flds);
        $count = 0;
        $fields_query = "";
        for($i=0;$i<$total_fields;$i++)
        {
            $count++;
            //$val = mysql_clean($vls[$i]);
            $val = ($vls[$i]);
            preg_match('/\|no_mc\|/',$val,$matches);
            //pr($matches);
            //if(getArrayValue($matches, 0)!='')
            if($matches)  
                $val = preg_replace('/\|no_mc\|/','',$val);
            else
                $val = $this->clean_var($val);

            $needle = substr($val,0,3);

            if($needle != '|f|')
                $fields_query .= $flds[$i]."='".$val."'";
            else
            {
                $val = substr($val,3,strlen($val));
                $fields_query .= $flds[$i]."=".$val."";
            }
            if($total_fields!=$count)
                $fields_query .= ',';
        }
        //Complete Query
        $query = "UPDATE $tbl SET $fields_query WHERE $cond $ep";

        //if(!mysql_query($query)) die($query.'<br>'.mysql_error());
        if(isset($this->total_queries)) $this->total_queries++;
        $this->total_queries_sql[] = $query;

        try
        {
            $this->mysqli->query($query);
        }
        catch(DB_Exception $e)
        {
            $e->getError();
        }

    }


    function delete($tbl,$flds,$vls,$ep=NULL)
    {
        //dbDelete($tbl,$flds,$vls,$ep);


        global $db ;
        $total_fields = count($flds);
        $fields_query = "";
        $count = 0;
        for($i=0;$i<$total_fields;$i++)
        {
            $count++;
            $val = mysql_clean($vls[$i]);
            $needle = substr($val,0,3);
            if($needle != '|f|')
                $fields_query .= $flds[$i]."='".$val."'";
            else
            {
                $val = substr($val,3,strlen($val));
                $fields_query .= $flds[$i]."=".$val."";
            }
            if($total_fields!=$count)
                $fields_query .= ' AND ';
        }
        //Complete Query
        $query = "DELETE FROM $tbl WHERE $fields_query $ep";
        //if(!mysql_query($query)) die(mysql_error());
        if(isset($this->total_queries)) $this->total_queries++;
        $this->total_queries_sql[] = $query;


        try
        {
            $this->mysqli->query($query);
        }
        catch(DB_Exception $e)
        {
            $e->getError();
        }

    }


    /**
     * Function used to insert values in database
     *
     *
     */
    function insert($tbl,$flds,$vls,$ep=NULL)
    {
        //dbInsert($tbl,$flds,$vls,$ep);
        $total_fields = count($flds);
        $count = 0;
        $fields_query = "";
        $values_query = "";
        foreach($flds as $field)
        {
            $count++;
            $fields_query .= $field;
            if($total_fields!=$count)
                $fields_query .= ',';
        }
        $total_values = count($vls);
        $count = 0;
        foreach($vls as $value)
        {
            $count++;

            preg_match('/\|no_mc\|/',$value,$matches);
            //pr($matches);
            //if(getArrayValue($matches, 0)!='')
            if($matches)   
                $val = preg_replace('/\|no_mc\|/','',$value);
            else
                $val = $this->clean_var($value);
            $needle = substr($val,0,3);

            if($needle != '|f|')
                $values_query .= "'".$val."'";
            else
            {
                $val = substr($val,3,strlen($val));
                $values_query .= "'".$val."'";
            }

            $val ;
            if($total_values!=$count)
                $values_query .= ',';
        }

        //Complete Query
        $query = "INSERT INTO $tbl ($fields_query) VALUES ($values_query) $ep";
        $this->total_queries_sql[] = $query;
        //if(!mysql_query($query)) die(mysql_error());
        if(isset($this->total_queries)) $this->total_queries++;

        try
        {
            $this->mysqli->query($query);
            return $this->mysqli->insert_id;
        }
        catch(DB_Exception $e)
        {
            echo $e->getError();
        }

    }

    /**
     * Returns last insert id.
     *
     * Always use this right after calling insert method or before
     * making another mysqli query.
     *
     * @return mixed
     */
    function insert_id() {
        return $this->mysqli->insert_id;
    }

    /**
     * Clean variable for mysql
     *
     * @todo : Write method to clean stuff otherwise SQL injection is easily achievable
     */
    function clean_var($var)
    {
        return $var;
    }

    /**
     * Get effect rows
     */
    function Affected_Rows()
    {
        return $this->mysqli->affected_rows;
    }

}

?>