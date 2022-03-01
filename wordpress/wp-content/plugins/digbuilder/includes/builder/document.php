<?php

if (!defined('ABSPATH')) {
    exit;
}


class Digits_Elem_Document extends Elementor\Core\Base\Document
{

    /**
     * @access public
     */
    public function get_name()
    {
        return 'digits-forms-popup';
    }

    /**
     * @access public
     * @static
     */
    public static function get_title()
    {
        return esc_html__('Digits Popup', 'digits');
    }

    /**
     * [_register_controls description]
     * @return [type] [description]
     */
    protected function _register_controls()
    {

        parent::_register_controls();


        $popup_id = '#digits-forms-popup-' . $this->get_main_id();


        $this->register_popup_container_section($popup_id);

        $this->register_popup_container_background_section($popup_id);

        $this->register_popup_overlay_background_section($popup_id);

        $this->register_close_button_section($popup_id);

        $this->register_popup_advance_section($popup_id);
    }

    private function register_popup_container_section($popup_id)
    {
        $this->start_controls_section(
            'digits_popup_style',
            [
                'label' => esc_html__('Popup Container', 'digits'),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'container_width',
            [
                'label' => esc_html__('Width', 'digits'),
                'type' => Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range' => [
                    'px' => [
                        'min' => 380,
                        'max' => 1200,
                    ],
                    'em' => [
                        'min' => 30,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 450,
                    'unit' => 'px',
                ],
                'selectors' => [
                    $popup_id . ' .digits-popup-container' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'container_custom_height',
            [
                'label' => esc_html__('Custom Height', 'digits'),
                'type' => Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'digits'),
                'label_off' => esc_html__('No', 'digits'),
                'return_value' => 'yes',
                'default' => 'no',
            ]
        );

        $this->add_responsive_control(
            'container_height',
            [
                'label' => esc_html__('Height', 'digits'),
                'type' => Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'range' => [
                    'px' => [
                        'min' => 250,
                        'max' => 1200,
                    ],
                    'em' => [
                        'min' => 18,
                        'max' => 100,
                    ],
                    '%' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'size' => 500,
                    'unit' => 'px',
                ],
                'selectors' => [
                    $popup_id . ' .digits-popup-container-child' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'container_custom_height' => 'yes',
                ],
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'container_border',
                'label' => esc_html__('Border', 'digits'),
                'placeholder' => '1px',
                'default' => '1px',
                'selector' => $popup_id . ' .digits-popup-container-child',
                'fields_options' => [
                    'width' => [
                        'label' => esc_html__('Border Width', 'digits'),
                    ],
                    'color' => [
                        'label' => esc_html__('Border Color', 'digits'),
                    ],
                ],
            ]
        );

        $this->add_control(
            'container_border_radius',
            [
                'label' => esc_html__('Border Radius', 'digits'),
                'type' => Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    $popup_id . ' .digits-popup-container-child' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    $popup_id . ' .digits-popup-container-background' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_group_control(
            Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'container_shadow',
                'selector' => $popup_id . ' .digits-popup-container-child',
            ]
        );
        $this->end_controls_section();
    }

    private function register_popup_container_background_section($popup_id)
    {
        $this->start_controls_section(
            'digits_form_popup_container_background_style',
            [
                'label' => esc_html__('Popup Container Background', 'digits'),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'container_background',
                'selector' => $popup_id . ' .digits-popup-container-child',
            ]
        );

        $this->end_controls_section();

    }

    private function register_popup_overlay_background_section($popup_id)
    {
        $this->start_controls_section(
            'digits_form_overlay_background_style',
            [
                'label' => esc_html__('Popup Overlay Background', 'digits'),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Elementor\Group_Control_Background::get_type(),
            [
                'name' => 'overlay_background',
                'selector' => $popup_id . ' .digits-form-popup-background-overlay',
            ]
        );

        $this->end_controls_section();
    }

    private function register_close_button_section($popup_id)
    {
        $this->start_controls_section(
            'digits_form_close_button_style',
            [
                'label' => esc_html__('Close Button', 'digits'),
                'tab' => Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'use_close_button',
            [
                'label' => esc_html__('Close Button', 'digits'),
                'type' => Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'digits'),
                'label_off' => esc_html__('No', 'digits'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'close_button_icon',
            [
                'label' => esc_html__('Icon', 'digits'),
                'type' => Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fa fa-times',
                    'library' => 'solid',
                ],
            ]
        );
        $this->start_controls_tabs('close_button_style_tabs');

        $this->start_controls_tab(
            'close_button_control_normal_tab',
            [
                'label' => esc_html__('Normal', 'digits'),
            ]
        );

        $this->add_group_control(
            \Digits_Group_Control_Box_Style::get_type(),
            [
                'name' => 'close_button_box_style_normal',
                'label' => esc_html__('Icon Styles', 'digits'),
                'selector' => $popup_id . ' .digits-popup-close-button',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'close_button_control_hover_tab',
            [
                'label' => esc_html__('Hover', 'digits'),
            ]
        );

        $this->add_group_control(
            \Digits_Group_Control_Box_Style::get_type(),
            [
                'name' => 'close_button_box_style_hover',
                'label' => esc_html__('Icon Styles', 'digits'),
                'selector' => $popup_id . ' .digits-popup-close-button:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    private function register_popup_advance_section($popup_id)
    {

        $this->register_advanced_section($popup_id);

        $this->register_popup_positioning_section($popup_id);

        $this->register_popup_motion_effects_section();
    }

    private function register_advanced_section($popup_id)
    {
        $this->start_controls_section(
            'digits_popup_advanced',
            [
                'label' => esc_html__('Advanced', 'digits'),
                'tab' => Elementor\Controls_Manager::TAB_ADVANCED,
            ]
        );

        $this->add_responsive_control(
            'container_margin',
            [
                'label' => esc_html__('Margin', 'digits'),
                'type' => Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    $popup_id . ' .digits-popup-container-child' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'container_padding',
            [
                'label' => esc_html__('Padding', 'digits'),
                'type' => Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    $popup_id . ' .digits-popup-container-child' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();
    }

    private function register_popup_positioning_section($popup_id)
    {
        $this->start_controls_section(
            'digits_popup_positioning_advanced',
            [
                'label' => esc_html__('Positioning', 'digits'),
                'tab' => Elementor\Controls_Manager::TAB_ADVANCED,
            ]
        );


        $this->add_responsive_control(
            'digits_form_content_position',
            [
                'label' => esc_html__('Content Position', 'digits'),
                'type' => Elementor\Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'flex-start',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Top', 'digits'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__('Middle', 'digits'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Bottom', 'digits'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors' => [
                    $popup_id . ' .digits-popup-container' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'digits_form_horizontal_position',
            [
                'label' => esc_html__('Horizontal Position', 'digits'),
                'type' => Elementor\Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Left', 'digits'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'digits'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Right', 'digits'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'selectors' => [
                    $popup_id . ' .digits-form-popup-box' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'digits_form_vertical_position',
            [
                'label' => esc_html__('Vertical Position', 'digits'),
                'type' => Elementor\Controls_Manager::CHOOSE,
                'default' => 'center',
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Top', 'digits'),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'center' => [
                        'title' => esc_html__('Middle', 'digits'),
                        'icon' => 'eicon-v-align-middle',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('Bottom', 'digits'),
                        'icon' => 'eicon-v-align-bottom',
                    ],
                ],
                'selectors' => [
                    $popup_id . ' .digits-form-popup-box' => 'align-items: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function register_popup_motion_effects_section()
    {
        $this->start_controls_section(
            'digits_popup_motion_effects_advanced',
            [
                'label' => esc_html__('Motion Effects', 'digits'),
                'tab' => Elementor\Controls_Manager::TAB_ADVANCED,
            ]
        );


        $this->add_control(
            'entrance_animation',
            [
                'label' => esc_html__('Entrance', 'digits'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );


        $this->add_control(
            'entrance_animation_type',
            [
                'label' => esc_html__('Animation', 'digits'),
                'type' => Elementor\Controls_Manager::SELECT,
                'default' => 'fadeIn',
                'options' => [
                    'bounce' => esc_html__('bounce', 'digits'),
                    'flash' => esc_html__('flash', 'digits'),
                    'pulse' => esc_html__('pulse', 'digits'),
                    'rubberBand' => esc_html__('rubberBand', 'digits'),
                    'shake' => esc_html__('shake', 'digits'),
                    'swing' => esc_html__('swing', 'digits'),
                    'tada' => esc_html__('tada', 'digits'),
                    'wobble' => esc_html__('wobble', 'digits'),
                    'jello' => esc_html__('jello', 'digits'),
                    'heartBeat' => esc_html__('heartBeat', 'digits'),
                    'bounceIn' => esc_html__('bounceIn', 'digits'),
                    'bounceInDown' => esc_html__('bounceInDown', 'digits'),
                    'bounceInLeft' => esc_html__('bounceInLeft', 'digits'),
                    'bounceInRight' => esc_html__('bounceInRight', 'digits'),
                    'bounceInUp' => esc_html__('bounceInUp', 'digits'),
                    'fadeIn' => esc_html__('fadeIn', 'digits'),
                    'fadeInDown' => esc_html__('fadeInDown', 'digits'),
                    'fadeInDownBig' => esc_html__('fadeInDownBig', 'digits'),
                    'fadeInLeft' => esc_html__('fadeInLeft', 'digits'),
                    'fadeInLeftBig' => esc_html__('fadeInLeftBig', 'digits'),
                    'fadeInRight' => esc_html__('fadeInRight', 'digits'),
                    'fadeInRightBig' => esc_html__('fadeInRightBig', 'digits'),
                    'fadeInUp' => esc_html__('fadeInUp', 'digits'),
                    'fadeInUpBig' => esc_html__('fadeInUpBig', 'digits'),
                    'flip' => esc_html__('flip', 'digits'),
                    'flipInX' => esc_html__('flipInX', 'digits'),
                    'flipInY' => esc_html__('flipInY', 'digits'),
                    'lightSpeedIn' => esc_html__('lightSpeedIn', 'digits'),
                    'rotateIn' => esc_html__('rotateIn', 'digits'),
                    'rotateInDownLeft' => esc_html__('rotateInDownLeft', 'digits'),
                    'rotateInDownRight' => esc_html__('rotateInDownRight', 'digits'),
                    'rotateInUpLeft' => esc_html__('rotateInUpLeft', 'digits'),
                    'rotateInUpRight' => esc_html__('rotateInUpRight', 'digits'),
                    'slideInUp' => esc_html__('slideInUp', 'digits'),
                    'slideInDown' => esc_html__('slideInDown', 'digits'),
                    'slideInLeft' => esc_html__('slideInLeft', 'digits'),
                    'slideInRight' => esc_html__('slideInRight', 'digits'),
                    'zoomIn' => esc_html__('zoomIn', 'digits'),
                    'zoomInDown' => esc_html__('zoomInDown', 'digits'),
                    'zoomInLeft' => esc_html__('zoomInLeft', 'digits'),
                    'zoomInRight' => esc_html__('zoomInRight', 'digits'),
                    'zoomInUp' => esc_html__('zoomInUp', 'digits'),
                    'jackInTheBox' => esc_html__('jackInTheBox', 'digits'),
                    'rollIn' => esc_html__('rollIn', 'digits'),
                ],
            ]
        );

        $this->add_control(
            'entrance_animation_speed',
            [
                'label' => esc_html__('Animation Speed', 'digits'),
                'type' => Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    'faster' => esc_html__('Faster', 'digits'),
                    'fast' => esc_html__('Fast', 'digits'),
                    '' => esc_html__('Normal', 'digits'),
                    'slow' => esc_html__('Slow', 'digits'),
                    'slower' => esc_html__('Slower', 'digits'),
                ],
            ]
        );

        $this->end_controls_section();
    }


}
