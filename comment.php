<?php

namespace highfly;

//Comment notifications
class Comment extends Notifier {

function type() {
	return 'comment';
}

function __construct() {
	add_filter( 'comment_post', array( $this, 'posted' ), 10, 2 );
	add_filter( 'transition_comment_status',
		array( $this, 'transition' ), 10, 3 );
	add_filter( 'comment_notification_recipients',
		array( $this, 'kick_redundant_recipients' ) );

	add_filter( pre . 'comment_mail_body', array( $this, 'get_body' ), 10, 2 );
	add_filter( pre . 'comment_mail_subject',
		array( $this, 'get_subject' ), 10, 2 );
}

function posted( $id, $approved ) {
	if ( $approved && 'spam' !== $approved ) {
		$this->mail( get_comment( $id ) );
	}
}

function transition( $new_status, $old_status, $comment ) {
	//Transition hook is not called if new_status == old_status
	if ( 'approved' === $new_status ) {
		$this->mail( $comment );
	}
}

function mail( $comment ) {
	$status = get_post_status( $comment->comment_post_ID );
	if ( !$this->is_public_status( $status ) ) {
		return;
	}
	$this->send( array( $comment ) );
}

function kick_redundant_recipients( $default_emails ) {
	return array_filter( $default_emails,
		array( $this, 'is_not_recipient' ) );
}

function is_not_recipient( $email ) {
	return !in_array( $email, $this->get_cached_recipients() );
}

function get_body( $body, $comment ) {
	$body = $this->get_comment_line( $comment ) . "\n";
	$link = get_comment_link( $comment );
	$body .= sprintf( __( 'URL: %s', __NAMESPACE__ ), $link ) . "\n";
	$content_name = __( 'Excerpt', __NAMESPACE__ );
	if ( empty( $comment->comment_type ) ) {
		$content_name = __( 'Comment', __NAMESPACE__ );
	}
	$body .= "$content_name:\n";
	$body .= $comment->comment_content;
	return apply_filters( pre . 'get_comment_mail_body', $body );
}

function get_comment_line( $comment ) {
	$strings = array(
		'' => __( 'New comment to "%s"', __NAMESPACE__ ),
		'trackback' => __( 'New trackback to "%s"', __NAMESPACE__ ),
		'pingback' => __( 'New pingback to "%s"', __NAMESPACE__ ),
		);
	$post = get_post( $comment->comment_post_ID );
	return sprintf( $strings[$comment->comment_type], $post->post_title );
}

function get_subject( $subject, $comment ) {
	return $this->get_comment_line( $comment );
}

}
