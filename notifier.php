<?php

namespace highfly;

abstract class Notifier {

private $recipients;

function __construct() {
	$this->recipients = null;
}

/**
 * Name of notification type, by default 'post' or 'comment'
 */
abstract function type();

/**
 * Checks with a list of content states we want to catch. Per default the status
 * 'publish' is the only 'public status'.
 * @return bool public?
 */
function is_public_status( $status ) {
	$states =  array( 'publish' );
	$states = apply_filters( pre . 'public_statuses', $states, $this->type() );
	return in_array( $status, $states, TRUE );
}

/**
 * Sends notification mails. You can edit the mails with filters.
 * @param array $args arguments passed to the filters
 */
function send( $args ) {
	$mail = new Mail( $this->type() );
	$data = $mail->get();
	$data['recipients'] = $this->get_cached_recipients();
	$mail->set( $data );
	$mail->send( $args );
}

function get_cached_recipients() {
	if ( is_null( $this->recipients ) ) {
		$this->recipients = Utils::get_recipients();
	}
	return $this->recipients;
}

}
