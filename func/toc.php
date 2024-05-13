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
 * Remove br
 * 
 * @package silohon-all-in-one
 */
add_filter( 'the_content', 'silohon_adding_id_to_heading', 11 );
function silohon_adding_id_to_heading( $content ){

    $content = preg_replace('/\s*<br\s*\/?>\s*|\s*\n\s*<br\s*\/?>\s*|\s*<br\s*\/?>\s*\n\s*|\s*\n\s*/i', '', $content);
    return $content;
}



/**
 * Adding ID to heading the Content
 * 
 * This for h2 & h3
 * 
 * @package silohon-all-in-one
 */
add_filter( 'the_content', 'silohon_generate_and_adding_id_to_heading', 12 );
function silohon_generate_and_adding_id_to_heading( $content ){
    $tocOption = get_option( 'sls_options' );
    $disable_toc = get_post_meta( get_the_ID(), 'silohon_disable_toc', true );

    if( empty( $tocOption['toc'] ) || $disable_toc === 'true' ){
        return $content;
    } else{
        preg_match('/<h2>(.*?)<\/h2>/i', $content, $first_h2_match, PREG_OFFSET_CAPTURE);

        if( !empty( $first_h2_match )){
            $toc = silohon_build_toc_html_output( $content );
            $content = substr_replace($content, $toc, $first_h2_match[0][1], 0);
        }

        $content = preg_replace_callback('/<h([2-3])>(.*?)<\/h[2-3]>/i', function($matches) {
            $tag = $matches[1];
            $text = $matches[2];
            $isReplace = array(
                ' ', '.', ',', '-',
                '?', '!', '#', '*', '#', '"',
                '@', '$', '%', '^', '(', ')', '+',
                '=', '{', '}', '[', ']', ':', '\''
            );
            $id = strtolower(str_replace($isReplace, '_', preg_replace('/\s+/', '-', preg_replace('/[^a-zA-Z0-9\s]/', '', $text))));
            return '<h' . $tag . ' id="' . $id . '">' . $text . '</h' . $tag . '>';
        }, $content);

        return $content;
    }
}



/**
 * silohon_build_toc_html_output
 * 
 * @package silohon-all-in-one
 */
function silohon_build_toc_html_output( $content ){
    $toc = '';

    $toc .= '<div class="silo_toc">';
    $toc .= '<div class="toc-title">';
    $toc .= '<p class="this_toc">Table of Contents:</p>';
    $toc .= '<div id="silo_icon_toc" class="silo_icon_toc">';
    $toc .= '<span></span><span></span><span></span>';
    $toc .= '</div>';
    $toc .= '</div>';
    $toc .= '<ul id="this_toc_counters" class="tocNone">';

    preg_match_all('/<h([2-3])>(.*?)<\/h[2-3]>/i', $content, $matches);
    $level = 2; // Start at h2 level
    foreach ($matches[1] as $key => $tag) {
        $text = $matches[2][$key];
        $isReplace = array(
            ' ', '.', ',', '-',
            '?', '!', '#', '*', '#', '"',
            '@', '$', '%', '^', '(', ')', '+',
            '=', '{', '}', '[', ']', ':', '\''
        );
        $id = strtolower(str_replace($isReplace, '_', preg_replace('/\s+/', '-', preg_replace('/[^a-zA-Z0-9\s]/', '', $text))));
        if ($tag == 2) {
            if ($level > 2) {
                $toc .= '</ul></li>';
            }
            $toc .= '<li><a title="'. $text .'" href="#' . $id . '">' . $text . '</a>';
            $level = 2;
        } elseif ($tag == 3) {
            if ($level == 2) {
                $toc .= '<ul>';
            }
            $toc .= '<li><a title="'. $text .'" href="#' . $id . '">' . $text . '</a></li>';
            $level = 3;
        }
    }

    if ($level == 3) {
        $toc .= '</ul>';
    }

    $toc .= '</ul>';
    $toc .= '</div>';
    $toc .= "<script>const thisTOC = document.getElementById('silo_icon_toc');const thiTOCTar = document.getElementById('this_toc_counters');thisTOC.addEventListener('click', function(){thiTOCTar.classList.toggle('tocNone');});</script>";

    return $toc;
}