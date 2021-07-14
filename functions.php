<?php

function theme_css_js() {
    // css normalize
    //wp_enqueue_style('normalize', 'https://cdnjs.cloudflare.com/ajax/libs/modern-normalize/1.1.0/modern-normalize.min.css', false, null, 'screen');
    wp_enqueue_style('normalize', get_template_directory_uri().'/css/modern-normalize.min.css', false, '1.1.0', 'screen');
    // font style
    wp_enqueue_style('font', get_template_directory_uri().'/css/font.css', 'normalize', '2021051501', 'screen');
    // general style
    wp_enqueue_style('general', get_template_directory_uri().'/css/general.css', 'font', '2021061701', 'screen');
    // jQuery
    //wp_enqueue_script('jQuery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js', false, null);
}
add_action('wp_enqueue_scripts', 'theme_css_js');

//add style SRI
/*
function add_style_attributes( $html, $handle ) {
    if ( 'normalize' === $handle ) {
        return str_replace( "media='screen'", "media='screen' integrity='sha512-wpPYUAdjBVSE4KJnH1VR1HeZfpl1ub8YT/NKx4PuQ5NmX2tKuGu6U/JRp5y+Y8XG2tV+wKQpNHVUX03MfMFn9Q==' crossorigin='anonymous' referrerpolicy='no-referrer'", $html );
    }
    return $html;
}
add_filter( 'style_loader_tag', 'add_style_attributes', 10, 2 );
*/
//add script SRI
/*
function add_script_attributes( $tag, $handle ) {
    if ( 'jQuery' === $handle ) {
        return str_replace( "src", "integrity='sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==' crossorigin='anonymous' referrerpolicy='no-referrer' src", $tag );
    }
    return $tag;
}
add_filter( 'script_loader_tag', 'add_script_attributes', 10, 2 );
*/

function disable_embed_feature(){
    wp_deregister_script( 'wp-embed' );
}
add_action('wp_footer', 'disable_embed_feature');

// Disable emojis in Wordpress
function disable_emoji_feature()
{
    // Prevent Emoji from loading on the front-end
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');

    // Remove from admin area also
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');

    // Remove from RSS feeds also
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');

    // Remove from Embeds
    remove_filter('embed_head', 'print_emoji_detection_script');

    // Remove from emails
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

    // Disable from TinyMCE editor. Currently disabled in block editor by default
    add_filter('tiny_mce_plugins', 'disable_emojis_tinymce');

    /** Finally, prevent character conversion too
     ** without this, emojis still work
    ** if it is available on the user's device
    */

    add_filter('option_use_smilies', '__return_false');
}

// Disables emojis in WYSIWYG editor
function disable_emojis_tinymce($plugins)
{
    if (is_array($plugins)) {
        $plugins = array_diff($plugins, array('wpemoji'));
    }
    return $plugins;
}
add_action('init', 'disable_emoji_feature');

// Fully Disable Gutenberg editor.
add_filter('use_block_editor_for_post_type', '__return_false', 10);
// Don't load Gutenberg-related stylesheets.
add_action( 'wp_enqueue_scripts', 'remove_block_css', 100);
function remove_block_css() {
    wp_dequeue_style( 'wp-block-library' ); // WordPress core
    wp_dequeue_style( 'wp-block-library-theme' ); // WordPress core
    wp_dequeue_style( 'wc-block-style' ); // WooCommerce
    wp_dequeue_style( 'storefront-gutenberg-blocks' ); // Storefront theme
}

remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rest_output_link_wp_head', 10);
remove_action('template_redirect', 'rest_output_link_header', 11, 0);
remove_action('wp_head', 'wp_generator');

//hide rss generator version
function remove_wp_version_rss() {
    return '<generator>https://wordpress.org/</generator>';
}
add_filter('the_generator','remove_wp_version_rss');

//disable author page access
function oc_author_page_redirect() {
    if ( is_author() ) {
        wp_redirect( home_url() );
    }
}
add_action( 'template_redirect', 'oc_author_page_redirect' );

//remove html comments tags
function callback($buffer) {
    $buffer = preg_replace('/<!--(.|s)*?-->/', '', $buffer);
    return $buffer;
}
function buffer_start() {
    ob_start("callback");
}
function buffer_end() {
    ob_end_flush();
}
add_action('get_header', 'buffer_start');
add_action('wp_footer', 'buffer_end');

//hide admin bar from front end
function hide_admin_bar_from_front_end() {
    if (is_blog_admin()) {
        return true;
    }
    return false;
}
add_filter( 'show_admin_bar', 'hide_admin_bar_from_front_end' );

#Disable LiteSpeed Cache Comment
add_filter( 'litespeed_comment', '__return_false' );
