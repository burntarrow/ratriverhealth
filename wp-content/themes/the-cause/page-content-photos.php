<?php
/**
 * Template Name: Photos Template
 *
 * @package WordPress
 * @subpackage The_Cause
 */

get_header();

$current_language = function_exists('pll_current_language') ? pll_current_language() : 'en';

if (function_exists('soliloquy')) {
    if ('fr' === $current_language) {
        soliloquy('1124');
    } else {
        soliloquy('1119');
    }
}

if (have_posts()) :
    while (have_posts()) :
        the_post();
        $post_id        = get_the_ID();
        $post_thumbnail = tb_get_thumbnail($post_id, 'dfl');
        ?>
        <div id="content_wide_blank">
            <?php if ($post_thumbnail) : ?>
                <?php $image_full = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), 'full'); ?>
                <?php if (is_array($image_full) && !empty($image_full[0])) : ?>
                    <div class="doubleFramed large alignleft">
                        <a href="<?php echo esc_url($image_full[0]); ?>" title="<?php echo esc_attr(get_the_title()); ?>">
                            <?php echo wp_kses_post($post_thumbnail); ?>
                        </a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <?php
            the_content();
            wp_link_pages();
            ?>
        </div>
        <?php
    endwhile;
endif;

get_footer();
