<?php

define('SBERBANK_ID_ENABLED_SCOPES', serialize([
	'name',
	'email',
	'phone'
]));

define('SBERBANK_ID_ROOT_DIR', dirname(dirname(dirname(dirname(__FILE__)))) . '/');
define('SBERBANK_ID_PLUGIN_DIR', realpath(dirname(__FILE__)) . '/');
define('SBERBANK_ID_SERTIFICATE_DIR', SBERBANK_ID_PLUGIN_DIR . 'sertificate/');
define('SBERBANK_ID_TEMPLATES_DIR', SBERBANK_ID_PLUGIN_DIR . 'templates/');
?>