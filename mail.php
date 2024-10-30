<?php

namespace highfly;

class Mail {

private $filter_prefix;
private $data;

function __construct( $filter_prefix ) {
	$this->filter_prefix = "{$filter_prefix}_mail";

	$this->data = array(
		'recipients' => array(),
		'subject' => '',
		'body' => '',
		'header' => array(),
		);
}

function get() {
	return $this->data;
}

/**
 * The first three arguments are header-injection safe. Praise the PHPMailer!
 */
function set( $data ) {
	$this->data = $data;
}

/**
 * Sends the constructed mail to all recipients. 
 * @param array $args passed to the filters
 */
function send( $args ) {
	$data = Utils::filter_data( $this->data, $this->filter_prefix, $args );
	extract( $data );

	foreach( $recipients as $to ) {
		wp_mail( $to, $subject, $body, $header );
	}
}

}
