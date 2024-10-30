<?php

namespace highfly;

//prefix for options, hooks etc.
define( __NAMESPACE__ . '\pre', 'highfly_' );

class Utils {

function get_username( $id ) {
	return get_the_author_meta( 'display_name', $id );
}

/**
 * @return array email addresses
 */
function get_recipients() {
	$recipients = get_option( pre . 'email_addresses' );
	$recipients = str_replace( ' ', "\n", $recipients );
	$recipients = explode( "\n", $recipients );

	return apply_filters( pre . 'recipients', $recipients );
}

/**
 * Runs each element of an associative array through a filter. Uses the key of
 * the element to determine the name of the filter.
 *
 * @param array $data associative array. key = filter, value = variable
 * @param string $prefix prefix for the filter
 * @param array $arguments additional filter arguments
*/
function filter_data( $data, $prefix, $arguments ) {
	foreach( $data as $name => $value ) {
		$args = array( pre . "{$prefix}_{$name}", $value );
		$args = array_merge( $args, $arguments );
		$value = call_user_func_array( 'apply_filters', $args ); 
		$data[$name] = $value;
	}
	return $data;
}

}
