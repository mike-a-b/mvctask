<?php

function exlogCustomShouldExcludeUser($userData) {
    if (has_filter(EXLOG_HOOK_FILTER_CUSTOM_EXCLUDE)) {
        return apply_filters(
            EXLOG_HOOK_FILTER_CUSTOM_EXCLUDE,
            $userData
        );
    }
    return false;
}

function exlogShouldExcludeUserBasedOnSettingsPageExcludeUsersSettings($user) {
    $exclude_users_data = exlog_get_option('exlog_exclude_users_field_name_repeater');
    if (gettype($exclude_users_data) == 'array') {
        foreach ($exclude_users_data as $field) {
            $field_name = $field['exlog_exclude_users_field_name'];
            $field_values = $field['exlog_exclude_users_field_value_repeater'];
            foreach ($field_values as $value_object) {
                $value = $value_object['exlog_exclude_users_field_value'];
                if (isset($user[$field_name]) && $user[$field_name] === $value) return true;
            }
        }
    }
    return false;
}
