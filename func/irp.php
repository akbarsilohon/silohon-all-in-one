<?php
/**
 * Pengaturan di Admin panel silohon-related
 * 
 * @package silohon-all-in-one
 * 
 * @link https://github.com/akbarsilohon/silohon-all-in-one.git
 */

add_action( 'admin_init', 'sl_re_settings_init' );

function sl_re_settings_section_callback() {
    echo '<p class="sl_re-p">Customize the style and appearance of Silohon Related Post, set the number of inline related posts in each article, set the injection at every word count, choose the link type, target link, and adjust the color to your liking.</p>';
}

function sl_re_bg_color_render() {
    $options = get_option('sl_re_options');
    $bgColor = !empty($options['bg_color']) ? $options['bg_color'] : '#333333';
    ?>
    <input type='color' name='sl_re_options[bg_color]' value='<?php echo esc_attr($bgColor); ?>'>
    <?php
}

function sl_re_border_color_render() {
    $options = get_option('sl_re_options');
    $borderColor = !empty($options['border_color']) ? $options['border_color'] : '#e74b2c';
    ?>
    <input type='color' name='sl_re_options[border_color]' value='<?php echo esc_attr($borderColor); ?>'>
    <?php
}

function sl_re_text_color_render() {
    $options = get_option('sl_re_options');
    $textColor = !empty( $options['text_color']) ? $options['text_color'] : '#ffffff';
    ?>
    <input type='color' name='sl_re_options[text_color]' value='<?php echo esc_attr( $textColor ); ?>'>
    <?php
}

function sl_re_limit_render(){
    $options = get_option('sl_re_options');
    $reCount = !empty($options['limit']) ? $options['limit'] : 300; ?>
    <input type='number' name='sl_re_options[limit]' value='<?php echo esc_attr($reCount); ?>'>
    <?php
}

function sl_re_jumlah_render(){
    $options = get_option('sl_re_options');
    $reJumlah = !empty($options['jumlah']) ? $options['jumlah'] : 3; ?>
    <input type='number' name='sl_re_options[jumlah]' value='<?php echo esc_attr($reJumlah); ?>'>
    <?php
}

function sl_re_text_read_to_render(){
    $options = get_option('sl_re_options');
    $reText = !empty($options['text_read_to']) ? $options['text_read_to'] : 'Read Too:'; ?>
    <input type='text' name='sl_re_options[text_read_to]' value='<?php echo esc_attr($reText); ?>'>
    <?php
}

function sl_re_type_link_render(){
    $options = get_option('sl_re_options'); ?>
    <select name='sl_re_options[type_link]'>
        <option value='nofollow' <?php selected( $options['type_link'], 'nofollow' ); ?>>No Follow</option>
        <option value='dofollow' <?php selected( $options['type_link'], 'dofollow' ); ?>>Do Follow</option>
    </select>
    <?php
}

function sl_re_target_render(){
    $options = get_option('sl_re_options'); ?>
    <select name='sl_re_options[target]'>
        <option value='_blank' <?php selected( $options['target'], '_blank' ); ?>>_blank</option>
        <option value='_self' <?php selected( $options['target'], '_self' ); ?>>_self</option>
    </select>
    <?php
}

function sl_re_terkait_render(){
    $options = get_option('sl_re_options'); ?>
    <select name='sl_re_options[terkait]'>
        <option value='all' <?php selected( $options['terkait'], 'all' ); ?>>All Posts</option>
        <option value='category' <?php selected( $options['terkait'], 'category' ); ?>>Category Only</option>
        <option value='tag' <?php selected( $options['terkait'], 'tag' ); ?>>Tag Only</option>
        <option value="cat_tag" <?php selected( $options['terkait'], 'cat_tag' ); ?>>Category & Tag</option>
    </select>
    <?php
}


function sl_re_settings_init() {
    register_setting( 'sl-re-settings', 'sl_re_options' );

    add_settings_section(
        'sl_re_plugin_section',
        __( null, 'wordpress' ),
        'sl_re_settings_section_callback',
        'sls_irp'
    );

    // Active or not ----------------
    add_settings_field( 
        'sl_re_active', 
        'Active', function(){
            $options = get_option('sl_re_options'); ?>

            <input type="checkbox" name="sl_re_options[active]" value="true" <?php if(!empty($options['active']) && $options['active'] === 'true' ) echo 'checked'; ?>>

            <?php
        },
        'sls_irp',
        'sl_re_plugin_section'
    );


    // Chose your style --------------------------
    add_settings_field( 
        'sl_re_style',
        'Style',
        function(){
            $options = get_option('sl_re_options');
            $text = !empty($options['text_read_to']) ? $options['text_read_to'] : 'Read Too:';
            $bg = !empty($options['bg_color']) ? $options['bg_color'] : '#e5ac1b';
            $border = !empty($options['border_color']) ? $options['border_color'] : '#000000';
            $color = !empty($options['text_color']) ? $options['text_color'] : '#000000'; ?>

            <div class="choose_style">
                <input type="radio" name="sl_re_options[style]" value="" <?php checked( $options['style'], '' ); ?>>
                <div class="silohon-irp" style="background-color: <?php echo $bg; ?>;border-left: 4px solid <?php echo $border; ?>;">
                    <div class="irp-relative">
                        <span class="irp-button" style="background-color: <?php echo $border; ?>; color: #ffffff;"><?php echo $text; ?></span>
                        <p class="irp-title" style="color: <?php echo $color; ?>;">Example Style 1 Related Articles Plugin By Silohon</p>
                    </div>
                    <img src="<?php echo plugin_dir_url( __FILE__ ) . '../img/thumb1.webp'; ?>" class="re-thumbnail">
                </div>
            </div>

            <div class="choose_style">
                <input type="radio" name="sl_re_options[style]" value="style2" <?php checked( $options['style'], 'style2' ); ?>>
                <div class="silohon-irp2" style="border-left: 4px solid <?php echo $border; ?>;background-color: <?php echo $bg; ?>;">
                    <div class="irp-relative2">
                        <p class="irp-title2" style="color:<?php echo $color; ?>;">Example Style 2 Related Articles Plugin By Silohon</p>
                        <span class="irp-button2" style="background-color: <?php echo $border; ?>;color:#ffffff;"><?php echo $text; ?></span>
                    </div>
                    <img style="border-left: 4px solid <?php echo $border; ?>;" src="<?php echo plugin_dir_url( __FILE__ ) . '../img/thumb1.webp'; ?>" class="re-thumbnail2">
                </div>
            </div>

            <div class="choose_style">
                <input type="radio" name="sl_re_options[style]" value="style3" <?php checked( $options['style'], 'style3' ); ?>>
                <div class="silohon-irp3">
                    <img style="border: 4px solid <?php echo $border; ?>;" src="<?php echo plugin_dir_url( __FILE__ ) . '../img/thumb1.webp'; ?>" class="re-thumbnail3">
                    <div class="irp-relative3" style="background-color: <?php echo $bg; ?>;border-right: 4px solid <?php echo $border; ?>;">
                        <p class="irp-title3" style="color:<?php echo $color; ?>;">Example Style 3 Related Articles Plugin By Silohon</p>
                        <span class="irp-button3" style="background-color: <?php echo $border; ?>;color:#ffffff;"><?php echo $text; ?></span>
                    </div>
                </div>
            </div>

            <?php
        },
        'sls_irp',
        'sl_re_plugin_section'
    );

    // Jumlah artikel di injek --------------------
    add_settings_field(
        'sl_re_jumlah',
        __( 'Inline Related Count', 'wordpress' ),
        'sl_re_jumlah_render',
        'sls_irp',
        'sl_re_plugin_section'
    );

    // Injek setelah berapa kata --------------------
    add_settings_field(
        'sl_re_limit',
        __( 'Every Number of Words', 'wordpress' ),
        'sl_re_limit_render',
        'sls_irp',
        'sl_re_plugin_section'
    );

    // Text read to -----------------------------
    add_settings_field(
        'sl_re_text_read_to',
        __( 'Text Read Too', 'wordpress' ),
        'sl_re_text_read_to_render',
        'sls_irp',
        'sl_re_plugin_section'
    );

    // Type link ---------------------------------
    add_settings_field(
        'sl_re_type_link',
        __( 'Type Link', 'wordpress' ),
        'sl_re_type_link_render',
        'sls_irp',
        'sl_re_plugin_section'
    );

    // Target ---------------------------------
    add_settings_field(
        'sl_re_target',
        __( 'Target', 'wordpress' ),
        'sl_re_target_render',
        'sls_irp',
        'sl_re_plugin_section'
    );

    // Terkait dengan --------------------------
    add_settings_field(
        'sl_re_terkait',
        __( 'Related Type', 'wordpress' ),
        'sl_re_terkait_render',
        'sls_irp',
        'sl_re_plugin_section'
    );

    add_settings_field(
        'sl_re_bg_color',
        __( 'Background Color', 'wordpress' ),
        'sl_re_bg_color_render',
        'sls_irp',
        'sl_re_plugin_section'
    );

    add_settings_field(
        'sl_re_border_color',
        __( 'Border Color', 'wordpress' ),
        'sl_re_border_color_render',
        'sls_irp',
        'sl_re_plugin_section'
    );

    add_settings_field(
        'sl_re_text_color',
        __( 'Text Color', 'wordpress' ),
        'sl_re_text_color_render',
        'sls_irp',
        'sl_re_plugin_section'
    );
}