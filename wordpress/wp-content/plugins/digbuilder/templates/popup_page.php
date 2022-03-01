<?php

if (!defined('ABSPATH')) {
    exit;
}

dipagebuilder_if_loggedin_redirect();

$popup_id = get_the_ID();


do_action('digits_page_ini');

function digbuilder_modal_page($class)
{
    $class[] = ' digits_no_dismiss';
    return $class;
}

add_filter('digits_modal_class_' . $popup_id, 'digbuilder_modal_page');

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta name="viewport" content="width=device-width"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name='robots' content='noindex,noarchive'/>
    <meta name='referrer' content='strict-origin-when-cross-origin'/>
    <?php
    do_action('wp_head');
    ?>
    <style>
        #digits-forms-popup-<?php echo $popup_id;?> {
            display: block !important;
        }
    </style>
</head>
<body <?php body_class(); ?>>
<?php

if (have_posts()) {
    while (have_posts()) :
        the_post();
        load_template(digbuilder_popup_template());
    endwhile;
}
?>

<?php
do_action('wp_footer');
?>
</body>
</html>
