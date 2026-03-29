<?php
add_action('wp_head', function() {
    if (!WP_DEBUG) {
?>
    <link rel="preconnect" href="https://vlibras.gov.br">
<?php
    }
    if (has_custom_logo()) {
        $logo_src = wp_get_attachment_image_src( get_theme_mod('custom_logo'), 'full' );
        if ( $logo_src ) {
            echo '<link rel="preload" href="' . esc_url( $logo_src[0] ) . '" as="image"/>';
        }
    } else {
        echo '<link rel="preload" href="' . esc_url( get_stylesheet_directory_uri() ) . '/img/ifrs.png" as="image"/>';
    }
}, 0);
