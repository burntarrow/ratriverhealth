<?php include(TBI . 'plugins/newsletterForm.php'); ?>
<!DOCTYPE HTML>

<?php
/*
	@package WordPress
	@subpackage The Cause
*/

?>

<html <?php language_attributes(); ?>>
<head>

<meta charset="<?php bloginfo('charset'); ?>">
<title><?php if (is_home() || is_front_page()) { bloginfo('name'); ?><?php } elseif (is_category() || is_page() ||is_single()) { ?> <?php } ?><?php wp_title(''); ?></title>

<?php $favicon = get_option('tb_favicon', DEFAULT_FAVICON); ?>

<link rel="icon" type="image/png" href="<?php echo $favicon; ?>">

<!-- STYLES -->
<?php get_template_part('tbFonts'); ?>
<link rel="stylesheet" href="<?php echo TEMPLATE_DIRECTORY; ?>/styles/grid960.css" type="text/css" media="screen"/>
<?php  if (substr($_SERVER['REQUEST_URI'], 0, 12) == "/foundation/") { ?>

    <link rel="stylesheet" href="<?php echo TEMPLATE_DIRECTORY; ?>/style_foundation.css" type="text/css" media="screen"/>

<?php  } else {?>

    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />

<?php  } ?>

<?php wp_head(); ?>


</head>

<body <?php body_class(); ?>>

<a id="top"></a>

<!-- HEADER -->
<?php  if (substr($_SERVER['REQUEST_URI'], 0, 12) == "/foundation/") { ?>
<div id="header" class="width1000">
<?php  } else { ?>
<div id="header" class="width100" <?php tb_write_bckg(); ?>>
<?php  } ?>
    <div class="width1000">
        
        <div id="logo_foundation"><h1><a href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>">
            
            <?php  if (substr($_SERVER['REQUEST_URI'], 0, 12) == "/foundation/") { ?>
            <img src="<?php echo TEMPLATE_DIRECTORY; ?>/images_foundation/foundation_header.png" alt="<?php bloginfo('name'); ?>">
            <?php  } else { ?>
            <img src="<?php echo tb_get_logo(); ?>" alt="<?php bloginfo('name'); ?>">
            <?php  } ?>
        
        </a></h1></div>
		
    </div>
</div>
<!-- .HEADER -->

<div class="clear"></div>

<!-- CONTENT -->
<?php  if (substr($_SERVER['REQUEST_URI'], 0, 12) == "/foundation/") { ?>
<div id="contentHolder" class="width1000">
    <div id="separator"></div>
<?php  } else { ?>
<div id="contentHolder" class="width100">
<?php  } ?> 
    
	<!-- Navigation -->
    <div id="navigationBckg" class="<?php tb_write_bckg('navigation'); ?> width100">
		
        <div id="navigation">
		
		<div class="width1000">
		<?php
			if (has_nav_menu('Navigation')) {
		        wp_nav_menu(
		            array(
		                'theme_location' => 'Navigation', 
		                'container' => false, 
		                'menu_class' => 'navigation',
		                'fallback_cb' => 'tb_default_navigation'
		            )
		        );
			} else {
				tb_default_navigation();
			}
	    ?>
                </div>
                
                <?php  require_once('lang_switch.php'); ?>
		
        </div>
		
    </div>
	<!-- .Navigation -->
        
    <!-- MAIN -->
    <div id="main" class="width100">
	
		<?php
		global $post;
		$postID = $post->ID;
		$mainImageA = get_post_meta($postID, '_main_image', false);
		$mainImage = false;
		foreach ($mainImageA as $tempImg) {
			if (wp_get_attachment_image_src($tempImg)) {
				$mainImage = $tempImg;
				break;
			}
		}
		
		$mainShadow = get_post_meta($postID, '_main_shadow', true);		
		$mainImageArray = tb_get_main_image($mainImage, $mainShadow);
		$sidebarPosition = get_post_meta($postID, '_sidebar_position', true);
		?>
	
		<!-- Promo Image -->
		<div id="promoImage" class="width100">
			<?php if ($mainImageArray['mainImage']) { ?>
			<div style="background-image: url('<?php echo $mainImageArray['mainImage']; ?>');">
				<img src="<?php echo $mainImageArray['mainImage']; ?>">
				<?php if ($mainImageArray['mainShadow'] == 'yes') echo '<div class="mainShadow"></div>'; ?>
			</div>
			<?php } ?>
		</div>
		<!-- .Promo Image -->
                
        <!-- Slider -->
        <div id="parallaxRevolution" class="width1000"><div>
		
		<?php putRevSlider("wide") ?>

        </div></div>
        
        <?php  if (substr($_SERVER['REQUEST_URI'], 0, 12) == "/foundation/") { ?>
        <div id="donate_now">
            <?php dynamic_sidebar('donate_now_banner_foundation'); ?>
        </div>
        <?php  } ?>
        
        <!-- .Slider -->
					
        <!-- Content -->
        <div id="content" class="<?php tb_write_bckg('buttons'); ?> <?php tb_write_bckg('buttonsExtra'); ?>  <?php tb_write_bckg('sidebar'); ?> <?php echo tb_get_sidebar_position($sidebarPosition); ?> width1000" style="margin-top: 0 !important;">
		<div class="fullWidth">
