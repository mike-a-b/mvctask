=== External Login ===
Contributors: tbenyon
Donate link: https://www.paypal.me/tombenyon
Tags: external login, external, database, login, users, db, WordPress, different, username, password, hashing, md, md2, md4, md5, bcrypt, sha, sha1, sha256, sha384, sha512
Requires at least: 4.6
Tested up to: 5.6.1
Stable tag: 1.11.2
Requires PHP: 5.6.34
License: MIT

External Login allows users to log in to the WordPress site with a different database of users.


== Description ==

External Login allows you to login to your WordPress site using an 'external database' instead of the WordPress database. This means if you already have a login system you can integrate that into your WordPress site. The 'external database' that you would like to use does not have to be a WordPress database.

The plugin will re-create users in the WordPress database which has has two benefits:
    1. It will allow you to use WordPress correctly with other plugins that require a named user to be logged in.
    2. If the 'external database' is not available, you can allow the plugin to log them in with the local WordPress version of their user.

== Features ==

* Use your current table of users in a different database to login to WordPress
* Map the names for your database fields against the required WordPress fields
* Map roles from your 'external database' to those found in WordPress e.g. a student in the 'external database' becomes an editor in the WordPress database.
* Every time a user logs in, their WordPress user will be updated with the details from the 'external database'.
* Exclude certain users from accessing the WordPress admin area based on any field in the Users table on the External Database
* Ability to fall back to the generated WordPress user for login if the 'external database' is unavailable.
* Block users based on their role
* Block a user based on any field in your users table
* Redirect the user after being successfully authenticated
* Support for different database types:
    * MySQL
    * PostgreSQL
    * Microsoft SQL
* Many password hashing methods are supported including:
    * bcrypt
    * md2
    * md4
    * md5
    * sha1
    * sha256
    * sha384
    * sha512
    * no hashing
    * and more...
* Support for separate password salts for each password
* Support for one salt for all passwords
* Support for salts being prepended or appended to the password
* Test the connection in the settings menu to make sure your data is being pulled correctly
* Option to delete plugin data on plugin deactivation


== Installation ==

1. Install the plugin through the WordPress plugins screen directly, or upload the plugin files to the `/wp-content/plugins/external-login` directory.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Take a backup of your WordPress Database
1. Access the plugin settings by clicking the link on the plugins page or via wp-admin -> settings -> External Login (see first screenshot).
1. Begin to fill out the settings page.
1. BEFORE TICKING THE 'Enable External Login' BOX - Test the connection using the "Test" button.


== Frequently Asked Questions ==

= How does the plugin log someone in? =

To give an idea of whether this plugin does the job you need it to, here is the basic logic flow:
1. User logs in using the normal WordPress login screen.
2. We hash the users password with the method and salt (if given) that is chosen in the settings
3. We do a simple database query to the external database to see if their username and the hashed password match a user.
4. We create or update the details of the new user.
5. We log that user in
6. When the user logs out of Wordpress, the Wordpress session ends

Please note that this system is built for the login process to be a completely different login process to anything else.
If you are looking for Single Sign On (log in to one website and you're logged in else where) you should be looking for an OAuth solution in my opinion.

= How do I add a port to the database connection? =

For ports that differ the standard 3306, add them to the end of the host separated by a colon eg: 'localhost:3306'.

= What hashes are available and which does my external database use? =

Below is a list of the available hashing options. Within each there are examples of what the hashed string might look like.

* bcrypt
    * $2b$10$MaTFwF7Ov2JRTTPnV.I4X.q0KQ3VVAiwTzULlPnBYeSBkBztnXfJO
    * $2y$10$fEg5j9N5zrrqq9Bjm7yoB.Xprd9iZZfO3pgHZNl0FiLMnqMDlSQh.
    * $2a$06$qAOKdDYnSWcSp6UI1Hpkau/8sRfsvahYvRPq5vpDxMRMzPdQNGw8m
* phpass
    * $P$BEldufbwSc73mu/epnZsHmmnX7/.Ni0
* phpcrypt
    * This is for use with other algorithms that store the algorithm, salt and hash in the same field in the external databse. These are:
        * Standard DES:
            * rl.3StKT.4T8M
        * Extended DES:
            * _J9..rasmBYk8r9AiWNc
        * MD5:
            * $1$rasmusle$rISCgZzpwk3UhDidwXvin0
        * Blowfish:
            * $2a$07$usesomesillystringfore2uDLvp1Ii2e./U9C8sBjqp8I90dH6hi
        * SHA-256:
            * $5$rounds=5000$usesomesillystri$KqJWpanXZHKq2BOB43TSaYhEWsQ1Lr5QNyPCDH/Tp.6
        * SHA-512:
            * $6$rounds=5000$usesomesillystri$D4IrlXatmP7rx3P3InaxBeoomnAihCKRVQP22JZ6EY47Wc6BkroIuUUBOov1i.S5KPgErtP/EN5mcO.ChWQW21
* md2
    * f03881a88c6e39135f0ecc60efd609b9
* md4
    * 8a9d093f14f8701df17732b2bb182c74
* md5
    * 5f4dcc3b5aa765d61d8327deb882cf99
* sha1
    * 5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8
* sha256
    * 5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8
* sha384
    * 9c1565e99afa2ce7800e96a73c125363c06697c5674d59f227b3368fd00b85ead506eefa90702673d873cb2c9357eafc
* sha512
    * b109f3bbbc244eb82441917ed06d618b9008dd09b3befd1b5e07394c706a8bb980b1d7785e5976ec049b46df5f1326af5a2ea6d103fd07c95385ffab0cacbc86
* ripemd128
    * c9c6d316d6dc4d952a789fd4b8858ed7
* ripemd160
    * 2c08e8f5884750a7b99f6f2f342fc638db25ff31
* ripemd256
    * f94cf96c79103c3ccad10d308c02a1db73b986e2c48962e96ecd305e0b80ef1b
* ripemd320
    * c571d82e535de67ff5f87e417b3d53125f2d83ed7598b89d74483e6c0dfe8d86e88b380249fc8fb4
* whirlpool
    * 74dfc2b27acfa364da55f93a5caee29ccad3557247eda238831b3e9bd931b01d77fe994e4f12b9d4cfa92a124461d2065197d8cf7f33fc88566da2db2a4d6eae
* tiger128,3
    * d476a6b8b5c35ce912781497d02d09fa
* tiger160,3
    * d476a6b8b5c35ce912781497d02d09faeb8aa05a
* tiger192,3
    * d476a6b8b5c35ce912781497d02d09faeb8aa05a489223f5
* tiger128,4
    * b1e057f1b2e82506f13d4d43fd17d8b8
* tiger160,4
    * b1e057f1b2e82506f13d4d43fd17d8b843e173a8
* tiger192,4
    * b1e057f1b2e82506f13d4d43fd17d8b843e173a8a1ea3f7c
* snefru
    * 8ec80c31fab12b5f7930e6c9288c3076852aeef8f560a9ed91fb2e33838e6871
* gost
    * db4d9992897eda89b50f1d3208db607902da7e79c6f3bc6e6933cc5919068564
* adler32
    * 0f910374
* crc32
    * 35c246d5
* crc32b
    * 4fa7edbb
* haval128,3
    * 2221b19499669a2da53c49caf3c5e5be
* haval160,3
    * 9e997134ef585a1b143574ddc38cb8617c597230
* haval192,3
    * d8d7e2b0c045418daf3e696f1c819f3da8b751fb539780a0
* haval224,3
    * 46f2dff67ec34847e71128386569438e9186f9b0f993f77c06f4794e
* haval256,3
    * 502ef024050f4b58d9cdf57b9d1e847ca53b7cdbbbe31071bb2f2176824ca4aa
* haval128,4
    * a2ac4348ff7caf14d2a16a9bb207315a
* haval160,4
    * 757921aaa14a05363dd9bea6a5cafa622333d191
* haval192,4
    * 7670901e6a800b1787e08555e62ae0e68310b0d66e3ad4d4
* haval224,4
    * 5857f727b00252f6bd7abf6569d658bdab66003407b6db8989805a03
* haval256,4
    * 68e61bcce9204cf87e8c7563bd32074124205299d43205d1a086d3566adda324
* haval128,5
    * 9bec7b503c2680c94cefcadee5c72c93
* haval160,5
    * 756700c90d00421529549def07a3512b258c42f4
* haval192,5
    * e7218675e85e01c85cc196ab4aabc99cc218749b7944a788
* haval224,5
    * b21cbe5ac421cce98ee10a5d2f65607d59095c3777de339c1e978efd
* haval256,5
    * a7dac1b901376073284fbe145b37ffe6bcf6fc1ae94728186939ce91bcf73e51
* none

= My plugin uses a different or custom hashing algorithm, can the plugin handle this? =

Yes. It will require you to use a built in hook (exlog_hook_filter_authenticate_hash) that you can add to your functions.php file.

Documentation on how to use this and other hooks can be found in the [FAQ section](https://wordpress.org/plugins/external-login/#what%20hooks%20are%20available%20in%20the%20external%20login%20flow%3F).


= What values can I set in wp-config.php? =
Here is a full listing of possible fields and values.

* Enable External Login
    * Constant Name
        * EXTERNAL_LOGIN_OPTION_ENABLE_EXTERNAL_LOGIN
    * Possible Values
        * on
        * off

* Disable Local Login
    * Constant Name
        * EXTERNAL_LOGIN_OPTION_DISABLE_LOCAL_LOGIN
    * Possible Values
        * on
        * off

* Migration Mode
    * Constant Name
        * EXTERNAL_LOGIN_OPTION_MIGRATION_MODE
    * Possible Values
        * on
        * off

* Delete Settings on Plugin Deactivation
    * Constant Name
        * EXTERNAL_LOGIN_OPTION_DELETE_PLUGIN_SETTINGS
    * Possible Values
        * on
        * off

* Database Name
    * Constant Name
        * EXTERNAL_LOGIN_OPTION_DB_NAME
    * Possible Values
        * Any String

* Database Host
    * Constant Name
        * EXTERNAL_LOGIN_OPTION_DB_HOST
    * Possible Values
        * Any String

* Database Port
    * Constant Name
        * EXTERNAL_LOGIN_OPTION_DB_PORT
    * Possible Values
        * Any String

* Database Username
    * Constant Name
        * EXTERNAL_LOGIN_OPTION_DB_USERNAME
    * Possible Values
        * Any String

* Database Password
    * Constant Name
        * EXTERNAL_LOGIN_OPTION_DB_PASSWORD
    * Possible Values
        * Any String

* Database Type
    * Constant Name
        * EXTERNAL_LOGIN_OPTION_DB_TYPE
    * Possible Values
        * mysql
        * postgresql
        * mssql

* Database Hash Type
    * Constant Name
        * EXTERNAL_LOGIN_OPTION_HASH_ALGORITHM
    * Possible Values
        * bcrypt
        * phpass
        * phpcrypt
        * md2
        * md4
        * md5
        * sha1
        * sha256
        * sha384
        * sha512
        * ripemd128
        * ripemd160
        * ripemd256
        * ripemd320
        * whirlpool
        * tiger128,3
        * tiger160,3
        * tiger192,3
        * tiger128,4
        * tiger160,4
        * tiger192,4
        * snefru
        * gost
        * adler32
        * crc32
        * crc32b
        * haval128,3
        * haval160,3
        * haval192,3
        * haval224,3
        * haval256,3
        * haval128,4
        * haval160,4
        * haval192,4
        * haval224,4
        * haval256,4
        * haval128,5
        * haval160,5
        * haval192,5
        * haval224,5
        * haval256,5
        * none

* Salting Method
    * Constant Name
        * EXTERNAL_LOGIN_OPTION_DB_SALTING_METHOD
    * Possible Values
        * none
        * one
        * all

* Salt Location
    * Constant Name
        * EXTERNAL_LOGIN_OPTION_DB_SALT_LOCATION
    * Possible Values
        * before
        * after

* Password Salt
    * Constant Name
        * EXTERNAL_LOGIN_OPTION_DB_SALT
    * Possible Values
        * Any String

* Table Name
    * Constant Name
        * EXLOG_DBSTRUCTURE_TABLE
    * Possible Values
        * Any String

* Username Field Name
    * Constant Name
        * EXLOG_DBSTRUCTURE_USERNAME
    * Possible Values
        * Any String

* Password Field Name
    * Constant Name
        * EXLOG_DBSTRUCTURE_PASSWORD
    * Possible Values
        * Any String

* Salt Field Name
    * Constant Name
        * EXLOG_DBSTRUCTURE_SALT
    * Possible Values
        * Any String

* E-mail Field Name
    * Constant Name
        * EXLOG_DBSTRUCTURE_EMAIL
    * Possible Values
        * Any String

* First Name Field Name
    * Constant Name
        * EXLOG_DBSTRUCTURE_FIRST_NAME
    * Possible Values
        * Any String

* Last Name Field Name
    * Constant Name
        * EXLOG_DBSTRUCTURE_LAST_NAME
    * Possible Values
        * Any String

* Role Field Name
    * Constant Name
        * EXLOG_DBSTRUCTURE_ROLE
    * Possible Values
        * Any String

= What hooks are available in the External Login flow? =

- exlog_hook_filter_authenticate_hash

You can use this hook to check if the password is correct in a custom way. For example, if you use a hashing algorithm not supported by the plugin by default.

This hook provides you with a range of different information:
- `$password` - the password that was typed in at the login screen
- `$hashFromDatabase` - the hash stored in the database
- `$username` - the username that was typed in in the login screen
- `$externalUserData` - the rest of the data retrieved from the external database for the user that was found

Returning `true` will authenticate the user and returning `false` will treat them as unauthorised.

The below example shows how you could use the filter:

`
function myExlogHashAuthenticator($password, $hashFromDatabase, $username, $externalUserData) {
    return password_verify($password, $hashFromDatabase);
}
add_filter('exlog_hook_filter_authenticate_hash', 'myExlogHashAuthenticator', 10, 4);
`


- exlog_hook_action_authenticated
This hook is run after the user has been authenticated from the external database.

This will not run if the user is authenticated from the local WordPress database.

Below is an example of code that could be added to your `functions.php` file to add additional fields from the users
table on the external database as user meta data in WordPress.

Please note the use of the third parameter $rawResponse that returns all fields from the users table in the external
database.
`
/**
 * Example function to do something after External Login has authenticated a user
 */
function exlog_add_additional_user_data($wp_user, $exlog_user_data, $rawResponse) {
    update_user_meta(
        $wp_user->ID,                       // User ID
        'someKeyForUserMetaData',           // WP Meta field key
        $rawResponse['someExternalField'],  // External table data
        false                               // Not unique
    );
}

add_action('exlog_hook_action_authenticated', 'exlog_add_additional_user_data', 10, 3);
`

Below is a different example that deletes a user from the external database after they have logged in for the first time.
`
/**
 * Example function to do something after External Login has authenticated a user
 *
 * In this case we are deleting the user from the external database
 *
 * WP User Object $wp_user The WordPress user object for the authenticated user.
 *
 * Array $exlog_user_data An associative array of user data generated when attempting to authenticate the user
 */
function my_function_to_do_something_after_authentication($wp_user, $exlog_user_data) {
  // Uses the data provided to the plugin to create the database object and data required for a query
  $db_data = exlog_get_external_db_instance_and_fields('mysql');

  // A query of your choice
  $rows = $db_data["db_instance"]->delete(
    esc_sql($db_data["dbstructure_table"]),
    array( esc_sql($db_data["dbstructure_username"]) => esc_sql($exlog_user_data['user_login']) )
  );

  // Checking if the user was deleted
  if ($rows) {
    error_log('User Successfully deleted from external database');
  } else {
    error_log('Unable to delete user from external database');
  }
}

add_action('exlog_hook_action_authenticated', 'my_function_to_do_something_after_authentication', 10, 2);
`

- exlog_hook_filter_custom_should_exclude

This hook allows you to add custom logic to exclude users.

It provides you with all the data for the user that is stored in the external database users table.
If you return `true` (or a string - see below regarding custom error messages) from this function it will prevent the
user logging in, and returning `false` will bypass this exclusion.

For example, let's say your external users table had a field called `expiry` which stored a date. In this example we
want to block users if they login after their expiry date.

Adding the following to your `functions.php` would achieve this:

`
function myExlogCustomExcluder($userData) {
    return strtotime($userData['expiry']) < strtotime('now');
}
add_filter('exlog_hook_filter_custom_should_exclude', 'myExlogCustomExcluder', 10, 1);
`

Alternatively if you provide a string the user will be blocked and the string will be used as the error for the user.
`
function myExlogCustomExcluder($userData) {
    return strtotime($userData['expiry']) < strtotime('now') ? 'Your account has expired' : false;
}
add_filter('exlog_hook_filter_custom_should_exclude', 'myExlogCustomExcluder', 10, 1);
`

= How do I add additional fields from my external database in to WordPress? =
The `exlog_hook_action_authenticated` hook can be used to achieve this. It is only run if the user is authenticated
with the external database and not the local WordPress database.

If you are unfamiliar with where to put such code, at the top of your `functions.php` file in your them folder is a
good place to start. My personal preference is to store this type of code in "must use plugins" but that is your own
decision. Alternatively you could use a plugin like `Code Snippets` to add the code.

If the additional data you require is in the users table achiving your goal is slightly easier. Below is an example
where by there is a field in the external database called `someExternalField` and we want to store that as user meta
data in WordPress under the name `someKeyForUserMetaData`.

`
/**
 * Example function to do something after External Login has authenticated a user
 */
function exlog_add_additional_user_data($wp_user, $exlog_user_data, $rawResponse) {
    update_user_meta(
        $wp_user->ID,                       // User ID
        'someKeyForUserMetaData',           // WP Meta field key
        $rawResponse['someExternalField'],  // External table data
        false                               // Not unique
    );
}

add_action('exlog_hook_action_authenticated', 'exlog_add_additional_user_data', 10, 3);
`

If you needed multiple fields you could add multiple calls to `update_user_meta`.

If the additional data you require is not in the users table of your external database you will have to write your own
query to access the data you require.

If you wish to fetch the connection details from the plugin, the below code snippet shows how you can use an External
Login function to get the connection details you require and also shows an example query:

`
// Uses the data provided to the plugin to create the database object and data required for a query
$db_data = exlog_get_external_db_instance_and_fields('mysql');

// A query of your choice
$query_string = "SELECT * FROM someTable";
$result = $db_data["db_instance"]->get_results($query_string, ARRAY_A);
`
You may need to pass the string "mssql" or "postgresql" instead of "mysql" depending on your database.

- exlog_hook_filter_assign_roles
This hook is used to add additional roles to a user with custom logic and potentially custom queries.

This hook provides the user with a range of different information:
- `$roles` - the array of roles already mapped using the built in logic based on data set it the admin panel
- `$username` - the username that was typed in the login screen
- `$userData` - the data that was originally queried for the user

It is expected that you will return an array of roles.

The below is a very simple example that adds the role 'everyoneRole', to all users who are authenticated with the plugin.
The below example assumes that you want to use the roles derived from the built in logic and add to it.
For this reason new roles are added onto the passed array and that is returned.
If you ONLY want roles from your logic, you could return your own array.
`
function myExlogRolesMapper($roles, $username, $userData) {
    array_push($roles, "everyoneRole");
    return $roles;
}
add_filter('exlog_hook_filter_assign_roles', 'myExlogRolesMapper', 10, 3);
`

The below is a relatively complex example that shows how you could write your own query to fetch data and use that to add WordPress roles.
`
function myExlogRolesMapper($roles, $username, $userData) {
    // Uses the data provided to the plugin to create the database object and data required for a query
    $db_data = exlog_get_external_db_instance_and_fields();

    // Start building a query to fetch the user
    // This is the start of the query that you may want to use if you require additional data from your database
    // It may well be that all the data you need is in the passed $userData
    $query_string =
        'SELECT *' .
        // This is specifying the table specified in the settings panel, you can hard code these if you rather
        ' FROM ' . esc_sql($db_data["dbstructure_table"]) .
        // This finds the correct user based on the username field set in the settings and the username that they typed in
        ' WHERE (' . esc_sql($db_data["dbstructure_username"]) . '="' . esc_sql($username) . '"';

    if ($db_data["dbstructure_email"]) {
        // Because the username they type in can be an e-mail, if you have set an e-mail field in the settings panel we will also try and find the user by e-mail
        $query_string .= ' OR ' . esc_sql($db_data["dbstructure_email"]) . '="' . esc_sql($username) . '")';
    } else {
        $query_string .= ')';
    }

    // Use the above computed query actually fetch the data
    $rows = $db_data["db_instance"]->get_results($query_string, ARRAY_A);

    // Checking if a user was found
    if ($rows && count($rows) > 0) {
        $foundData = $rows[0];
        // If the custom field in your database called 'myCustomRoleField' has 'editingKing' stored in it
        if ($foundData['myCustomRoleField'] == 'editingKing') {
            // Add the wordpress role 'editor' to the user
            array_push($roles, "editor");
        }
    }

    // return the array of roles as WordPress supports multiple roles in the backend even though their settings pane only shows one
    return $roles;
}
add_filter('exlog_hook_filter_assign_roles', 'myExlogRolesMapper', 10, 3);
`

= I need an extra feature. Can you add it? =

Get in contact on the support forum and we can discuss it :)


== Screenshots ==

1. Main Settings View
2. Functionality Settings
3. Database Connection Settings
4. Password Hashing Settings
5. Database Table Mapping Settings
6. Role Settings
7. Exclude Users


== Changelog ==

= 1.11.2 =
* Add hook to allow custom manipulation of roles
* Fix bug that meant preventing fall back to the WordPress login only worked from username logins and not e-mails
* Add better explanation regarding cropped strings in the test connection output

= 1.11.1 =
* Add additional safeguards as some users were unable to use the blocking users based on roles feature

= 1.11.0 =
* Allow custom error messages to be returned from the exlog_hook_filter_custom_should_exclude hook
* Allow users to have multiple sessions (so long as their password has not changed)
* Prevent error thrown for some users when an external user was not found in the WordPress database

= 1.10.0 =
* Fix inconsistencies with logic for MS SQL Server connections
* Modify the exlog_hook_action_authenticated hook to provide more data from the external database users table
* Prevent a couple of harmless PHP Notices that clog up error logs. Needs further work

= 1.9.0 =
* Add exlog_hook_filter_custom_should_exclude hook to allow custom coded user exclusions
* Fix bug that prevented 'Role Settings' for mapping roles being saved in the database
* Fix but that prevented 'Exclude Users' settings saving

= 1.8.4 =
* Update deployment process

= 1.8.3 =
* Pass additional data to the authenticating hash hook
* Remove deprecated method (screen_icon())
* Make the plugin easier for other developers to work on (integrating docker and an improved README.md)

= 1.7.3 =
* Fix issue that prevented the BETA feature of excluding users based on custom data in the users table from functioning
* Update plugin details and screenshots

= 1.7.2 =
* Add redirection tool that allows internal or external sites to be redirected to once a user is authenticated
* Add Microsoft SQL Server support (BETA)
* Add ability to validate a users password based on custom custom code using the 'exlog_hook_filter_authenticate_hash' hook
* Fix issue that caused using the 'None' hash setting to have the password always set to be lower case
* Remove code that causes a warning in debug mode

= 1.7.1 =
* Allow mysql databases to accept username or e-mail address for the new username
* Fixed missing logo on production (Hopefully :P )

= 1.7.0 =
* Add Migration Mode setting
* Add action hook 'exlog_hook_action_authenticated' that is triggered when the user is authenticated with the external database

= 1.6.0 =
* Prevent password change e-mail coming through when a users details are updated in WordPress in the External Login flow
* Add feature to block users based on their role
* Fix for the admin area form on repeater items
* Fix a nested function error that appeared for some users

= 1.5.0 =
* Add feature to exclude users based on another field in the database
* Add feature to get multiple roles from the external database roles field (Requires additional plugin for admin manipulation)
* Improve cosmetic bugs in admin area

= 1.4.1 =
* Fix Test button on WP installs in sub directories
* Improve error descriptions to the end user

= 1.4.0 =
* Add PostgreSQL functionality
* Add support links to options page
* Add additional styling to options page

= 1.3.1 =
* Fix broken link from plugin page to settings that caused plugin failure for some users.

= 1.3.0 =
* Make plugin compatible with PHP 5.6.34.

= 1.2.1 =
* Fix broken Test button and improve options page description link.

= 1.2.0 =
* Add new hashing methods.

= 1.1.2 =
* Remove old data from the DB if you switch to using wp-config to store your settings.

= 1.1.1 =
* Improve plugin documentation

= 1.1.0 =
* Add ability to store settings in wp-config

= 1.0.3 =
* Add further sanitisation of data in SQL command
* Refactor code

= 1.0.2 =
* Update readme.txt to better present plugin information again.

= 1.0.1 =
* Update readme.txt to better present plugin information.

= 1.0.0 =
* Initial production version


== Upgrade Notice ==

= 1.11.2 =
* Add hook to allow custom manipulation of roles
* Fix bug that meant preventing fall back to the WordPress login only worked from username logins and not e-mails
* Add better explanation regarding cropped strings in the test connection output

= 1.11.1 =
* Add additional safeguards as some users were unable to use the blocking users based on roles feature

= 1.11.0 =
* Allow custom error messages to be returned from the exlog_hook_filter_custom_should_exclude hook
* Allow users to have multiple sessions (so long as their password has not changed)
* Prevent error thrown for some users when an external user was not found in the WordPress database

= 1.10.0 =
* Fix inconsistencies with logic for MS SQL Server connections
* Modify the exlog_hook_action_authenticated hook to provide more data from the external database users table
* Prevent a couple of harmless PHP Notices that clog up error logs. Needs further work

= 1.9.0 =
* Add exlog_hook_filter_custom_should_exclude hook to allow custom coded user exclusions
* Fix bug that prevented 'Role Settings' for mapping roles being saved in the database
* Fix but that prevented 'Exclude Users' settings saving

= 1.8.4 =
* Update deployment process

= 1.8.3 =
* Pass additional data to the authenticating hash hook
* Remove deprecated method (screen_icon())
* Make the plugin easier for other developers to work on (integrating docker and an improved README.md)

= 1.7.3 =
* Fix issue that prevented the BETA feature of excluding users based on custom data in the users table from functioning
* Update plugin details and screenshots

= 1.7.2 =
* Add redirection tool that allows internal or external sites to be redirected to once a user is authenticated
* Add Microsoft SQL Server support (BETA)
* Add ability to validate a users password based on custom custom code using the 'exlog_hook_filter_authenticate_hash' hook
* Fix issue that caused using the 'None' hash setting to have the password always set to be lower case
* Remove code that causes a warning in debug mode

= 1.7.1 =
* Allow mysql databases to accept username or e-mail address for the new username
* Fixed missing logo on production (Hopefully :P )

= 1.7.0 =
* Add Migration Mode setting
* Add action hook 'exlog_hook_action_authenticated' that is triggered when the user is authenticated with the external database

= 1.6.0 =
* Prevent password change e-mail coming through when a users details are updated in WordPress in the External Login flow
* Add feature to block users based on their role
* Fix for the admin area form on repeater items
* Fix a nested function error that appeared for some users

= 1.5.0 =
* Add feature to exclude users based on another field in the database
* Add feature to get multiple roles from the external database roles field (Requires additional plugin for admin manipulation)
* Improve cosmetic bugs in admin area

= 1.4.1 =
* Fix Test button on WP installs in sub directories
* Improve error descriptions to the end user

= 1.4.0 =
* Add PostgreSQL functionality
* Add support links to options page
* Add additional styling to options page

= 1.3.1 =
* Fix broken link from plugin page to settings that caused plugin failure for some users.

= 1.3.0 =
* Make plugin compatible with PHP 5.6.34.

= 1.2.1 =
* Fix broken Test button and improve options page description link.

= 1.2.0 =
* Add new hashing methods.

= 1.1.2 =
* Remove old data from the DB if you switch to using wp-config to store your settings.

= 1.1.1 =
* Improve plugin documentation

= 1.1.0 =
* Add ability to store settings in wp-config

= 1.0.3 =
* Add further sanitisation of data in SQL command
* Refactor code

= 1.0.2 =
* Update readme.txt to better present plugin information again.

= 1.0.1 =
* Update readme.txt to better present plugin information.

= 1.0.0 =
* Initial production version


== Is this plugin what I need? ==

To give an idea of whether this plugin does the job you need it to, here is the basic logic flow:
1. User logs in to the normal WordPress login screen.
2. We hash the users password with the method and salt (if given) that is chosen in the settings
3. We so a simple SQL query to the external database to see if their username and the hashed password match a user.
4. We create or update the details of the new user.
5. We log that user in
6. When the user logs out of Wordpress the Wordpress session ends

Please note that this system is built for the login process to be a completely different login process to anything else.
If you are looking for Single Sign On (log in to one website and you're logged in else where) you should be looking for a OAuth solution in my opinion.

== FUNCTIONALITY WARNINGS AND LIMITATIONS ==

1. ALWAYS take a backup of your database and test the functionality before using this plugin in production.
2. Users created in WordPress will be overwritten if users in the external database have the same username. This could be fixed by appending usernames with a separate string.
3. Edits to a user made in WordPress will be overwritten when the user logs back in with the 'external database'. This is only the case for fields that are being pulled from the external database.


== Security Notes ==

__Database User__
It is recommended that you create a new Database user to access the external database. This way you can set appropriate permissions to the user so that they do not have write access.

__Hashing__
For the security of your users, your 'external database' should be hashing your users passwords. Although support is given for other hashing methods, 'bcrypt' is advised as it uses SLOW hashing. Without this it would be far easier for someone to derive your users password through a brute force attack if they gained access to your database.

It is also highly recommended that a salt is used. This is done by default with 'bcrypt'. Using one salt for all passwords is supported but it is recommended to use a separate salt for each password as a different field in your database. This helps prevent the use of 'rainbow tables' to derive your users passwords.

For explanation and more information on this I recommend [this article](https://martinfowler.com/articles/web-security-basics.html) starting from the section "Hash and Salt Your Users' Passwords".

__Storing Settings in wp-config.php__
You may prefer to store your settings in 'wp-config.php'. This could have security benefits, so long as you are careful not to store your code in a publicly accessible repository and you ensure your wp-config file cannot be accessed on the server.

Below is an example of code that can be added to 'wp-config.php'.

`
// ** EXTERNAL LOGIN SETTINGS ** //
/** EXLOG - The External Database Name */
define('EXTERNAL_LOGIN_OPTION_DB_NAME', 'dojo2016');

/** EXLOG - The External Database Host */
define('EXTERNAL_LOGIN_OPTION_DB_HOST', 'localhost');

/** EXLOG - The External Database Port */
define('EXTERNAL_LOGIN_OPTION_DB_PORT', '3306');

/** EXLOG - The External Database Username */
define('EXTERNAL_LOGIN_OPTION_DB_USERNAME', 'root');

/** EXLOG - The External Database Password */
define('EXTERNAL_LOGIN_OPTION_DB_PASSWORD', 'root');

/** EXLOG - The External Database Type */
define('EXTERNAL_LOGIN_OPTION_DB_TYPE', 'mysql');

/** EXLOG - Password Salt */
define('EXTERNAL_LOGIN_OPTION_DB_SALT', 'ksjefh2lkrh2r2oh23');
`

You can of course set these with environment variables if you wish in the following way:

`
/** EXLOG - The External Database Name */
define('EXTERNAL_LOGIN_OPTION_DB_NAME', getenv('MY_EXLOG_DB_NAME_ENVIRONMENT_VARIABLE'));
`

All settings (except from those mapping roles) can currently be set this way. For a full list and possible settings see the "FAQ" question - "What values can I set in wp-config.php?".


== Special Thanks ==

A special thank you to Ben Lobaugh for a [great article](https://ben.lobaugh.net/blog/7175/wordpress-replace-built-in-user-authentication) which I used heavily for this plugin.


== DONATE ==
Like the plugin and want to [buy me a beer](https://www.paypal.me/tombenyon)? Well, thank you!
