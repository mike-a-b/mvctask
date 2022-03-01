<?php


if (!defined('ABSPATH')) {
    exit;
}

add_action('init', 'register_digits_form_post');
add_action('digits_activation_hooks', 'register_digits_form_post_type');
add_filter('default_option_elementor_cpt_support', 'dig_add_elem_support');
add_action('template_include', 'digits_popup_template', 999);
add_action('elementor/documents/register', 'digits_register_elementor_document_type');
add_action('wp_insert_post', 'digits_set_document_type_on_post_create', 10, 2);


function digits_register_elementor_document_type($documents_manager)
{
    require('builder/document.php');
    $documents_manager->register_document_type('digits-forms-popup', 'Digits_Elem_Document');
}

add_filter('display_post_states', 'digits_pg_remove_elementor', 20, 2);

function digits_pg_add_digits_page_type($page_templates, $wp_theme, $post)
{
    if (!did_action('elementor/loaded')) {
        return $page_templates;
    }


    if ($post) {
        // FIX ME: Gutenberg not send $post as WP_Post object, just the post ID.
        $post_id = !empty($post->ID) ? $post->ID : $post;

        $document = Elementor\Plugin::$instance->documents->get($post_id);
        if ($document && !$document::get_property('support_wp_page_templates')) {
            return $page_templates;
        }
    }

    $page_templates = [
            Elementor\Modules\PageTemplates\Module::TEMPLATE_CANVAS => _x('Elementor Canvas', 'Page Template', 'elementor'),
            Elementor\Modules\PageTemplates\Module::TEMPLATE_HEADER_FOOTER => _x('Elementor Full Width', 'Page Template', 'elementor'),
        ] + $page_templates;

    return $page_templates;

}

add_filter("theme_digits-forms-page_templates", 'digits_pg_add_digits_page_type', 10, 4);
function digits_pg_remove_elementor($post_states, $post)
{
    if (did_action('elementor/loaded')) {
        if (is_digbuilder_type($post->post_type)) {

            if (Elementor\User::is_current_user_can_edit($post->ID) && Elementor\Plugin::$instance->db->is_built_with_elementor($post->ID)) {
                unset($post_states['elementor']);
            }
        }
    }

    return $post_states;
}


function digits_set_document_type_on_post_create($post_id, $post)
{

    if ($post->post_type != 'digits-forms-popup') {
        return;
    }

    if (!did_action('elementor/loaded')) {
        return;
    }

    $documents = Elementor\Plugin::instance()->documents;
    $doc_type = $documents->get_document_type('digits-forms-popup');

    update_post_meta($post_id, $doc_type::TYPE_META_KEY, 'digits-forms-popup');

}

function dig_add_elem_support($value)
{

    if (empty($value)) {
        $value = array();
    }

    return array_merge($value, array('digits-forms-popup', 'digits-forms-page'));
}

function register_digits_form_post_type()
{

    register_digits_form_post();
    flush_rewrite_rules();

}

function register_digits_form_post()
{

    $popup_labels = array(
        'name' => esc_html__('Popup Builder (Beta)', 'digits'),
        'singular_name' => esc_html__('Popup Builder', 'digits'),
        'all_items' => esc_html__('All Popups', 'digits'),
        'add_new' => esc_html__('Add New Popup', 'digits'),
        'add_new_item' => esc_html__('Add New Popup', 'digits'),
        'edit_item' => esc_html__('Edit Popup', 'digits')
    );
    $page_labels = array(
        'name' => esc_html__('Page Builder (Beta)', 'digits'),
        'singular_name' => esc_html__('Page Builder', 'digits'),
        'all_items' => esc_html__('All Pages', 'digits'),
        'add_new' => esc_html__('Add New Page', 'digits'),
        'add_new_item' => esc_html__('Add New Page', 'digits'),
        'edit_item' => esc_html__('Edit Page', 'digits')
    );

    $args = digits_post_args();
    $args['labels'] = $popup_labels;
    $args['capability_type'] = 'post';
    register_post_type('digits-forms-popup', $args);

    $args['labels'] = $page_labels;
    $args['capability_type'] = 'page';
    register_post_type('digits-forms-page', $args);

}

function digits_post_args()
{
    return array(
        'hierarchical' => false,
        'description' => 'description',
        'taxonomies' => [],
        'public' => true,
        'publicly_queryable' => true,
        'show_in_menu' => false,
        'show_in_nav_menus' => false,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'supports' => array('title'),
    );
}

add_filter('single_template', 'digits_pg_set_page_template');
function digits_pg_set_page_template($original)
{
    global $post;
    if ($post->post_type == 'digits-forms-page') {
        $base_name = 'page.php';
        dipagebuilder_if_loggedin_redirect();
        $template = locate_template($base_name);
        if ($template && !empty($template)) {
            return $template;
        }
    }

    return $original;
}

function digits_popup_template($template)
{
    if (is_singular('digits-forms-popup')) {
        return digbuilder_dir() . 'templates/popup_page.php';
    } else {
        return $template;
    }
}

function digbuilder_remove_page_slug($post_link, $post, $leavename)
{

    if ('digits-forms-page' != $post->post_type || 'publish' != $post->post_status) {
        return $post_link;
    }

    $post_link = str_replace('/' . $post->post_type . '/', '/', $post_link);

    return $post_link;
}

add_filter('post_type_link', 'digbuilder_remove_page_slug', 10, 3);

function digbuilder_parse_request($query)
{
    if (!$query->is_main_query() || 2 != count($query->query) || !isset($query->query['page'])) {
        return;
    }


    if (!empty($query->query['pagename']) && empty($query->query['name']) && !strstr($query->query['pagename'], '/')) {
        $query->set('name', $query->query['pagename']);
    }

    if (!empty($query->query['pagename']) || !empty($query->query['name']) && !get_query_var('post_type')) {
        $post_type = 'digits-forms-page';
        $query->set('post_type', array('page', 'post', $post_type));
        add_action('posts_pre_query', 'digbuilder_pre_query', 10, 2);
    }
}

add_action('pre_get_posts', 'digbuilder_parse_request');

function digbuilder_pre_query($posts, $query)
{
    $query->set('post_type', 'post');
    return $posts;
}


add_filter('digits_page_list', 'digbuilder_add_page');
add_filter('digits_modal_list', 'digbuilder_add_modal');

function digbuilder_add_page($list)
{
    return array_merge($list, digbuilder_getlist('page'));
}

function digbuilder_add_modal($list)
{
    return array_merge($list, digbuilder_getlist('modal'));
}


function digbuilder_getlist($type)
{
    $post_type = array('modal' => 'digits-forms-popup', 'page' => 'digits-forms-page');
    $posts = get_posts(array('post_type' => $post_type[$type], 'post_status' => 'publish', 'posts_per_page' => -1));
    $list = array();
    foreach ($posts as $post) {
        $list[$type . '_' . $post->ID] = array(
            'label' => $post->post_title,
            'value' => $post->ID,
            'type' => $type
        );
    }
    return $list;
}


function digbuilder_post_check_elementor()
{
    $check_elem = digbuilder_check_elem('');

    if (empty($check_elem)) {
        return;
    }

    $screen = get_current_screen();
    if (!is_digbuilder_type($screen->post_type) || $screen->base != 'edit') return;
    ?>
    <style>
        body {
            overflow: hidden;
        }
    </style>
    <div class="digits-builder_new_form digits-overlay" data-loaded="0">
        <div class="digits-builder-popup-box digits-center-align">
            <div class="install_elementor">
                <?php
                echo $check_elem;
                ?>
            </div>
        </div>
    </div>
    <?php
}

add_action('admin_head', 'digbuilder_post_check_elementor');