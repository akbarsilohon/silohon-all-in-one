<?php
/**
 * Auto table of content functions
 * 
 * Silohon All In One Plugin Wordpress
 * 
 * @package silohon-all-in-one
 * 
 * @link https://github.com/akbarsilohon/silohon-all-in-one.git
 */


/**
 * Adding id to heading the_content
 * 
 * @package silohon-all-in-one
 */
// add_filter( 'the_content', 'sls_add_id_to_heading', 7, 1 );
// function sls_add_id_to_heading( $content ){
//     $content = preg_replace_callback('/<h([2-3])>(.*?)<\/h[2-3]>/i', function( $matches ){
//         $ThisReplace = array(
//             ' ', '.', ',', '-',
//             '?', '!', '#', '*', '#', '"',
//             '@', '$', '%', '^', '(', ')', '+',
//             '=', '{', '}', '[', ']', ':', '\''
//         );

//         $id = strtolower( str_replace( $ThisReplace, '_', $matches[2] ) );
//         return '<h'. $matches[1] .' id="'. $id .'">'. $matches[2] .'</h'. $matches[1] .'>';
//     }, $content );

//     return $content;
// }

/**
 * Render table of content by silohon
 * 
 * @package silohon-all-in-one
 */
