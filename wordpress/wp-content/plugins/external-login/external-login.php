<?php
/*
Plugin Name: External Login
Plugin URI: http://tom.benyon.io/plugins/external-login
Description: A plugin to allow login and syncing from a secondary database
Author: Tom Benyon
Version: 1.11.2
Author URI: http://tom.benyon.io
Text Domain: external-login
*/

define( 'EXLOG_PLUGIN_FILE_PATH', __FILE__);
define( 'EXLOG_PATH_PLUGIN_BASE', __DIR__);
define( 'EXLOG_PATH_PLUGIN_VIEWS', EXLOG_PATH_PLUGIN_BASE . '/views');
define( 'EXLOG_PATH_PLUGIN_LOGIN', EXLOG_PATH_PLUGIN_BASE . '/login');
define( 'EXLOG_PATH_PLUGIN_OPTIONS', EXLOG_PATH_PLUGIN_BASE . '/options');
define( 'EXLOG_PATH_PLUGIN_TOOLS', EXLOG_PATH_PLUGIN_BASE . '/tools');
define( 'EXLOG_PATH_PLUGIN_LIB', EXLOG_PATH_PLUGIN_BASE . '/lib');
define( 'EXLOG_PATH_PLUGIN_SANITISATION_VALIDATION', EXLOG_PATH_PLUGIN_BASE . '/sanitisation_validation');
define( 'EXLOG_PATH_ASSETS', plugins_url( '/appAssets/', EXLOG_PLUGIN_FILE_PATH ));

define( 'EXLOG_HOOK_FILTER_AUTHENTICATE_HASH', 'exlog_hook_filter_authenticate_hash');
define( 'EXLOG_HOOK_FILTER_ASSIGN_ROLES', 'exlog_hook_filter_assign_roles');
define( 'EXLOG_HOOK_FILTER_CUSTOM_EXCLUDE', 'exlog_hook_filter_custom_should_exclude');
define( 'EXLOG_ROLE_BLOCK_VALUE', 'exlog_block');

include EXLOG_PATH_PLUGIN_OPTIONS . '/wpconfig_options.php';
include EXLOG_PATH_PLUGIN_SANITISATION_VALIDATION . '/validation.php';
include EXLOG_PATH_PLUGIN_TOOLS . '/get_roles.php';
include EXLOG_PATH_PLUGIN_TOOLS . '/map_role.php';
include EXLOG_PATH_PLUGIN_TOOLS . '/exclude.php';
include EXLOG_PATH_PLUGIN_LIB . '/Exlog_built_plugin_data.php';
include EXLOG_PATH_PLUGIN_LIB . '/Exlog_view_building.php';
include EXLOG_PATH_PLUGIN_LIB . '/Exlog_redirect.php';
include EXLOG_PATH_PLUGIN_OPTIONS . '/options_external_login.php';
include EXLOG_PATH_PLUGIN_OPTIONS . '/cleanup.php';
include EXLOG_PATH_PLUGIN_OPTIONS . '/testing_ajax.php';
include EXLOG_PATH_PLUGIN_OPTIONS . '/add_plugin_options_links.php';
include EXLOG_PATH_PLUGIN_LOGIN . '/validate_password.php';
include EXLOG_PATH_PLUGIN_LOGIN . '/db.php';
include EXLOG_PATH_PLUGIN_LOGIN . '/authenticate.php';

// Setup redirection hooks
$exlog_redirection = new Exlog_redirect();
$exlog_redirection->exlog_setup_redirection();
