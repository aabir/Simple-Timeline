<?php
/*
Plugin Name: Simple Timeline
Description: Display Timeline with year, postion, company name and short preview description. 
Author: Shible Noman 
Version: 1.0.1
*/

// Ensure WordPress has been bootstrapped
if( !defined( 'ABSPATH' ) )
	exit;

$path = trailingslashit( dirname( __FILE__ ) );

// Ensure our class dependencies class has been defined

if( !class_exists( 'Simple_Timeline_Plugin' ) )
require_once( $path . 'class.simple-timeline.php' );

require_once($path . 'shortcode.php');

// Boot Simple Testimonials
new Simple_Timeline_Plugin();

?>
