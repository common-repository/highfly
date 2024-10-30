<?php

namespace highfly;

class Admin {

function __construct() {
	add_action( 'admin_init', array( $this, 'init' ) );
}

function init() {
	add_settings_section( pre . "options", __( 'Highfly', __NAMESPACE__ ),
		array( $this, 'heading' ), 'discussion' );

	add_settings_field( pre . "email_addresses",
		__( 'Email Addresses', __NAMESPACE__ ),
		array( $this, 'email_addresses_textarea' ),
		'discussion', pre . "options" );

	register_setting( 'discussion', pre . "email_addresses",
		array( $this, 'validate_email_addresses' ) );
}

function heading() {
	_e( 'Email addresses of people to notify when new posts and comments are published. One per line.', __NAMESPACE__ );
}

function email_addresses_textarea() {
	$email_addresses = get_option( pre . "email_addresses" );
	$email_addresses = str_replace( ' ', "\n", $email_addresses );

	echo '<textarea class="large-text" id="' . pre . 'email_addresses" cols="50"',
		" rows='10' name='" . pre . "email_addresses'>$email_addresses</textarea>";
}

function validate_email_addresses( $email_addresses ) {
	// Make array out of textarea lines
	$valid_addresses = '';
	$recipients      = str_replace( ' ', "\n", $email_addresses );
	$recipients      = explode( "\n", $recipients );

	foreach ( $recipients as $recipient ) {
		$recipient = trim( $recipient );
		if ( is_email( $recipient ) )
			$valid_addresses .= $recipient . "\n";
	}
	$valid_addresses = trim( $valid_addresses );

	return apply_filters( pre . "validate_email_addresses", $valid_addresses );
}


}
