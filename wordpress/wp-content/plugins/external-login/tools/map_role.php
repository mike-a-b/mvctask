<?php

function exlog_map_role($db_role, $username, $userData) {
    $delimiter = exlog_get_option("exlog_multiple_roles_delimiter");
    $delimiter = str_replace("{{space}}", " ", $delimiter);
    if (exlog_get_option("exlog_multiple_roles_toggle") == "on" && $delimiter != "") {
        $roles = array_map('trim', explode($delimiter, $db_role));
    } else {
        $roles = array($db_role);
    }

    $role_map = exlog_get_role_mappings();

    $wp_roles = array();
    if (is_array($role_map)) {
        foreach ($roles as $role) {
            foreach ($role_map as $map) {
                if ($map["external_role_value"] == $role) {
                    array_push($wp_roles, $map["wordpress_role_value"]);
                }
            }
        }
    }

    if (has_filter(EXLOG_HOOK_FILTER_ASSIGN_ROLES)) {
        $wp_roles = apply_filters(
            EXLOG_HOOK_FILTER_ASSIGN_ROLES,
            $wp_roles,
            $username,
            $userData
        );

        if (is_string($wp_roles)) {
            $wp_roles = Array($wp_roles);
        }
    }

    // If we've managed to find some roles that can be mapped, return them
    if (count($wp_roles) > 0) {
        return $wp_roles;
    }

    $unspecified_role = exlog_get_option("exlog_unspecified_role");
    if ($unspecified_role != "") {
        return array($unspecified_role);
    } else {
        error_log("EXLOG: Error: User role could not be mapped and no fall back role has been given. The role 'subscriber' has been given");
        return array("subscriber");
    }
}
