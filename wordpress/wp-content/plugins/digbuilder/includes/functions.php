<?php

if (!defined('ABSPATH')) {
    exit;
}

add_filter('digits_login_fields', 'digbuilder_login_default_fields', 10, 2);
function digbuilder_login_default_fields($fields, $values)
{
    if (isset($_POST['digbuilder_form']) || (isset($values['is_elem']) && !empty($values['is_elem']))) {
        if (isset($values['post_id'])) {
            $post_id = $values['post_id'];
            $form_id = $values['form_id'];
        } else {
            $post_id = sanitize_text_field($_POST['post_id']);
            $form_id = sanitize_text_field($_POST['form_id']);
        }
        if (empty($post_id) || empty($form_id)) {
            return $fields;
        }
        $login_fields = digbuilder_default_login_fields();

        if (isset($values['settings'])) {
            $element_settings = $values['settings'];
        } else {
            $element = digbuilder_get_form_data($post_id, $form_id);
            if (!empty($element) && $element && isset($element['settings'])) {
                $element_settings = $element['settings'];
            }
        }
        if (!empty($element_settings) && $element_settings) {

            foreach ($login_fields as $field_key => $field_value) {
                if (array_key_exists($field_key, $element_settings)) {
                    $element_settings_field = $element_settings[$field_key];
                    if (!$element_settings_field) {
                        $login_fields[$field_key] = 0;
                    } else if ($element_settings_field) {
                        $login_fields[$field_key] = 1;
                    } else {
                        $login_fields[$field_key] = $element_settings_field;
                    }

                }
            }
        }

        return $login_fields;
    }

    return $fields;
}


add_filter('digits_registration_fields', 'digbuilder_registration_fields', 10);
function digbuilder_registration_fields($fields)
{
    return digbuilder_get_registration_fields($fields, 3);
}

add_filter('digits_registration_default_fields', 'digbuilder_registration_default_fields', 10);
function digbuilder_registration_default_fields($fields)
{
    return digbuilder_get_registration_fields($fields, 2);
}


function digbuilder_get_registration_fields($fields, $type)
{
    if (isset($_POST['digbuilder_form'])) {
        $post_id = sanitize_text_field($_POST['post_id']);
        $form_id = sanitize_text_field($_POST['form_id']);

        if (empty($post_id) || empty($form_id)) {
            return $fields;
        }
        $element = digbuilder_get_form_data($post_id, $form_id);
        if (!empty($element) && $element && isset($element['settings'])) {
            $element_settings = $element['settings'];
            if (!empty($element_settings)) {
                $data = digpage_get_registration_fields_data($element_settings['fields'], $type);
                return !empty($data) ? $data : $fields;
            }
        }
    }

    return $fields;
}


function digbuilder_default_login_fields()
{
    return array(
        'dig_login_email' => 1,
        'dig_login_username' => 1,
        'dig_login_mobilenumber' => 1,
        'dig_login_otp' => 1,
        'dig_login_password' => 1,
        'dig_login_captcha' => 0,
        'dig_login_rememberme' => 1,
    );
}


add_filter('digits_login_redirect', 'digbuilder_login_redirect');
function digbuilder_login_redirect($redirect_url)
{
    return digbuilder_form_get_value('login_redirect', $redirect_url);
}

add_filter('digits_forgot_redirect', 'digbuilder_forgotpass_redirect');
function digbuilder_forgotpass_redirect($redirect_url)
{
    return digbuilder_form_get_value('forgot_redirect', $redirect_url);
}

add_filter('digits_register_redirect', 'digbuilder_register_redirect');
function digbuilder_register_redirect($redirect_url)
{
    return digbuilder_form_get_value('register_redirect', $redirect_url);
}


function digbuilder_get_form_data($post_id, $form_id)
{
    if (!did_action('elementor/loaded')) {
        return null;
    }
    try {
        $document = Elementor\Plugin::$instance->documents->get($post_id);
        Elementor\Plugin::$instance->documents->switch_to_document($document);
        $data = $document->get_elements_data();
        return !empty($data) ? digbuilder_find_element_recursive($data, $form_id) : null;
    } catch (Exception $e) {
        return null;
    }
}


function digbuilder_show_hidden_logged_in_fields($hide, $type, $meta_key)
{

    if (is_user_logged_in()) {
        if (did_action('elementor/loaded')) {
            if (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode()) {
                return false;
            }
        }
    }

    return $hide;
}

add_filter('dig_show_field_to_loggedin_user', 'digbuilder_show_hidden_logged_in_fields', 100, 3);


function digbuilder_form_get_value($key, $value)
{
    if (!isset($_POST['digbuilder_form'])) {
        return $value;
    } else {
        $post_id = sanitize_text_field($_POST['post_id']);
        $form_id = sanitize_text_field($_POST['form_id']);
        $element = digbuilder_get_form_data($post_id, $form_id);
        if (!empty($element) && $element) {
            $element_settings = $element['settings'];
            if (isset($element_settings[$key])) {
                $elem_value = $element_settings[$key];
                if (!empty($elem_value)) {
                    return $elem_value;
                }

            }
            return $value;
        }

        return $value;
    }
}

add_filter('digits_register_user_role', 'digbuilder_register_user_role');
function digbuilder_register_user_role($user_role)
{
    return digbuilder_form_get_value('custom_user_role_value', $user_role);
}

/*
 * 2- custom fields
 * 3- Core
 */
function digpage_get_registration_fields_data($elem_reg_fields, $type)
{
    $fields = digbuilder_get_formatted_fields($elem_reg_fields, $type);

    if ($type != 2) {
        return $fields;
    }
    if (!empty($fields)) {
        $mobileaccp = 0;
        $passaccep = 0;
        $emailaccep = 0;
        $usernameaccep = 0;
        $nameaccep = 0;
        foreach ($fields as $field) {
            $type = strtolower($field['type']);
            if ($type == 'first_name') {
                $nameaccep = 1;
            } else if ($type == 'username') {
                $usernameaccep = $field['required'];
            } else if ($type == 'password') {

                $passaccep = $field['required'] == 1 ? 2 : 1;

            } else if (in_array($type, array('mobmail', 'mobile_number', 'email'))) {

                if (in_array($type, array('mobmail', 'mobile_number'))) {
                    $mobileaccp = $field['required'] == 1 ? 2 : 1;
                }

                if (in_array($type, array('email', 'mobmail'))) {
                    $emailaccep = $field['required'] == 1 ? 2 : 1;
                }

                if ($emailaccep > 0 && $mobileaccp > 0 && $type != 'mobile_number') {
                    if ($type == 'email') {
                        if ($field['required'] == 1) {
                            $emailaccep = 2;
                        }
                    }

                }
            }
        }
        if ($usernameaccep == 0 && $emailaccep == 0 && $mobileaccp == 0) {
            $emailaccep = 2;
        }
        $dig_reg_details = digit_get_reg_fields();
        $dig_reg_details['dig_reg_name'] = $nameaccep;
        $dig_reg_details['dig_reg_uname'] = $usernameaccep;
        $dig_reg_details['dig_reg_email'] = $emailaccep;
        $dig_reg_details['dig_reg_mobilenumber'] = $mobileaccp;
        $dig_reg_details['dig_reg_password'] = $passaccep;

        return $dig_reg_details;
    }
}


function digbuilder_digits_field_categories()
{
    $fields = array();

    $fields['core_fields'] = [
        'username' => array('label' => 'Username', 'meta_key' => 'username', 'type' => 'text', 'pre' => '1'),
        'mobmail' => array('label' => 'Email/Mobile Number', 'meta_key' => 'mobmail', 'type' => 'text', 'pre' => '1'),
        'mobile_number' => array('label' => 'Mobile Number', 'meta_key' => 'mobile_number', 'type' => 'text', 'pre' => '1'),
        'email' => array('label' => 'Email', 'meta_key' => 'email', 'type' => 'text', 'pre' => '1'),
        'password' => array('label' => 'Password', 'meta_key' => 'password', 'type' => 'password', 'pre' => '1'),
        'first_name' => array('label' => 'Name', 'meta_key' => 'first_name', 'type' => 'text'),
    ];

    $fields['wp_predefined'] = array(
        'first_name' => array('meta_key' => 'first_name', 'type' => 'text'),
        'last_name' => array('meta_key' => 'last_name', 'type' => 'text'),
        'user_role' => array('meta_key' => 'user_role', 'type' => 'user_role'),
        'display_name' => array('meta_key' => 'display_name', 'type' => 'text')
    );

    $fields['wc_predefined'] = array(
        'first_name' => array('meta_key' => 'billing_first_name', 'type' => 'text'),
        'last_name' => array('meta_key' => 'billing_last_name', 'type' => 'text'),
        'company' => array('meta_key' => 'billing_company', 'type' => 'text'),
        'addr1' => array('meta_key' => 'billing_address_1', 'type' => 'text'),
        'addr2' => array('meta_key' => 'billing_address_2', 'type' => 'text'),
        'city' => array('meta_key' => 'billing_city', 'type' => 'text'),
        'state' => array('meta_key' => 'billing_state', 'type' => 'text'),
        'country' => array('meta_key' => 'billing_country', 'type' => 'text'),
        'zip' => array('meta_key' => 'billing_postcode', 'type' => 'text'),
    );
    return $fields;
}

/*
 * $data_type ->
 * 1 - all
 * 2 - core
 * 3 - all except core
 * */
function digbuilder_get_formatted_fields($fields, $data_type)
{
    $digit_fields = digbuilder_digits_field_categories();
    $core_fields = $digit_fields['core_fields'];
    $wp_predefined = $digit_fields['wp_predefined'];
    $wc_predefined = $digit_fields['wc_predefined'];

    $formatted_fields = array();

    $options_required = array('dropdown', 'checkbox', 'radio');
    foreach ($fields as $field) {

        if (in_array($field['sub_type'], $options_required) && empty($field['field_options'])) {
            continue;
        }
        $values = array();


        if ($data_type == 2 && $field['type'] == 'wp_predefined' && $field['field_wp_type'] == 'first_name') {
            $field['type'] = 'first_name';
        }

        $options = preg_split('/\R/', $field['field_options'], -1, PREG_SPLIT_NO_EMPTY);

        $values['label'] = $field['label'];
        $values['type'] = $field['type'];
        if (array_key_exists($field['type'], $core_fields)) {
            if ($data_type == 3) continue;

            $pre_values = $core_fields[$field['type']];
            $values['type'] = $field['type'];
            $values['meta_key'] = $pre_values['meta_key'];
            $values['label'] = $pre_values['label'];

        } else {
            if ($data_type == 2) continue;
            if ($field['type'] == 'wp_predefined' || $field['type'] == 'wc_predefined') {
                if ($field['type'] == 'wp_predefined') {
                    $pre_values = $wp_predefined[$field['field_wp_type']];
                } else {
                    $pre_values = $wc_predefined[$field['field_wc_type']];
                }

                $values['type'] = $pre_values['type'];
                $values['meta_key'] = $pre_values['meta_key'];


                if (isset($field['user_roles'])) {
                    $options = $field['user_roles'];
                }
            } else {
                if (!isset($field['meta_key']) || empty($field['meta_key'])) {
                    continue;
                }
                $values['type'] = $field['sub_type'];
                $values['meta_key'] = $field['meta_key'];

                if ($values['type'] == 'tac') {
                    $values['tac_link'] = $field['terms_link'];
                    $values['tac_privacy_link'] = $field['privacy_link'];
                }
            }
        }

        if (!empty($values)) {

            if (!empty($options)) {
                $values['options'] = $options;
            }

            $values['required'] = ($field['required'] ? '1' : '0');

            $formatted_fields[$values['meta_key']] = $values;
        }

    }

    $mobile_number = esc_html__('Email/Mobile Number', 'digits');
    if (array_key_exists($mobile_number, $formatted_fields)) {
        $remove_keys = array(
            'mobile_number' => esc_html__('Mobile Number', 'digits'),
            'email' => esc_html__('Email', 'digits')
        );
        foreach ($remove_keys as $key => $value) {
            if (isset($formatted_fields[$value])) {
                unset($formatted_fields[$value]);
            }
        }
    }

    return $formatted_fields;
}
