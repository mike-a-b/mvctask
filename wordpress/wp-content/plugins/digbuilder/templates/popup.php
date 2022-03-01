<?php

if (!defined('ABSPATH')) {
    exit;
}


if (!isset($popup_id)) {
    $popup_id = get_the_ID();
}
$meta_settings = get_post_meta($popup_id, '_elementor_page_settings', true);

$popup_settings_main = wp_parse_args($meta_settings, array());


$entrance_animation_speed = isset($popup_settings_main['entrance_animation_speed']) ? $popup_settings_main['entrance_animation_speed'] : '';

$entrance_animation = isset($popup_settings_main['entrance_animation_type']) ? 'animated ' . $popup_settings_main['entrance_animation_type'] : '';


$close_button_html = '';

$use_close_button = isset($popup_settings_main['use_close_button']) && isset($popup_settings_main['close_button_icon']) ? filter_var($popup_settings_main['use_close_button'], FILTER_VALIDATE_BOOLEAN) : true;


$popup_anim = 'class="digits-popup-anim digits-effects-element ' . $entrance_animation . ' ' . $entrance_animation_speed . '" data-animation-type="' . $entrance_animation . '" data-animation-speed="' . $entrance_animation_speed . '"';

$digits_modal_class = apply_filters('digits_modal_class_' . $popup_id, array());
$digits_modal_class = apply_filters('digits_modal_class', $digits_modal_class);
$digits_modal_class = implode(" ", $digits_modal_class);

?>
<div id="digits-forms-popup-<?php echo $popup_id; ?>"
     class="digits-form-popup digits-builder digits-overlay <?php echo $digits_modal_class; ?>"
     data-id="<?php echo $popup_id; ?>">
    <div class="digits-form-popup-box">
        <div class="digits-popup-container">
            <div <?php echo $popup_anim; ?>>
                <?php echo $close_button_html; ?>
                <?php
                echo '<div class="digits-popup-close-button digits-overlay-close">';
                if ($use_close_button && did_action('elementor/loaded') && isset($popup_settings_main['close_button_icon'])) {
                    \Elementor\Icons_Manager::render_icon($popup_settings_main['close_button_icon'], ['aria-hidden' => 'true']);
                }
                echo '</div>';
                ?>
                <div class="digits-popup-container-child digits_scrollbar">
                    <?php
                    if (isset($popup_content)) {
                        echo $popup_content;
                    } else {
                        the_content();
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="digits-form-popup-background-overlay digits-overlay-close"></div>
</div>
