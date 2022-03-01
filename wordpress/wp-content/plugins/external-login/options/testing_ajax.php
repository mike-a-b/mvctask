<?php

add_action( 'wp_ajax_exlog_test_connection', 'exlog_test_connection' );
//Line causes a warning in debug mode - no longer requried. Delete in future version?
//wp_localize_script( 'exlog-test', 'exlog_ajax_url', array( 'ajax_url' => admin_url( 'admin-ajax.php' )));
function exlog_test_connection() {
    $exlog_test_results_data = exlog_test_query(5);

    include EXLOG_PATH_PLUGIN_VIEWS . '/test_results.php';

    wp_die();
}
