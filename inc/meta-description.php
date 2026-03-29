<?php
/**
 * Gera a meta descrição dinâmica para cada contexto de página.
 *
 * @return string Meta descrição sanitizada e truncada a 160 caracteres.
 */

define('META_DESCRIPTION_MAX_LENGTH', 160);

/**
 * Trunca um texto ao comprimento máximo para meta descrição.
 *
 * @param string $text Texto a truncar.
 * @return string Texto truncado.
 */
function portal_truncate_meta_description($text) {
    if (mb_strlen($text) > META_DESCRIPTION_MAX_LENGTH) {
        return mb_substr($text, 0, META_DESCRIPTION_MAX_LENGTH - 3) . '...';
    }
    return $text;
}

/**
 * Sanitiza e trunca a descrição de um termo taxonômico.
 *
 * @param string $description Descrição HTML do termo.
 * @return string Descrição limpa e truncada, ou string vazia se não houver descrição.
 */
function portal_sanitize_term_description($description) {
    $description = wp_strip_all_tags($description);
    $description = trim($description);
    if (empty($description)) {
        return '';
    }
    return portal_truncate_meta_description($description);
}

function portal_get_meta_description() {
    $default = __('O IFPE é uma instituição federal de ensino público e gratuito. Atua com uma estrutura multicampi para promover a educação profissional e tecnológica de excelência e impulsionar o desenvolvimento sustentável das regiões.', 'ifpe-portal-theme');

    if (is_singular()) {
        $post = get_queried_object();

        if (!empty($post->post_excerpt)) {
            $description = $post->post_excerpt;
        } else {
            $description = wp_strip_all_tags($post->post_content);
        }

        $description = preg_replace('/\s+/', ' ', trim($description));
        $description = portal_truncate_meta_description($description);

        return !empty($description) ? $description : $default;
    } elseif (is_category()) {
        $description = portal_sanitize_term_description(category_description());
        if (!empty($description)) {
            return $description;
        }
        return sprintf(__('Notícias na categoria %s - %s', 'ifpe-portal-theme'), single_cat_title('', false), get_bloginfo('name'));
    } elseif (is_tag()) {
        $description = portal_sanitize_term_description(tag_description());
        if (!empty($description)) {
            return $description;
        }
        return sprintf(__('Posts com a tag %s - %s', 'ifpe-portal-theme'), single_tag_title('', false), get_bloginfo('name'));
    } elseif (is_tax()) {
        $term = get_queried_object();
        $description = portal_sanitize_term_description(term_description($term->term_id, $term->taxonomy));
        if (!empty($description)) {
            return $description;
        }
        return sprintf(__('%s - %s', 'ifpe-portal-theme'), $term->name, get_bloginfo('name'));
    } elseif (is_search()) {
        return sprintf(__('Resultados da busca por "%s" no portal %s.', 'ifpe-portal-theme'), get_search_query(), get_bloginfo('name'));
    } elseif (is_404()) {
        return __('A página que você tentou acessar não foi encontrada. Navegue pelo portal para encontrar o que procura.', 'ifpe-portal-theme');
    } elseif (is_author()) {
        $author = get_queried_object();
        return sprintf(__('Posts publicados por %s no portal %s.', 'ifpe-portal-theme'), $author->display_name, get_bloginfo('name'));
    } elseif (is_year()) {
        return sprintf(__('Arquivo de posts do ano %s no portal %s.', 'ifpe-portal-theme'), get_the_date('Y'), get_bloginfo('name'));
    } elseif (is_month()) {
        return sprintf(__('Arquivo de posts de %s no portal %s.', 'ifpe-portal-theme'), get_the_date('F Y'), get_bloginfo('name'));
    } elseif (is_day()) {
        return sprintf(__('Arquivo de posts do dia %s no portal %s.', 'ifpe-portal-theme'), get_the_date('d/m/Y'), get_bloginfo('name'));
    } elseif (is_home()) {
        $page_for_posts = get_option('page_for_posts');
        if ($page_for_posts) {
            $page = get_post($page_for_posts);
            if (!empty($page->post_excerpt)) {
                return $page->post_excerpt;
            }
        }
        return sprintf(__('Últimas notícias do %s.', 'ifpe-portal-theme'), get_bloginfo('name'));
    }

    return $default;
}
