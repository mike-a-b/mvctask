<?php

//If a constant is defined for an option, make that take president
function exlog_get_option($option_name) {
    $constant_option_name = strtoupper($option_name);

    if (defined($constant_option_name)) {
        return constant($constant_option_name);
    } else {
        $option_data = get_option($option_name);
        $decoded_option_data = json_decode(base64_decode($option_data), true);
        if ($decoded_option_data) {
            return exlog_modify_repeater_data_for_view_use($decoded_option_data); // If data was JSON return PHP array (for repeater fields)
        }
        return $option_data;
    }
}

function exlog_is_wpconfig_option_set($option_name) {
    return defined(strtoupper($option_name));
}

function exlog_modify_repeater_data_for_view_use($repeater_data) {
    $formatted_data = array();
    if (is_array($repeater_data)) {
        foreach ($repeater_data as $repeater_item_data) {
            $formatted_repeater_items_data = array();
            foreach ($repeater_item_data as $repeater_item_datum) {
                if ($repeater_item_datum['repeater_field']) {
                    $value = exlog_modify_repeater_data_for_view_use($repeater_item_datum['value']);
                } else {
                    $value = $repeater_item_datum['value'];
                }
                $formatted_repeater_items_data[$repeater_item_datum['name']] = $value;
            }
            array_push($formatted_data, $formatted_repeater_items_data);
        }
        return $formatted_data;
    } else {
        return $repeater_data;
    }
}