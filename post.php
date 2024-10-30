<?php

namespace highfly;

//Post notifications
class Post extends Notifier {

function type() {
	return 'post';
}

function __construct() {
	add_action( 'transition_post_status',
		array( $this, 'mail_wrapper' ), 10, 3 );

	add_filter( pre . 'post_mail_body', array( $this, 'get_body' ), 10, 2 );
	add_filter( pre . 'post_mail_subject', array( $this, 'get_subject' ), 10, 2 );
}

/**
 * Misuses the post_transition_status filter
 */
function mail_wrapper( $new, $old, $post ) {
	$this->mail( $new, $old, $post );
	return $new;
}

function mail( $new_status, $old_status, $post ) {
	$notify = false;
	if ( 'post' === $post->post_type ) {
		$notify = $this->was_published( $old_status, $new_status );
	}
	$data = compact( 'new_status', 'old_status', 'post' );
	if ( !apply_filters( pre . 'notify_post', $notify, $data ) ) {
		return;
	}
	
	$this->send( array( $data ) );
}

function get_subject( $subject, $data ) {
	$post = $data['post'];
	$type = get_post_type_object( $post->post_type );
	$type_name = $type->labels->singular_name;;
	$author = Utils::get_username( $post->post_author );
	$subject = __( '[%1$s] %2$s: "%3$s" by %4$s', __NAMESPACE__ );
	$subject = sprintf( $subject, get_bloginfo( 'name' ), $type_name,
		$post->post_title, $author );
	return $subject;
}

/**
 * supports other post types and transitions
 */
function get_body( $body, $data ) {
	//I made use of core translation strings where I throught they won't change
	// in the near future.
	$post = $data['post'];
	//Some languages may don't use the ':' character. So you can translate that
	//template
	$format = __( '%1$s: %2$s', __NAMESPACE__ ) . "\n";

	$type = get_post_type_object( $post->post_type )->labels->singular_name;
	$body .= sprintf( $format, $type, $post->post_title );

	$transition = __( 'Updated' );
	if ( $this->was_published( $data['old_status'], $data['new_status'] ) ) {
		$transition = __( 'Published' );
	}
	$transition = apply_filters( pre . 'transition_name', $transition, $data );
	$body .= sprintf( $format, __( 'Action', __NAMESPACE__ ), $transition );

	$author = Utils::get_username( $post->post_author );
	$body .= sprintf( $format, __( 'Username' ), $author );

	$url = get_permalink( $post->ID );
	$body .= sprintf( $format, __( 'URL', __NAMESPACE__ ), $url );

	$content = wp_strip_all_tags( $post->post_content );
	$body .= sprintf( __( "Content:\n%s", __NAMESPACE__ ), $content ) . "\n";

	return apply_filters( pre . 'get_post_mail_body', $body, $data );
}

/**
 * Determines whether the post was set from a non-public to a public status
 * @return bool published?
 */
public function was_published( $old_status, $new_status ) {
	$was_public = $this->is_public_status( $old_status );
	$is_public = $this->is_public_status( $new_status );
	return $is_public && !$was_public;
}

}
