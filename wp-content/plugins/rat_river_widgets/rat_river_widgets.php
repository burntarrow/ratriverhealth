<?php
/**
 * Plugin Name: Rat River Widgets
 * Description: Custom widgets used by the Rat River Health archive.
 * Author: Jan Lucki
 * Version: 1.1.0
 * Requires PHP: 8.1
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Return the active theme URL without relying on a legacy global constant.
 */
function rrh_widgets_theme_uri(): string
{
    if (defined('TEMPLATE_DIRECTORY')) {
        return untrailingslashit((string) TEMPLATE_DIRECTORY);
    }

    return untrailingslashit(get_template_directory_uri());
}

class DonateNowBannerFoundation extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'DonateNowBannerFoundation',
            __('Donate Now Hospital Foundation', 'rat-river-widgets'),
            array(
                'classname'   => 'DonateNowBannerFoundation',
                'description' => __('Donate Now Hospital Foundation', 'rat-river-widgets'),
            )
        );
    }

    public function form($instance)
    {
        $instance = wp_parse_args((array) $instance, array('url' => ''));
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('url')); ?>">
                <?php esc_html_e('Link:', 'rat-river-widgets'); ?>
            </label>
            <input
                class="widefat"
                id="<?php echo esc_attr($this->get_field_id('url')); ?>"
                name="<?php echo esc_attr($this->get_field_name('url')); ?>"
                type="url"
                value="<?php echo esc_attr($instance['url']); ?>"
            />
        </p>
        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance        = (array) $old_instance;
        $instance['url'] = esc_url_raw($new_instance['url'] ?? '');

        return $instance;
    }

    public function widget($args, $instance)
    {
        $url = esc_url($instance['url'] ?? '');

        echo $args['before_widget'] ?? '';

        if ($url) {
            printf(
                '<a href="%1$s"><img src="%2$s" alt="%3$s" loading="lazy"></a>',
                $url,
                esc_url(rrh_widgets_theme_uri() . '/images_foundation/donate_now_banner.png'),
                esc_attr__('Donate now', 'rat-river-widgets')
            );
        }

        echo $args['after_widget'] ?? '';
    }
}

class DonateNowWidget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'DonateNowWidget',
            __('Donate Now Widget', 'rat-river-widgets'),
            array(
                'classname'   => 'DonateNowWidget',
                'description' => __('Donate Now sidebar', 'rat-river-widgets'),
            )
        );
    }

    public function form($instance)
    {
        $instance = wp_parse_args(
            (array) $instance,
            array(
                'banner' => '',
                'url'    => '',
                'text'   => '',
                'filter' => false,
            )
        );
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('banner')); ?>">
                <?php esc_html_e('Banner image:', 'rat-river-widgets'); ?>
            </label>
            <input
                class="widefat"
                id="<?php echo esc_attr($this->get_field_id('banner')); ?>"
                name="<?php echo esc_attr($this->get_field_name('banner')); ?>"
                type="url"
                value="<?php echo esc_attr($instance['banner']); ?>"
            />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('url')); ?>">
                <?php esc_html_e('Link:', 'rat-river-widgets'); ?>
            </label>
            <input
                class="widefat"
                id="<?php echo esc_attr($this->get_field_id('url')); ?>"
                name="<?php echo esc_attr($this->get_field_name('url')); ?>"
                type="url"
                value="<?php echo esc_attr($instance['url']); ?>"
            />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('text')); ?>">
                <?php esc_html_e('Text:', 'rat-river-widgets'); ?>
            </label>
            <textarea
                class="widefat"
                rows="16"
                cols="20"
                id="<?php echo esc_attr($this->get_field_id('text')); ?>"
                name="<?php echo esc_attr($this->get_field_name('text')); ?>"
            ><?php echo esc_textarea($instance['text']); ?></textarea>
        </p>
        <p>
            <input
                id="<?php echo esc_attr($this->get_field_id('filter')); ?>"
                name="<?php echo esc_attr($this->get_field_name('filter')); ?>"
                type="checkbox"
                value="1"
                <?php checked(!empty($instance['filter'])); ?>
            />
            <label for="<?php echo esc_attr($this->get_field_id('filter')); ?>">
                <?php esc_html_e('Automatically add paragraphs', 'rat-river-widgets'); ?>
            </label>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance           = (array) $old_instance;
        $instance['banner'] = esc_url_raw($new_instance['banner'] ?? '');
        $instance['url']    = esc_url_raw($new_instance['url'] ?? '');
        $instance['text']   = sanitize_textarea_field($new_instance['text'] ?? '');
        $instance['filter'] = !empty($new_instance['filter']);

        return $instance;
    }

    public function widget($args, $instance)
    {
        $instance = wp_parse_args(
            (array) $instance,
            array(
                'banner' => '',
                'url'    => '',
                'text'   => '',
                'filter' => false,
            )
        );

        $banner = esc_url($instance['banner']);
        $url    = esc_url($instance['url']);
        $text   = apply_filters('widget_text', $instance['text'], $instance, $this);

        echo $args['before_widget'] ?? '';
        echo '<div class="donate_now_widget">';

        if ($banner) {
            echo '<br><div id="position_img">';
            if ($url) {
                printf('<a href="%1$s"><img src="%2$s" alt="%3$s" loading="lazy"></a>', $url, $banner, esc_attr__('Donate now', 'rat-river-widgets'));
            } else {
                printf('<img src="%1$s" alt="%2$s" loading="lazy">', $banner, esc_attr__('Donate now', 'rat-river-widgets'));
            }
            echo '</div>';
        }

        echo '<div class="donate_now_text">';
        echo !empty($instance['filter']) ? wpautop(wp_kses_post($text)) : wp_kses_post($text);
        echo '</div></div>';
        echo $args['after_widget'] ?? '';
    }
}

class TwtFbWidget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'TwtFbWidget',
            __('Twitter/Facebook Widget', 'rat-river-widgets'),
            array(
                'classname'   => 'TwtFbWidget',
                'description' => __('Sets links for social media in the footer', 'rat-river-widgets'),
            )
        );
    }

    public function form($instance)
    {
        $instance = wp_parse_args(
            (array) $instance,
            array(
                'twitter_url'  => '',
                'facebook_url' => '',
            )
        );
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('twitter_url')); ?>">
                <?php esc_html_e('Twitter URL:', 'rat-river-widgets'); ?>
            </label>
            <input
                class="widefat"
                id="<?php echo esc_attr($this->get_field_id('twitter_url')); ?>"
                name="<?php echo esc_attr($this->get_field_name('twitter_url')); ?>"
                type="url"
                value="<?php echo esc_attr($instance['twitter_url']); ?>"
            />
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('facebook_url')); ?>">
                <?php esc_html_e('Facebook URL:', 'rat-river-widgets'); ?>
            </label>
            <input
                class="widefat"
                id="<?php echo esc_attr($this->get_field_id('facebook_url')); ?>"
                name="<?php echo esc_attr($this->get_field_name('facebook_url')); ?>"
                type="url"
                value="<?php echo esc_attr($instance['facebook_url']); ?>"
            />
        </p>
        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance                 = (array) $old_instance;
        $instance['twitter_url']  = esc_url_raw($new_instance['twitter_url'] ?? '');
        $instance['facebook_url'] = esc_url_raw($new_instance['facebook_url'] ?? '');

        return $instance;
    }

    public function widget($args, $instance)
    {
        $twitter_url  = esc_url($instance['twitter_url'] ?? '');
        $facebook_url = esc_url($instance['facebook_url'] ?? '');
        $theme_uri    = rrh_widgets_theme_uri();

        echo $args['before_widget'] ?? '';
        echo '<div class="twtfbwidget">';

        if ($twitter_url) {
            printf(
                '<a href="%1$s"><img src="%2$s" alt="%3$s" loading="lazy"></a>',
                $twitter_url,
                esc_url($theme_uri . '/images/twitter_icon.png'),
                esc_attr__('Twitter', 'rat-river-widgets')
            );
        }

        if ($facebook_url) {
            printf(
                '<a href="%1$s"><img src="%2$s" alt="%3$s" loading="lazy"></a>',
                $facebook_url,
                esc_url($theme_uri . '/images/facebook_icon.png'),
                esc_attr__('Facebook', 'rat-river-widgets')
            );
        }

        echo '</div>';
        echo $args['after_widget'] ?? '';
    }
}

class SocialLinksWidget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'SocialLinksWidget',
            __('Social Links Widget', 'rat-river-widgets'),
            array(
                'classname'   => 'SocialLinksWidget',
                'description' => __('Sets links for social media', 'rat-river-widgets'),
            )
        );
    }

    public function form($instance)
    {
        $instance = wp_parse_args(
            (array) $instance,
            array(
                'image' => '',
                'title' => '',
                'url'   => '',
                'text'  => '',
            )
        );

        $this->render_text_field('image', __('Image:', 'rat-river-widgets'), $instance['image'], 'url');
        $this->render_text_field('title', __('Title:', 'rat-river-widgets'), $instance['title']);
        $this->render_text_field('url', __('Link:', 'rat-river-widgets'), $instance['url'], 'url');
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('text')); ?>">
                <?php esc_html_e('Text:', 'rat-river-widgets'); ?>
            </label>
            <textarea
                class="widefat"
                id="<?php echo esc_attr($this->get_field_id('text')); ?>"
                name="<?php echo esc_attr($this->get_field_name('text')); ?>"
            ><?php echo esc_textarea($instance['text']); ?></textarea>
        </p>
        <?php
    }

    private function render_text_field(string $key, string $label, string $value, string $type = 'text'): void
    {
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id($key)); ?>"><?php echo esc_html($label); ?></label>
            <input
                class="widefat"
                id="<?php echo esc_attr($this->get_field_id($key)); ?>"
                name="<?php echo esc_attr($this->get_field_name($key)); ?>"
                type="<?php echo esc_attr($type); ?>"
                value="<?php echo esc_attr($value); ?>"
            />
        </p>
        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance          = (array) $old_instance;
        $instance['image'] = esc_url_raw($new_instance['image'] ?? '');
        $instance['title'] = sanitize_text_field($new_instance['title'] ?? '');
        $instance['url']   = esc_url_raw($new_instance['url'] ?? '');
        $instance['text']  = sanitize_textarea_field($new_instance['text'] ?? '');

        return $instance;
    }

    public function widget($args, $instance)
    {
        $image = esc_url($instance['image'] ?? '');
        $title = (string) ($instance['title'] ?? '');
        $url   = esc_url($instance['url'] ?? '');
        $text  = (string) ($instance['text'] ?? '');

        echo $args['before_widget'] ?? '';
        echo '<div class="social">';

        if ($image) {
            printf('<div class="social_image"><img src="%1$s" alt="" loading="lazy"></div>', $image);
        }
        if ($title) {
            echo '<div class="social_title">';
            echo $url ? '<a href="' . $url . '">' . esc_html($title) . '</a>' : esc_html($title);
            echo '</div>';
        }
        if ($text) {
            echo '<div class="social_text">' . wp_kses_post($text) . '</div>';
        }

        echo '</div>';
        echo $args['after_widget'] ?? '';
    }
}

class AffiliateLinksWidget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            'AffiliateLinksWidget',
            __('Affiliate Links Widget', 'rat-river-widgets'),
            array(
                'classname'   => 'AffiliateLinksWidget',
                'description' => __('Sets links for affiliates', 'rat-river-widgets'),
            )
        );
    }

    public function form($instance)
    {
        $instance = wp_parse_args(
            (array) $instance,
            array(
                'image' => '',
                'url'   => '',
                'title' => '',
                'text'  => '',
            )
        );

        foreach (array(
            'image' => __('Image:', 'rat-river-widgets'),
            'url'   => __('Link:', 'rat-river-widgets'),
            'title' => __('Title:', 'rat-river-widgets'),
        ) as $key => $label) {
            $type = in_array($key, array('image', 'url'), true) ? 'url' : 'text';
            ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id($key)); ?>"><?php echo esc_html($label); ?></label>
                <input
                    class="widefat"
                    id="<?php echo esc_attr($this->get_field_id($key)); ?>"
                    name="<?php echo esc_attr($this->get_field_name($key)); ?>"
                    type="<?php echo esc_attr($type); ?>"
                    value="<?php echo esc_attr($instance[$key]); ?>"
                />
            </p>
            <?php
        }
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('text')); ?>">
                <?php esc_html_e('Text:', 'rat-river-widgets'); ?>
            </label>
            <textarea
                class="widefat"
                id="<?php echo esc_attr($this->get_field_id('text')); ?>"
                name="<?php echo esc_attr($this->get_field_name('text')); ?>"
            ><?php echo esc_textarea($instance['text']); ?></textarea>
        </p>
        <?php
    }

    public function update($new_instance, $old_instance)
    {
        $instance          = (array) $old_instance;
        $instance['image'] = esc_url_raw($new_instance['image'] ?? '');
        $instance['url']   = esc_url_raw($new_instance['url'] ?? '');
        $instance['title'] = sanitize_text_field($new_instance['title'] ?? '');
        $instance['text']  = sanitize_textarea_field($new_instance['text'] ?? '');

        return $instance;
    }

    public function widget($args, $instance)
    {
        $image = esc_url($instance['image'] ?? '');
        $url   = esc_url($instance['url'] ?? '');
        $title = (string) ($instance['title'] ?? '');
        $text  = (string) ($instance['text'] ?? '');

        echo $args['before_widget'] ?? '';
        echo '<div class="affiliate">';

        if ($title) {
            echo '<div class="affiliate_title">' . esc_html($title) . '</div>';
        }
        if ($image) {
            echo '<div class="affiliate_image">';
            echo $url ? '<a href="' . $url . '"><img src="' . $image . '" alt="" loading="lazy"></a>' : '<img src="' . $image . '" alt="" loading="lazy">';
            echo '</div>';
        }
        if ($text) {
            echo '<div class="affiliate_text">' . wp_kses_post($text) . '</div>';
        }

        echo '</div>';
        echo $args['after_widget'] ?? '';
    }
}

/**
 * Register all custom widgets without the removed create_function() API.
 */
function rrh_register_custom_widgets(): void
{
    register_widget(DonateNowBannerFoundation::class);
    register_widget(DonateNowWidget::class);
    register_widget(TwtFbWidget::class);
    register_widget(SocialLinksWidget::class);
    register_widget(AffiliateLinksWidget::class);
}
add_action('widgets_init', 'rrh_register_custom_widgets');
