<? if (substr($_SERVER['REQUEST_URI'], 0, 12) == "/foundation/") { ?>



<?php

/*
	@package WordPress
	@subpackage The Cause
	
	Template Name: St-Pierre Foundation Blank
*/

get_header('parallax');

?>
        
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    
    <?php $postID = get_the_ID(); ?>
	<?php $postTitle = get_the_title($postID); ?>
	<?php $postPermalink = get_permalink($postID); ?>

    <div id="post-<?php echo $postID; ?>">
	<div>

	<div id="sidebar">
	<?php dynamic_sidebar( 'Home' ); ?>
        <div class="horShadow2"></div>
        <?php dynamic_sidebar('other_menu_links'); ?>
	</div>
	
    <!-- INNER content -->
    <div id="inner">   

    <h2><?php echo $postTitle; ?></h2>
    <?php $postThumbnail = tb_get_thumbnail($postID, 'dfl'); ?>
    <?php if ($postThumbnail) { ?>
	
	<?php $imageFull = wp_get_attachment_image_src( get_post_thumbnail_id($postID), 'full'); ?>
            
    <div class="doubleFramed large alignleft">
		<a href="<?php echo $imageFull[0]; ?>" title="<?php echo $postTitle; ?>">
    	<?php echo $postThumbnail; ?>
		</a>
    </div>
    <?php } ?>
    
    <?php the_content(); ?>
    
    <div class="horDashed"></div>
    
    <p class="newsInfo">
	
	<?php wp_link_pages(array(
		'before' => 'Pages: ',
		'after' => '<br>'
	)); ?>
    
	Posted by <?php the_author_posts_link(); ?> on <?php echo get_the_date('l, F jS, Y @ g:iA'); ?><br> 
    Categories: <?php the_category(', '); ?>
    <?php the_tags('<br>Tags: ', ', '); ?>
    </p>
    
    <div class="horDashed"></div>
	
    <?php endwhile; endif; ?>
    
    <?php comments_template(); ?>

    </div>

    </div>
	</div>

<?php
get_footer();
?>

<? } else { ?>

<?php

/*
	@package WordPress
	@subpackage The Cause
*/

get_header('sidebar');

if (function_exists('pll_current_language')) {
    $curlang = pll_current_language();
} else {
    $curlang = "en";
}

$strings = array(
    "en" => array(
        "postedby" => "Posted by",
        "categories" => "Categories"
    ),
    "fr" => array(
        "postedby" => "Post&eacute; par",
        "categories" => "Cat&eacute;gories"
    )    
);

?>

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    
    <?php $postID = get_the_ID(); ?>
	<?php $postTitle = get_the_title($postID); ?>
	<?php $postPermalink = get_permalink($postID); ?>

    <h2><?php echo $postTitle; ?></h2>
    
    <div id="post-<?php echo $postID; ?>">
	<div>

    <?php get_sidebar(); ?>
	
    <!-- INNER content -->
    <div id="inner">   


    <?php $postThumbnail = tb_get_thumbnail($postID, 'dfl'); ?>
    <?php if ($postThumbnail) { ?>
	
	<?php $imageFull = wp_get_attachment_image_src( get_post_thumbnail_id($postID), 'full'); ?>
            
    <div class="doubleFramed large alignleft">
		<a href="<?php echo $imageFull[0]; ?>" title="<?php echo $postTitle; ?>">
    	<?php echo $postThumbnail; ?>
		</a>
    </div>
    <?php } ?>
    
    <?php the_content(); ?>
    
    <div class="horDashed"></div>
    
    <p class="newsInfo">
	
	<?php wp_link_pages(array(
		'before' => 'Pages: ',
		'after' => '<br>'
	)); ?>
    
	<?=$strings[$curlang]['postedby']?> <?php the_author_posts_link(); ?> on <?php echo get_the_date('l, F jS, Y @ g:iA'); ?><br> 
    <?=$strings[$curlang]['categories']?>: <?php the_category(', '); ?>
    <?php the_tags('<br>Tags: ', ', '); ?>
    </p>
    
    <div class="horDashed"></div>
	
    <?php endwhile; endif; ?>
    
    <?php comments_template(); ?>

    </div>

    </div>
	</div>

<?php
get_footer();
?>

<? } ?>