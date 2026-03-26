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
<?php if ($children && count($children) > 0) : ?>
    <ol class="nav flex-column">
        <?php if ($parent && $depth >= 3) : ?>
            <li class="nav-item"><a class="nav-link" href="<?php echo esc_url( get_permalink( $parent ) ); ?>">Subir ao n&iacute;vel anterior</a></li>
        <?php endif; ?>
        <?php foreach ($children as $child): ?>
            <li class="nav-item"><a class="nav-link" href="<?php echo esc_url( get_permalink( $child->ID ) ); ?>"><?php echo esc_html( $child->post_title ); ?></a></li>
        <?php endforeach; ?>
    </ol>
<?php else : ?>
    <hr class="page__separator">
<?php endif; ?>
