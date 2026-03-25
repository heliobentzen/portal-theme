<div class="title">
    <h1 class="visually-hidden"><?php bloginfo('name'); ?></h1>
    <?php if (has_custom_logo()) : ?>
        <?php the_custom_logo(); ?>
    <?php else : ?>
        <a href="<?php echo esc_url(home_url('/')); ?>">
            <?php $imgsize = getimagesize(get_stylesheet_directory() . '/img/ifrs.png'); ?>
            <img src="<?php echo esc_url( get_stylesheet_directory_uri() ); ?>/img/ifrs.png" alt="<?php bloginfo('name'); ?>" class="img-fluid title__logo" width="<?php echo absint( $imgsize[0] ); ?>" height="<?php echo absint( $imgsize[1] ); ?>"/>
        </a>
    <?php endif; ?>
</div>
