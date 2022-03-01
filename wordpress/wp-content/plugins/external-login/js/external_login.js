(function ($) {
    $(function () {
        var $role_section = $(".options_section.role_settings");
        var $roles = $(".roles", $role_section);
        var hidden_roles_input_selector = ".exlog_custom_roles";

        var external_role_name_prefix = $roles.attr("data-exlog-external-role-prefix");
        var wordpress_role_name_prefix = $roles.attr("data-exlog-wordpress-role-prefix");

        var json_key_external_role_value = $roles.attr("data-exlog-json-key-external-value");
        var json_key_external_role_name = $roles.attr("data-exlog-json-key-external-name");
        var json_key_wordpress_role_value = $roles.attr("data-exlog-json-key-wordpress-value");
        var json_key_wordpress_role_name = $roles.attr("data-exlog-json-key-wordpress-name");

        var $new_role_element = $($roles.attr("data-exlog-field-markup"));

        function update_roles_data() {
            var roles_data = [];
            $(".role", $role_section).each(function () {
                var $this = $(this);
                var role_data = {};

                role_data[json_key_external_role_value] = $(".external_role", $this).val();
                role_data[json_key_wordpress_role_value] = $(".wordpress_role", $this).val();
                role_data[json_key_wordpress_role_name] = $(".wordpress_role", $this).attr("name");
                role_data[json_key_external_role_name] = $(".external_role", $this).attr("name");

                roles_data.push(role_data);
            });

            var input_ready_roles = window.exlog.json_to_input(roles_data);
            $(hidden_roles_input_selector, $role_section).attr("value", input_ready_roles);
        }

        function watchRoleTextInputs() {
            $(".external_role", $role_section)
                .change(function () {
                    var value = $(this).attr("value");
                    var slugged_value = window.exlog.slugify($(this).attr("value"));
                    var slugged_external_role = external_role_name_prefix + slugged_value;
                    $(this).attr("name", slugged_external_role);
                    update_roles_data();
                });

            $(".wordpress_role", $role_section).change(function () {
                var external_role_value = $(this).parent().find(".external_role").attr("value");
                var slugged_value = window.exlog.slugify(external_role_value);
                var slugged_wordpress_role = wordpress_role_name_prefix + slugged_value;
                $(this).attr("name", slugged_wordpress_role);
                update_roles_data();
            });

            $(".remove_role_pairing", $role_section).click(function () {
                $(this).parent().remove();
                update_roles_data();
            });
        }

        $(".add_button", $role_section).click(function () {
            var $new_item = $new_role_element.clone().appendTo($roles);

            $(".external_role", $new_item)
                .attr("readonly", false)
                .attr("value", "");
            $(".description", $new_item).remove();
            $(".wordpress_role", $new_item)[0].selectedIndex = 0;
            $(".remove_role_pairing", $new_item).attr("type", "button");

            watchRoleTextInputs();
        });

        update_roles_data();
        watchRoleTextInputs();
    })
}(jQuery));
