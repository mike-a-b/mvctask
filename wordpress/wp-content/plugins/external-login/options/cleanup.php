<?php

register_deactivation_hook(EXLOG_PLUGIN_FILE_PATH, 'exlog_cleanup_plugin_data');

function exlog_cleanup_plugin_data() {
    if (exlog_get_option('external_login_option_delete_plugin_settings') == "on") {
        foreach (EXLOG_OPTION_FIELDS as $section) {
            foreach ($section['section_fields'] as $form_field) {
                delete_option($form_field['field_slug']);
            }
        }
    }
}