<?php

/*
	@package WordPress
	@subpackage The Cause
*/

?>

        </div></div>
        <!-- .Content -->
    
    </div>
    <!-- .MAIN -->

</div>
<!-- .CONTENT -->

<!-- FOOTER -->
<? if (substr($_SERVER['REQUEST_URI'], 0, 12) == "/foundation/") { ?>
<div id="footer" class="width1000">
<? } else { ?>
<div id="footer" class="width100">
<? } ?>
	<div class="width1000">
        
        <div class="container_12">
        
        <div class="grid_3">
		<?php dynamic_sidebar( 'Footer 1' ); ?>
        </div>
        
        <div class="grid_3">
		<?php dynamic_sidebar( 'Footer 2' ); ?>
        </div>
        
        <div class="grid_3">
		<?php dynamic_sidebar( 'Footer 3' ); ?>
        </div>
        
        <div class="grid_3">
		<?php dynamic_sidebar( 'Footer 4' ); ?>
        </div>
        
        </div>
        
	</div>
</div>
<!-- .FOOTER -->

<!-- BOTTOM LINE -->
<? if (substr($_SERVER['REQUEST_URI'], 0, 12) == "/foundation/") { ?>
<div id="bottomLine" class="width1000">
<? } else { ?>
<div id="bottomLine" class="width100">
<? } ?>
	<div class="width1000">
        <div id="bottomNav">
		<?php
			if (has_nav_menu('Footer')) {
		        wp_nav_menu(
		            array(
		                'theme_location' => 'Footer', 
		                'container' => false, 
		                'menu_class' => '',
		                'fallback_cb' => 'tb_default_navigation'
		            )
		        );				
			}
	    ?>
        </div>
		<?php
			if(pll_current_language() == 'en') {
				echo "<div id='credits'>Copyright &copy; Rat River Health 2014. All rights reserved.<br>
Website Design & Development by <a href='http://cometwebmedia.com' target='_blank'>Comet Web Media</a></div>";
			}elseif (pll_current_language() == 'fr') {
				echo "<div id='credits'>&copy; Rat River Health 2014. Tous droits réservés.<br>
Conception et développement du site Web par <a href='http://cometwebmedia.com' target='_blank'>Comet Web Media</a></div>";
			}
		?>
		
	</div>
</div>
<!-- .BOTTOM LINE -->

<?php
$googleAnalytics = get_option('tb_gac');
if (!empty($googleAnalytics)) {
	echo stripslashes($googleAnalytics);
}
?>

<?php wp_footer(); ?>

</body>
</html>