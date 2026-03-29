<?php
if ( ! function_exists( 'escopo_taxonomy' ) ) {
    function escopo_taxonomy() {
        $labels = array(
            'name'                       => _x( 'Escopos', 'Taxonomy General Name', 'ifpe-portal-theme' ),
            'singular_name'              => _x( 'Escopo', 'Taxonomy Singular Name', 'ifpe-portal-theme' ),
            'menu_name'                  => __( 'Escopo', 'ifpe-portal-theme' ),
            'all_items'                  => __( 'Todos os Escopos', 'ifpe-portal-theme' ),
            'parent_item'                => __( 'Escopo Pai', 'ifpe-portal-theme' ),
            'parent_item_colon'          => __( 'Escopo Pai:', 'ifpe-portal-theme' ),
            'new_item_name'              => __( 'Novo Escopo', 'ifpe-portal-theme' ),
            'add_new_item'               => __( 'Adicionar Novo Escopo', 'ifpe-portal-theme' ),
            'edit_item'                  => __( 'Editar Escopo', 'ifpe-portal-theme' ),
            'update_item'                => __( 'Atualizar Escopo', 'ifpe-portal-theme' ),
            'view_item'                  => __( 'Visualizar Escopo', 'ifpe-portal-theme' ),
            'separate_items_with_commas' => __( 'Escopos separados por vírgulas', 'ifpe-portal-theme' ),
            'add_or_remove_items'        => __( 'Adicionar ou Remover Escopos', 'ifpe-portal-theme' ),
            'choose_from_most_used'      => __( 'Escolher pelo Escopo mais usado', 'ifpe-portal-theme' ),
            'popular_items'              => __( 'Escopos Populares', 'ifpe-portal-theme' ),
            'search_items'               => __( 'Buscar Escopos', 'ifpe-portal-theme' ),
            'not_found'                  => __( 'Não encontrado', 'ifpe-portal-theme' ),
            'no_terms'                   => __( 'Sem Escopos', 'ifpe-portal-theme' ),
            'items_list'                 => __( 'Lista de Escopos', 'ifpe-portal-theme' ),
            'items_list_navigation'      => __( 'Lista de Navegação de Escopos', 'ifpe-portal-theme' ),
        );
        $rewrite = array(
            'slug'                       => 'noticias/escopos',
            'with_front'                 => true,
            'hierarchical'               => true,
        );
        $capabilities = array(
            'manage_terms'               => 'manage_escopos',
            'edit_terms'                 => 'edit_escopos',
            'delete_terms'               => 'delete_escopos',
            'assign_terms'               => 'edit_posts',
        );
        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => false,
            'show_tagcloud'              => false,
            'rewrite'                    => $rewrite,
            'capabilities'               => $capabilities,
            'show_in_rest'               => false,
        );
        register_taxonomy( 'escopo', array( 'post' ), $args );
    }
    add_action( 'init', 'escopo_taxonomy', 0 );
}
