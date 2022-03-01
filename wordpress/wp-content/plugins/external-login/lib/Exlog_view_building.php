<?php

class Exlog_view_building {
    public static function render_field_views($formSectionFields, $exlog_parent_repeater_slug = false) {
        foreach ($formSectionFields as $form_field) {
            if ($form_field["type"] == "text") :
                include EXLOG_PATH_PLUGIN_VIEWS . '/form_elements/text_field.php';
            elseif ($form_field["type"] == "select") :
                include EXLOG_PATH_PLUGIN_VIEWS . '/form_elements/select_field.php';
            elseif ($form_field["type"] == "checkbox") :
                include EXLOG_PATH_PLUGIN_VIEWS . '/form_elements/checkbox_field.php';
            elseif ($form_field["type"] == "roles") :
                include EXLOG_PATH_PLUGIN_VIEWS . '/form_elements/roles_fields_builder.php';
            elseif ($form_field["type"] == "button") :
                include EXLOG_PATH_PLUGIN_VIEWS . '/form_elements/button.php';
            elseif ($form_field["type"] == "repeater") :
                include EXLOG_PATH_PLUGIN_VIEWS . '/form_elements/repeater_field.php';
            endif;
        }
    }
}