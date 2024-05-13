<?php
/**
 * Admin control Silohon All In One Plugin
 * 
 * Wordpress Plugin
 * 
 * @package silohon-all-in-one
 * 
 * @link https://github.com/akbarsilohon/silohon-all-in-one.git
 */

add_action( 'admin_menu', function(){

    /**
     * Welcome
     * 
     * @package silohon-all-in-one
     */
    add_menu_page( 
        'Silohon All In One', 
        'Silohon All In One', 
        'manage_options', 
        'sls_admin', 
        'sls_admin_welcome',
        'dashicons-media-code', 
        3
    );

    add_submenu_page( 
        'sls_admin', 
        'Welcome', 
        'Welcome', 
        'manage_options', 
        'sls_admin',
        'sls_admin_welcome'
    );

    /**
     * Inline related Posts
     * 
     * @package silohon-all-in-one
     */
    add_submenu_page( 
        'sls_admin', 
        'Inline Related Posts', 
        'Inline Related Posts', 
        'manage_options', 
        'sls_irp', 
        'sls_admin_irp'
    );


    /**
     * Header & footer panel controll
     * 
     * @package silohon-all-in-one
     */
    add_submenu_page( 
        'sls_admin', 
        'Inser Header & Footer', 
        'Header & Footer', 
        'manage_options', 
        'sls_hnf', 
        'sls_render_panel_header_and_footer' 
    );
});


// Welcome
function sls_admin_welcome(){ ?>

<div class="sl_re-container">
    <div class="sl_re-content">
        <h1 class="sl_re-h1">Silohon All In One</h1>
        <p class="sl_re-p">Next Update for this feature</p>
        <form action="options.php" method="post" class="sl_re-form">
            <?php settings_fields( 'sl-all-settings' ); ?>
            <?php do_settings_sections( 'sls_admin' ); ?>
            <?php submit_button('Save'); ?>
        </form>
    </div>

    <div class="sl_re-sidebar">
        <div class="sl_re-author"></div>
    </div>
</div>

<?php
}


// Inline related posts
function sls_admin_irp(){ ?>

<div class="sl_re-container">
    <div class="sl_re-content">
        <h1 class="sl_re-h1">Inline Related Posts</h1>
        <form action="options.php" method="post" class="sl_re-form">
            <?php settings_fields( 'sl-re-settings' ); ?>
            <?php do_settings_sections( 'sls_irp' ); ?>
            <?php submit_button('Save'); ?>
        </form>
    </div>

    <div class="sl_re-sidebar">
        <div class="sl_re-author"></div>
    </div>
</div>

<?php
}

// Header & Footer
function sls_render_panel_header_and_footer(){ ?>

<div class="sl_re-container">
    <div class="sl_re-content">
        <h1 class="sl_re-h1">Insert HTML</h1>
        <form action="options.php" method="post" class="sl_re-form">
            <?php settings_fields( 'sl-hnf-settings' ); ?>
            <?php do_settings_sections( 'sls_hnf' ); ?>
            <?php submit_button('Save'); ?>
        </form>
    </div>

    <div class="sl_re-sidebar">
        <div class="sl_re-author"></div>
    </div>
</div>

<?php
}


/**
 * Generate Related Posts By Silohon
 * 
 * @package silohon-all-in-one
 */
$options = get_option('sl_re_options');
if( !empty($options['active']) && $options['active'] === 'true' ){
    add_filter( 'the_content', 'silohon_irp_active', 99 );
}


/**
 * Meta boxes
 * 
 * silohon_add_related_posts_meta_box
 * 
 * @package silohon-all-in-one
 */
function silohon_add_related_posts_meta_box() {
    add_meta_box(
        'show_related_posts_meta_box',
        'Silohon All In One',
        'silohon_display_related_posts_meta_box',
        'post',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'silohon_add_related_posts_meta_box');


function silohon_display_related_posts_meta_box($post) {
    $disable_silohon_irp = get_post_meta($post->ID, 'silohon_irp_disable', true);
    $use_faq_meta = get_post_meta( $post->ID, 'silohon_faq_meta', true );
    $disable_silohon_toc = get_post_meta( $post->ID, 'silohon_disable_toc', true );
    ?>
    <input type="checkbox" name="silohon_irp_disable" id="silohon_irp_disable" <?php checked($disable_silohon_irp, 'true'); ?> />
    <label for="silohon_irp_disable">Disable Silohon IRP</label><br>

    <input type="checkbox" name="silohon_disable_toc" id="silohon_disable_toc" <?php checked($disable_silohon_toc, 'true'); ?> />
    <label for="silohon_disable_toc">Disable Silohon TOC</label><br>

    <input type="checkbox" name="silohon_faq_meta" id="silohon_faq_meta" <?php checked($use_faq_meta, 'true'); ?>>
    <label for="silohon_faq_meta">Use FAQ Meta SEO</label><br>

    <?php
}

function silohon_save_related_posts_meta_box($post_id) {

    // DIsable Related Posts
    if (isset($_POST['silohon_irp_disable'])) {
        update_post_meta($post_id, 'silohon_irp_disable', 'true');
    } else {
        delete_post_meta($post_id, 'silohon_irp_disable');
    }


    // Adding FAQs meta SEO
    if(isset($_POST['silohon_faq_meta'])){
        update_post_meta( $post_id, 'silohon_faq_meta', 'true' );
    } else{
        delete_post_meta($post_id, 'silohon_faq_meta');
    }

    // Disable TOC
    if(isset($_POST['silohon_disable_toc'])){
        update_post_meta( $post_id, 'silohon_disable_toc', 'true' );
    } else{
        delete_post_meta($post_id, 'silohon_disable_toc');
    }
}
add_action('save_post', 'silohon_save_related_posts_meta_box');



/**
 * Main Admin Panel
 * 
 * @package silohon-all-in-one
 */
add_action( 'admin_init', function(){
    add_settings_section( 'sls-section-1', null, null, 'sls_admin' );
    register_setting( 'sl-all-settings', 'sls_options' );

    // Auto table of Content
    add_settings_field( 
        'sls-toc', 
        'Auto Table Of Content', 
        function(){
            $myOptions = get_option('sls_options');
            $tocValue = isset($myOptions['toc']) ? $myOptions['toc'] : ''; ?>

            <input type="checkbox" name="sls_options[toc]" value="true" <?php checked( $tocValue, 'true' ); ?>>

            <?php
        }, 
        'sls_admin', 
        'sls-section-1'
    );

    // Faq main color
    add_settings_field( 
        'sls-faq', 
        'FAQS Main Color', 
        function(){
            $myOptions = get_option('sls_options');
            $faqValue = isset($myOptions['faqMcolor']) ? $myOptions['faqMcolor'] : '#90ee90'; ?>

            <input type="color" name="sls_options[faqMcolor]" value="<?php echo $faqValue; ?>">

            <?php
        }, 
        'sls_admin', 
        'sls-section-1'
    );
});