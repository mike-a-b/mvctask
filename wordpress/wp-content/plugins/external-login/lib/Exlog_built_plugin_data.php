<?php

final class Exlog_built_plugin_data {

    private $plugin_data;
    private $option_fields_data;

    /**
     * Call this method to get singleton
     *
     * @return UserFactory
     */
    public static function Instance()
    {
        static $inst = null;
        if ($inst === null) {
            $inst = new Exlog_built_plugin_data();
        }
        return $inst;
    }

    /**
     * Private ctor so nobody else can instantiate it
     *
     */
    private function __construct() {
        $this->option_fields_data = $this->build_option_fields_data();
        $this->plugin_data = $this->build_plugin_data();
    }

    function get_option_fields() {
        return $this->option_fields_data;
    }

    function get_plugin_data() {
        return $this->plugin_data;
    }

    private function build_plugin_data() {
        return get_file_data(EXLOG_PLUGIN_FILE_PATH, [
            'name' => 'Plugin Name',
            'slug' => 'Text Domain'
        ], 'plugin');
    }

    function build_option_fields_data() {
        return array(
            array(
                "section_name" => "Functionality Settings",
                "section_slug" => "feature_settings",
                "section_description" => "These settings are for functionality of the plugin.",
                "section_fields" => array(
                    array(
                        "field_name" => "Test External DB Connection",
                        "field_description" => "Click this button to see an example result of users from your external database to ensure data is being pulled correctly.",
                        "field_slug" => "external_login_option_test_button",
                        "field_text" => "Test Connection",
                        "type" => "button",
                        "input_class" => "exlog_test_connection"
                    ),
                    array(
                        "field_name" => "Enable External Login",
                        "field_description" => "Tick this box if you want to enable the main functionality of logging in with the external DB.",
                        "field_slug" => "external_login_option_enable_external_login",
                        "type" => "checkbox",
                    ),
                    array(
                        "field_name" => "Migration Mode",
                        "field_description" => "Tick this box if you want the plugin to prioritise the local WordPress database for authentication over the external database. If the user is found in the local database, authentication will not be attempted on the external database.",
                        "field_slug" => "external_login_option_migration_mode",
                        "type" => "checkbox",
                    ),
                    array(
                        "field_name" => "Redirect After Authentication",
                        "field_description" => "The place to redirect the user to after they have logged in. This can be local (on the same site) or external (to another site)",
                        "field_slug" => "external_login_option_redirection_type",
                        "type" => "select",
                        "select_options" => array(
                            "none" => "Default",
                            "local" => "Local",
                            "external" => "External",
                        ),
                        "required" => true
                    ),
                    array(
                        "field_name" => "Redirect Location (Internal)",
                        "field_description" => "This is the location to navigate to within the site.<br>e.g. For home page \"<b>/</b>\"",
                        "field_slug" => "external_login_option_redirection_location_internal",
                        "type" => "text",
                        "required" => false,
                        "conditionals" => array(
                            "and",
                            array(
                                "condition_field" => "external_login_option_redirection_type",
                                "condition_field_value" => "local",
                                "condition_operator" => "="
                            ),
                        )
                    ),
                    array(
                        "field_name" => "Redirect Location (External)",
                        "field_description" => "This is the location to navigate to outside of the site.<br>e.g. \"<b>https://wordpress.org/plugins/external-login/#reviews</b>\"",
                        "field_slug" => "external_login_option_redirection_location_external",
                        "type" => "text",
                        "required" => false,
                        "conditionals" => array(
                            "and",
                            array(
                                "condition_field" => "external_login_option_redirection_type",
                                "condition_field_value" => "external",
                                "condition_operator" => "="
                            ),
                        )
                    ),
                    array(
                        "field_name" => "Disable Local Login",
                        "field_description" => "Tick this box if you want to disable the login attempt with the Wordpress Database if the external login fails. This will only take effect if External Login is enabled.",
                        "field_slug" => "external_login_option_disable_local_login",
                        "type" => "checkbox",
                        "conditionals" => array(
                            "and",
                            array(
                                "condition_field" => "external_login_option_enable_external_login",
                                "condition_field_value" => "true",
                                "condition_operator" => "="
                            ),
                            array(
                                "condition_field" => "external_login_option_migration_mode",
                                "condition_field_value" => "false",
                                "condition_operator" => "="
                            )
                        )
                    ),
                    array(
                        "field_name" => "Delete Settings on Plugin Deactivation",
                        "field_description" => "Tick this box if you want to delete all settings when you deactivate the plugin.",
                        "field_slug" => "external_login_option_delete_plugin_settings",
                        "type" => "checkbox",
                    ),
                ),
            ),
            array(
                "section_name" => "Database Connection",
                "section_slug" => "database_connection",
                "section_description" => "These settings are for connecting to the database.",
                "section_fields" => array(
                    array(
                        "field_name" => "Database Name",
                        "field_description" => "This is the name of the database to connect to.",
                        "field_slug" => "external_login_option_db_name",
                        "type" => "text",
                        "required" => true

                    ),
                    array(
                        "field_name" => "Database Host",
                        "field_description" => "This is the database host. This may well be 'localhost'.",
                        "field_slug" => "external_login_option_db_host",
                        "type" => "text",
                        "required" => true

                    ),
                    array(
                        "field_name" => "Database Port",
                        "field_description" => "This is the database port.",
                        "field_slug" => "external_login_option_db_port",
                        "type" => "text",
                        "required" => false
                    ),
                    array(
                        "field_name" => "Database Username",
                        "field_description" => "The username for the account to access the database.",
                        "field_slug" => "external_login_option_db_username",
                        "type" => "text",
                        "required" => true
                    ),
                    array(
                        "field_name" => "Database Password",
                        "field_description" => "The password for the account to access the database.",
                        "field_slug" => "external_login_option_db_password",
                        "type" => "text",
                        "required" => false
                    ),
                    array(
                        "field_name" => "Database Type",
                        "field_description" => "The password for the account to access the database.",
                        "field_slug" => "external_login_option_db_type",
                        "type" => "select",
                        "select_options" => array(
                            "mysql" => "MySQL",
                            "postgresql" => "PostgreSQL",
							"mssql" => "MSSQLServer",
                        ),
                        "required" => true
                    ),
                ),
            ),
            array(
                "section_name" => "Password Hashing",
                "section_slug" => "password_hashing",
                "section_description" => "These settings specify how the password has been hashed in the external database.",
                "section_fields" => array(
                    array(
                        "field_name" => "Hash Type",
                        "field_description" => "
                    This is the hashing algorithm used.
                    Hashing should ALWAYS be used in production.
                    For help on knowing which algorithm was used when creating your external database password hashes check out the plugin <a target='_blank' href='https://wordpress.org/plugins/external-login/#what%20hashes%20are%20available%20and%20which%20does%20my%20external%20database%20use%3F'>FAQ</a> section.
                 ",
                        "field_slug" => "external_login_option_hash_algorithm",
                        "type" => "select",
                        "select_options" => array(
                            "bcrypt" => "bcrypt",
                            "phpass" => "phpass",
                            "phpcrypt" => "phpcrypt",
                            "md2" => "md2",
                            "md4" => "md4",
                            "md5" => "md5",
                            "sha1" => "sha1",
                            "sha256" => "sha256",
                            "sha384" => "sha384",
                            "sha512" => "sha512",
                            "ripemd128" => "ripemd128",
                            "ripemd160" => "ripemd160",
                            "ripemd256" => "ripemd256",
                            "ripemd320" => "ripemd320",
                            "whirlpool" => "whirlpool",
                            "tiger128,3" => "tiger128,3",
                            "tiger160,3" => "tiger160,3",
                            "tiger192,3" => "tiger192,3",
                            "tiger128,4" => "tiger128,4",
                            "tiger160,4" => "tiger160,4",
                            "tiger192,4" => "tiger192,4",
                            "snefru" => "snefru",
                            "gost" => "gost",
                            "adler32" => "adler32",
                            "crc32" => "crc32",
                            "crc32b" => "crc32b",
                            "haval128,3" => "haval128,3",
                            "haval160,3" => "haval160,3",
                            "haval192,3" => "haval192,3",
                            "haval224,3" => "haval224,3",
                            "haval256,3" => "haval256,3",
                            "haval128,4" => "haval128,4",
                            "haval160,4" => "haval160,4",
                            "haval192,4" => "haval192,4",
                            "haval224,4" => "haval224,4",
                            "haval256,4" => "haval256,4",
                            "haval128,5" => "haval128,5",
                            "haval160,5" => "haval160,5",
                            "haval192,5" => "haval192,5",
                            "haval224,5" => "haval224,5",
                            "haval256,5" => "haval256,5",
                            "none" => "None"
                        )
                    ),
                    array(
                        "field_name" => "Salting Method",
                        "field_description" => "How salts are being used for added security.",
                        "field_slug" => "external_login_option_db_salting_method",
                        "type" => "select",
                        "select_options" => array(
                            "none" => "No Salting",
                            "one" => "One salt for all passwords",
                            "all" => "Separate salt for each password"
                        ),
                        "conditionals" => array(
                            "and",
                            array(
                                "condition_field" => "external_login_option_hash_algorithm",
                                "condition_field_value" => "bcrypt",
                                "condition_operator" => "!="
                            ),
                            array(
                                "condition_field" => "external_login_option_hash_algorithm",
                                "condition_field_value" => "none",
                                "condition_operator" => "!="
                            )
                        )
                    ),
                    array(
                        "field_name" => "Salt Location",
                        "field_description" => "Whether the salt is concatenated before or after the password. This is ignored if bcrypt is chosen as the salt will be stored within the hash.",
                        "field_slug" => "external_login_option_db_salt_location",
                        "type" => "select",
                        "select_options" => array(
                            "before" => "Salt Before",
                            "after" => "Salt After"
                        ),
                        "conditionals" => array(
                            "and",
                            array(
                                "condition_field" => "external_login_option_db_salting_method",
                                "condition_field_value" => "none",
                                "condition_operator" => "!="
                            ),
                            array(
                                "condition_field" => "external_login_option_hash_algorithm",
                                "condition_field_value" => "bcrypt",
                                "condition_operator" => "!="
                            ),
                            array(
                                "condition_field" => "external_login_option_hash_algorithm",
                                "condition_field_value" => "none",
                                "condition_operator" => "!="
                            )
                        )
                    ),
                    array(
                        "field_name" => "Password Salt",
                        "field_description" => "The salt used when hashing the password. If no salt is specified above this will have no effect. This is ignored if bcrypt is chosen as the salt will be stored within the hash.",
                        "field_slug" => "external_login_option_db_salt",
                        "type" => "text",
                        "conditionals" => array(
                            "and",
                            array(
                                "condition_field" => "external_login_option_db_salting_method",
                                "condition_field_value" => "none",
                                "condition_operator" => "!="
                            ),
                            array(
                                "condition_field" => "external_login_option_db_salting_method",
                                "condition_field_value" => "all",
                                "condition_operator" => "!="
                            ),
                            array(
                                "condition_field" => "external_login_option_hash_algorithm",
                                "condition_field_value" => "bcrypt",
                                "condition_operator" => "!="
                            ),
                            array(
                                "condition_field" => "external_login_option_hash_algorithm",
                                "condition_field_value" => "none",
                                "condition_operator" => "!="
                            )
                        )
                    ),
                ),
            ),
            array(
                "section_name" => "Database Table Mapping",
                "section_slug" => "database_table_mapping",
                "section_description" => "These settings are for mapping the data in your users table with that that will be stored in this WordPress database.",
                "section_fields" => array(
                    array(
                        "field_name" => "Table Name",
                        "field_description" => "The name of the table that stores your users.",
                        "field_slug" => "exlog_dbstructure_table",
                        "type" => "text",
                        "required" => true
                    ),
                    array(
                        "field_name" => "Username Field Name",
                        "field_description" => "This is the name of the field that stores your users' username or other unique ID. It MUST be unique.",
                        "field_slug" => "exlog_dbstructure_username",
                        "type" => "text",
                        "required" => true
                    ),
                    array(
                        "field_name" => "Password Field Name",
                        "field_description" => "This is the name of the field that stores your users' hashed passwords.",
                        "field_slug" => "exlog_dbstructure_password",
                        "type" => "text",
                        "required" => true
                    ),
                    array(
                        "field_name" => "Salt Field Name",
                        "field_description" => "This is the name of the field the salt for the password hashing of that users password.",
                        "field_slug" => "exlog_dbstructure_salt",
                        "type" => "text",
                        "conditionals" => array(
                            "and",
                            array(
                                "condition_field" => "external_login_option_db_salting_method",
                                "condition_field_value" => "all",
                                "condition_operator" => "="
                            )
                        )
                    ),
                    array(
                        "field_name" => "E-mail Field Name",
                        "field_description" => "This is the name of the field that stores your users' e-mail.",
                        "field_slug" => "exlog_dbstructure_email",
                        "type" => "text",
                    ),
                    array(
                        "field_name" => "First Name Field Name",
                        "field_description" => "This is the name of the field that stores your users' first name.",
                        "field_slug" => "exlog_dbstructure_first_name",
                        "type" => "text",
                    ),
                    array(
                        "field_name" => "Last Name Field Name",
                        "field_description" => "This is the name of the field that stores your users' last name.",
                        "field_slug" => "exlog_dbstructure_last_name",
                        "type" => "text",
                    ),
                    array(
                        "field_name" => "Role Field Name",
                        "field_description" => "This is the name of the field that stores your users' role. This could be admin, subscriber, etc.",
                        "field_slug" => "exlog_dbstructure_role",
                        "type" => "text",
                    ),
                    array(
                        "field_name" => "Multiple Roles Per User",
                        "field_description" => "Tick this box if your role field has more than one role stored within it.",
                        "field_slug" => "exlog_multiple_roles_toggle",
                        "type" => "checkbox",
                    ),
                    array(
                        "field_name" => "Delimiter",
                        "field_description" => "This is the character that breaks up your roles in the field in the database.<br>Here is an example of what your field might look like:<br><i>&nbsp&nbsp&nbsp&nbspadmin, developer, editor</i><br>If this was your data you would simply type a comma below.<br>White space will be stripped after the roles are split.<br>A space is a valid delimiting value but spaces should be written as this string (including the brackets):<br><i>&nbsp&nbsp&nbsp&nbsp{{space}}</i><br><br><b>Warning!</b><br>This functionality does store multiple roles at login but they are not visible in the admin area. Also saving in the admin area may wipe this data out. It is suggested that you should install and activate the <a href=\"https://en-gb.wordpress.org/plugins/multiple-roles/\">Multiple Roles</a> plugin to solve this issue.",
                        "field_slug" => "exlog_multiple_roles_delimiter",
                        "type" => "text",
                        "conditionals" => array(
                            "and",
                            array(
                                "condition_field" => "exlog_multiple_roles_toggle",
                                "condition_field_value" => "true",
                                "condition_operator" => "="
                            )
                        ),
                    ),
                )
            ),
            array(
                "section_name" => "Role Settings",
                "section_slug" => "role_settings",
                "section_description" => "These settings map roles from the external database to the WordPress one.<br><br><b>Important:</b><br>You can block users by their role here. However, if your are using the 'Multiple Roles Per User' feature, the user will only be blocked if all their roles are listed as blocked.<br>This means that you can block users with low level roles but have them allowed into WP if they have a higher level role.<br>For more specific blocking use the 'Exclude Users' feature.",
                "section_fields" => array(
                    array(
                        "field_name" => "Unspecified Role",
                        "field_description" => "This is default role that will be assigned to users who don't match a following role mapping.",
                        "field_slug" => "exlog_unspecified_role",
                        "type" => "select",
                        "select_options" => exlog_get_wp_role_types(),
                    ),
                    array(
                        "field_name" => "Role Mappings",
                        "type" => "roles",
                        "field_description" => "These are mappings from role types in your external Database to role types in Wordpress.",
                        "field_slug" => "exlog_roles_custom_fields",
                    ),
                ),
            ),
            array(
                "section_name" => "Exclude Users (BETA)",
                "section_slug" => "exclude_users",
                "section_description" => "This will allow you to block users based on the value of additional fields in the database.",
                "section_fields" => array(
                    array(
                        "field_name" => "Enable Excluding Users",
                        "field_description" => "Tick this box if you want to enable the excluding users functionality.",
                        "field_slug" => "external_login_option_enable_exclude_users",
                        "type" => "checkbox",
                    ),
                    array(
                        "field_name" => "Field Names",
                        "field_description" => "",
                        "field_slug" => "exlog_exclude_users_field_name_repeater",
                        "type" => "repeater",
                        "conditionals" => array(
                            "and",
                            array(
                                "condition_field" => "external_login_option_enable_exclude_users",
                                "condition_field_value" => "true",
                                "condition_operator" => "="
                            )
                        ),
                        "repeater_fields" => array(
                            array(
                                "field_name" => "Field Name",
                                "field_description" => "The field name in the database that stores the data that could block users.",
                                "field_slug" => "exlog_exclude_users_field_name",
                                "type" => "text"
                            ),
                            array(
                                "field_name" => "Field Values",
                                "field_description" => "A value in the field above that would prevent a user from logging in.",
                                "field_slug" => "exlog_exclude_users_field_value_repeater",
                                "type" => "repeater",
                                "repeater_fields" => array(
                                    array(
                                        "field_name" => "",
                                        "field_description" => "",
                                        "field_slug" => "exlog_exclude_users_field_value",
                                        "type" => "text"
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        );
    }
}
