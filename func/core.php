<?php
/**
 * Active related posts for silohon irp
 * 
 * @package silohon-all-in-one
 * 
 * @package https://github.com/akbarsilohon/silohon-all-in-one.git
 */

function silohon_irp_active( $content ){
    global $post;
    $meta_checker = get_post_meta( $post->ID, 'silohon_irp_disable', true );

    if( empty( $content ) || $meta_checker === 'true' ){
        return $content;
    } else{
        $kata_per_injeksi = !empty(get_option('sl_re_options')['limit']) ? get_option('sl_re_options')['limit'] : 300;
        $pengulangan = get_option('sl_re_options')['jumlah'];
        $kata_tambahan = render_related_func($post->ID);

        $dom = new DOMDocument();
        @$dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $xpath = new DOMXPath($dom);
        $totalKata = 0;
        $jumlah_dalam_artikel = !empty( $pengulangan ) ? $pengulangan : 3;
        $pengulanganSaatIni = 0;
        $nodes = $xpath->query('//text()');

        foreach( $nodes as $node ){
            if( $node->parentNode->nodeName === 'p' ){
                $kata = explode(' ', trim($node->nodeValue));
                $jumlahKata = count($kata);
                $totalKata += $jumlahKata;

                if ($totalKata >= $kata_per_injeksi && !empty($kata_tambahan) && $pengulanganSaatIni < $jumlah_dalam_artikel) {
                    $fragment = $dom->createDocumentFragment();
                    $random_key = array_rand($kata_tambahan);
                    $random_post_id = $kata_tambahan[$random_key];
    
                    unset($kata_tambahan[$random_key]);
    
                    $related_post_content = render_html_related_post($random_post_id);
                    if (!empty($related_post_content)) {
                        $fragment->appendXML($related_post_content);
                        
                        if ($node->parentNode->nextSibling) {
                            $node->parentNode->parentNode->insertBefore($fragment, $node->parentNode->nextSibling);
                        } else {
                            $node->parentNode->parentNode->appendChild($fragment);
                        }
                        $totalKata = 0;
                        $pengulanganSaatIni++;
                    }
                }
            }
        }

        $content = $dom->saveHTML();
        return $content;
    }
}


/**
 * Create custom query related posts
 * 
 * @package silohon-all-in-one
 */
function render_related_func($current_post_id) {
    $terkait = get_option('sl_re_options')['terkait'];
    $kategori = get_the_category($current_post_id);
    $tags = wp_get_post_tags($current_post_id, array('fields' => 'ids'));
    $jumlah = !empty(get_option('sl_re_options')['jumlah']) ? get_option('sl_re_options')['jumlah'] : 3;

    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $jumlah,
        'post__not_in' => array($current_post_id),
        'fields' => 'ids',
        'orderby'   =>  'rand'
    );

    if( $terkait === 'category' ){
        $args['tax_query'] = array(
            'relation' => 'OR',
            array(
                'taxonomy' => 'category',
                'field'    => 'term_id',
                'terms'    => wp_list_pluck($kategori, 'term_id'),
            ),
        );
    } else if( $terkait === 'tag' ){
        $args['tax_query'] = array(
            'relation' => 'OR',
            array(
                'taxonomy' => 'post_tag',
                'field'    => 'term_id',
                'terms'    => $tags,
            ),
        );
    } else if( $terkait === 'cat_tag' ){
        $args['tax_query'] = array(
            'relation' => 'OR',
            array(
                'taxonomy' => 'category',
                'field'    => 'term_id',
                'terms'    => wp_list_pluck($kategori, 'term_id'),
            ),
            array(
                'taxonomy' => 'post_tag',
                'field'    => 'term_id',
                'terms'    => $tags,
            ),
        );
    }



    $query = new WP_Query($args);
    return $query->posts;
}


/**
 * Render html Output
 * 
 * @package silohon-all-in-one
 */
function render_html_related_post($post_id) {

    $thumbnailUri = '';
    if(has_post_thumbnail( $post_id )){
        $thumbnailUri = get_the_post_thumbnail_url( $post_id, 'thumbnail' );
    } else{
        $getContent = get_post_field('post_content', $post_id);
        $array_thumbnail = '';

        preg_match('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $getContent, $array_thumbnail);

        if (!empty($array_thumbnail[1])) {
            $thumbnailUri = esc_url( $array_thumbnail[1] );
        } else {
            $thumbnailUri = esc_url( plugin_dir_url( __FILE__ ) . '../img/thumb1.webp' );
        }
    }

    $bgColor = !empty( get_option('sl_re_options')['bg_color']) ? get_option('sl_re_options')['bg_color'] : '#333';
    $borderColor = !empty( get_option('sl_re_options')['border_color']) ? get_option('sl_re_options')['border_color'] : '#90ee90';
    $textColor = !empty( get_option('sl_re_options')['text_color']) ? get_option('sl_re_options')['text_color'] : '#fff';
    $text = !empty( get_option('sl_re_options')['text_read_to']) ? get_option('sl_re_options')['text_read_to'] : 'Baca juga:';
    $link = !empty( get_option('sl_re_options')['type_link']) ? get_option('sl_re_options')['type_link'] : 'nofollow';
    $target = !empty( get_option('sl_re_options')['target']) ? get_option('sl_re_options')['target'] : '_blank';
    $style = get_option('sl_re_options')['style'];

    if( $style === 'style2' ){
        $outputHtml = '<a target="'.$target.'" href="'. esc_url(get_the_permalink( $post_id )) .'" rel="'.$link.'" title="'.get_the_title($post_id).'" class="silohon-irp2" style="background-color: '.$bgColor.';border-left: 4px solid '.$borderColor.';">';
        $outputHtml .= '<div class="irp-relative2">';
        $outputHtml .= '<p class="irp-title2" style="color: '.$textColor.';">'.get_the_title($post_id).'</p>';
        $outputHtml .= '<span class="irp-button2" style="background-color: '.$borderColor.'; color: #ffffff;">'.$text.'</span>';
        $outputHtml .= '</div>';
        $outputHtml .= '<img style="border-left: 4px solid '.$borderColor.';" src="' . $thumbnailUri . '" alt="' . get_the_title($post_id) . '" loading="lazy" class="re-thumbnail2"/>';
        $outputHtml .= '</a>';
    } else if( $style === 'style3' ){
        $outputHtml = '<a target="'.$target.'" href="'. esc_url(get_the_permalink( $post_id )) .'" rel="'.$link.'" title="'.get_the_title($post_id).'" class="silohon-irp3">';
        $outputHtml .= '<img style="border: 4px solid '.$borderColor.';" src="' . $thumbnailUri . '" alt="' . get_the_title($post_id) . '" loading="lazy" class="re-thumbnail3"/>';
        $outputHtml .= '<div class="irp-relative3" style="background-color: '.$bgColor.';">';
        $outputHtml .= '<p class="irp-title3" style="color: '.$textColor.';">'.get_the_title($post_id).'</p>';
        $outputHtml .= '<span class="irp-button3" style="background-color: '.$borderColor.'; color: #ffffff;">'.$text.'</span>';
        $outputHtml .= '</div>';
        $outputHtml .= '</a>';
    } else{
        $outputHtml = '<a target="'.$target.'" href="'. esc_url(get_the_permalink( $post_id )) .'" rel="'.$link.'" title="'.get_the_title($post_id).'" class="silohon-irp" style="background-color: '.$bgColor.';border-left: 4px solid '.$borderColor.';">';
        $outputHtml .= '<div class="irp-relative">';
        $outputHtml .= '<span class="irp-button" style="background-color: '.$borderColor.'; color: #ffffff;">'.$text.'</span>';
        $outputHtml .= '<p class="irp-title" style="color: '.$textColor.';">'.get_the_title($post_id).'</p>';
        $outputHtml .= '</div>';
        $outputHtml .= '<img src="' . $thumbnailUri . '" alt="' . get_the_title($post_id) . '" loading="lazy" class="re-thumbnail"/>';
        $outputHtml .= '</a>';
    }

    return $outputHtml;
}