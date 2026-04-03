<?php
/**
 * Gera as meta tags Open Graph e Twitter Card para melhorar o SEO social.
 *
 * Adiciona as principais tags og: e twitter: no <head> do documento,
 * cobrindo todos os tipos de página do WordPress.
 */

/**
 * Retorna a URL canônica para a página atual.
 *
 * @return string URL canônica.
 */
function portal_get_canonical_url() {
    if (is_singular()) {
        return get_permalink();
    } elseif (is_front_page()) {
        return home_url('/');
    } elseif (is_home()) {
        $page_for_posts = get_option('page_for_posts');
        if ($page_for_posts) {
            return get_permalink($page_for_posts);
        }
        return home_url('/');
    } elseif (is_category() || is_tag() || is_tax()) {
        $term = get_queried_object();
        $url  = get_term_link($term);
        return is_wp_error($url) ? home_url('/') : $url;
    } elseif (is_search()) {
        return get_search_link();
    } elseif (is_author()) {
        return get_author_posts_url(get_queried_object_id());
    } elseif (is_archive()) {
        return get_the_archive_link();
    }

    return home_url('/');
}

add_action('wp_head', 'portal_open_graph_tags', 5);

/**
 * Retorna a URL da imagem para as meta tags Open Graph.
 *
 * Prioridade: imagem destacada do post → logo personalizado → logo padrão do tema.
 *
 * @return string URL da imagem ou string vazia.
 */
function portal_get_og_image() {
    if (is_singular() && has_post_thumbnail()) {
        $thumb = wp_get_attachment_image_src(get_post_thumbnail_id(), 'large');
        if ($thumb) {
            return $thumb[0];
        }
    }

    if (has_custom_logo()) {
        $logo_src = wp_get_attachment_image_src(get_theme_mod('custom_logo'), 'full');
        if ($logo_src) {
            return $logo_src[0];
        }
    }

    return get_stylesheet_directory_uri() . '/img/ifrs.png';
}

/**
 * Imprime as meta tags Open Graph e Twitter Card no <head>.
 */
function portal_open_graph_tags() {
    $site_name   = get_bloginfo('name');
    $title       = wp_get_document_title();
    $description = portal_get_meta_description();
    $url         = '';
    $type        = 'website';
    $locale      = str_replace('-', '_', get_bloginfo('language'));
    $image       = portal_get_og_image();

    if (is_singular()) {
        $url  = get_permalink();
        $type = 'article';
    } elseif (is_home()) {
        $page_for_posts = get_option('page_for_posts');
        if ($page_for_posts) {
            $page_url = get_permalink($page_for_posts);
            $url = $page_url ? $page_url : home_url('/');
        } else {
            $url = home_url('/');
        }
    } elseif (is_front_page()) {
        $url = home_url('/');
    } elseif (is_category() || is_tag() || is_tax()) {
        $term = get_queried_object();
        $url  = get_term_link($term);
        if (is_wp_error($url)) {
            $url = '';
        }
    } elseif (is_search()) {
        $url = get_search_link();
    } elseif (is_author()) {
        $url = get_author_posts_url(get_queried_object_id());
    } elseif (is_archive()) {
        $url = get_the_archive_link();
    }

    ?>
    <!-- Open Graph -->
    <meta property="og:type" content="<?php echo esc_attr($type); ?>">
    <meta property="og:site_name" content="<?php echo esc_attr($site_name); ?>">
    <meta property="og:locale" content="<?php echo esc_attr($locale); ?>">
    <meta property="og:title" content="<?php echo esc_attr($title); ?>">
    <meta property="og:description" content="<?php echo esc_attr($description); ?>">
    <?php if ($url) : ?>
    <meta property="og:url" content="<?php echo esc_url($url); ?>">
    <?php endif; ?>
    <?php if ($image) : ?>
    <meta property="og:image" content="<?php echo esc_url($image); ?>">
    <?php endif; ?>
    <?php if ($type === 'article') : ?>
    <meta property="article:published_time" content="<?php echo esc_attr(get_the_date('c')); ?>">
    <meta property="article:modified_time" content="<?php echo esc_attr(get_the_modified_date('c')); ?>">
    <?php $categories = get_the_category();
    if (!empty($categories)) : ?>
    <meta property="article:section" content="<?php echo esc_attr($categories[0]->cat_name); ?>">
    <?php endif; ?>
    <?php endif; ?>

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo esc_attr($title); ?>">
    <meta name="twitter:description" content="<?php echo esc_attr($description); ?>">
    <?php if ($image) : ?>
    <meta name="twitter:image" content="<?php echo esc_url($image); ?>">
    <?php endif; ?>
    <?php
}
