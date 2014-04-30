<?php
/*
 * Plugin Name: ThatBlogger Syntax Highlighter
 * Plugin URI: http://www.thatblogger.co
 * Description: A widget allows you to display code on your Website
 * Version: 2.0
 * Author: Daniel Roizer
 * Author URI: http://www.thatblogger.co
 * License: GPL2
 
 * This plugin is a fork of the plugin Paulund Syntax Highlighter by http://www.paulund.co.uk
*/

add_action('wp_enqueue_scripts', 'pu_load_styles');
function pu_load_styles()
{
    wp_enqueue_script( 'prism_js', plugins_url( '/js/prism.js' , __FILE__ ) , array( 'jquery' ), NULL, true );
    wp_enqueue_style( 'prism_css', plugins_url( '/css/prism.css' , __FILE__ ) );
}

remove_filter( 'the_content', 'wpautop' );
add_filter( 'the_content', 'wpautop' , 99);
add_filter( 'the_content', 'shortcode_unautop',100 );
remove_filter('the_content', 'wptexturize');

remove_filter('comment_text', 'wptexturize');
remove_filter('the_excerpt', 'wptexturize');

add_shortcode( 'html'			, 'thatblogger_hightlight_html' );
add_shortcode( 'css'			, 'thatblogger_hightlight_css' );
add_shortcode( 'javascript'	, 'thatblogger_hightlight_javascript' );
add_shortcode( 'php'			, 'thatblogger_hightlight_php' );

function thatblogger_hightlight_html($atts, $content = null)
{
    return pu_encode_content('html', $content);
}

function thatblogger_hightlight_css($atts, $content = null)
{
    return pu_encode_content('css', $content);
}

function thatblogger_hightlight_javascript($atts, $content = null)
{
    return pu_encode_content('javascript', $content);
}

function thatblogger_hightlight_php($atts, $content = null)
{
    return pu_encode_content('php', $content);
}

function pu_encode_content($lang, $content)
{
    $find_array = array( '&#91;', '&#93;' );
    $replace_array = array( '[', ']' );

    $content = preg_replace_callback( '|(.*)|isU', 'pu_pre_entities', trim( str_replace( $find_array, $replace_array, $content ) ) );

    $content = str_replace('#038;', '', $content);

    return sprintf('<pre class="language-%s line-numbers"><code>%s</code></pre>', $lang, $content);
}

function pu_pre_entities( $matches ) {
    return str_replace( $matches[1], htmlspecialchars( $matches[1]), $matches[0] );
}