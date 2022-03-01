<?php

if (!defined('ABSPATH')) {
    exit;
}
require_once('post_cst.php');
require_once('import_export.php');
require_once('form.php');
require_once('functions.php');
require_once('shortcodes.php');

function add_digits_page_builder_menu()
{
    add_submenu_page('digits_settings',
        esc_html__('Page Builder (Beta)', 'digits'),
        esc_html__('Page Builder (Beta)', 'digits'),
        'manage_options',
        'edit.php?post_type=digits-forms-page'
    );
    add_submenu_page('digits_settings',
        esc_html__('Popup Builder (Beta)', 'digits'),
        esc_html__('Popup Builder (Beta)', 'digits'),
        'manage_options',
        'edit.php?post_type=digits-forms-popup'
    );


}

add_action("digits_register_menu", "add_digits_page_builder_menu");


add_action('elementor/widgets/widgets_registered', 'dig_register_elementor_form');

function dig_register_elementor_form($widgets_manager)
{
    require_once('widget/element_form_widget.php');
    require_once('widget/login-register.php');
    require_once('widget/login.php');
    require_once('widget/register.php');
    require_once('widget/forgotpassword.php');
    require_once('widget/element_button_widget.php');

    $widgets_manager->register_widget_type(new Elementor_Digits_Login_Register());
    $widgets_manager->register_widget_type(new Elementor_Digits_Login());
    $widgets_manager->register_widget_type(new Elementor_Digits_Register());
    $widgets_manager->register_widget_type(new Elementor_Digits_Forgot_Password());
    $widgets_manager->register_widget_type(new Elementor_Digits_Button());
}

add_action('elementor/editor/before_enqueue_scripts', 'digbuilder_enqueue_scripts');
function digbuilder_enqueue_scripts()
{
    $screen = get_current_screen();

    wp_register_script('digbuilder', digbuilder_url() . '/js/page.min.js', array('jquery'), digbuilder_version());
    $obj = array(
        'terms_label' => esc_attr__('I Agree [t]Terms and Conditions[/t] & [p]Privacy Policy[/t]', 'digits')
    );
    wp_localize_script('digbuilder', 'digbuilder', $obj);
    wp_enqueue_script('digbuilder');
}


add_action('elementor/init', 'dig_elementor_init');

function dig_elementor_init()
{

    Elementor\Plugin::$instance->elements_manager->add_category(
        'digits-form',
        [
            'title' => __('Digits', 'digits'),
        ]
    );
}

add_action('elementor/controls/controls_registered', 'dig_add_controls', 10);

function dig_add_controls($controls_manager)
{
    $grouped = array(
        'digits-icon-box-style' => 'Digits_Group_Control_Box_Style',
    );
    require_once 'builder/Digits_Group_Control_Box_Style.php';

    foreach ($grouped as $control_id => $class) {
        $controls_manager->add_group_control($control_id, new $class());
    }
}

function digbuilder_add_admin_scripts($hook)
{

    global $post;

    if ($hook == 'post-new.php' || $hook == 'post.php' || $hook == 'edit.php') {
        if (empty($post)) {
            $type = $_GET['post_type'];
        } else {
            $type = $post->post_type;
        }
        if (is_digbuilder_type($type)) {
            wp_enqueue_style('digbuilder', digbuilder_url() . '/css/builder.min.css', array(), digbuilder_version(), 'all');

            wp_register_script('digbuilder', digbuilder_url() . '/js/digbuilder_settings.js');
            $pagebuilder = array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'import' => esc_attr__('Import', 'digits'),
                'import_preset' => esc_attr__('Import Preset', 'digits'),
                'preset_library' => esc_attr__('Preset Library', 'digits'),
                'please_select_type' => esc_attr__('Please select the form type you want to import', 'digits'),
                'multiple_not_supported' => esc_attr__('Multiple preset imports are not supported!', 'digits'),
                'browser_not_supported' => esc_attr__('Browser not supported', 'digits'),
                'only_json' => esc_attr__('Only json are supported', 'digits'),
                'error_loading_preset' => esc_attr__('Error while loading presets', 'digits'),
            );
            wp_localize_script('digbuilder', 'digbuilder', $pagebuilder);
            wp_enqueue_script('digbuilder');
        }
    }
}

add_action('elementor/editor/before_enqueue_styles', 'digbuilder_enqueue_styles');
add_action('elementor/preview/enqueue_styles', 'digbuilder_enqueue_styles');
add_action('elementor/frontend/before_enqueue_styles', 'digbuilder_enqueue_styles');

function digbuilder_enqueue_styles()
{
    wp_enqueue_style('digits-form-popup-style', digbuilder_url() . '/css/page.min.css', array(), digbuilder_version(), 'all');
    wp_enqueue_style('animate.css', digbuilder_url() . '/css/animate.min.css', array(), '3.7.2', 'all');

    wp_enqueue_style('digbuilder', digbuilder_url() . '/css/builder.min.css', array(), digbuilder_version(), 'all');


    if (function_exists('dig_add_menu_css') && current_user_can('manage_options')) {
        dig_add_menu_css();
    }
}


add_action('admin_enqueue_scripts', 'digbuilder_add_admin_scripts', 10, 1);

add_filter('manage_digits-forms-page_posts_columns', 'digbuilder_form_shortcode_coloumn');
add_filter('manage_digits-forms-popup_posts_columns', 'digbuilder_form_shortcode_coloumn');
function digbuilder_form_shortcode_coloumn($columns)
{
    unset($columns['date']);
    $columns['shortcode'] = __('Shortcode', 'digits');
    $columns['date'] = __('Date');
    return $columns;
}

add_action('manage_digits-forms-page_posts_custom_column', 'digpage_page_coloumn', 10, 2);
add_action('manage_digits-forms-popup_posts_custom_column', 'digpage_popup_coloumn', 10, 2);
function digpage_popup_coloumn($column, $post_id)
{
    switch ($column) {
        case 'shortcode' :
            digpage_render_shortode_coloumn('[digits-popup id=' . $post_id . ']');
            break;

    }
}

function digpage_page_coloumn($column, $post_id)
{
    switch ($column) {
        case 'shortcode' :
            digpage_render_shortode_coloumn('[digits-page id=' . $post_id . ']');
            break;

    }
}

function digpage_render_shortode_coloumn($shortcode)
{
    ?>
    <div class="digits_shortcode_tbs">
        <input type="text" onfocus="this.select();" readonly="readonly"
               value="<?php echo esc_attr($shortcode); ?>" class="digpage_shortcode large-text">
    </div>
    <?php
}


function is_digbuilder_type($type)
{
    if ($type == 'digits-forms-popup' || $type == 'digits-forms-page') {
        return true;
    }

    return false;
}

function digpage_add_noaccess()
{
    if (get_post_type() == 'digits-forms-page') {
        dipagebuilder_if_loggedin_redirect();
        do_action('digits_page_ini');
    };
}

add_action('template_redirect', 'digpage_add_noaccess');

function dipagebuilder_if_loggedin_redirect()
{
    if (is_user_logged_in()) {
        if (did_action('elementor/loaded')) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode()) {
                return;
            }
        }
        $redirect_url = isset($_GET['redirect_to']) ? esc_html__($_GET['redirect_to']) : get_home_url();
        wp_safe_redirect($redirect_url);
        die();


    }
}
