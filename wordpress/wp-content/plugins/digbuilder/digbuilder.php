<?php

/*
 * Plugin Name: DIGITS: Builder
 * Description: This Digits addon allows you to create custom Login/Signup Page/Modal
 * Version: 0.9.3.5
 * Plugin URI: https://digits.unitedover.com/addons
 * Author URI: https://www.unitedover.com/
 * Author: UnitedOver
 * Text Domain: digbuilder
 * Requires PHP: 5.5
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit;
}

function digbuilder_version()
{
    return '0.9.3.5';
}

require dirname(__FILE__) . '/Puc/v4p6/Factory.php';
require dirname(__FILE__) . '/Puc/v4/Factory.php';
require dirname(__FILE__) . '/Puc/v4p6/Autoloader.php';
new Puc_v4p6_Autoloader();

foreach (
    array(
        'Plugin_UpdateChecker' => 'Puc_v4p6_Plugin_UpdateChecker',
        'Vcs_PluginUpdateChecker' => 'Puc_v4p6_Vcs_PluginUpdateChecker',
    )
    as $pucGeneralClass => $pucVersionedClass
) {
    Puc_v4_Factory::addVersion($pucGeneralClass, $pucVersionedClass, '4.6');

    Puc_v4p6_Factory::addVersion($pucGeneralClass, $pucVersionedClass, '4.6');
}

require_once('includes/main.php');

function digbuilder_dir()
{
    return plugin_dir_path(__FILE__);
}

function digbuilder_popup_template()
{
    return digbuilder_dir() . 'templates/popup.php';
}

function digbuilder_url()
{
    return plugins_url('digbuilder');
}


function digbuilder_notice()
{
    $check_elem = digbuilder_check_elem();
    if (!empty($check_elem)) {
        echo $check_elem;
    }


    $screen = get_current_screen();
    if (!is_digbuilder_type($screen->post_type) || $screen->base != 'edit') return;

    ?>
    <div class="notice notice-warning digits-notice">
        <p>Digits Builder does not support Additional Fields & Logic Builder addon and One Click Login/Signup addon
            currently, compatibility with them will be available soon.
        </p>
    </div>
    <?php
}

add_action('admin_notices', 'digbuilder_notice');


register_activation_hook(__FILE__, 'digbuilder_activate');
function digbuilder_activate()
{
    update_option('digbuilder_activated', true);
    digbuilder_update_elementor_supported_types();
}

function digbuilder_update_elementor_supported_types()
{


    $cpt_support = get_option('elementor_cpt_support');

    if (!$cpt_support) {
        return;
    }

    $update = false;
    $types = array('digits-forms-popup', 'digits-forms-page');
    foreach ($types as $type) {
        if (!in_array($type, $cpt_support)) {
            $cpt_support[] = $type;
            $update = true;
        }
    }
    if ($update) {
        update_option('elementor_cpt_support', $cpt_support);
    }

}

add_action('admin_init', 'digbuilder_check_activate_elem');

function digbuilder_check_activate_elem()
{
    if (get_option('digbuilder_activated', false) == true) {
        update_option('digbuilder_activated', false);
        $check_elem = digbuilder_check_elem();
        if (!empty($check_elem)) {
            wp_die($check_elem);
        }
    }
}

function digbuilder_check_elem($class = 'notice notice-error install-elementor')
{
    if (!did_action('elementor/loaded')) {

        $url = get_admin_url() . 'plugin-install.php?s=Elementor&tab=search&type=term';
        $notice = '<div class="' . $class . '">
                <p>
                Please install & activate Elementor plugin to enable Digits page builder.</p>
                <p><a class="button button-primary" href="' . $url . '">Click here to Install</a>
                </p>
                </div>';

        return $notice;
    }
    return null;
}

function digits_update_pagebuilder_settings()
{
    if (isset($_POST['digbuilder'])) {
        $digits_pages = get_option('digits_default_pages', array());
        foreach (digbuilder_default_types() as $key => $fields) {
            $values = array();
            foreach ($fields['types'] as $field_key => $field_label) {
                $field_value = $key . '_' . $field_key;
                $values[$field_key] = sanitize_text_field($_POST[$field_value]);
            }
            $digits_pages[$key] = $values;
        }
        update_option('digits_default_pages', $digits_pages);
    }
}

add_action('digits_save_settings_data', 'digits_update_pagebuilder_settings');

function register_digbuilder($list)
{
    $list[] = 'digbuilder';
    return $list;
}

add_filter('digits_addon', 'register_digbuilder');


function digits_addon_digbuilder()
{
    return 'digbuilder';
}


function dig_show_digbuilder($active_tab)
{
    ?>
    <div data-tab="digbuildertab"
         class="dig_admin_in_pt digbuildertab digtabview <?php echo $active_tab == digits_addon_digbuilder() ? 'digcurrentactive' : '" style="display:none;'; ?>">
        <?php digad_show_pagebuilder_settings(); ?>
    </div>

    <?php

}

add_action('digits_settings_page', 'dig_show_digbuilder');

function digbuilder_default_types()
{
    $form_types = array(
        'login_register' => esc_attr__('Login / Register', 'digits'),
        'login' => esc_attr__('Login', 'digits'),
        'register' => esc_attr__('Register', 'digits'),
        'forgot' => esc_attr__('Forgot', 'digits'),
    );
    return array(
        'modal' => array('label' => esc_attr__('Primary Modal', 'digits'), 'types' => $form_types),
        'page' => array('label' => esc_attr__('Primary Page', 'digits'), 'types' => $form_types),
    );
}

function digad_show_pagebuilder_settings()
{
    $digpc = get_site_option('dig_purchasecode');

    if (empty($digpc)) return;

    $digits_pages = get_option('digits_default_pages', array());
    foreach (digbuilder_default_types() as $key => $fields) {
        $pages = digits_pages_list($key);
        ?>
        <div class="dig_admin_head"><span><?php echo $fields['label'] ?></span></div>
        <table class="form-table">

            <?php
            $key_values = array_key_exists($key, $digits_pages) ? $digits_pages[$key] : array();

            foreach ($fields['types'] as $field_key => $field_label) {
                $key_type = $key . '_' . $field_key;
                $selected_value = isset($key_values[$field_key]) ? $key_values[$field_key] : '';
                ?>
                <tr>
                    <th scope="row"><label
                                for="<?php echo $key_type; ?>"><?php echo $field_label; ?>
                        </label></th>
                    <td>
                        <select name="<?php echo $key_type; ?>" id="<?php echo $key_type; ?>">
                            <?php
                            echo '<option value="default" data-display="(' . esc_attr__('select', 'digits') . ')">(' . esc_attr__('select', 'digits') . ')</option>';
                            foreach ($pages as $page_key => $page_values) {
                                $sel = '';
                                if ($page_key == $selected_value) {
                                    $sel = 'selected="selected"';
                                }
                                echo '<option value="' . esc_attr__($page_key) . '" ' . $sel . '>' . esc_html__($page_values['label']) . '</option>';
                            } ?>
                        </select>
                    </td>
                </tr>
                <?php
            }
            ?>


        </table>
        <?php
    }
    ?>
    <input type="hidden" name="digbuilder" value="1">
    <?php
}

function digbuilder_add_pagelock_settings($post_id)
{
    $current_form_id = get_post_meta($post_id, 'diglock_lock_form_id', true);

    $default_types = digbuilder_default_types();
    foreach (array('modal', 'page') as $key) {
        $pages = digits_pages_list($key);
        $key_type = 'digbuilder_lock_type_' . $key;
        ?>
        <div class="components-base-control digbuilder_lock_type_select <?php echo $key_type; ?>">
            <div class="components-base-control__field">
                <label class="components-base-control__label"><?php echo $default_types[$key]['label']; ?>:</label>
                <select class="components-select-control__input" name="<?php echo $key_type; ?>"
                        id="<?php echo $key_type; ?>">
                    <?php
                    foreach ($pages as $page_key => $page_values) {
                        $sel = '';
                        if ($page_key == $current_form_id) {
                            $sel = 'selected="selected"';
                        }
                        $page_id = preg_replace('/' . $key . '_/', '', $page_key, 1);

                        echo '<option value="' . esc_attr__($page_id) . '" ' . $sel . '>' . esc_html__($page_values['label']) . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>

        <script>
            jQuery(document).ready(function () {
                jQuery("#diglock_lock_mode").on('change', function () {
                    var value = jQuery(this).val();
                    jQuery(".digbuilder_lock_type_select").hide();
                    if (value == 1) {
                        jQuery(".digbuilder_lock_type_page").show();
                    } else {
                        jQuery(".digbuilder_lock_type_modal").show();
                    }
                }).trigger('change');
            })
        </script>
        <?php
    }
}

add_action('digits_pagelock_single_page_settings', 'digbuilder_add_pagelock_settings');

add_action('digits_pagelock_single_page_settins_update', 'digbuilder_update_pagelock_settings');
function digbuilder_update_pagelock_settings($post_id)
{
    if (!current_user_can('edit_page', $post_id)) {
        return;
    }
    $lock_mode = sanitize_text_field($_POST['diglock_lock_mode']);
    if ($lock_mode == 1) {
        $lock = 'digbuilder_lock_type_page';
    } else {
        $lock = 'digbuilder_lock_type_modal';
    }
    $lock = sanitize_text_field($_POST[$lock]);
    update_post_meta($post_id, 'diglock_lock_form_id', $lock);
}

add_filter('is_digits_login_reg_page', 'check_digbuilder_login_reg_page', 10, 3);

function check_digbuilder_login_reg_page($is_login, $lock_page_id, $current_url)
{

    if (!$lock_page_id) {
        $page_id = digbuilder_get_default_form(true, 'login_register');
        if ($page_id) {
            $permalink = get_permalink($page_id);
            if (Digits_Pagebuilder_shortcodes::is_same_url(dig_removeStringParameter($current_url, "redirect_to"), $permalink)) {
                return true;
            }
        }

    } else if (is_digbuilder_type(get_post_type($lock_page_id))) {
        return true;
    }

    return $is_login;
}


add_filter('digpagelock_modal_lock', 'digbuilder_modal_lock', 10, 2);
function digbuilder_modal_lock($filter, $lock_page_id)
{
    $popup_id = 0;
    if ($lock_page_id && !empty($lock_page_id)) {
        $popup_id = get_post_meta($lock_page_id, 'diglock_lock_form_id', true);
    }

    if (!$popup_id || empty($popup_id)) {
        $popup_id = digbuilder_get_default_form(false, 'login_register');
    }

    if ($popup_id != null && is_numeric($popup_id)) {
        Digits_Pagebuilder_shortcodes::add_popup($popup_id);
        return 'digits_modal_class_' . $popup_id;
    }
    return $filter;
}

add_filter('digits_pagelock_login_url', 'digbuilder_get_default_login', 10, 3);

function digbuilder_get_default_login($login_url, $current_url, $lock_page_id)
{
    $page_id = 0;
    if ($lock_page_id && !empty($lock_page_id)) {
        $current_form_id = get_post_meta($lock_page_id, 'diglock_lock_form_id', true);
        if (!empty($current_form_id)) {
            $page_id = $current_form_id;
        }
    }

    if (!$page_id || empty($page_id))
        $page_id = digbuilder_get_default_form(true, 'login_register');

    if ($page_id != null && is_numeric($page_id)) {
        return Digits_Pagebuilder_shortcodes::get_url($page_id, $current_url);
    }

    return $login_url;
}

function digbuilder_get_default_form($is_page, $form_type)
{
    $digits_pages = get_option('digits_default_pages', array());
    $type = ($is_page == true) ? 'page' : 'modal';
    if (!empty($digits_pages) && isset($digits_pages[$type])) {

        $page_id = $digits_pages[$type][$form_type];
        $page_id = preg_replace('/' . $type . '_/', '', $page_id, 1);
        if (!empty($page_id) && is_numeric($page_id)) {
            return $page_id;
        }
    }
    return null;
}

function digbuilder_get_default_page_link($url, $current_url, $form_type)
{

    if (empty($form_type)) {
        return $url;
    }

    $page_id = digbuilder_get_default_form(true, $form_type);

    if ($page_id != null && is_numeric($page_id)) {
        return Digits_Pagebuilder_shortcodes::get_url($page_id, $current_url);
    }

    return $url;
}

add_filter('digits_page_url', 'digbuilder_get_default_page_link', 10, 3);

function digbuilder_load_modal_and_check_digits_modal($load)
{
    $digits_pages = get_option('digits_default_pages', array());
    $type = 'modal';
    if (!empty($digits_pages) && isset($digits_pages[$type])) {
        $load = false;
        foreach ($digits_pages[$type] as $load_type => $digits_page) {
            if ($digits_page == 'default') {
                $load = true;
            } else {

                do_action('digbuilder_load_modal', $load_type, $digits_page);
            }
        }
    }

    return $load;
}

add_filter('load_digits_modal', 'digbuilder_load_modal_and_check_digits_modal');

add_action('digbuilder_load_modal', 'digbuilder_load_modal', 10, 2);

function digbuilder_load_modal($load_type, $modal_id)
{
    $type = 'modal';
    $modal_id = preg_replace('/' . $type . '_/', '', $modal_id, 1);
    if (!empty($modal_id) && is_numeric($modal_id)) {

        add_filter('digits_modal_class_' . $modal_id, 'digbuilder_default_popup_' . $load_type);
        Digits_Pagebuilder_shortcodes::add_popup($modal_id);


    }
}

function digbuilder_default_popup_login_register($class)
{
    $class[] = 'digits_modal_default_login_register';

    return $class;
}

function digbuilder_default_popup_login($class)
{
    $class[] = 'digits_modal_default_login';
    return $class;
}

function digbuilder_default_popup_register($class)
{
    $class[] = 'digits_modal_default_register';
    return $class;
}

function digbuilder_default_popup_forgot($class)
{
    $class[] = 'digits_modal_default_forgot';
    return $class;
}


$digits_builder_updates = Puc_v4_Factory::buildUpdateChecker(
    'https://bridge.unitedover.com/updates/changelog/addons.php?addon=digbuilder',
    __FILE__,
    'digbuilder'
);

$digits_builder_updates->addQueryArgFilter('digits_builder_updates_check');
function digits_builder_updates_check($queryArgs)
{


    $queryArgs['license_key'] = get_site_option('dig_purchasecode');
    $queryArgs['license_type'] = get_site_option('dig_license_type', 1);

    $queryArgs['request_site'] = network_home_url();

    $plugin_data = get_plugin_data(__FILE__);
    $plugin_version = $plugin_data['Version'];

    $queryArgs['version'] = $plugin_version;


    return $queryArgs;
}

