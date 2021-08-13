<?php

// remove html comments tags
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

// hide admin bar from front end
function hide_admin_bar_from_front_end() {
    if (is_blog_admin()) {
        return true;
    }
    return false;
}
add_filter( 'show_admin_bar', 'hide_admin_bar_from_front_end' );

// disable LiteSpeed Cache html comment
include_once ABSPATH . 'wp-admin/includes/plugin.php';
if ( is_plugin_active( 'litespeed-cache/litespeed-cache.php' ) ) {
    add_filter( 'litespeed_comment', '__return_false' );
}