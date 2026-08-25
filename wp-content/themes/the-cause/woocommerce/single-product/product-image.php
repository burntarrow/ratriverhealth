<?php
/**
 * Single Product Image
 */

global $post, $woocommerce;

?>
<div class="wooImage">

	<div class="mainImage">

	<?php if ( has_post_thumbnail() ) : ?>

		<a itemprop="image" href="<?php echo wp_get_attachment_url( get_post_thumbnail_id() ); ?>" class="zoom" rel="thumbnails" title="<?php echo get_the_title( get_post_thumbnail_id() ); ?>"><?php echo get_the_post_thumbnail( $post->ID, 'dfl' ) ?></a>

	<?php else : ?>
	
		<img src="<?php echo woocommerce_placeholder_img_src(); ?>" alt="Placeholder" />
	
	<?php endif; ?>
	
	</div>

	<?php do_action('woocommerce_product_thumbnails'); ?>

</div>