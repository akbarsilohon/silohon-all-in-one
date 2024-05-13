<?php
/**
 * Register shortcode
 * 
 * Silohon All In One Plugin Wordpress
 * 
 * @package silohon-all-in-one
 * 
 * @link https://github.com/akbarsilohon/silohon-all-in-one.git
 */


define( 'SLS_JS' , plugin_dir_url( __FILE__ ) . '../js/shortcode.js' );
add_action( 'admin_init', function(){
    global $typenow;

    if( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' )){
        return;
    }

    if( get_user_option( 'rich_editing' ) == 'true' ){

        add_filter( 'mce_external_plugins', function( $plugin_array ){
            $plugin_array['silohon_all_in_one'] = SLS_JS;
            return $plugin_array;
        });


        add_filter( 'mce_buttons', function( $buttons ){
            array_push( $buttons, 'silohon_all_in_one' );
            return $buttons;
        });
    }
});



/**
 * FAQs Shortcode
 * 
 * @package silohon-all-in-one
 */
add_shortcode( 'add_faq', function( $atts, $content = null ){
    $myOptions = get_option('sls_options');
    $faqValue = isset($myOptions['faqMcolor']) ? $myOptions['faqMcolor'] : '#90ee90';
    $title = !empty($atts['judul']) ? $atts['judul'] : 'FAQs';
    $intro = !empty($atts['paragraf']) ? '<p>'. $atts['paragraf'] .'</p>' : '';

    preg_match_all('/\[faq_q\](.*?)\[\/faq_q\](?:\s*<p>|\s*<\/p>)/s', $content, $question_matches);
    preg_match_all('/\[faq_a\](.*?)\[\/faq_a\](?:\s*<p>|\s*<\/p>)/s', $content, $answer_matches);

    $ouput = '<style>.sl_newFaqs{margin-bottom:1rem}.sl_newFaqs .faqHeader{font-weight:700;display:flex;align-items:center;justify-content:space-between;gap:1rem;padding:10px 0;border-bottom:1px solid '.$faqValue.';margin-bottom:25px;font-size:16px;transition:.5s}.faqHeader span{word-wrap:break-word;line-height:25px}.faqHeader #faqToggle{background-color:'.$faqValue.';color:#fff;padding:5px 10px;font-weight:700;cursor:pointer}.sl_newFaqs .jawabFaq{display:none;transition:.5s}</style>';

    $title_id = strtolower(str_replace(' ', '_', $title));
    $ouput .= '<h2 id="' . $title_id . '">' . $title . '</h2>';
    $ouput .= $intro;

    $ouput .= '<div class="sl_newFaqs">';

    for( $i = 0; $i < count($question_matches[1]); $i++ ){
        $question = trim($question_matches[1][$i]);
        $answer = trim($answer_matches[1][$i]);

        $ouput .= '<div class="faqHeader">';
        $ouput .= '<span class"faqPertanyaan">' . esc_html( $question ) . '</span><span id="faqToggle">+</span>';
        $ouput .= '</div>';
        $ouput .= '<p class="jawabFaq">'. $answer .'</p>';
    }

    $ouput .= '</div>';

    $meta_faq = get_post_meta( get_the_ID(), 'silohon_faq_meta', true );
    if(!empty($meta_faq) && $meta_faq === 'true'){
        $json_ld = array(
            "@context" => "https://schema.org",
            "@type" => "FAQPage",
            "mainEntity" => array()
        );

        for( $i = 0; $i < count($question_matches[1]); $i++ ){
            $question = trim(strip_tags($question_matches[1][$i]));
            $answer = trim(strip_tags($answer_matches[1][$i]));
    
            $json_ld["mainEntity"][] = array(
                "@type" => "Question",
                "name" => esc_html( $question ),
                "acceptedAnswer" => array(
                    "@type" => "Answer",
                    "text" => esc_html( $answer )
                )
            );
        }
        $json_ld_string = json_encode($json_ld, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        $ouput .= '<script type="application/ld+json">' . $json_ld_string . '</script>';
    }

    

    $ouput .= '<script>
        document.addEventListener( "click", function( event ){
            var toggleButton = event.target;
        
            if( toggleButton.id === "faqToggle" ){
                var answer = toggleButton.parentNode.nextElementSibling;
                if( answer.style.display === "block" ){
                    answer.style.display = "none";
                    toggleButton.textContent = "+";
                } else{
                    answer.style.display = "block";
                    toggleButton.textContent = "-";
                }
            }
        });
    </script>';

    return $ouput;
});



/**
 * Youtube Shortcode
 * 
 * @package silohon-all-in-one
 */
add_shortcode( 'sl-yt', function($atts){
    $judulVideo = get_the_title();
    $videID = $atts['videoid'];

    if( !empty($videID)){

        $shortCodeYt = '<div style="width: 100%;height: 100%;box-shadow: 6px 6px 10px rgba(0, 0, 0, .3);margin-bottom: 25px;">';
        $shortCodeYt .= '<div style="position: relative;padding-bottom: 56.15%;height: 0;overflow: hidden;">';
        $shortCodeYt .= '<iframe style="position: absolute;top: 0;left: 0;width: 100%;height: 100%; border: 0;" loading="lazy"srcdoc="<style>*{padding:0;margin:0;overflow:hidden}body,html{height:100%}img{position:absolute;width:100%;height:auto;top:0;bottom:0;margin:auto}svg{filter:drop-shadow(1px 1px 6px hsl(206.5, 70.7%, 8%));transition:250ms ease-in-out}body:hover svg{filter:drop-shadow(1px 1px 6px hsl(206.5, 0%, 10%);)}svg {position: absolute;width: 50px;height: auto;left: 50%;top: 50%;transform: translate(-50%, -50%);}</style><a href=\'https://www.youtube.com/embed/'. $videID .'?autoplay=1\'><img src=\'https://i.ytimg.com/vi/'. $videID .'/sddefault.jpg\' alt=\''. $judulVideo .'\'><svg width=\'64px\' height=\'64px\' viewBox=\'0 -3 20 20\' version=\'1.1\' xmlns=\'http://www.w3.org/2000/svg\' xmlns:xlink=\'http://www.w3.org/1999/xlink\' fill=\'#e74b2c\'><g id=\'SVGRepo_bgCarrier\' stroke-width=\'0\'></g><g id=\'SVGRepo_tracerCarrier\' stroke-linecap=\'round\' stroke-linejoin=\'round\'></g><g id=\'SVGRepo_iconCarrier\'><title>youtube [#e74b2c]</title><desc>Created with Sketch.</desc><defs> </defs><g id=\'Page-1\' stroke=\'none\' stroke-width=\'1\' fill=\'none\' fill-rule=\'evenodd\'> <g id=\'Dribbble-Light-Preview\' transform=\'translate(-300.000000, -7442.000000)\' fill=\'#fff\'><g id=\'icons\' transform=\'translate(56.000000, 160.000000)\'><path d=\'M251.988432,7291.58588 L251.988432,7285.97425 C253.980638,7286.91168 255.523602,7287.8172 257.348463,7288.79353 C255.843351,7289.62824 253.980638,7290.56468 251.988432,7291.58588 M263.090998,7283.18289 C262.747343,7282.73013 262.161634,7282.37809 261.538073,7282.26141 C259.705243,7281.91336 248.270974,7281.91237 246.439141,7282.26141 C245.939097,7282.35515 245.493839,7282.58153 245.111335,7282.93357 C243.49964,7284.42947 244.004664,7292.45151 244.393145,7293.75096 C244.556505,7294.31342 244.767679,7294.71931 245.033639,7294.98558 C245.376298,7295.33761 245.845463,7295.57995 246.384355,7295.68865 C247.893451,7296.0008 255.668037,7296.17532 261.506198,7295.73552 C262.044094,7295.64178 262.520231,7295.39147 262.895762,7295.02447 C264.385932,7293.53455 264.28433,7285.06174 263.090998,7283.18289\' id=\'youtube-[#e74b2c]\'></path></g></g></g></g></svg></a>"></iframe>';
        $shortCodeYt .= '</div>';
        $shortCodeYt .= '</div>';

        return $shortCodeYt;
    } else{
        return '';
    }
});