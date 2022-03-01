<?php


if (!defined('ABSPATH')) {
    exit;
}

final class Digits_Pagebuilder_shortcodes
{
    protected static $_instance = null;
    public static $popups = array();

    /**
     *  Constructor.
     */
    public function __construct()
    {
        $this->init_hooks();
    }

    private function init_hooks()
    {
        add_shortcode('digits-page', array($this, 'page_shortcode'));
        add_shortcode('digits-popup', array($this, 'popup_shortcode'));

        add_action('wp_footer', array($this, 'load_popup'), 10);

    }

    public function load_popup()
    {
        if (!did_action('elementor/loaded') || is_user_logged_in()) {
            return;
        }

        if (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode()) {
            return;
        }

        foreach (self::$popups as $popup_id) {

            $popup_status = get_post_status($popup_id);

            if ($popup_status == 'publish') {
                try {
                    $popup_content = \Elementor\Plugin::$instance->frontend->get_builder_content($popup_id);

                    set_query_var('popup_id', absint($popup_id));
                    set_query_var('popup_content', $popup_content);
                    load_template(digbuilder_popup_template(), false);

                } catch (Exception $e) {

                }
            }

        }
    }

    public function popup_shortcode($attr)
    {

        $popup_id = $attr['id'];
        $permalink = get_permalink($popup_id);

        if (!$permalink) return '';

        $attr = array();
        $attr['url'] = '#';
        if (!is_user_logged_in()) {
            $attr['data'] = 'data-show="' . self::popup_id($popup_id, true) . '"';
        }

        return dig_login_contents(true, 1, false, $attr);

    }

    public static function popup_id($popup_id, $load_popup = true)
    {
        if ($load_popup) self::add_popup($popup_id);
        return '#digits-forms-popup-' . $popup_id;
    }

    public static function add_popup($popup_id)
    {
        if (!in_array($popup_id, self::$popups)) {
            self::$popups[] = $popup_id;
        }
    }

    public function page_shortcode($attr)
    {
        $post_id = $attr['id'];

        $url = self::get_url($post_id, null);
        if (empty($url)) return;
        $attr = array();
        $attr['url'] = $url;
        return dig_login_contents(false, true, 1, $attr);
    }

    public static function get_url($post_id, $redirect_url = null)
    {
        $permalink = get_permalink($post_id);

        if (!$permalink) return '';
        return self::get_redirect_url($permalink, $redirect_url);
    }

    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }


    public static function get_redirect_url($permalink, $redirect_url = null)
    {
        global $wp;
        if ($redirect_url == null) $redirect_url = home_url($wp->request);

        if (!self::is_same_url($redirect_url, $permalink)) {
            return add_query_arg(array(
                'redirect_to' => urlencode($redirect_url),
            ), $permalink);
        } else {
            return $permalink;
        }

    }

    public static function is_same_url($url1, $url2)
    {
        if (substr($url1, -1) !== ' / ') {
            $url1 = $url1 . ' / ';
        }
        if (substr($url2, -1) !== ' / ') {
            $url2 = $url2 . ' / ';
        }
        $url1 = parse_url($url1);
        $url2 = parse_url($url2);
        return $url1['path'] == $url2['path'] ? true : false;
    }


}

function digits_pagebuilder_shortcodes()
{
    return Digits_Pagebuilder_shortcodes::instance();
}

digits_pagebuilder_shortcodes();
