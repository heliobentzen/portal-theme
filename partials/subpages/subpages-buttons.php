<?php
    $children = get_posts(
        array(
            'post_type'   => 'page',
            'orderby'     => 'menu_order',
            'order'       => 'ASC',
            'post_parent' => get_the_ID(),
            'numberposts' => -1,
        )
    );
    $parent = wp_get_post_parent_id( get_the_ID() );
    $ancestors = get_post_ancestors( get_the_ID() );
    $depth = count($ancestors);
?>
<?php if (count($children) > 0) : ?>
    <ul class="nav menu-subpages">
        <?php if ($parent && $depth >= 3) : ?>
            <li class="nav-item menu-subpages__item"><a class="nav-link menu-subpages__link menu-subpages__link--parent" href="<?php echo esc_url( get_permalink( $parent ) ); ?>" title="Subir ao nível anterior"><span class="visually-hidden">Subir ao n&iacute;vel anterior</span></a></li>
        <?php endif; ?>
        <?php foreach ($children as $child): ?>
            <li class="nav-item menu-subpages__item"><a class="nav-link menu-subpages__link" href="<?php echo esc_url( get_permalink( $child->ID ) ); ?>"><?php echo esc_html( $child->post_title ); ?></a></li>
        <?php endforeach; ?>
    </ul>
<?php else : ?>
    <hr class="page__separator">
<?php endif; ?>
