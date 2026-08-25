<?php
    /*
    Plugin Name: Rat River Widgets
    Description: Custom Widgets
    Author: Jan Lucki
    Version: 1.0
    */
    
    class DonateNowBannerFoundation extends WP_Widget {
        
        function DonateNowBannerFoundation() {
            $widget_ops = array('classname' => 'DonateNowBannerFoundation', 'description' => 'Donate Now Hospital Foundation' );
            $this->WP_Widget('DonateNowBannerFoundation', 'Donate Now Hospital Foundation', $widget_ops);
        }
        
        function form($instance) {
            if($instance) {
                $url = esc_attr($instance['url']);
            } else {
                $url = '';
            }
            ?>
            <p>
            <label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('Link:', 'wp_widget_plugin'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo $url; ?>" />
            </p>
            <?php
        }
     
        function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $instance['url'] = strip_tags($new_instance['url']);
            return $instance;
        }
        
        // display widget
        function widget($args, $instance) {
            
            extract($args);
            // these are the widget options
            $url = $instance['url'];
            
            echo $before_widget;
            echo '<a href="' . $url . '"><img src="' . TEMPLATE_DIRECTORY . '/images_foundation/donate_now_banner.png" /></a>';
            echo $after_widget;
        }
     
    }
    add_action( 'widgets_init', create_function('', 'return register_widget("DonateNowBannerFoundation");') );
    
    class DonateNowWidget extends WP_Widget {
        
        function DonateNowWidget() {
            $widget_ops = array('classname' => 'DonateNowWidget', 'description' => 'Donate Now sidebar' );
            $this->WP_Widget('DonateNowWidget', 'Donate Now Widget', $widget_ops);
        }
        
        function form($instance) {
            if($instance) {
                $banner = esc_attr($instance['banner']);
                $url = esc_attr($instance['url']);
                $text = esc_attr($instance['text']);
                $instance = wp_parse_args( (array) $instance, array( 'url' => '', 'text' => '' ) );
            } else {
                $banner = '';
                $url = '';
                $text = '';
            }
            ?>
            <p>
            <label for="<?php echo $this->get_field_id('banner'); ?>"><?php _e('Banner image:', 'wp_widget_plugin'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('banner'); ?>" name="<?php echo $this->get_field_name('banner'); ?>" type="text" value="<?php echo $banner; ?>" />
            </p>
            <p>
            <label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('Link:', 'wp_widget_plugin'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo $url; ?>" />
            </p>
            <p>
            <label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Text:', 'wp_widget_plugin'); ?></label>
            <textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>            
            </p>
            <p><input id="<?php echo $this->get_field_id('filter'); ?>" name="<?php echo $this->get_field_name('filter'); ?>" type="checkbox" <?php checked(isset($instance['filter']) ? $instance['filter'] : 0); ?> />&nbsp;<label for="<?php echo $this->get_field_id('filter'); ?>"><?php _e('Automatically add paragraphs'); ?></label></p>
            <?php
        }
     
        function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $instance['banner'] = strip_tags($new_instance['banner']);
            $instance['url'] = strip_tags($new_instance['url']);
            $instance['text'] = strip_tags($new_instance['text']);
            $instance['filter'] = isset($new_instance['filter']);
            return $instance;
        }
        
        // display widget
        function widget($args, $instance) {
            
            extract($args);
            // these are the widget options
            $banner = $instance['banner'];
            $url = $instance['url'];
            $text = apply_filters( 'widget_text', empty( $instance['text'] ) ? '' : $instance['text'], $instance );
            
            echo $before_widget;
            echo '<div class="donate_now_widget">';
            echo '<br /><div id="position_img"><a href="' . $url . '"><img src="' . $banner . '" /></a></div>';
            echo '<div class="donate_now_text">';
            echo !empty( $instance['filter'] ) ? wpautop( $text ) : $text;
            echo '</div>';
            echo '</div>';
            echo $after_widget;
        }
     
    }
    add_action( 'widgets_init', create_function('', 'return register_widget("DonateNowWidget");') );
    
    class TwtFbWidget extends WP_Widget {
        
        function TwtFbWidget() {
            $widget_ops = array('classname' => 'TwtFbWidget', 'description' => 'Sets links for social media in the footer' );
            $this->WP_Widget('TwtFbWidget', 'Twitter/Facebook Widget', $widget_ops);
        }
        
        function form($instance) {
            if($instance) {
                $twitter_url = esc_attr($instance['twitter_url']);
                $facebook_url = esc_attr($instance['facebook_url']);
            } else {
                $twitter_url = '';
                $facebook_url = '';
            }
            ?>
            <p>
            <label for="<?php echo $this->get_field_id('twitter_url'); ?>"><?php _e('Twitter url:', 'wp_widget_plugin'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('twitter_url'); ?>" name="<?php echo $this->get_field_name('twitter_url'); ?>" type="text" value="<?php echo $twitter_url; ?>" />
            </p>
            <p>
            <label for="<?php echo $this->get_field_id('facebook_url'); ?>"><?php _e('Facebook url:', 'wp_widget_plugin'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('facebook_url'); ?>" name="<?php echo $this->get_field_name('facebook_url'); ?>" type="text" value="<?php echo $facebook_url; ?>" />
            </p>
            <p>      
            <?php
        }
     
        function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $instance['twitter_url'] = strip_tags($new_instance['twitter_url']);
            $instance['facebook_url'] = strip_tags($new_instance['facebook_url']);
            return $instance;
        }
        
        // display widget
        function widget($args, $instance) {
            
            extract($args);
            // these are the widget options
            $twitter_url = $instance['twitter_url'];
            $facebook_url = $instance['facebook_url'];
            
            echo $before_widget;            
            echo '<div class="twtfbwidget">';
            echo '<a href="' . $twitter_url . '"><img src="' . TEMPLATE_DIRECTORY . '/images/twitter_icon.png" /></a>';
            echo '<a href="' . $facebook_url . '"><img src="' . TEMPLATE_DIRECTORY . '/images/facebook_icon.png" /></a>';
            echo '</div>';
            echo $after_widget;
        }
     
    }
    add_action( 'widgets_init', create_function('', 'return register_widget("TwtFbWidget");') );
        
    class SocialLinksWidget extends WP_Widget {
        
        function SocialLinksWidget() {
            $widget_ops = array('classname' => 'SocialLinksWidget', 'description' => 'Sets links for social media' );
            $this->WP_Widget('SocialLinksWidget', 'Social Links Widget', $widget_ops);
        }
        
        function form($instance) {
            if($instance) {
                $image = esc_attr($instance['image']);
                $title = esc_attr($instance['title']);
                $url = esc_attr($instance['url']);
                $text = esc_attr($instance['text']);
            } else {
                $image = '';
                $title = '';
                $url = '';
                $text = '';
            }
            ?>
            <p>
            <label for="<?php echo $this->get_field_id('image'); ?>"><?php _e('Image:', 'wp_widget_plugin'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('image'); ?>" type="text" value="<?php echo $image; ?>" />
            </p>
            <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wp_widget_plugin'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
            </p>
            <p>
            <label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('Link:', 'wp_widget_plugin'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo $url; ?>" />
            </p>
            <p>
            <label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Text:', 'wp_widget_plugin'); ?></label>
            <textarea class="widefat" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>
            </p>        
            <?php
        }
     
        function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $instance['image'] = strip_tags($new_instance['image']);
            $instance['title'] = strip_tags($new_instance['title']);
            $instance['url'] = strip_tags($new_instance['url']);
            $instance['text'] = strip_tags($new_instance['text']);
            return $instance;
        }
        
        // display widget
        function widget($args, $instance) {
            
            extract($args);
            // these are the widget options
            $image = $instance['image'];
            $title = $instance['title'];
            $url = $instance['url'];
            $text = $instance['text'];
            
            echo $before_widget;
            echo '<div class="social">';
            echo '<div class="social_image"><img src="' . $image . '" /></div>';
            echo '<div class="social_title"><a href="' . $url . '">' . $title . '</a></div>';
            echo '<div class="social_text">' . $text . '</div>';
            echo '</div>';
            echo $after_widget;
        }
     
    }
    add_action( 'widgets_init', create_function('', 'return register_widget("SocialLinksWidget");') );
    
    class AffiliateLinksWidget extends WP_Widget {
        
        function AffiliateLinksWidget() {
            $widget_ops = array('classname' => 'AffiliateLinksWidget', 'description' => 'Sets links for affiliates' );
            $this->WP_Widget('AffiliateLinksWidget', 'Affiliate Links Widget', $widget_ops);
        }
        
        function form($instance) {
            if($instance) {
                $image = esc_attr($instance['image']);
                $url = esc_attr($instance['url']);
                $title = esc_attr($instance['title']);
                $text = esc_attr($instance['text']);
            } else {
                $image = '';
                $url = '';
                $title = '';
                $text = '';
            }
            ?>
            <p>
            <label for="<?php echo $this->get_field_id('image'); ?>"><?php _e('Image:', 'wp_widget_plugin'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('image'); ?>" name="<?php echo $this->get_field_name('image'); ?>" type="text" value="<?php echo $image; ?>" />
            </p>
            <p>
            <label for="<?php echo $this->get_field_id('url'); ?>"><?php _e('Link:', 'wp_widget_plugin'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('url'); ?>" name="<?php echo $this->get_field_name('url'); ?>" type="text" value="<?php echo $url; ?>" />
            </p>
            <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wp_widget_plugin'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
            </p>
            <p>
            <label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Text:', 'wp_widget_plugin'); ?></label>
            <textarea class="widefat" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo $text; ?></textarea>
            </p>        
            <?php
        }
     
        function update($new_instance, $old_instance) {
            $instance = $old_instance;
            $instance['image'] = strip_tags($new_instance['image']);
            $instance['url'] = strip_tags($new_instance['url']);
            $instance['title'] = strip_tags($new_instance['title']);
            $instance['text'] = strip_tags($new_instance['text']);
            return $instance;
        }
        
        // display widget
        function widget($args, $instance) {
            
            extract($args);
            // these are the widget options
            $image = $instance['image'];
            $url = $instance['url'];
            $title = $instance['title'];
            $text = $instance['text'];
            
            echo $before_widget;
            echo '<div class="affiliate">';
            echo '<div class="affiliate_title">' . $title . '</div>';
            echo '<div class="affiliate_image"><a href="' . $url . '"><img src="' . $image . '" /></a></div>';
            echo '<div class="affiliate_text">' . $text . '</div>';
            echo '</div>';
            echo $after_widget;
        }
     
    }
    add_action( 'widgets_init', create_function('', 'return register_widget("AffiliateLinksWidget");') );

?>