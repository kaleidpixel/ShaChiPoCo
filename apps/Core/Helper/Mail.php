<?php
/**
 * Mail helper.
 *
 * @since    1.0.0
 * @author   KUCKLU <kuck1u@users.noreply.github.com>
 * @licenses MIT License.
 * @package  ShaChiPoCo
 */

realpath( $_SERVER[ 'SCRIPT_FILENAME' ] ) !== realpath( __FILE__ ) OR exit( 'No direct script access allowed' );

require_once COREPATH . DIRECTORY_SEPARATOR . 'Helper'. DIRECTORY_SEPARATOR . 'String.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * メールアドレスの検証を行い、問題がなければ true を返す
 *
 * @see https://qiita.com/mpyw/items/346f1789ad0e1b969ebc
 *
 * @param string $email
 * @param bool   $check_dns
 *
 * @return bool
 */
function is_valid_email( $email, $check_dns = false ) {
	switch ( true ) {
		case false === filter_var( $email, FILTER_VALIDATE_EMAIL ):
		case ! preg_match( '/@([^@\[]++)\z/', $email, $m ):
			return false;

		case ! $check_dns:
		case checkdnsrr( $m[ 1 ], 'MX' ):
		case checkdnsrr( $m[ 1 ], 'A' ):
		case checkdnsrr( $m[ 1 ], 'AAAA' ):
			return true;

		default:
			return false;
	}
}


/**
 * PHPMailer を使ってメールを送信する
 *
 * @param string[] $to
 * @param string[] $from
 * @param string   $subject
 * @param string   $body
 * @param string[] $cc
 * @param string[] $bcc
 * @param string   $language
 * @param string   $charset
 * @param string   $encoding
 *
 * @return bool|\Exception|Exception
 */
function sendmail( $to = array(), $from = array(), $subject = '', $body = '', $cc = array(), $bcc = array(), $language = APP_LANGUAGE, $charset = APP_CHARSET, $encoding = 'base64' ) {
	mb_language( $language );
	mb_internal_encoding( $charset );

	$to   = array_merge( array( 'addr' => '', 'name' => '' ), $to );
	$from = array_merge( array( 'addr' => '', 'name' => '' ), $from );

	if ( is_string( $cc ) ) {
		$cc = (array) $cc;
	}

	if ( is_string( $bcc ) ) {
		$bcc = (array) $bcc;
	}

	switch ( $encoding ) {
		case $encoding !== 'base64':
			$transfer_encoding = 'Q';
			break;
		default:
			$transfer_encoding = 'B';
			break;
	}

	$mail = new PHPMailer( true );

	try {
		$mail->addAddress( $to[ 'addr' ], mb_encode_mimeheader( $to[ 'name' ], $charset, $transfer_encoding ) );
		$mail->addReplyTo( $from[ 'addr' ], mb_encode_mimeheader( $from[ 'name' ], $charset, $transfer_encoding ) );
		$mail->setFrom( $from[ 'addr' ], mb_encode_mimeheader( $from[ 'name' ], $charset, $transfer_encoding ) );

		if ( is_array( $cc ) && ! empty( $cc ) ) {
			foreach ( $cc as $addr ) {
				if ( is_string( $addr ) && is_valid_email( $addr ) ) {
					$mail->addCC( convert_str( $addr ) );
				}
			}
		}

		if ( is_array( $bcc ) && ! empty( $bcc ) ) {
			foreach ( $bcc as $addr ) {
				if ( is_string( $addr ) && is_valid_email( $addr ) ) {
					$mail->addBCC( convert_str( $addr ) );
				}
			}
		}

		if ( false !== strpos( $body, '</body>' ) ) {
			$mail->isHTML( true );
		}

		$mail->XMailer  = 'PHPMailer (https://packagist.org/packages/phpmailer/phpmailer)';
		$mail->CharSet  = $charset;
		$mail->Encoding = $encoding;
		$mail->Subject  = $subject;
		$mail->Body     = $body;

		$result = $mail->send();
	} catch ( Exception $error ) {
		$result = $error;
	}

	return $result;
}
