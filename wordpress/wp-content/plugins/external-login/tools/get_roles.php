<?php
function exlog_get_wp_role_types() {
    global $wp_roles;

    if ( ! isset( $wp_roles ) )
        $wp_roles = new WP_Roles();

    return array_reverse($wp_roles->get_names());
}

function exlog_get_role_mappings() {
    return exlog_decode_json_data(get_option('exlog_roles_custom_fields'));
}

function exlog_decode_json_data($raw_data) {
    return json_decode(urldecode($raw_data), true);
}

function exlog_encode_json_data($raw_data) {
    return urlencode(json_encode($raw_data));
}
