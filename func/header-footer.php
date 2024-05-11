<?php
/**
 * Create & register options silohon header & footer
 * 
 * @package silohon-all-in-one
 * 
 * @link https://github.com/akbarsilohon/silohon-all-in-one.git
 */

add_action( 'admin_init', 'sls_render_options_silohon_header_and_footer' );
function sls_render_options_silohon_header_and_footer(){
    add_settings_section( 'sls-section-hnf', null, null, 'sls_hnf' );
    register_setting( 'sl-hnf-settings', 'options_hnf' );

    /**
     * Inser HTML Header
     * 
     * @package silohon-all-in-one
     */
    add_settings_field( 
        'insert-hsls', 
        'HTML Header', 
        function(){
            $options = get_option('options_hnf');
            $htmlHeader = !empty($options['header']) ? $options['header'] : ''; ?>

            <textarea name="options_hnf[header]" cols="80" rows="7"><?php echo $htmlHeader; ?></textarea>

            <?php
        }, 
        'sls_hnf', 
        'sls-section-hnf'
    );


    /**
     * Insert HTML Footer
     * 
     * @package silohon-all-in-one
     */
    add_settings_field( 
        'insert-fsls', 
        'HTML Footer', 
        function(){
            $options = get_option('options_hnf');
            $htmlFooter = !empty($options['footer']) ? $options['footer'] : ''; ?>

            <textarea name="options_hnf[footer]" cols="80" rows="7"><?php echo $htmlFooter; ?></textarea>

            <?php
        }, 
        'sls_hnf', 
        'sls-section-hnf'
    );
}


$htmlInsert = get_option('options_hnf');

/**
 * Print HTML Header to header
 * 
 * @package silohon-all-in-one
 */
if (!empty($htmlInsert['header'])) {
    add_action('wp_head', function() use ($htmlInsert) {
        echo $htmlInsert['header'];
    });
}


/**
 * Print HTML Footer to Footer
 * 
 * @package silohon-all-in-one
 */
if(!empty($htmlInsert['footer'])){
    add_action( 'wp_footer', function() use ($htmlInsert){
        echo $htmlInsert['footer'];
    });
}