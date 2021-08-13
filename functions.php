<?php

require get_template_directory() . '/include/disable_comments.php';
require get_template_directory() . '/include/hide_html-comment.php';
require get_template_directory() . '/include/disable_wp-emoji.php';
require get_template_directory() . '/include/disable_wp-embed.php';

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

remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rest_output_link_wp_head', 10);
remove_action('template_redirect', 'rest_output_link_header', 11, 0);
remove_action('wp_head', 'wp_generator');

// hide rss generator version
function remove_wp_version_rss() {
    return '<generator>https://wordpress.org/</generator>';
}
add_filter('the_generator','remove_wp_version_rss');

// disable author page access
function oc_author_page_redirect() {
    if ( is_author() ) {
        wp_redirect( home_url() );
    }
}
add_action( 'template_redirect', 'oc_author_page_redirect' );

// Require Authentication for All Requests (REST API)
add_filter( 'rest_authentication_errors', function( $result ) {
    // If a previous authentication check was applied,
    // pass that result along without modification.
    if ( true === $result || is_wp_error( $result ) ) {
        return $result;
    }
 
    // No authentication has been performed yet.
    // Return an error if user is not logged in.
    if ( ! is_user_logged_in() ) {
        return new WP_Error(
            'rest_not_logged_in',
            __( 'You are not currently logged in.' ),
            array( 'status' => 401 )
        );
    }
 
    // Our custom authentication check should have no effect
    // on logged-in requests
    return $result;
});