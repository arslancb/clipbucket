<?php

/**
 * functions related to database
 * 
 */
function db_select($query)
{
    global $db;
    return $db->_select($query);
}

function db_update($tbl, $fields, $cond)
{
    global $db;

    $count = 0;
    foreach ($fields as $field => $val) {

        if ($count > 0)
            $fields_query .= ',';


        $needle = substr($val, 0, 2);

        if ($needle != '{{')
            $value = "'" . filter_sql($val) . "'";
        else
        {
            $val = substr($val, 2, strlen($val) - 4);
            $value = filter_sql($val);
        }

        $fields_query .= $field . "=$value ";
        $count++;
    }

    //Complete Query
    $query = "UPDATE $tbl SET $fields_query WHERE $cond $ep";
    //if(!mysql_query($query)) die($query.'<br>'.mysql_error());
    //$db->total_queries++;
    //$db->total_queries_sql[] = $query;
    //$db->Execute($query);

    mysqli_query($query);

    if (mysql_error())
        die($db->db_query . '<br>' . mysql_error());

    return true;
}

function db_insert($tbl, $fields)
{
    global $db;

    $count = 0;

    $query_fields = array();
    $query_values = array();


    foreach ($fields as $field => $val) {

        $query_fields[] = $field;

        $needle = substr($val, 0, 2);

        if ($needle != '{{')
            $query_values[] = "'" . filter_sql($val) . "'";
        else
        {
            $val = substr($val, 2, strlen($val) - 4);
            $query_values[] = filter_sql($val);
        }

        $count++;
    }

    $fields_query = implode(',', $query_fields);
    $values_query = implode(',', $query_values);




    //Complete Query
    $query = "INSERT INTO $tbl ($fields_query) VALUES ($values_query) $ep";

    //if(!mysql_query($query)) die($query.'<br>'.mysql_error());
    $db->total_queries++;
    $db->total_queries_sql[] = $query;
    $db->Execute($query);

    if (mysql_error())
    {
        //if(LOG_DB_ERRORS)

        die($db->db_query . '<br>' . mysql_error());
    }

    return $db->insert_id();
}

function filter_sql($data)
{
    $data = mysql_real_escape_string($data);
    return $data;
}

/**
 * Function used to count fields in mysql
 * @param TABLE NAME
 * @param Fields
 * @param condition
 */
function dbcount($tbl, $fields = '*', $cond = false)
{
   global $db;
    return $db->count($tbl,$fields,$cond);
}

function cb_query_id( $query ) {
    return md5( $query );
}

?>
