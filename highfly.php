<?php
/*
Plugin Name: Highfly
Description: Sends an email to the addresses of your choice when a new post or comment is made.
Author: edik
Author URI: http://edik.ch/
Version: 2.0
License: GPLv3

Copyright 2014  Edgard Schmidt  (email : edik(ATT)edik.ch)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

*/

namespace highfly;

//We use __DIR__ because same-named WP core files instead of ours could be
//included
require_once __DIR__ . '/utils.php';
require_once __DIR__ . '/mail.php';
require_once __DIR__ . '/notifier.php';
require_once __DIR__ . '/admin.php';
require_once __DIR__ . '/post.php';
require_once __DIR__ . '/comment.php';
require_once __DIR__ . '/deinstall.php';

class Main {

private $modules;

function __construct() {
	add_action( 'plugins_loaded', array( $this, 'load_modules' ) );
	add_action( 'init', array( $this, 'load_textdomain' ) );
	Deinstall::register( __FILE__ );
}

/**
 * You can specify own modules
 */
function load_modules() {
	$default = array(
		'admin' => 'Admin',
		'post' => 'Post',
		'comment' => 'Comment',
		);
	$this->modules = apply_filters( pre . 'custom_modules', array() );
	foreach( $default as $name => $class ) {
		if ( isset( $this->modules[$name] ) ) {
			continue;
		}
		$class = new \ReflectionClass( __NAMESPACE__ . "\\$class" );
		$this->modules[$name] = $class->newInstance();
	}
}

function &modules() {
	return $this->modules;
}

function load_textdomain() {
	$locale = apply_filters( pre . "locale", get_locale() );
	$mofile = __DIR__ . "/languages/$locale.mo";
	if ( file_exists( $mofile ) ) {
		load_textdomain( __NAMESPACE__, $mofile );
	}
}

}

$highfly = new Main();
