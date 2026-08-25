<?php

/*
	@package WordPress
	@subpackage The Cause
	
	Template Name: Woo Commerce
	
*/

get_header();

?>

<?php

if (have_posts()) : while (have_posts()) : the_post();

?>


<h2><?php the_title(); ?></h2>   

<div id="tbWooCommerce">
		
<?php

the_content();

endwhile; endif;

?>

</div>

<?php

get_footer();

?>