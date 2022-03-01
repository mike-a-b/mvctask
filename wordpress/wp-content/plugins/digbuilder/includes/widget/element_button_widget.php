<?php
/**
 * @package Elementor
 *
 * Elementor is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * any later version.
 *
 * Elementor is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Controls_Manager;
use Elementor\Core\Schemes;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;
use Elementor\Widget_Base as Widget_Base;


/**
 * Elementor button widget.
 *
 * Elementor widget that displays a button with the ability to control every
 * aspect of the button design.
 *
 * @since 1.0.0
 */
class Elementor_Digits_Button extends Widget_Base
{

    /**
     * Get widget name.
     *
     * Retrieve button widget name.
     *
     * @return string Widget name.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_name()
    {
        return 'digits-button';
    }

    /**
     * Get widget title.
     *
     * Retrieve button widget title.
     *
     * @return string Widget title.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_title()
    {
        return __('Button', 'elementor');
    }

    /**
     * Get widget icon.
     *
     * Retrieve button widget icon.
     *
     * @return string Widget icon.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_icon()
    {
        return 'icon-digits-button icon-digits-elem-dims';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the button widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * @return array Widget categories.
     * @since 2.0.0
     * @access public
     *
     */
    public function get_categories()
    {
        return ['digits-form'];
    }

    /**
     * Get button sizes.
     *
     * Retrieve an array of button sizes for the button widget.
     *
     * @return array An array containing button sizes.
     * @since 1.0.0
     * @access public
     * @static
     *
     */
    public static function get_button_sizes()
    {
        return [
            'xs' => __('Extra Small', 'elementor'),
            'sm' => __('Small', 'elementor'),
            'md' => __('Medium', 'elementor'),
            'lg' => __('Large', 'elementor'),
            'xl' => __('Extra Large', 'elementor'),
        ];
    }

    /**
     * Register button widget controls.
     *
     * Adds different input fields to allow the user to change and customize the widget settings.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _register_controls()
    {
        $this->start_controls_section(
            'section_button',
            [
                'label' => __('Button', 'elementor'),
            ]
        );


        $this->add_control(
            'text',
            [
                'label' => __('Text', 'elementor'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => __('Click here', 'elementor'),
                'placeholder' => __('Click here', 'elementor'),
            ]
        );


        $this->add_control(
            'use_as',
            [
                'label' => __('Use as', 'digits'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'back' => esc_attr__('Back', 'digits'),
                    'popup' => esc_attr__('Popup Trigger', 'digits'),
                    'page' => esc_attr__('Page Link', 'digits'),
                ],
                'default' => 'popup',
            ]
        );

        $this->add_control(
            'popup',
            [
                'label' => __('Popup', 'digits'),
                'type' => Controls_Manager::SELECT,
                'options' => $this->convert_options(digits_pages_list('modal')),
                'default' => 'default',
                'condition' => [
                    'use_as' => 'popup',
                ],
            ]
        );
        $this->add_control(
            'page',
            [
                'label' => __('Page', 'digits'),
                'type' => Controls_Manager::SELECT,
                'options' => $this->convert_options(digits_pages_list('page')),
                'default' => 'default',
                'condition' => [
                    'use_as' => 'page',
                ],
            ]
        );

        $this->add_responsive_control(
            'align',
            [
                'label' => __('Alignment', 'elementor'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'elementor'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'elementor'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'elementor'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => __('Justified', 'elementor'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'prefix_class' => 'elementor%s-align-',
                'default' => '',
            ]
        );

        $this->add_control(
            'size',
            [
                'label' => __('Size', 'elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'sm',
                'options' => self::get_button_sizes(),
                'style_transfer' => true,
            ]
        );

        $this->add_control(
            'selected_icon',
            [
                'label' => __('Icon', 'elementor'),
                'type' => Controls_Manager::ICONS,
                'label_block' => true,
                'fa4compatibility' => 'icon',
            ]
        );

        $this->add_control(
            'icon_align',
            [
                'label' => __('Icon Position', 'elementor'),
                'type' => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'left' => __('Before', 'elementor'),
                    'right' => __('After', 'elementor'),
                ],
                'condition' => [
                    'selected_icon[value]!' => '',
                ],
            ]
        );

        $this->add_control(
            'icon_indent',
            [
                'label' => __('Icon Spacing', 'elementor'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-button .elementor-align-icon-right' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .elementor-button .elementor-align-icon-left' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'view',
            [
                'label' => __('View', 'elementor'),
                'type' => Controls_Manager::HIDDEN,
                'default' => 'traditional',
            ]
        );

        $this->add_control(
            'button_css_id',
            [
                'label' => __('Button ID', 'elementor'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => [
                    'active' => true,
                ],
                'default' => '',
                'title' => __('Add your custom id WITHOUT the Pound key. e.g: my-id', 'elementor'),
                'label_block' => false,
                'description' => __('Please make sure the ID is unique and not used elsewhere on the page this form is displayed. This field allows <code>A-z 0-9</code> & underscore chars without spaces.', 'elementor'),
                'separator' => 'before',

            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Button', 'elementor'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'typography',
                'scheme' => Schemes\Typography::TYPOGRAPHY_4,
                'selector' => '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button',
            ]
        );

        $this->add_group_control(
            Group_Control_Text_Shadow::get_type(),
            [
                'name' => 'text_shadow',
                'selector' => '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button',
            ]
        );

        $this->start_controls_tabs('tabs_button_style');

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => __('Normal', 'elementor'),
            ]
        );

        $this->add_control(
            'button_text_color',
            [
                'label' => __('Text Color', 'elementor'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'fill: {{VALUE}}; color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label' => __('Background Color', 'elementor'),
                'type' => Controls_Manager::COLOR,
                'scheme' => [
                    'type' => Schemes\Color::get_type(),
                    'value' => Schemes\Color::COLOR_4,
                ],
                'selectors' => [
                    '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => __('Hover', 'elementor'),
            ]
        );

        $this->add_control(
            'hover_color',
            [
                'label' => __('Text Color', 'elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover, {{WRAPPER}} a.elementor-button:focus, {{WRAPPER}} .elementor-button:focus' => 'color: {{VALUE}};',
                    '{{WRAPPER}} a.elementor-button:hover svg, {{WRAPPER}} .elementor-button:hover svg, {{WRAPPER}} a.elementor-button:focus svg, {{WRAPPER}} .elementor-button:focus svg' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_background_hover_color',
            [
                'label' => __('Background Color', 'elementor'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover, {{WRAPPER}} a.elementor-button:focus, {{WRAPPER}} .elementor-button:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'button_hover_border_color',
            [
                'label' => __('Border Color', 'elementor'),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'border_border!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} a.elementor-button:hover, {{WRAPPER}} .elementor-button:hover, {{WRAPPER}} a.elementor-button:focus, {{WRAPPER}} .elementor-button:focus' => 'border-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hover_animation',
            [
                'label' => __('Hover Animation', 'elementor'),
                'type' => Controls_Manager::HOVER_ANIMATION,
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'border',
                'selector' => '{{WRAPPER}} .elementor-button',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => __('Border Radius', 'elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'selector' => '{{WRAPPER}} .elementor-button',
            ]
        );

        $this->add_responsive_control(
            'text_padding',
            [
                'label' => __('Padding', 'elementor'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} a.elementor-button, {{WRAPPER}} .elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();
    }

    private function convert_options($pages)
    {
        $list = array();
        foreach ($pages as $page_key => $values) {
            $list[$page_key] = $values['label'];
        }
        return $list;
    }

    /**
     * Render button widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function render()
    {
        if (is_user_logged_in()) {
            if (!\Elementor\Plugin::$instance->editor->is_edit_mode() && !\Elementor\Plugin::$instance->preview->is_preview_mode()) {
                return;
            }
        }
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute('wrapper', 'class', 'elementor-button-wrapper');


        $use_as = $settings['use_as'];

        if ($use_as == 'page' || $use_as == 'back') {
            if ($use_as == 'back') {
                $link = isset($_GET['redirect_to']) ? esc_html__($_GET['redirect_to']) : get_home_url();
            } else {
                $page = $settings['page'];
                $pid = preg_replace('/^page_/', '', $page);
                $link = Digits_Pagebuilder_shortcodes::get_url($pid, null);
            }
            if (!empty($link)) {
                $this->add_render_attribute('button', 'href', $link);
                $this->add_render_attribute('button', 'data-link', 1);
            }
        } else if ($use_as == 'popup') {
            $popup = $settings['popup'];
            $pid = preg_replace('/^modal_/', '', $popup);

            $this->add_render_attribute('button', 'data-show', Digits_Pagebuilder_shortcodes::popup_id($pid, true));
        }
        $this->add_render_attribute('button', 'class', 'elementor-button-link');


        $this->add_render_attribute('button', 'class', 'elementor-button digits-login-modal');
        $this->add_render_attribute('button', 'role', 'button');

        if (!empty($settings['button_css_id'])) {
            $this->add_render_attribute('button', 'id', $settings['button_css_id']);
        }

        if (!empty($settings['size'])) {
            $this->add_render_attribute('button', 'class', 'elementor-size-' . $settings['size']);
        }

        if ($settings['hover_animation']) {
            $this->add_render_attribute('button', 'class', 'elementor-animation-' . $settings['hover_animation']);
        }

        ?>
        <div <?php echo $this->get_render_attribute_string('wrapper'); ?>>
            <a <?php echo $this->get_render_attribute_string('button'); ?>>
                <?php $this->render_text(); ?>
            </a>
        </div>
        <?php
    }

    /**
     * Render button widget output in the editor.
     *
     * Written as a Backbone JavaScript template and used to generate the live preview.
     *
     * @since 1.0.0
     * @access protected
     */
    protected function _content_template()
    {
        ?>
        <#
        view.addRenderAttribute( 'text', 'class', 'elementor-button-text' );
        view.addInlineEditingAttributes( 'text', 'none' );
        var iconHTML = elementor.helpers.renderIcon( view, settings.selected_icon, { 'aria-hidden': true }, 'i' , 'object' ),
        migrated = elementor.helpers.isIconMigrated( settings, 'selected_icon' );
        #>
        <div class="elementor-button-wrapper">
            <a id="{{ settings.button_css_id }}"
               class="elementor-button elementor-size-{{ settings.size }} elementor-animation-{{ settings.hover_animation }}"
               href="#" role="button">
				<span class="elementor-button-content-wrapper">
					<# if ( settings.icon || settings.selected_icon ) { #>
					<span class="elementor-button-icon elementor-align-icon-{{ settings.icon_align }}">
						<# if ( ( migrated || ! settings.icon ) && iconHTML.rendered ) { #>
							{{{ iconHTML.value }}}
						<# } else { #>
							<i class="{{ settings.icon }}" aria-hidden="true"></i>
						<# } #>
					</span>
					<# } #>
					<span {{{ view.getRenderAttributeString( 'text' ) }}}>{{{ settings.text }}}</span>
                </span>
            </a>
        </div>
        <?php
    }

    /**
     * Render button text.
     *
     * Render button widget text.
     *
     * @since 1.5.0
     * @access protected
     */
    protected function render_text()
    {
        $settings = $this->get_settings_for_display();

        $migrated = isset($settings['__fa4_migrated']['selected_icon']);
        $is_new = empty($settings['icon']) && Icons_Manager::is_migration_allowed();

        if (!$is_new && empty($settings['icon_align'])) {
            // @todo: remove when deprecated
            // added as bc in 2.6
            //old default
            $settings['icon_align'] = $this->get_settings('icon_align');
        }

        $this->add_render_attribute([
            'content-wrapper' => [
                'class' => 'elementor-button-content-wrapper',
            ],
            'icon-align' => [
                'class' => [
                    'elementor-button-icon',
                    'elementor-align-icon-' . $settings['icon_align'],
                ],
            ],
            'text' => [
                'class' => 'elementor-button-text',
            ],
        ]);

        $this->add_inline_editing_attributes('text', 'none');
        ?>
        <span <?php echo $this->get_render_attribute_string('content-wrapper'); ?>>
			<?php if (!empty($settings['icon']) || !empty($settings['selected_icon']['value'])) : ?>
                <span <?php echo $this->get_render_attribute_string('icon-align'); ?>>
				<?php if ($is_new || $migrated) :
                    Icons_Manager::render_icon($settings['selected_icon'], ['aria-hidden' => 'true']);
                else : ?>
                    <i class="<?php echo esc_attr($settings['icon']); ?>" aria-hidden="true"></i>
                <?php endif; ?>
			</span>
            <?php endif; ?>
			<span <?php echo $this->get_render_attribute_string('text'); ?>><?php echo $settings['text']; ?></span>
		</span>
        <?php
    }

    public function on_import($element)
    {
        return Icons_Manager::on_import_migration($element, 'icon', 'selected_icon');
    }
}
