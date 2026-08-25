<?php
/**
 * Legacy ThemeBlossom options-page controller.
 *
 * Modernized for PHP 8.x while preserving the original public class and method
 * names used throughout the theme.
 */

class dashboardPages
{
    /** @var array<int|string, mixed> */
    public $options = array();

    /** @var array<string, mixed> */
    public $pageoptions = array();

    /**
     * @param array<int|string, mixed> $options
     * @param array<string, mixed>     $pageoptions
     */
    public function __construct($options, $pageoptions)
    {
        $this->options     = is_array($options) ? $options : array();
        $this->pageoptions = is_array($pageoptions) ? $pageoptions : array();

        global $level;
        $level = isset($level) ? (int) $level + 1 : 1;

        add_action('admin_menu', array($this, 'add_admin_menu'), $level);
    }

    public function add_admin_menu()
    {
        $option_file = isset($this->pageoptions['file'])
            ? sanitize_key((string) $this->pageoptions['file'])
            : '';

        if ('' === $option_file) {
            return;
        }

        $requested_page = isset($_GET['page'])
            ? sanitize_key(wp_unslash($_GET['page']))
            : '';
        $action = isset($_POST['action'])
            ? sanitize_key(wp_unslash($_POST['action']))
            : '';

        if ($option_file === $requested_page && 'save' === $action && current_user_can('manage_options')) {
            foreach ($this->options as $value) {
                if (!is_array($value) || empty($value['id'])) {
                    continue;
                }

                $option_id = sanitize_key((string) $value['id']);
                if (array_key_exists($option_id, $_POST)) {
                    update_option($option_id, wp_unslash($_POST[$option_id]));
                }
            }

            wp_safe_redirect(
                add_query_arg(
                    array(
                        'page'  => $option_file,
                        'saved' => 'true',
                    ),
                    admin_url('admin.php')
                )
            );
            exit;
        }

        $menu_icon = get_option('tb_menu_icon');
        if (!$menu_icon && defined('DEFAULT_MENU_ICON')) {
            $menu_icon = DEFAULT_MENU_ICON;
        }

        $page_name = isset($this->pageoptions['name'])
            ? (string) $this->pageoptions['name']
            : __('Theme Options', 'the-cause');

        if (!empty($this->pageoptions['child']) && defined('THEME_OPTIONS_PAGE')) {
            add_submenu_page(
                THEME_OPTIONS_PAGE,
                $page_name,
                $page_name,
                'manage_options',
                $option_file,
                array($this, 'tb_admin_page')
            );
            return;
        }

        add_menu_page(
            $page_name,
            $page_name,
            'manage_options',
            $option_file,
            array($this, 'tb_admin_page'),
            $menu_icon ?: 'dashicons-admin-generic'
        );

        if (!defined('THEME_OPTIONS_PAGE')) {
            define('THEME_OPTIONS_PAGE', $option_file);
        }
    }

    public function tb_admin_page()
    {
        if (!empty($_GET['saved'])) {
            echo '<div id="message" class="updated notice is-dismissible"><p><strong>';
            esc_html_e('Settings saved.', 'the-cause');
            echo '</strong></p></div>';
        }

        $this->displayOptionsPage();
    }

    public function displayOptionsPage()
    {
        if (function_exists('display')) {
            display($this->options);
        }
    }
}
