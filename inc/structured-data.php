<?php
/**
 * Gera dados estruturados JSON-LD para melhorar a visibilidade nos resultados do Google.
 *
 * Implementa schemas:
 *  - WebSite (com SearchAction) na página inicial
 *  - Organization na página inicial
 *  - Article em posts individuais
 *  - BreadcrumbList em todas as páginas (exceto a inicial)
 */

add_action('wp_head', 'portal_structured_data', 5);

/**
 * Imprime os blocos JSON-LD de dados estruturados no <head>.
 */
function portal_structured_data() {
    $schemas = array();

    if (is_front_page()) {
        $schemas[] = portal_schema_website();
        $schemas[] = portal_schema_organization();
    }

    if (is_singular()) {
        $schemas[] = portal_schema_article();
    }

    if (!is_front_page()) {
        $breadcrumb = portal_schema_breadcrumb();
        if ($breadcrumb) {
            $schemas[] = $breadcrumb;
        }
    }

    foreach ($schemas as $schema) {
        if ($schema) {
            echo '<script type="application/ld+json">' . wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>' . "\n";
        }
    }
}

/**
 * Retorna o schema WebSite com SearchAction para o sitelinks search box do Google.
 *
 * @return array Schema WebSite.
 */
function portal_schema_website() {
    return array(
        '@context' => 'https://schema.org',
        '@type'    => 'WebSite',
        'name'     => get_bloginfo('name'),
        'url'      => home_url('/'),
        'potentialAction' => array(
            '@type'       => 'SearchAction',
            'target'      => array(
                '@type'       => 'EntryPoint',
                'urlTemplate' => home_url('/?s={search_term_string}'),
            ),
            'query-input' => 'required name=search_term_string',
        ),
    );
}

/**
 * Retorna o schema Organization para o IFRS.
 *
 * @return array Schema Organization.
 */
function portal_schema_organization() {
    $logo_url = '';
    if (has_custom_logo()) {
        $logo_src = wp_get_attachment_image_src(get_theme_mod('custom_logo'), 'full');
        if ($logo_src) {
            $logo_url = $logo_src[0];
        }
    } else {
        $logo_url = get_stylesheet_directory_uri() . '/img/ifrs.png';
    }

    $schema = array(
        '@context'    => 'https://schema.org',
        '@type'       => 'EducationalOrganization',
        'name'        => get_bloginfo('name'),
        'description' => get_bloginfo('description'),
        'url'         => home_url('/'),
    );

    if ($logo_url) {
        $schema['logo'] = array(
            '@type' => 'ImageObject',
            'url'   => $logo_url,
        );
    }

    return $schema;
}

/**
 * Retorna o schema Article para um post individual.
 *
 * @return array|null Schema Article ou null se não for singular.
 */
function portal_schema_article() {
    if (!is_singular()) {
        return null;
    }

    $post = get_queried_object();
    if (!$post) {
        return null;
    }

    $image_url = '';
    if (has_post_thumbnail($post->ID)) {
        $thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large');
        if ($thumb) {
            $image_url = $thumb[0];
        }
    }

    $schema = array(
        '@context'         => 'https://schema.org',
        '@type'            => (get_post_type($post) === 'post') ? 'NewsArticle' : 'Article',
        'headline'         => get_the_title($post),
        'description'      => portal_get_meta_description(),
        'url'              => get_permalink($post),
        'datePublished'    => get_the_date('c', $post),
        'dateModified'     => get_the_modified_date('c', $post),
        'author'           => array(
            '@type' => 'Organization',
            'name'  => get_bloginfo('name'),
        ),
        'publisher'        => array(
            '@type' => 'Organization',
            'name'  => get_bloginfo('name'),
        ),
        'inLanguage'       => get_bloginfo('language'),
        'isPartOf'         => array(
            '@type' => 'WebSite',
            'name'  => get_bloginfo('name'),
            'url'   => home_url('/'),
        ),
    );

    if ($image_url) {
        $schema['image'] = array(
            '@type' => 'ImageObject',
            'url'   => $image_url,
        );
    }

    $logo_url = '';
    if (has_custom_logo()) {
        $logo_src = wp_get_attachment_image_src(get_theme_mod('custom_logo'), 'full');
        if ($logo_src) {
            $logo_url = $logo_src[0];
        }
    } else {
        $logo_url = get_stylesheet_directory_uri() . '/img/ifrs.png';
    }

    if ($logo_url) {
        $schema['publisher']['logo'] = array(
            '@type' => 'ImageObject',
            'url'   => $logo_url,
        );
    }

    return $schema;
}

/**
 * Retorna o schema BreadcrumbList baseado na hierarquia da página atual.
 *
 * @return array|null Schema BreadcrumbList ou null se não houver itens suficientes.
 */
function portal_schema_breadcrumb() {
    $items   = array();
    $position = 1;

    // Sempre inclui a página inicial
    $items[] = array(
        '@type'    => 'ListItem',
        'position' => $position,
        'name'     => get_bloginfo('name'),
        'item'     => home_url('/'),
    );
    $position++;

    if (is_category() || is_tag() || is_tax()) {
        $term = get_queried_object();
        $term_url = get_term_link($term);
        if (!is_wp_error($term_url)) {
            $items[] = array(
                '@type'    => 'ListItem',
                'position' => $position,
                'name'     => $term->name,
                'item'     => $term_url,
            );
        }
    } elseif (is_single()) {
        $categories = get_the_category();
        if (!empty($categories)) {
            $cat_url = get_category_link($categories[0]->term_id);
            if (!is_wp_error($cat_url)) {
                $items[] = array(
                    '@type'    => 'ListItem',
                    'position' => $position,
                    'name'     => $categories[0]->cat_name,
                    'item'     => $cat_url,
                );
                $position++;
            }
        }
        $items[] = array(
            '@type'    => 'ListItem',
            'position' => $position,
            'name'     => get_the_title(),
            'item'     => get_permalink(),
        );
    } elseif (is_page()) {
        $ancestors = array_reverse(get_post_ancestors(get_the_ID()));
        foreach ($ancestors as $ancestor_id) {
            $items[] = array(
                '@type'    => 'ListItem',
                'position' => $position,
                'name'     => get_the_title($ancestor_id),
                'item'     => get_permalink($ancestor_id),
            );
            $position++;
        }
        $items[] = array(
            '@type'    => 'ListItem',
            'position' => $position,
            'name'     => get_the_title(),
            'item'     => get_permalink(),
        );
    } elseif (is_home()) {
        $page_for_posts = get_option('page_for_posts');
        if ($page_for_posts) {
            $items[] = array(
                '@type'    => 'ListItem',
                'position' => $position,
                'name'     => get_the_title($page_for_posts),
                'item'     => get_permalink($page_for_posts),
            );
        }
    } elseif (is_search()) {
        $items[] = array(
            '@type'    => 'ListItem',
            'position' => $position,
            'name'     => sprintf(__('Resultados para "%s"', 'ifrs-portal-theme'), get_search_query()),
            'item'     => get_search_link(),
        );
    } elseif (is_404()) {
        $items[] = array(
            '@type'    => 'ListItem',
            'position' => $position,
            'name'     => __('Página não encontrada', 'ifrs-portal-theme'),
            'item'     => home_url('/404'),
        );
    }

    if (count($items) < 2) {
        return null;
    }

    return array(
        '@context'        => 'https://schema.org',
        '@type'           => 'BreadcrumbList',
        'itemListElement' => $items,
    );
}
