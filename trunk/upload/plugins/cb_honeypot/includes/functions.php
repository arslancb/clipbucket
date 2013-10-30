<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Fawaz
 * Date: 10/11/13
 * Time: 3:23 PM
 * To change this template use File | Settings | File Templates.
 */

function cb_honeypot_name() {
    $name = cb_get_honeypot_name();

    if ( count( $name ) == 0 OR empty( $name ) ) {
        return false;
    }

    $field_name = implode( CB_HONEYPOT_NAME_SEPARATOR, $name );
    return cb_honeypot_hash( $field_name );
}

function cb_honeypot_hash( $name ) {
    return md5( $name );
}

function cb_honeypot_hash_fieldname( $name, $salt ) {
    return md5( $name.$salt.CB_HONEYPOT_SPAM_SALT );
}

function cb_add_honeypot_name_part( $name ) {
    global $CB_HONEYPOT_NAME;

    if ( $name ) {
        $CB_HONEYPOT_NAME[] = $name;
    }

    return $CB_HONEYPOT_NAME;
}

function cb_get_honeypot_name() {
    global $CB_HONEYPOT_NAME;
    return $CB_HONEYPOT_NAME;
}

function cb_honeypot_assignment() {
    $new_salt = cb_honeypot_name();

    $timestamp_id = cb_honeypot_hash_fieldname( 'cb_timestamp', $new_salt );
    $honeypot_id = cb_honeypot_hash_fieldname( 'cb_honeypot', $new_salt );

    $output = '<input type="hidden" value="'.$new_salt.'" id="cb_verifier" name="cb_verifier" />';
    $output .= '<input type="hidden" name="'.$timestamp_id.'" id="'.$timestamp_id.'" value="'.CB_HONEYPOT_TIMESTAMP.'" />';
    $output .= '<div class="cb_field_container_type_input_style">If you are reading this, please do not touch the next field<br/><input type="text" class="cb_field_type_input_style" placeholder="PLEASE IT AS IT IS" name="'.$honeypot_id.'" id="'.$honeypot_id.'" value="'.CB_HONEYPOT_DEFAULT_VALUE.'" /></div>';

    return $output;
}


function cb_verify_honeypot() {
    global $eh;

    $salt = mysql_clean( post( 'cb_verifier' ) );

    if ( $salt ) {
        $timestamp_name = cb_honeypot_hash_fieldname( 'cb_timestamp', $salt );
        $timestamp = mysql_clean( post( $timestamp_name ) );

        if ( $timestamp ) {

            $difference = time() - $timestamp;
            if ( ( $difference ) < CB_HONEYPOT_FORM_SUBMISSION_WINDOW ) {
                e( lang( sprintf( 'Submitting form in %d %s. Are you even a human ?', $difference, ( $difference == 1 ) ? 'second' : 'seconds' ) ) );
                return false;
            }

            $honeypot_name = cb_honeypot_hash_fieldname( 'cb_honeypot', $salt );
            $honeypot = mysql_clean( post( $honeypot_name ) );

            if( CB_HONEYPOT_DEFAULT_VALUE == '' ) {

                if( $honeypot != '' ) {
                    e( lang( 'Someone is tampering with forms. Can not process further.' ) );
                    return false;
                }

            } else {

                if ( !$honeypot OR ( $honeypot != CB_HONEYPOT_DEFAULT_VALUE ) ) {
                    e( lang( 'Someone is tampering with forms. Can not process further.' ) );
                    return false;
                }

            }

            return true;

        } else {
            e( lang( 'Unable to locate timestamp. Can not process further.' ) );
        }
    } else {
        e( lang( 'No verification code provided. Can not process further.' ) );
    }

    return false;
}