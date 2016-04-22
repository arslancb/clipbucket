<?php

/**
 * File: Database Class
 * Description: All mysql function being called from one place. Simplifies things for developers 
 * @author : Arslan Hassan, Saqib Razzaq
 * @since : ClipBucket 2.7
 * @modified: ClipBucket 2.8.1 [ Saqib Razzaq ]
 * @functions: Various
 * @db_actions: connect, select, update, count, insert
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
     * @param : { string } { $host } { your database host e.g localhost }
     * @param : { string } { $name } { name of database to connect to }
     * @param : { string } { $uname } { your database username }
     * @param : { string } { $pwd } { password of database to connect to }
     * @return { boolean } { true or false }
     */

    function connect($host=String,$name=String,$uname=String,$pwd=String) {
        try {
            if(!$host) $host = $this->db_host;
            if(!$name) $name = $this->db_name;
            if(!$uname) $uname = $this->db_uname;
            if(!$pwd) $pwd = $this->db_pwd;

            $this->mysqli = new mysqli($host,$uname, $pwd, $name);
            if($this->mysqli->connect_errno) return false;

        } catch(DB_Exception $e) {
            $e->getError();
        }
    }

    /**
    * Select elements from database with query
    *
    * @param : { string } { $query } { mysql query to run }
    * @return : { array } { $data } { array of selected data }
    */

    function _select($query) {
        $result = $this->mysqli->query($query);
        $this->num_rows = $result->num_rows ;
        $data = array();

        for ($row_no = 0; $row_no < $this->num_rows; $row_no++) {
            $result->data_seek($row_no);
            $data[] = $result->fetch_assoc();
        }

        if($result)
            $result->close();

        return $data;
    }


    /**
    * Select elements from database with numerous conditions
    *
    * @param : { string } { $tbl } { table to select data from }
    * @param : { string } { $fields } { all by default, element to fetch }
    * @param : { string } { $cond } { false by default, mysql condition for fetching data }
    * @param : { integer } { $limit } { false by default, number of entires to fetch }
    * @param : { string } { $order } { false by default, order to sort results }
    * @return : { array } { $data } { array of selected data }
    */

    function select($tbl,$fields='*',$cond=false,$limit=false,$order=false,$ep=false) {
        //return dbselect($tbl,$fields,$cond,$limit,$order);
        $query_params = '';
        //Making Condition possible
        if($cond)
            $where = " WHERE ";
        else
            $where = false;

        $query_params .= $where;
        if($where) {
            $query_params .= $cond;
        }

        if($order)
            $query_params .= " ORDER BY $order ";
        if($limit)
            $query_params .= " LIMIT $limit ";

        $query = " SELECT $fields FROM $tbl $query_params $ep ";
        return $this->_select($query);
    }

    /**
    * Count values in given table using MySQL COUNT
    * 
    * @param : { string }   { $tbl } { table to count data from }
    * @param : { string } { $fields } { field that you want to count }
    * @param : { string } { $cond } { condition for mysql }
    * @return : { integer } { $field } { count of elements }
    */

    function count($tbl,$fields='*',$cond=false) {
        global $db;
        if ($cond)
            $condition = " Where $cond ";
        $query = "Select Count($fields) From $tbl $condition";
        $result = $this->_select($query);
        $fields = $result[0];

        if ($fields)
        {
            foreach ($fields as $field)
                return $field;
        }

        return false;
    }

    /**
     * Get row using query
     * @param : { string } { $query } { query to run to get row }
     */

    function GetRow($query)
    {
        $result = $this->_select($query);
        if($result) return $result[0];
    }

    /**
     * Execute a MYSQL query directly without processing
     * @param : { string } { $query } { query that you want to execute }
     * @return : { array } { array of data depending on query }
     */

    function Execute($query)
    {
        try {
            return $this->mysqli->query($query);
        } catch(DB_Exception $e) {
            $e->getError();
        }
    }

    /**
     * Update database fields { table, fields, values style }
     *
     * @param : { string } { $tbl } { table to ujpdate values in }
     * @param : { array } { $flds } { array of fields you want to update }
     * @param : { array } { $vls } { array of values to update against fields }
     * @param : { string } { $cond } { mysql condition for query }
     * @param : { string } { $ep } { extra parameter after condition }
     * @return : { null }
     */

    function update($tbl,$flds,$vls,$cond,$ep=NULL) {
        # handling ( ' ) in title problem (ex: you can't)
        if (strpos($vls[0], "'")) {
            $vls[0] = str_replace("'", "&#39;", $vls[0]);
        }

        $total_fields = count($flds);
        $count = 0;
        $fields_query = "";
        for($i=0;$i<$total_fields;$i++) {
            $count++;
            $val = ($vls[$i]);
            preg_match('/\|no_mc\|/',$val,$matches);
            if($matches) {
                $val = preg_replace('/\|no_mc\|/','',$val);
            } else {
                $val = $this->clean_var($val);
            }

            $needle = substr($val,0,3);
            if($needle != '|f|') {
                $fields_query .= $flds[$i]."='".$val."'";
            } else {
                $val = substr($val,3,strlen($val));
                $fields_query .= $flds[$i]."=".$val."";
            }
            if($total_fields!=$count)
                $fields_query .= ',';
        }
        //Complete Query
        $query = "UPDATE $tbl SET $fields_query WHERE $cond $ep";

        if(isset($this->total_queries)) $this->total_queries++;
        $this->total_queries_sql[] = $query;

        try {
            $this->mysqli->query($query);
        } catch(DB_Exception $e) {
            $e->getError();
        }
    }

    /**
     * Update database fields { table, associative array style }
     *
     * @param : { string } { $tbl } { table to ujpdate values in }
     * @param : { array } { $fields } { associative array with fields and values }
     * @param : { string } { $cond } { mysql condition for query }
     * @return : { boolean } { true of false }
     */

    function db_update($tbl, $fields, $cond) {
        $count = 0;
        foreach ($fields as $field => $val) {
            if ($count > 0)
                $fields_query .= ',';
            $needle = substr($val, 0, 2);
            if ($needle != '{{') {
                $value = "'" . filter_sql($val) . "'";
            } else {
                $val = substr($val, 2, strlen($val) - 4);
                $value = filter_sql($val);
            }

            $fields_query .= $field . "=$value ";
            $count += $count;
        }
        //Complete Query
        $query = "UPDATE $tbl SET $fields_query WHERE $cond $ep";
        try {
            $db->mysqli->query($query);
        } catch(DB_Exception $e) {
            $e->getError();
        }
        return true;
    }

    /**
    * Delete an element from database
    *
    * @param : { string } { $tbl } { table to delete value from }
    * @param : { array } { $flds } { array of fields to update }
    * @param : { array } { $vlds } { array of values to update against fields }
    * @param : { string } { $ep } { extra paramters to consider }
    * @return : { null }
    */

    function delete($tbl,$flds,$vls,$ep=NULL) {
        global $db ;
        $total_fields = count($flds);
        $fields_query = "";
        $count = 0;
        for($i=0;$i<$total_fields;$i++) {
            $count++;
            $val = mysql_clean($vls[$i]);
            $needle = substr($val,0,3);
            if($needle != '|f|') {
                $fields_query .= $flds[$i]."='".$val."'";
            } else {
                $val = substr($val,3,strlen($val));
                $fields_query .= $flds[$i]."=".$val."";
            }
            if($total_fields!=$count) {
                $fields_query .= ' AND ';
            }
        }
        //Complete Query
        $query = "DELETE FROM $tbl WHERE $fields_query $ep";
        if(isset($this->total_queries)) $this->total_queries++;
        $this->total_queries_sql[] = $query;
        try {
            $this->mysqli->query($query);
        } catch(DB_Exception $e) {
            $e->getError();
        }
    }


    /**
    * Function used to insert values in database { table, fields, values style }
    * @param : { string } { $tbl } { table to insert values in }
    * @param : { array } { $flds } { array of fields to update }
    * @param : { array } { $vlds } { array of values to update against fields }
    * @param : { string } { $ep } { extra paramters to consider }
    * @return : { integer } { $insert_id } { id of inserted element }
    */

    function insert($tbl,$flds,$vls,$ep=NULL) {
        $total_fields = count($flds);
        $count = 0;
        $fields_query = "";
        $values_query = "";
        foreach($flds as $field) {
            $count++;
            $fields_query .= $field;
            if($total_fields!=$count)
                $fields_query .= ',';
        }
        $total_values = count($vls);
        $count = 0;
        foreach($vls as $value) {
            $count++;
            preg_match('/\|no_mc\|/',$value,$matches);
            if($matches) {
                $val = preg_replace('/\|no_mc\|/','',$value);
            } else {
                $val = $this->clean_var($value);
            }
            $needle = substr($val,0,3);
            if($needle != '|f|') {
                $values_query .= "'".$val."'";
            } else {
                $val = substr($val,3,strlen($val));
                $values_query .= "'".$val."'";
            }

            $val ;
            if($total_values!=$count) {
                $values_query .= ',';
            }
        }
        $query = "INSERT INTO $tbl ($fields_query) VALUES ($values_query) $ep";
        $this->total_queries_sql[] = $query;
        if(isset($this->total_queries)) $this->total_queries++;

        try {
            $this->mysqli->query($query);
            return $this->mysqli->insert_id;
        } catch(DB_Exception $e) {
            echo $e->getError();
        }

    }
    /**
    * Function used to insert values in database { table, associative array style }
    * @param : { string } { $tbl } { table to insert values in }
    * @param : { array } { $flds } { array of fields and values to update (accosiatve array) }
    * @return : { integer } { $insert_id } { id of inserted element }
    */

    function db_insert($tbl, $fields)
    {
        $count = 0;
        $query_fields = array();
        $query_values = array();
        foreach ($fields as $field => $val) {
            $query_fields[] = $field;
            $needle = substr($val, 0, 2);
            if ($needle != '{{') {
                $query_values[] = "'" . filter_sql($val) . "'";
            } else {
                $val = substr($val, 2, strlen($val) - 4);
                $query_values[] = filter_sql($val);
            }

            $count += $count;
        }

        $fields_query = implode(',', $query_fields);
        $values_query = implode(',', $query_values);
        //Complete Query
        $query = "INSERT INTO $tbl ($fields_query) VALUES ($values_query) $ep";
        $db->total_queries++;
        $db->total_queries_sql[] = $query;
        try {
            $db->mysqli->query($query);
        } catch(DB_Exception $e) {
            $e->getError();
        }

        return $db->insert_id();
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