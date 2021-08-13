<?php

function disable_embed_feature(){
    wp_deregister_script( 'wp-embed' );
}
add_action('wp_footer', 'disable_embed_feature');