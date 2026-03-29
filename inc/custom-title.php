<?php
add_filter('pre_get_document_title', function($title) {
    $site_name = get_bloginfo('name');

    if (is_front_page()) {
        return $site_name . ' - ' . get_bloginfo('description');
    } elseif (is_single()) {
        $categories = get_the_category();
        if (!empty($categories)) {
            return get_the_title() . ' - ' . $categories[0]->cat_name . ' - ' . $site_name;
        }
        return get_the_title() . ' - ' . $site_name;
    } elseif (is_page()) {
        return get_the_title() . ' - ' . $site_name;
    } elseif (is_category()) {
        return single_cat_title('', false) . ' - ' . __('Notícias', 'ifpe-portal-theme') . ' - ' . $site_name;
    } elseif (is_tag()) {
        return single_tag_title('', false) . ' - ' . $site_name;
    } elseif (is_tax()) {
        return single_term_title('', false) . ' - ' . $site_name;
    } elseif (is_search()) {
        return sprintf(__('Resultados para "%s"', 'ifpe-portal-theme'), get_search_query()) . ' - ' . $site_name;
    } elseif (is_404()) {
        return __('Página não encontrada', 'ifpe-portal-theme') . ' - ' . $site_name;
    } elseif (is_author()) {
        $author = get_queried_object();
        return sprintf(__('Posts por %s', 'ifpe-portal-theme'), $author->display_name) . ' - ' . $site_name;
    } elseif (is_year()) {
        return sprintf(__('Arquivo: %s', 'ifpe-portal-theme'), get_the_date('Y')) . ' - ' . $site_name;
    } elseif (is_month()) {
        return sprintf(__('Arquivo: %s', 'ifpe-portal-theme'), get_the_date('F Y')) . ' - ' . $site_name;
    } elseif (is_day()) {
        return sprintf(__('Arquivo: %s', 'ifpe-portal-theme'), get_the_date('d/m/Y')) . ' - ' . $site_name;
    } elseif (is_home()) {
        $page_for_posts = get_option('page_for_posts');
        if ($page_for_posts) {
            return get_the_title($page_for_posts) . ' - ' . $site_name;
        }
        return __('Notícias', 'ifpe-portal-theme') . ' - ' . $site_name;
    }

    return $title;
}, 50);
