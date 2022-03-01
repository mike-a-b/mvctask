<?php

if (!defined('ABSPATH')) {
    exit;
}


function digbuilder_new_import_export_ui()
{

    if (!did_action('elementor/loaded')) {
        return;
    }

    $screen = get_current_screen();
    if (!is_digbuilder_type($screen->post_type) || $screen->base != 'edit') return;
    ?>
    <div class="digits-builder_new_form digits-overlay digits-hide" data-loaded="0">
        <div class="digits-builder-popup-box">
            <form class="digpage_new_import_form" method="post" enctype="multipart/form-data">
                <div class="digits-builder-popup-container">
                    <div class="digits-builder-popup-header">
                        <div class="digits-builder-popup-header_button digits-overlay-close"><?php esc_attr_e('Close', 'digits'); ?></div>
                        <div class="digits-builder-popup-heading"><?php esc_attr_e('Preset Library', 'digits'); ?></div>
                    </div>
                    <div class="digits-builder-popup-body modal-body digits_scrollbar">
                        <div class="digits-builder-presets"></div>
                        <div class="select_file_desc">
                            <div class="select_file_icon"></div>
                            <div class="select_file_text">
                                <?php esc_attr_e('Please choose a file or drag it here', 'digits'); ?>
                            </div>
                        </div>
                    </div>
                    <div style="display: none;visibility: hidden">
                        <input type="file" class="digpreset_upload" name="file"
                               accept=".json,application/json"/>
                        <input type="hidden" name="request_type" value="import"/>
                        <input type="hidden" name="preset_slug"/>
                        <input type="hidden" name="preset_type" class="preset_type"
                               value="<?php esc_html_e($screen->post_type); ?>"/>
                    </div>
                    <div class="digits-builder-popup-footer">
                        <div class="digits-builder-popup-footer_buttons">
                            <div class="digits-builder_inline digis-builder_import_type">
                                <label><?php esc_html_e('Select the form type you want to import', 'digits'); ?></label>
                                <select name="import_type" class="digits-settings_select">
                                    <option value="-1"><?php esc_html_e('(select)', 'digits'); ?></option>
                                    <option value="login-register"><?php esc_html_e('Login & Register', 'digits'); ?></option>
                                    <option value="login-only"><?php esc_html_e('Login', 'digits'); ?></option>
                                    <option value="register-only"><?php esc_html_e('Register', 'digits'); ?></option>
                                    <option value="forgot-pass"><?php esc_html_e('Forgot Password', 'digits'); ?></option>
                                </select>
                            </div>
                            <div class="digits-builder_inline digits-builder_import digpreset_import_button"><?php esc_attr_e('Import', 'digits'); ?></div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script id="digbuilder-preset-template" type="text/x-html-template">
        <div class="digb_preset_container">
            <a class="digb_select_preset"
               href="<?php echo add_query_arg(array('post_type' => $screen->post_type), admin_url('post-new.php')); ?>">
                <div class="digb_preset">
                    <div class="digb_preset_preview">
                        <div class="digb_preset_preview_desc">
                            <div class="dig_preset_sel">
                                <div class="dig_tick_center">
                                    <img class="dig_preset_sel_tick"
                                         src="<?php echo get_digits_asset_uri('/admin/assets/images/preset-tick.svg'); ?>"
                                         draggable="false"/>
                                </div>
                            </div>
                            <img class="digb_preset_preview" src=""/>
                            <a class="dig_preset_big_img" href="#" tabindex="0"></a>
                            <span class="fresh_start"><?php esc_attr_e('Fresh Start', 'digits'); ?></span>
                        </div>
                    </div>
                    <div class="digb_preset_name">
                        <?php esc_attr_e('Blank', 'digits'); ?>
                    </div>
                </div>
            </a>
        </div>
    </script>
    <div class="dig_big_preset_show">
        <div class="dig-flex_center">
            <img src="" draggable="false"/>
        </div>
    </div>
    <?php
}

function digbuilder_prepare_export($post_id)
{
    $source = Elementor\Plugin::instance()->templates_manager->get_source('local');
    if (!$source) {
        return new \WP_Error('template_error', 'Template source not found');
    }

    $template_data = $source->get_data(['template_id' => $post_id]);

    if (empty($template_data['content'])) {
        return new \WP_Error('empty_template', 'The template is empty');
    }


    $template_data['content'] = digbuilder_process_export_import_content($template_data['content'], 'on_export');

    if (get_post_meta($post_id, '_elementor_page_settings', true)) {
        $page = Elementor\Core\Settings\Manager::get_settings_managers('page')->get_model($post_id);

        $page_settings_data = digbuilder_process_element_export_import_content($page, 'on_export');

        if (!empty($page_settings_data['settings'])) {
            $template_data['page_settings'] = $page_settings_data['settings'];
        }
    }

    $export_data = [
        'version' => Elementor\DB::DB_VERSION,
        'title' => get_the_title($post_id),
        'post_type' => get_post_type($post_id),
        'type' => get_post_meta($post_id, Elementor\Core\Base\Document::TYPE_META_KEY, true)
    ];

    $page_template = get_post_meta($post_id, '_wp_page_template', true);
    if (!empty($page_template)) {
        $template_data['page_template'] = $page_template;
    }

    $export_data += $template_data;


    return [
        'name' => 'digits-' . $post_id . '-' . gmdate('Y-m-d') . '.json',
        'content' => wp_json_encode($export_data),
    ];

}

add_action('admin_footer', 'digbuilder_new_import_export_ui');
function digbuilder_export()
{
    if (isset($_REQUEST['post_type'])) {
        if (!is_digbuilder_type($_REQUEST['post_type'])) {
            return;
        }
    }

    if (!current_user_can('manage_options')) {
        return;
    }


    if (isset($_REQUEST['request_type'])) {
        if ($_REQUEST['request_type'] == 'export') {
            $post_id = sanitize_text_field($_REQUEST['export_id']);
            $file = digbuilder_prepare_export($post_id);

            header('Content-Type: application/json');
            header('Content-Disposition: attachment; filename=' . $file['name']);
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . strlen($file['content']));
            @ob_end_clean();
            flush();

            echo $file['content'];

            die();
        } else if ($_REQUEST['request_type'] == 'import') {

            if (isset($_FILES['file']) && !empty($_FILES['file']) && $_FILES['file']['size'] != 0) {
                $file = $_FILES['file'];

                if ('application/json' !== $file['type']) {
                    digbuilder_error_msg(__('Error: only json files are supported!', 'digits'));
                }
                $content = file_get_contents($file['tmp_name']);
            } else if (isset($_POST['preset_slug']) && !empty($_POST['preset_slug'])) {
                $import_type = sanitize_text_field($_POST['import_type']);

                $content_url = 'https://bridge.unitedover.com/digits/presets/?action=download&download_type=' . $_POST['preset_type'] . '&slug=' . $_POST['preset_slug'] . '&purchase_code=' . get_site_option('dig_purchasecode');

                $content = dig_curl($content_url);

                if ($import_type != -1)
                    $content = str_replace('login-register', $import_type, $content);

            } else {
                digbuilder_error_msg(__('Error: Nothing found to import!', 'digits'));
            }

            $content = json_decode($content, true);
            $content = digbuilder_process_assets($content);
            if (isset($content['message'])) {
                digbuilder_error_msg($content['message']);
            }

            if (empty($content) || isset($content['error_msg']) || !is_digbuilder_type($content['type'])) {
                if (empty($content)) {
                    digbuilder_error_msg(__('Unexpected error occurred  while importing!', 'digits'));
                } else if (isset($content['error_msg'])) {
                    digbuilder_error_msg(esc_html__($content['error_msg']));
                }
            }

            if (!is_digbuilder_type($content['post_type'])) {
                digbuilder_error_msg(__('The format is not supported!', 'digits'));
            }


            $content['content'] = digbuilder_process_export_import_content($content['content'], 'on_import');

            $page_settings = $content['page_settings'];

            if ($content['type'] == 'digits-forms-page') {
                $title = __('New Page', 'digits');
            } else {
                $title = __('New Popup', 'digits');
            }

            $post_data['post_title'] = !empty($content['title']) ? $content['title'] : $title;


            $defaults = [
                'title' => $title,
                'page_settings' => []
            ];

            $data = array(
                'content' => $content['content'],
                'title' => $content['title'],
                'type' => $content['type'],
                'page_settings' => $page_settings,
            );

            $template_data = wp_parse_args($data, $defaults);


            $document = Elementor\Plugin::$instance->documents->create(
                $template_data['type'],
                [
                    'post_title' => $template_data['title'],
                    'post_type' => $content['post_type'],
                ]
            );

            if (is_wp_error($document)) {
                digbuilder_error_msg($document->get_error_messages());
            }

            if (!empty($template_data['content'])) {
                $template_data['content'] = digbuilder_replace_elements_ids($template_data['content']);
            }

            $document->save([
                'elements' => $template_data['content'],
                'settings' => $template_data['page_settings'],
            ]);

            digbuilder_redirect($document->get_edit_url());
            die();


        }
    }
}

add_action('admin_init', 'digbuilder_export');


function digbuilder_error_msg($msg)
{
    if (dig_is_doing_ajax()) {
        wp_send_json_error(array('message' => $msg));
    } else {
        wp_die($msg);
    }

}

function digbuilder_redirect($location)
{
    if (dig_is_doing_ajax()) {
        wp_send_json_success(array('redirect' => $location));
    } else {
        wp_safe_redirect($location);
    }
}


function digits_page_export($actions, $post)
{
    if (!did_action('elementor/loaded')) {
        return $actions;
    }


    if (
        Elementor\User::is_current_user_can_edit($post->ID) &&
        Elementor\Plugin::$instance->db->is_built_with_elementor($post->ID) &&
        (
            'digits-forms-popup' === get_post_type($post->ID) ||
            'digits-forms-page' === get_post_type($post->ID)
        )
    ) {
        $link = add_query_arg(array('export_id' => $post->ID, 'request_type' => 'export'));
        $actions['dig_post_export'] = '<a href="' . $link . '">' . __('Export', 'digits') . '</a>';


    }

    return $actions;

}

add_filter('post_row_actions', 'digits_page_export', 10, 2);


function digbuilder_process_export_import_content($content, $method)
{
    return Elementor\Plugin::$instance->db->iterate_data(
        $content, function ($element_data) use ($method) {
        $element = Elementor\Plugin::$instance->elements_manager->create_element_instance($element_data);

        // If the widget/element isn't exist, like a plugin that creates a widget but deactivated
        if (!$element) {
            return null;
        }

        return digbuilder_process_element_export_import_content($element, $method);
    }
    );
}


function digbuilder_process_element_export_import_content($element, $method)
{
    $element_data = $element->get_data();

    if (method_exists($element, $method)) {
        // TODO: Use the internal element data without parameters.
        $element_data = $element->{$method}($element_data);
    }

    foreach ($element->get_controls() as $control) {
        $control_class = Elementor\Plugin::$instance->controls_manager->get_control($control['type']);

        // If the control isn't exist, like a plugin that creates the control but deactivated.
        if (!$control_class) {
            return $element_data;
        }

        if (method_exists($control_class, $method)) {
            $element_data['settings'][$control['name']] = $control_class->{$method}($element->get_settings($control['name']), $control);
        }

        // On Export, check if the control has an argument 'export' => false.
        if ('on_export' === $method && isset($control['export']) && false === $control['export']) {
            unset($element_data['settings'][$control['name']]);
        }
    }

    return $element_data;
}

function digbuilder_replace_elements_ids($content)
{
    return Elementor\Plugin::$instance->db->iterate_data($content, function ($element) {
        $element['id'] = Elementor\Utils::generate_random_string();
        return $element;
    });
}


function digbuilder_process_assets($content)
{
    array_walk_recursive($content, function (&$value, $key) {
        if (!is_array($value)) {
            if (filter_var($value, FILTER_VALIDATE_URL) === TRUE) {
                $value = digbuilder_download_image($value);
            }
        }
    });
    return $content;
}


function digbuilder_download_image($image_url)
{

    $image = $image_url;

    $get = wp_remote_get($image);

    $type = wp_remote_retrieve_header($get, 'content-type');

    if (!$type)
        return false;

    $mirror = wp_upload_bits(basename($image), '', wp_remote_retrieve_body($get));

    $attachment = array(
        'post_title' => basename($image),
        'post_mime_type' => $type
    );

    $attach_id = wp_insert_attachment($attachment, $mirror['file']);

    require_once(ABSPATH . 'wp-admin/includes/image.php');

    $attach_data = wp_generate_attachment_metadata($attach_id, $mirror['file']);

    wp_update_attachment_metadata($attach_id, $attach_data);

    return array('attach_id' => $attach_id, 'url' => wp_get_attachment_url($attach_id));

}