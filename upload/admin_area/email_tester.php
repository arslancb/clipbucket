<?php
require_once '../includes/admin_config.php';
$userquery->admin_login_check();
$userquery->login_check('web_config_access');

$pages->page_redir();

if( isset( $_POST[ 'start_test' ] ) ) {
	try {

		$to_email = $_POST[ 'to_email' ];

		if ( empty( $to_email ) ) {
			throw new Exception( 'Please provide a recipient email' );
		}

		$to_email = filter_var( $to_email, FILTER_SANITIZE_EMAIL );
		$to_email = filter_var( $to_email, FILTER_VALIDATE_EMAIL );

		if( $to_email === false ) {
			throw new Exception( 'Please provide a valid recipient email address' );
		}

		$to_name = $_POST[ 'to_name' ];

		if( empty( $to_name ) || !is_string( $to_name ) ) {
			$to_name = $to_email;
		}

		$from_email = $_POST[ 'from_email' ];

		if ( empty( $from_email ) ) {
			$from_email = SUPPORT_EMAIL;
		}

		$from_email = filter_var( $from_email, FILTER_SANITIZE_EMAIL );
		$from_email = filter_var( $from_email, FILTER_VALIDATE_EMAIL );

		if ( $from_email === false ) {
			throw new Exception( 'Please provide a valid sender email address' );
		}

		$from_name = $_POST[ 'from_name' ];

		if( empty( $from_name ) || !is_string( $from_name ) ) {
			$from_name = 'Tune.pk';
		}

		$subject = $_POST[ 'subject' ];
		$subject = trim( $subject );

		if ( empty( $subject ) ) {
			throw new Excpetion( 'Please provide test email subject' );
		}

		$body = $_POST[ 'body' ];
		$body = trim( $body );

		if ( empty( $body ) ) {
			throw new Excpetion( 'Please provide test email body' );
		}

		$code = $_POST[ 'email_template' ];

		if ( $code != -1 ) {
			$template = $cbemail->get_template( $code );

			if( $template ) {
				$dv = $_POST[ 'dv' ];
				$variables = array();

				if ( !empty( $dv ) ) {
					foreach( $dv as $key => $v ) {
						$variables[ $v[ 'name' ] ] = empty( $v[ 'value' ] ) ? $v[ 'name' ] : $v[ 'value' ];
					}
				}

				$subject = $cbemail->replace( $template[ 'email_template_subject' ], $variables );
				$body = $cbemail->replace( $template[ 'email_template' ], $variables );
			}
		}

		$mail = array(
			'to' => $to_email,
			'to_name' => $to_name,
			'from' => $from_email,
			'from_name' => $from_name,
			'subject' => $subject,
			'content' => $body
		);

		$test = cbmail( $mail );

		if ( $test == false ) {
			e( lang( sprintf( 'Unable to send email <strong>%s</strong>', $to_email ) ) );
		} else {
			e( lang( sprintf( 'Email successfully send to <strong>%s</strong>', $to_email ) ), 'm' );
		}

	} catch( Exception $e ) {
		e( lang( $e->getMessage() ) );
	}
}

$templates = $cbemail->get_templates();
$list = array();
$_templates = array();

$macros = array(
	'{website_title}'	=> TITLE,
	'{baseurl}'		=> BASEURL,
	'@baseurl'		=> BASEURL,
	'{website_url}'	=> BASEURL,
	'{date_format}'	=> cbdate(DATE_FORMAT),
	'{date}'			=> cbdate(),
	'{username}'		=> username(),
	'{userid}'			=> userid(),
	'{first_name}' => $userquery->udetails['first_name'],
	'{last_name}' => $userquery->udetails['last_name'],
	'{name}' => name( $userquery->udetails ),
	'{user}'		=> name( $userquery->udetails ),
	'{email}'		=> $userquery->udetails[ 'email' ],
	'{date_year}'		=> cbdate("Y"),
	'{date_month}'		=> cbdate("m"),
	'{date_day}'		=> cbdate("d"),
	'{now}'	=> NOW(),
	// '{ip_address}' => get_user_ip(),
	// '{ip}' => get_user_ip()
);

if ( !empty( $templates ) ) {
	foreach( $templates as $template ) {
		$code = $template[ 'email_template_code' ];
		$list[ $code ] = $template[ 'email_template_name' ];
		
		$HTML_template = BASEDIR.'/styles/global/v4/email_templates/'.$code.'.html';

		if ( file_exists( $HTML_template ) ) {
			$body = file_get_contents( $HTML_template );
		} else {
			$body = $template[ 'email_template' ];
		}

		$_templates[ $code ] = array(
			'subject' => $template[ 'email_template_subject' ],
			'body' => $body
		);
	}
}

assign( 'list', $list );
assign( '_templates', $_templates );
assign( 'macros', $macros );

subtitle("Email Tester");
template_files('email_tester.html');
display_it();