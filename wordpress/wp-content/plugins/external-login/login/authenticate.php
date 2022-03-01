<?php
function exlog_auth( $user, $username, $password ){
    $migration_mode = exlog_get_option('external_login_option_migration_mode') == "on";

    // If not in migration mode or you are in migration mode and the username isn't in the WordPress DB
    if (!$migration_mode || ($migration_mode && !username_exists($username))) {

        // Make sure a username and password are present for us to work with
        if ($username == '' || $password == '') return;

        $response = exlog_auth_query($username, $password);

        $roles = exlog_map_role(
            isset($response['wp_user_data']['role']) ? $response['wp_user_data']['role'] : "",
            $username,
            $response
        );

        $block_access_due_to_role = true;
        foreach ($roles as $role) {
            if ($role != EXLOG_ROLE_BLOCK_VALUE) {
                $block_access_due_to_role = false;
            }
        }

        // If a user was found
        if ($response) {

            // If role is blocking user access
            if ($block_access_due_to_role) {
                $user = new WP_Error('denied', __("You are not allowed access"));

                // If user was NOT authenticated
            } else if (!$response["authenticated"]) {
                $error_message = isset($response['error_message']) ? $response['error_message'] : "Invalid username or password";
                // User does not exist, send back an error message
                $user = new WP_Error('denied', __($error_message));

                // If user was authenticated
            } else if ($response["authenticated"]) {
                // External user exists, try to load the user info from the WordPress user table
                $userobj = new WP_User();
                $user = $userobj->get_data_by('login', $response['wp_user_data']['username']); // Does not return a WP_User object 🙁
                $user = new WP_User($user ? $user->ID : NULL); // Attempt to load up the user with that ID

                $exlog_userdata = array(
                    'user_login' => $response['wp_user_data']['username'],
                    'first_name' => $response['wp_user_data']['first_name'],
                    'last_name'  => $response['wp_user_data']['last_name'],
                    'role'       => $roles[0],
                    'user_email' => $response['wp_user_data']['email'],
                );

                // Only update the WordPress user's password if it has changed
                // Without this all other sessions for the user gets cleared
                $check = wp_authenticate_username_password( NULL, $username , $password );
                if (is_wp_error( $check )) {
                    $exlog_userdata['user_pass'] = $password;
                }

                // If user does not exist
                if ($user->ID == 0) {
                    // Setup the minimum required user information

                    $new_user_id = wp_insert_user( $exlog_userdata ); // A new user has been created

                    // Load the new user info
                    $user = new WP_User ($new_user_id);
                } else {
                    $exlog_userdata['ID'] = $user->ID;

                    add_filter('send_password_change_email', '__return_false'); // Prevent password update e-mail

                    wp_update_user($exlog_userdata);
                }

                $user->set_role($roles[0]); // Wipe out old roles

                // Add roles to user if more than one
                foreach ($roles as $role) {
                    $user->add_role($role);
                }

                // Hook that passes user data on successful login
                do_action('exlog_hook_action_authenticated', $user, $exlog_userdata, $response['raw_response']);
            }
        }

        // Whether to disable login fallback with the local Wordpress version of the username and password
        // Prevents local login if:
        // - Disable local login  is set in the admin area
        // - OR
        // - The user was found but the password was rejected
        if (exlog_get_option('external_login_option_disable_local_login') == "on" || is_wp_error($user)) {
            remove_action('authenticate', 'wp_authenticate_username_password', 20);
            remove_action('authenticate', 'wp_authenticate_email_password', 20);
        }
    }

    return $user;
}

if (exlog_get_option("external_login_option_enable_external_login") == "on") {
    add_filter('authenticate', 'exlog_auth', 10, 3);
}
