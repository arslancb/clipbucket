<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Fawaz
 * Date: 8/26/13
 * Time: 3:51 PM
 * To change this template use File | Settings | File Templates.
 */


if( !function_exists( 'tbl' ) ) { 
 
	function tbl($tbl)
	{
		global $DBNAME;
		$prefix = TABLE_PREFIX;
		$tbls = explode(",",$tbl);
		$new_tbls = "";
		foreach($tbls as $ntbl)
		{
			if(!empty($new_tbls))
				$new_tbls .= ",";
			$new_tbls .= "`".$DBNAME."`.".$prefix.$ntbl."";
		}

		return $new_tbls;
	}

}

/**
 * Format array into table fields
 *
 * @param $fields
 * @param bool $table
 * @return bool|string
 */
function table_fields( $fields, $table = false ) {
    $the_fields = '';

    if ( $fields ) {
        $array = $fields;
        foreach ($array as $key => $_fields)
        {

            if (is_array($_fields))
            {
                foreach ($_fields as $field)
                {
                    if ($the_fields)
                        $the_fields .=", ";
                    $the_fields .= $key . '.' . $field;
                }
            }else
            {
                $field = $_fields;

                if ($the_fields)
                    $the_fields .=", ";

                if ($tbl)
                    $the_tbl = tbl($tbl). '.' ;
                else
                    $the_tbl = '';

                $the_fields .= $the_tbl . $field;
            }
        }
    }

    return $the_fields ? $the_fields : false;
}

if( !function_exists( 'tbl_fields' ) ) {
	/**
	 * Alias function for table_fields
	 *
	 * @param $fields
	 * @param bool $table
	 * @return bool|string
	 */
	function tbl_fields( $fields, $table = false ) {
		return table_fields( $fields, $table );
	}
}

if ( !function_exists('cb_sql_table') ) {
    /**
     * Since we start using AS in our sql queries, it was getting
     * more and more difficult to know how author has defined
     * the table name. Using this, will confirm that table will be
     * defined AS it's name provided in $table.
     *
     * If author still wants to define table name differently, he
     * can provide it in $as
     *
     * @author Fawaz Tahir <fawaz.cb@gmail.com>
     * @param string $table
     * @param string $as
     * @return string $from_query
     */
    function cb_sql_table( $table, $as = null ) {
        if ( $table ) {
            $from_query = tbl( $table )." AS ".( ( !is_null( $as ) and is_string( $as ) ) ? $as : $table );
            return $from_query;
        }
        return false;
    }
}

if ( !function_exists( 'table' ) ) {
    function table( $table, $as = null ) {
        return cb_sql_table( $table, $as );
    }
}

/**
 * Alias function for method _select
 *
 * @param $query
 * @return mixed
 */
function cb_select( $query ) {
    global $db;
    return $db->_select( $query );
}

/**
 * Alias function for function cb_select
 *
 * @param $query
 * @return mixed
 */
function select( $query ) {
    return cb_select( $query );
}