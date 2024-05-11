<?php
/**
 * Plugin Name: Silohon All In One
 * Plugin URI: https://github.com/akbarsilohon/silohon-all-in-one.git
 * Version: 1.3.4
 * Description: All in one plugin, support Inline Related Posts, Inser HTML to header & footer, Shortcode for FAQs, Table of content, and Youtube Embed.
 * Tags: IRP, silohon, toc, faq, youtube
 * Author: Nur Akbar
 * Author URI: https://github.com/akbarsilohon
 * 
 * @package silohon-all-in-one
 * 
 * @link https://github.com/akbarsilohon/silohon-all-in-one.git
 */


require plugin_dir_path( __FILE__ ) . '/func/shorcode.php';
require plugin_dir_path( __FILE__ ) . '/func/core.php';
require plugin_dir_path( __FILE__ ) . '/func/admin.php';
require plugin_dir_path( __FILE__ ) . '/func/irp.php';
require plugin_dir_path( __FILE__ ) . '/func/header-footer.php';
// require plugin_dir_path( __FILE__ ) . '/func/toc.php';


/**
 * Register Css for frondent
 * 
 * @package silohon-all-in-one
 */
add_action( 'wp_enqueue_scripts', function(){
    wp_enqueue_style( 'sls_front_end_style', plugin_dir_url( __FILE__ ) . '/css/front.css', [], fileatime( plugin_dir_path( __FILE__ ) . '/css/front.css'), 'all' );
});



/**
 * Register Css for backend
 * 
 * @package silohon-all-in-one
 */
add_action( 'admin_enqueue_scripts', function(){
    wp_enqueue_style( 'slre-admin-style', plugins_url( './css/admin.css', __FILE__ ), array(), fileatime( plugin_dir_path( __FILE__ ) . '/css/admin.css'), 'all' );
});