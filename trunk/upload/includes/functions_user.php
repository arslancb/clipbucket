<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Fawaz
 * Date: 8/26/13
 * Time: 4:47 PM
 * To change this template use File | Settings | File Templates.
 */

function get_user_fields() {
    global $cb_columns;
    return $cb_columns->object( 'users' )->get_columns();
}