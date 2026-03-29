<?php
add_action('init', function() {
    // Remove Really Simple Discovery link (used for XML-RPC clients)
    remove_action('wp_head', 'rsd_link');

    // Remove Windows Live Writer manifest link
    remove_action('wp_head', 'wlwmanifest_link');

    // Remove REST API discovery link from <head>
    remove_action('wp_head', 'rest_output_link_wp_head');

    // Remove oEmbed discovery links (allows others to embed your content)
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
});
