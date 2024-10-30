<?php

namespace highfly;

class Deinstall {
	public static function register( $file ) {
		register_uninstall_hook( $file, array( __CLASS__, 'start' ) );
	}

	public static function start() {
		if ( !is_multisite() ) {
			self::on_current_blog();
			return;
		}

		$blogs = wp_get_sites( array( 'limit' => 0 ) );
		foreach ( $blogs as $blog ) {
			switch_to_blog( $blog['blog_id'] );
			self::on_current_blog();
			restore_current_blog ();
		}
	}

	public static function on_current_blog() {
		delete_option( pre . 'email_addresses' );
	}
}
