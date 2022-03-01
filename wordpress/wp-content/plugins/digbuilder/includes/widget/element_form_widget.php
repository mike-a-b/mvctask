<?php


if (!defined('ABSPATH')) {
    exit;
}


use Elementor\Plugin as Elementor;
use Elementor\Widget_Base as Widget_Base;

class Elementor_Digits_Widget extends Widget_Base
{

    public function __construct($data = [], $args = null)
    {
        parent::__construct($data, $args);
    }

    /**
     * Get widget name.
     *
     * Retrieve widget name.
     *
     * @return string Widget name.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_name()
    {

        switch ($this->get_widget_type()) {
            case 1:
                return 'login-register';
            case 2:
                return 'register-only';
            case 3:
                return 'forgot-pass';
            case 4:
                return 'login-only';
        }
    }

    public function get_widget_type()
    {

    }

    /**
     * Get widget title.
     *
     * Retrieve widget title.
     *
     * @return string Widget title.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_title()
    {
        switch ($this->get_widget_type()) {
            case 1:
                return 'Login & Register';
            case 2:
                return 'Register';
            case 3:
                return 'Forgot Password';
            case 4:
                return 'Login';
        }
    }

    public function is_only_register()
    {
        $type = $this->get_widget_type();
        if ($type == 2) {
            return true;
        }
    }

    /**
     * Get widget icon.
     *
     * Retrieve  widget icon.
     *
     * @return string Widget icon.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_icon()
    {
        switch ($this->get_widget_type()) {
            case 1:
                return 'icon-digits-login-reg icon-digits-elem-dims';
            case 2:
                return 'icon-digits-reg icon-digits-elem-dims';
            case 3:
                return 'icon-digits-forgotpass icon-digits-elem-dims';
            case 4:
                return 'icon-digits-login icon-digits-elem-dims';
        }
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the widget belongs to.
     *
     * @return array Widget categories.
     * @since 1.0.0
     * @access public
     *
     */
    public function get_categories()
    {
        return ['digits-form'];
    }

    /**
     * Render widget output on the frontend.
     *
     * Written in PHP and used to generate the final HTML.
     *
     * @since 1.0.0
     * @access protected
     */

    protected function render()
    {
        $settings = $this->get_active_settings();

        if (is_user_logged_in()) {
            if (!\Elementor\Plugin::$instance->editor->is_edit_mode() && !\Elementor\Plugin::$instance->preview->is_preview_mode()) {
                return;
            }
        }
        $show_placeholder = $settings['show_placeholder'];
        $show_label = $settings['show_label'];
        $floating_label = $settings['floating_label'];

        if (isset($settings['show_asterisk']) && $settings['show_asterisk']) {
            $show_asterisk = 1;
        } else {
            $show_asterisk = 0;
        }

        $button_texts = digits_form_button();
        foreach ($button_texts as $button_key => $button_text) {
            if (isset($settings[$button_key])) {
                $settings_button_value = $settings[$button_key];
                if (!empty($settings_button_value)) $button_texts[$button_key] = $settings[$button_key];
            }
        }

        if (isset($settings['dig_enable_forgot_password']) && $settings['dig_enable_forgot_password']) {
            $enable_forgot_password = 1;
        } else {
            $enable_forgot_password = 0;
        }

        ?>
        <div class="dig_lrf_box dig-elem <?php
        if (!$floating_label) {
            echo 'dig_pgmdl_2 ';
        } else echo 'dig_floating_label'; ?> <?php if ($show_label) {
            echo 'dig_show_label';
        } ?>" data-placeholder="<?php echo $show_placeholder; ?>" data-asterisk="<?php echo $show_asterisk; ?>">
            <div class="dig_form">
                <?php

                if (!isset($settings['dig_inline_otp_field']) || !$settings['dig_inline_otp_field']) {
                    dig_verify_otp_box();
                }
                ?>
                <div class="dig-log-par">
                    <?php
                    if ($this->get_widget_type() != 2) {
                        $form_data = '<input type="hidden" name="digbuilder_form" value="1" /><input type="hidden" name="post_id" value="' . $this->get_current_id() . '"/><input type="hidden" name="form_id" value="' . $this->get_id() . '"/>';
                        $data = array();
                        $data['is_elem'] = $this->get_widget_type();
                        $data['post_id'] = $this->get_current_id();
                        $data['form_id'] = $this->get_id();
                        $data['settings'] = $this->get_active_settings();
                        $data['button_texts'] = $button_texts;
                        $data['forgot_password'] = $enable_forgot_password;
                        $data['url'] = '';
                        digits_forms($data, $form_data);
                    }
                    ?>
                    <div class="register" <?php if ($this->get_widget_type() != 2) {
                        echo 'style="display:none;"';
                    } ?>>
                        <form accept-charset="utf-8" method="post" class="digits_register">
                            <div class="dig_reg_inputs">
                                <?php
                                if (isset($settings['fields'])) {
                                    $fields = digbuilder_get_formatted_fields($settings['fields'], 1);
                                    digbuilder_show_elem_fields($settings, $button_texts, $fields, $show_asterisk, $this->get_widget_type());

                                }
                                ?>
                            </div>

                            <input type="hidden" name="digbuilder_form" value="1"/>
                            <input type="hidden" name="post_id" value="<?php echo $this->get_current_id(); ?>"/>
                            <input type="hidden" name="form_id" value="<?php echo $this->get_id(); ?>"/>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php

    }

    public static function get_current_id()
    {
        if (isset(Elementor::$instance->documents) && !empty(Elementor::$instance->documents->get_current())) {
            return Elementor::$instance->documents->get_current()->get_main_id();
        }

        return get_the_ID();

    }

    protected function _register_controls()
    {


        $is_reg = $this->is_register();

        $user_roles = array();

        if ($is_reg) {
            foreach (wp_roles()->roles as $key => $value):
                $user_roles[$key] = $value['name'];
            endforeach;
        }

        $this->register_section_login();

        if ($is_reg) {
            $this->register_section_form_fields($user_roles);
        }
        $this->register_section_general_settings($user_roles);

        $this->register_section_redirect();

        $this->register_section_button_text();

        $this->register_section_general();
        $this->register_section_label();
        $this->register_section_field();

        $this->register_section_dropdown();

        if ($this->get_widget_type() != 3) {
            $this->register_section_checkbox();
            if ($is_reg) {
                $this->register_section_radio();
                $this->register_section_signup_button();
            }
        }


        $this->register_section_button();

        $this->register_section_text();

        $this->register_section_error();
    }

    public function is_register()
    {
        $type = $this->get_widget_type();
        if ($type == 1 || $type == 2) {
            return true;
        }
    }

    private function register_section_login()
    {
        if (!$this->is_forgotpass() && !$this->is_login()) {
            return;
        }

        if ($this->is_login()) {
            $field_labels = esc_html__('Login Fields', 'digits');
        } else {
            $field_labels = esc_html__('Forgot Fields', 'digits');
        }

        $this->start_controls_section(
            'section_login_fields',
            [
                'label' => esc_html__($field_labels, 'digits'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        if (!$this->is_only_forgotpass()) {
            $this->add_control(
                'dig_login_username',
                [
                    'label' => esc_html__('Username', 'digits'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => esc_html__('Yes', 'digits'),
                    'label_off' => esc_html__('No', 'digits'),
                    'return_value' => 'true',
                    'default' => 'true',
                    'render_type' => 'template',
                ]
            );
        }
        $this->add_control(
            'dig_login_email',
            [
                'label' => esc_html__('Email', 'digits'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'digits'),
                'label_off' => esc_html__('No', 'digits'),
                'return_value' => 'true',
                'default' => 'true',
                'render_type' => 'template',
            ]
        );
        $this->add_control(
            'dig_login_mobilenumber',
            [
                'label' => esc_html__('Mobile Number', 'digits'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'digits'),
                'label_off' => esc_html__('No', 'digits'),
                'return_value' => 'true',
                'default' => 'true',
            ]
        );
        if ($this->is_login()) {
            $this->add_control(
                'dig_login_password',
                [
                    'label' => esc_html__('Password', 'digits'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => esc_html__('Yes', 'digits'),
                    'label_off' => esc_html__('No', 'digits'),
                    'return_value' => 'true',
                    'default' => 'true',
                    'render_type' => 'template',

                ]
            );
            $this->add_control(
                'dig_login_otp',
                [
                    'label' => esc_html__('OTP', 'digits'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => esc_html__('Yes', 'digits'),
                    'label_off' => esc_html__('No', 'digits'),
                    'return_value' => 'true',
                    'default' => 'true',
                    'render_type' => 'template',
                ]
            );
            $this->add_control(
                'dig_login_captcha',
                [
                    'label' => esc_html__('Captcha', 'digits'),
                    'type' => \Elementor\Controls_Manager::SWITCHER,
                    'label_on' => esc_html__('Yes', 'digits'),
                    'label_off' => esc_html__('No', 'digits'),
                    'return_value' => 'true',
                    'default' => '',
                    'render_type' => 'template',
                ]
            );
            $this->add_control(
                'dig_login_rememberme',
                [
                    'label' => esc_html__('Remember Me', 'digits'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'default' => '1',
                    'render_type' => 'template',
                    'options' => [
                        '2' => esc_html__('Always', 'digits'),
                        '1' => esc_html__('Yes', 'digits'),
                        '0' => esc_html__('No', 'digits'),
                    ],
                ]
            );

        }
        $this->end_controls_section();
    }

    public function is_forgotpass()
    {
        $type = $this->get_widget_type();
        if ($type == 1 || $type == 3) {
            return true;
        }
    }

    public function is_login()
    {
        $type = $this->get_widget_type();
        if ($type == 1 || $type == 4) {
            return true;
        }
    }

    public function is_only_forgotpass()
    {
        $type = $this->get_widget_type();
        if ($type == 3) {
            return true;
        }
    }

    private function register_section_form_fields($user_roles)
    {
        $field_types = [
            'username' => esc_html__('Username', 'digits'),
            'mobmail' => esc_html__('Email/Mobile Number', 'digits'),
            'mobile_number' => esc_html__('Mobile Number', 'digits'),
            'email' => esc_html__('Email', 'digits'),
            'password' => esc_html__('Password', 'digits'),
            'wp_predefined' => esc_html__('WordPress Fields', 'digits'),
            'wc_predefined' => esc_html__('WooCommerce Fields', 'digits'),
            'custom' => esc_html__('Custom', 'digits'),
        ];
        $wp_fields = [
            'first_name' => esc_html__('First Name', 'digits'),
            'last_name' => esc_html__('Last Name', 'digits'),
            'display_name' => esc_html__('Display Name', 'digits'),
            'user_role' => esc_html__('User Role', 'digits'),
        ];
        $wc_fields = [
            'first_name' => esc_html__('First Name', 'digits'),
            'last_name' => esc_html__('Last Name', 'digits'),
            'company' => esc_html__('Company', 'digits'),
            'addr1' => esc_html__('Address Line 1', 'digits'),
            'addr2' => esc_html__('Address line 2', 'digits'),
            'city' => esc_html__('City', 'digits'),
            'state' => esc_html__('State', 'digits'),
            'country' => esc_html__('Country', 'digits'),
            'zip' => esc_html__('Postcode / ZIP', 'digits'),
        ];

        $sub_field_types = [
            'text' => esc_html__('Text', 'digits'),
            'textarea' => esc_html__('Textarea', 'digits'),
            'number' => esc_html__('Number', 'digits'),
            'dropdown' => esc_html__('DropDown', 'digits'),
            'checkbox' => esc_html__('Checkbox', 'digits'),
            'radio' => esc_html__('Radio', 'digits'),
            'tac' => esc_html__('Terms & Conditions', 'digits'),
            'captcha' => esc_html__('Captcha', 'digits'),
            'date' => esc_html__('Date', 'digits'),
        ];

        $this->start_controls_section(
            'section_form_registration',
            [
                'label' => esc_html__('Registration', 'digits'),
            ]
        );


        $this->add_control(
            'custom_user_role',
            [
                'label' => esc_html__('Custom User Role', 'digits'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'digits'),
                'label_off' => esc_html__('No', 'digits'),
                'return_value' => 'true',
                'default' => '',
            ]
        );

        $this->add_control(
            'custom_user_role_value',
            [
                'label' => esc_html__('User Role', 'digits'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $user_roles,
                'condition' => [
                    'custom_user_role' => 'true',
                ],
            ]
        );

        $this->add_control(
            'registration_fields',
            [
                'label' => esc_html__('Form Fields', 'digits'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );


        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
            '_id',
            [
                'label' => 'id',
                'type' => \Elementor\Controls_Manager::HIDDEN,
            ]
        );
        $repeater->add_control(
            'type',
            [
                'label' => esc_html__('Type', 'digits'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $field_types,
                'default' => 'username'
            ]
        );
        $repeater->add_control(
            'field_wp_type',
            [
                'label' => esc_html__('Field', 'digits'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $wp_fields,
                'default' => 'first_name',
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'type',
                            'operator' => 'equal',
                            'value' => 'wp_predefined',
                        ],
                    ],
                ],
            ]
        );
        $repeater->add_control(
            'field_wc_type',
            [
                'label' => esc_html__('Field', 'digits'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $wc_fields,
                'default' => 'first_name',
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'type',
                            'operator' => 'equal',
                            'value' => 'wc_predefined',
                        ],
                    ],
                ],
            ]
        );


        $repeater->add_control(
            'sub_type',
            [
                'label' => esc_html__('Sub Type', 'digits'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $sub_field_types,
                'default' => 'text',
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'type',
                            'operator' => 'equal',
                            'value' => 'custom',
                        ],
                    ],
                ],
            ]
        );

        $repeater->add_control(
            'meta_key',
            [
                'label' => esc_html__('Meta Key', 'digits'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'type',
                            'operator' => 'equal',
                            'value' => 'custom',
                        ],
                    ],
                ],
            ]
        );

        $repeater->add_control(
            'label',
            [
                'label' => esc_html__('Label', 'digits'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'value' => '{{ sub_type }}',
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'type',
                            'operator' => '!in',
                            'value' => [
                                'username',
                                'mobmail',
                                'mobile_number',
                                'email',
                                'password'
                            ],
                        ],
                    ],
                ],
            ]
        );

        $tac_desc = '<div class="elementor-control-field-description">';
        $tac_desc .= esc_html__('Enclose the word(s) between [t] and [/t] for terms and condition and [p] and [/t] for privacy policy.', 'digits');
        $tac_desc .= '<br /><br />';
        $tac_desc .= esc_html__('For example "I Agree [t]Terms and Conditions[/t] & [p]Privacy Policy[/t]"', 'digits');
        $tac_desc .= '</div>';

        $repeater->add_control(
            'tac_desc',
            [
                'label' => $tac_desc,
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'sub_type',
                            'operator' => 'equal',
                            'value' => 'tac',
                        ],
                    ],
                ],
            ]
        );
        $repeater->add_control(
            'terms_link',
            [
                'label' => esc_html__(' Terms & Conditions Link', 'digits'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'label_block' => true,
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'sub_type',
                            'operator' => 'equal',
                            'value' => 'tac',
                        ],
                    ],
                ],
            ]
        );
        $repeater->add_control(
            'privacy_link',
            [
                'label' => esc_html__('Privacy Link', 'digits'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
                'label_block' => true,
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'sub_type',
                            'operator' => 'equal',
                            'value' => 'tac',
                        ],
                    ],
                ],
            ]
        );


        $repeater->add_control(
            'field_options',
            [
                'name' => 'field_options',
                'label' => esc_html__('Options', 'digits'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => '',
                'description' => esc_html__('Enter each option in a separate line', 'digits'),
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'sub_type',
                            'operator' => 'in',
                            'value' => [
                                'checkbox',
                                'radio',
                                'dropdown',
                            ],
                        ],
                        [
                            'name' => 'type',
                            'operator' => 'equal',
                            'value' => 'custom',
                        ]
                    ],
                ],
            ]
        );

        $repeater->add_control(
            'user_roles',
            [
                'label' => esc_html__('User Role', 'digits'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple' => true,
                'options' => $user_roles,
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'type',
                            'operator' => 'equal',
                            'value' => 'wp_predefined',
                        ],
                        [
                            'name' => 'field_wp_type',
                            'operator' => 'equal',
                            'value' => 'user_role',
                        ],
                    ],
                ],
            ]
        );

        $repeater->add_control(
            'required',
            [
                'label' => esc_html__('Required', 'digits'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'return_value' => 'true',
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'type',
                            'operator' => '!in',
                            'value' => [
                                'checkbox',
                                'mobmail'
                            ],
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'fields',
            [
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'type' => 'wp_predefined',
                        'field_wp_type' => 'first_name',
                        'label' => esc_attr__('First Name', 'digits'),
                        'required' => 'true',
                    ],
                    [
                        'type' => 'mobmail'
                    ],
                    [
                        'type' => 'password'
                    ]
                ]

            ]
        );

        $this->end_controls_section();
    }

    private function register_section_general_settings($user_roles)
    {
        $this->start_controls_section(
            'section_general_settings',
            [
                'label' => esc_html__('General Settings', 'digits'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'dig_enable_forgot_password',
            [
                'label' => esc_html__('Forgot Password', 'digits'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'digits'),
                'label_off' => esc_html__('No', 'digits'),
                'return_value' => 'true',
                'default' => 'true',
                'render_type' => 'template',
            ]
        );

        $this->add_control(
            'dig_inline_otp_field',
            [
                'label' => esc_html__('Inline OTP Field', 'digits'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'digits'),
                'label_off' => esc_html__('No', 'digits'),
                'default' => '',
                'return_value' => 'true',
                'render_type' => 'template',
            ]
        );

        $this->end_controls_section();

    }

    private function register_section_redirect()
    {

        $this->start_controls_section(
            'section_redirect',
            [
                'label' => esc_html__('Redirection', 'digits'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        if ($this->is_login()) {
            $this->add_control(
                'login_redirect',
                [
                    'label' => esc_html__('Login', 'digits'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'description' => '<span class="digpage_desc">' . __('Leave blank for auto redirect', 'digits') . '</span>',
                    'default' => '',
                ]
            );
        }

        if ($this->is_forgotpass()) {
            $this->add_control(
                'forgot_redirect',
                [
                    'label' => esc_html__('Forgot Password', 'digits'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'description' => '<span class="digpage_desc">' . __('Leave blank for auto redirect', 'digits') . '</span>',
                    'default' => '',
                ]
            );
        }

        if ($this->is_register()) {
            $this->add_control(
                'register_redirect',
                [
                    'label' => esc_html__('Register', 'digits'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'description' => '<span class="digpage_desc">' . __('Leave blank for auto redirect', 'digits') . '</span>',
                    'default' => '',
                ]
            );
        }
        $this->end_controls_section();

    }

    private function register_section_button_text()
    {

        $this->start_controls_section(
            'section_translation',
            [
                'label' => esc_html__('Translation', 'digits'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        if ($this->is_login() || $this->is_forgotpass()) {
            $this->add_control(
                'login_text_translation',
                [
                    'label' => esc_html__('Login and Forgot Password', 'digits'),
                    'type' => \Elementor\Controls_Manager::HEADING,
                    'separator' => 'after',
                ]
            );

        }
        if ($this->is_login()) {
            $this->add_control(
                'dig_login_via_pass',
                [
                    'label' => esc_html__('Login', 'digits'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => esc_html__('Log In', 'digits'),
                    'render_type' => 'template',

                ]
            );
            $this->add_control(
                'dig_login_via_mob',
                [
                    'label' => esc_html__('Login With OTP', 'digits'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => esc_html__('Login With OTP', 'digits'),
                    'render_type' => 'template',

                ]
            );

            $this->add_control(
                'dig_login_remember_me',
                [
                    'label' => esc_html__('Remember Me', 'digits'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => esc_html__('Remember Me', 'digits'),
                    'render_type' => 'template',
                ]
            );
        }

        if ($this->is_forgotpass()) {
            $this->add_control(
                'dig_login_forgot_pass',
                [
                    'label' => esc_html__('Forgot Password', 'digits'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => esc_html__('Forgot your Password?', 'digits'),
                    'render_type' => 'template',

                ]
            );
        }
        if ($this->is_login() || $this->is_forgotpass()) {
            $this->add_control(
                'dig_login_otp_text',
                [
                    'label' => esc_html__('OTP', 'digits'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => esc_html__('OTP', 'digits'),
                    'render_type' => 'template',
                ]
            );


            $this->add_control(
                'dig_login_submit_otp',
                [
                    'label' => esc_html__('Submit OTP', 'digits'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => esc_html__('Submit OTP', 'digits'),
                    'render_type' => 'template',

                ]
            );

            $this->add_control(
                'dig_login_resend_otp',
                [
                    'label' => esc_html__('Resend OTP', 'digits'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => esc_html__('Resend OTP', 'digits'),
                    'render_type' => 'template',

                ]
            );

        }

        $this->add_control(
            'registration_translation',
            [
                'label' => esc_html__('Registration', 'digits'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );


        if ($this->is_register()) {

            $this->add_control(
                'dig_signup_desc_text',
                [
                    'label' => esc_html__('Dont have an account?', 'digits'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => esc_html__('Dont have an account?', 'digits'),
                    'render_type' => 'template',

                ]
            );
            $this->add_control(
                'dig_signup_button_text',
                [
                    'label' => esc_html__('Signup', 'digits'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => esc_html__('Signup', 'digits'),
                    'render_type' => 'template',

                ]
            );
            $this->add_control(
                'dig_signup_via_password',
                [
                    'label' => esc_html__('Signup With Password', 'digits'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => esc_html__('Signup With Password', 'digits'),
                    'render_type' => 'template',

                ]
            );
            $this->add_control(
                'dig_signup_via_otp',
                [
                    'label' => esc_html__('Signup With OTP', 'digits'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => esc_html__('Signup With OTP', 'digits'),
                    'render_type' => 'template',

                ]
            );


            $this->add_control(
                'dig_signup_otp_text',
                [
                    'label' => esc_html__('OTP', 'digits'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => esc_html__('OTP', 'digits'),
                    'render_type' => 'template',
                ]
            );

            $this->add_control(
                'dig_signup_submit_otp',
                [
                    'label' => esc_html__('Submit OTP', 'digits'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => esc_html__('Submit OTP', 'digits'),
                    'render_type' => 'template',

                ]
            );
            $this->add_control(
                'dig_signup_resend_otp',
                [
                    'label' => esc_html__('Resend OTP', 'digits'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'placeholder' => esc_html__('Resend OTP', 'digits'),
                    'render_type' => 'template',

                ]
            );
        }
        $this->end_controls_section();

    }

    private function register_section_general()
    {
        $this->start_controls_section(
            'section_style_general',
            [
                'label' => esc_html__('General', 'digits'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_responsive_control(
            'general_row_spacing',
            [
                'label' => esc_html__('Row Spacing', 'digits'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .minput, {{WRAPPER}} .button, {{WRAPPER}} a' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function register_section_label()
    {
        $this->start_controls_section(
            'section_style_label',
            [
                'label' => esc_html__('Label', 'digits'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'show_label',
            [
                'label' => esc_html__('Show Label', 'digits'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'digits'),
                'label_off' => esc_html__('No', 'digits'),
                'return_value' => 'true',
                'default' => 'true',
            ]
        );


        $this->add_control(
            'floating_label',
            [
                'label' => esc_html__('Floating Labels', 'digits'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'digits'),
                'label_off' => esc_html__('No', 'digits'),
                'return_value' => 'true',
                'default' => '',
                'condition' => [
                    'show_label' => 'true',
                ],
            ]
        );

        $this->add_control(
            'show_placeholder',
            [
                'label' => esc_html__('Placeholder', 'digits'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'digits'),
                'label_off' => esc_html__('No', 'digits'),
                'return_value' => 'true',
                'condition' => [
                    'floating_label' => '',
                ],
            ]
        );


        $this->add_control(
            'show_asterisk',
            [
                'label' => esc_html__('Show Asterisk', 'digits'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'digits'),
                'label_off' => esc_html__('No', 'digits'),
                'return_value' => 'true',
            ]
        );


        $this->add_control(
            'label_text_position',
            [
                'label' => esc_html__('Position', 'digits'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'left',
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'digits'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'digits'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'digits'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .minput label' => 'text-align: {{VALUE}};',
                ],
            ]
        );


        $this->start_controls_tabs('label_state');

        $this->start_controls_tab(
            'label_state_normal',
            [
                'label' => esc_html__('Normal', 'digits'),
            ]
        );


        $this->add_control(
            'label_color',
            [
                'label' => esc_html__('Text Color', 'digits'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} label' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .dig-label .current' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_label' => 'true',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'selector' => '{{WRAPPER}} .minput label',
                'condition' => [
                    'show_label' => 'true',
                ],
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'label_state_active',
            [
                'label' => esc_html__('Active', 'digits'),
            ]
        );


        $this->add_control(
            'label_active_color',
            [
                'label' => esc_html__('Text Color', 'digits'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .digits-active label' => 'color: {{VALUE}};',
                ],
                'condition' => [
                    'show_label' => 'true',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'label_active_typography',
                'selector' => '{{WRAPPER}} .digits-active label',

                'condition' => [
                    'show_label' => 'true',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->add_responsive_control(
            'label_spacing',
            [
                'label' => esc_html__('Spacing', 'digits'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'conditions' => [
                    'terms' => [
                        [
                            'name' => 'show_label',
                            'operator' => 'equal',
                            'value' => 'true',
                        ],
                        [
                            'name' => 'floating_label',
                            'operator' => 'equal',
                            'value' => '',
                        ],
                    ],
                ],

            ]
        );

        $this->end_controls_section();
    }

    private function register_section_field()
    {
        $this->start_controls_section(
            'section_style_field',
            [
                'label' => esc_html__('Field', 'digits'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('field_tabs');

        $this->start_controls_tab(
            'field_tab_value',
            [
                'label' => esc_html__('Value', 'digits'),
            ]
        );

        $this->add_control(
            'field_tab_color_value',
            [
                'label' => esc_html__('Text Color', 'digits'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .minput input, {{WRAPPER}} .minput textarea' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'field_tab_typography_value',
                'selector' => '{{WRAPPER}} .minput input, {{WRAPPER}} .minput textarea',

            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'field_tab_placeholder',
            [
                'label' => esc_html__('Placeholder', 'digits'),
            ]
        );

        $this->add_control(
            'field_tab_color_placeholder',
            [
                'label' => esc_html__('Text Color', 'digits'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .minput input::placeholder, {{WRAPPER}} .minput textarea::placeholder' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'field_tab_typography_placeholder',
                'selector' => '{{WRAPPER}} .minput input::placeholder, {{WRAPPER}} .minput textarea::placeholder',

            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_responsive_control(
            'field_padding',
            [
                'label' => esc_html__('Padding', 'digits'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .minput input, {{WRAPPER}} .minput textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'floating_label' => '',
                ],
            ]
        );

        $this->add_control(
            'fields_tabs_separator',
            [
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->start_controls_tabs('field_tabs_state');

        $this->start_controls_tab(
            'field_tab_state_normal',
            [
                'label' => esc_html__('Normal', 'digits'),
            ]
        );

        $this->add_control(
            'field_tab_background_color_normal',
            [
                'label' => esc_html__('Background Color', 'digits'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .minput input, {{WRAPPER}} .minput textarea' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'field_tab_border_normal',
                'selector' => '{{WRAPPER}} .minput input, {{WRAPPER}} .minput textarea',
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

        $this->add_responsive_control(
            'field_tab_border_radius_normal',
            [
                'label' => esc_html__('Border Radius', 'digits'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .minput input, {{WRAPPER}} .minput textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'field_tab_box_shadow_normal',
                'exclude' => [
                    'box_shadow_position',
                ],
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .minput input, {{WRAPPER}} .minput textarea',
            ]
        );

        $this->end_controls_tab();


        $this->start_controls_tab(
            'field_tab_state_focus',
            [
                'label' => esc_html__('Focus', 'digits'),
            ]
        );

        $this->add_control(
            'field_tab_background_color_focus',
            [
                'label' => esc_html__('Background Color', 'digits'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .minput input:focus, {{WRAPPER}} .minput textarea:focus' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'field_tab_border_focus',
                'selector' => '{{WRAPPER}} .minput input:focus, {{WRAPPER}} .minput textarea:focus',
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

        $this->add_responsive_control(
            'field_tab_border_radius_focus',
            [
                'label' => esc_html__('Border Radius', 'digits'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .minput input:focus, {{WRAPPER}} .minput textarea:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'field_tab_box_shadow_focus',
                'exclude' => [
                    'box_shadow_position',
                ],
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .minput input:focus, {{WRAPPER}} .minput textarea:focus',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->end_controls_section();
    }

    private function register_section_dropdown()
    {
        $this->start_controls_section(
            'section_style_dropdown',
            [
                'label' => esc_html__('DropDown', 'digits'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->start_controls_tabs('dropdown_tabs_state');

        $this->start_controls_tab(
            'dropdown_tab_state_normal',
            [
                'label' => esc_html__('Normal', 'digits'),
            ]
        );

        $this->add_control(
            'dropdown_tab_background_color_normal',
            [
                'label' => esc_html__('Background Color', 'digits'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .minput .digits-form-select .select2-selection--single' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'dropdown_tab_border_normal',
                'selector' => '{{WRAPPER}} .minput .digits-form-select .select2-selection--single',
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

        $this->add_responsive_control(
            'dropdown_tab_border_radius_normal',
            [
                'label' => esc_html__('Border Radius', 'digits'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .minput .digits-form-select .select2-selection--single' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'dropdown_tab_box_shadow_normal',
                'exclude' => [
                    'box_shadow_position',
                ],
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .minput .digits-form-select .select2-selection--single',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'dropdown_tab_state_focus',
            [
                'label' => esc_html__('Focus', 'digits'),
            ]
        );

        $this->add_control(
            'dropdown_tab_background_color_focus',
            [
                'label' => esc_html__('Background Color', 'digits'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .minput .digits-form-select.select2-container--open .select2-selection--single .select2-selection__rendered' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'dropdown_tab_border_focus',
                'selector' => '{{WRAPPER}} .minput .digits-form-select.select2-container--open .select2-selection--single',
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

        $this->add_responsive_control(
            'dropdown_tab_border_radius_focus',
            [
                'label' => esc_html__('Border Radius', 'digits'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .minput .digits-form-select.select2-container--open .select2-selection--single' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'dropdown_tab_box_shadow_focus',
                'exclude' => [
                    'box_shadow_position',
                ],
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .minput .digits-form-select.select2-container--open .select2-selection--single',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();


        $this->add_control(
            'dropdown_tab_color',
            [
                'label' => esc_html__('Text Color', 'digits'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .minput .digits-form-select .select2-selection--single .select2-selection__rendered' => 'color: {{VALUE}};'
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'dropdown_tab_typography',
                'selector' => '{{WRAPPER}} .minput .digits-form-select .select2-selection--single .select2-selection__rendered',

            ]
        );


        $this->add_responsive_control(
            'dropdown_padding',
            [
                'label' => esc_html__('Padding', 'digits'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .minput .digits-form-select .select2-selection--single' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    private function register_section_checkbox()
    {
        $this->start_controls_section(
            'section_style_checkbox',
            [
                'label' => esc_html__('Checkbox', 'digits'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'checkbox_row_spacing',
            [
                'label' => esc_html__('Row Spacing', 'digits'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .dig-custom-field-type-checkbox .dig_opt_mult' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .dig-custom-field-type-tac .dig_opt_mult' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'checkbox_spacing',
            [
                'label' => esc_html__('Spacing', 'digits'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .dig-custom-field-type-checkbox .dig_opt_mult .dig_input_wrapper div' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .dig-custom-field-type-tac .dig_opt_mult .dig_input_wrapper div' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',

                ],
                'condition' => [
                    'show_label' => 'true',
                ],
            ]
        );

        $this->add_responsive_control(
            'checkbox_size',
            [
                'label' => esc_html__('Size', 'digits'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .dig_login_rembe .dig_input_wrapper, {{WRAPPER}} .dig-custom-field-type-checkbox .dig_opt_mult_con .dig_input_wrapper' => 'padding-left: calc({{SIZE}}{{UNIT}} + 8px);line-height: calc({{SIZE}}{{UNIT}} + 2px);',
                    '{{WRAPPER}} .dig_login_rembe .dig_input_wrapper::before, {{WRAPPER}} .dig-custom-field-type-checkbox .dig_opt_mult_con .dig_input_wrapper::before' => 'top: calc(100% / 2 - {{SIZE}}{{UNIT}}/2);width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .dig-custom-field-type-tac .dig_input_wrapper' => 'padding-left: calc({{SIZE}}{{UNIT}} + 8px);line-height: calc({{SIZE}}{{UNIT}} + 2px);',
                    '{{WRAPPER}} .dig-custom-field-type-tac .dig_input_wrapper::before' => 'top: calc(100% / 2 - {{SIZE}}{{UNIT}}/2);width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',

                ],
            ]
        );

        $this->add_control(
            'checkbox_color',
            [
                'label' => esc_html__('Text Color', 'digits'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dig_login_rembe .dig_input_wrapper, {{WRAPPER}} .dig-custom-field-type-checkbox .dig_opt_mult_con .dig_input_wrapper' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .dig-custom-field-type-tac .dig_input_wrapper' => 'color: {{VALUE}};',

                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'checkbox_typography',
                'selector' => '{{WRAPPER}} .dig_login_rembe .dig_input_wrapper, {{WRAPPER}} .dig-custom-field-type-checkbox .dig_opt_mult_con .dig_input_wrapper, {{WRAPPER}} .dig-custom-field-type-tac .dig_input_wrapper',

            ]
        );
        $this->start_controls_tabs('checkbox_tabs_state');

        $this->start_controls_tab(
            'checkbox_tab_state_normal',
            [
                'label' => esc_html__('Normal', 'digits'),
            ]
        );

        $this->add_control(
            'checkbox_tab_background_color_normal',
            [
                'label' => esc_html__('Checkbox Color', 'digits'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dig_login_rembe .dig_input_wrapper::before, {{WRAPPER}} .dig-custom-field-type-checkbox .dig_opt_mult_con .dig_input_wrapper::before' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .dig-custom-field-type-tac .dig_input_wrapper::before' => 'background-color: {{VALUE}};',

                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'checkbox_tab_border_normal',
                'fields_options' => [
                    'width' => [
                        'label' => esc_html__('Border Width', 'digits'),
                    ],
                    'color' => [
                        'label' => esc_html__('Border Color', 'digits'),
                    ],
                ],
                'selector' => '{{WRAPPER}} .dig_login_rembe .dig_input_wrapper::before, {{WRAPPER}} .dig-custom-field-type-checkbox .dig_opt_mult_con .dig_input_wrapper::before, {{WRAPPER}} .dig-custom-field-type-tac .dig_input_wrapper::before',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'checkbox_tab_state_checked',
            [
                'label' => esc_html__('Checked', 'digits'),
            ]
        );

        $this->add_control(
            'checkbox_tab_background_color_checked',
            [
                'label' => esc_html__('Checkbox Color', 'digits'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dig_login_rembe .selected .dig_input_wrapper::before, {{WRAPPER}} .dig-custom-field-type-checkbox .dig_opt_mult_con .selected .dig_input_wrapper::before' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .dig-custom-field-type-tac .selected .dig_input_wrapper::before' => 'background-color: {{VALUE}};',

                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'checkbox_tab_border_checked',
                'fields_options' => [
                    'width' => [
                        'label' => esc_html__('Border Width', 'digits'),
                    ],
                    'color' => [
                        'label' => esc_html__('Border Color', 'digits'),
                    ],
                ],
                'selector' => '{{WRAPPER}} .dig_login_rembe .selected .dig_input_wrapper::before, {{WRAPPER}} .dig-custom-field-type-checkbox .dig_opt_mult_con .selected .dig_input_wrapper::before,{{WRAPPER}} .dig-custom-field-type-tac .selected .dig_input_wrapper::before',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    private function register_section_radio()
    {
        $this->start_controls_section(
            'section_style_radio',
            [
                'label' => esc_html__('Radio', 'digits'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'radio_row_spacing',
            [
                'label' => esc_html__('Row Spacing', 'digits'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .dig-custom-field-type-radio .dig_opt_mult' => 'margin-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        $this->add_responsive_control(
            'radio_spacing',
            [
                'label' => esc_html__('Spacing', 'digits'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .dig-custom-field-type-radio .dig_opt_mult .dig_input_wrapper div' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'condition' => [
                    'show_label' => 'true',
                ],
            ]
        );

        $this->add_responsive_control(
            'radio_size',
            [
                'label' => esc_html__('Size', 'digits'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}} .dig-custom-field-type-radio .dig_opt_mult_con .dig_input_wrapper' => 'padding-left: calc({{SIZE}}{{UNIT}} + 8px);line-height: calc({{SIZE}}{{UNIT}} + 2px);',
                    '{{WRAPPER}} .dig-custom-field-type-radio .dig_opt_mult_con .dig_input_wrapper::before' => 'top: calc(100% / 2 - {{SIZE}}{{UNIT}}/2);width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'radio_color',
            [
                'label' => esc_html__('Text Color', 'digits'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dig-custom-field-type-radio .dig_opt_mult_con .dig_input_wrapper' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'radio_typography',
                'selector' => '{{WRAPPER}} .dig-custom-field-type-radio .dig_opt_mult_con .dig_input_wrapper',

            ]
        );


        $this->start_controls_tabs('radio_tabs_state');

        $this->start_controls_tab(
            'radio_tab_state_normal',
            [
                'label' => esc_html__('Normal', 'digits'),
            ]
        );

        $this->add_control(
            'radio_tab_background_color_normal',
            [
                'label' => esc_html__('Radio Color', 'digits'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dig-custom-field-type-radio .dig_opt_mult_con .dig_input_wrapper::before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'radio_tab_border_normal',
                'fields_options' => [
                    'fields_options' => [
                        'width' => [
                            'label' => esc_html__('Border Width', 'digits'),
                        ],
                        'color' => [
                            'label' => esc_html__('Border Color', 'digits'),
                        ],
                    ],
                ],
                'selector' => '{{WRAPPER}} .dig-custom-field-type-radio .dig_opt_mult_con .dig_input_wrapper::before',
            ]
        );


        $this->end_controls_tab();

        $this->start_controls_tab(
            'radio_tab_state_checked',
            [
                'label' => esc_html__('Checked', 'digits'),
            ]
        );

        $this->add_control(
            'radio_tab_background_color_checked',
            [
                'label' => esc_html__('Radio Color', 'digits'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dig-custom-field-type-radio .dig_opt_mult_con .selected .dig_input_wrapper::before' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'radio_tab_border_checked',
                'fields_options' => [
                    'width' => [
                        'label' => esc_html__('Border Width', 'digits'),
                    ],
                    'color' => [
                        'label' => esc_html__('Border Color', 'digits'),
                    ],
                ],
                'selector' => '{{WRAPPER}} .dig-custom-field-type-radio .dig_opt_mult_con .selected .dig_input_wrapper::before',
            ]
        );


        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    private function register_section_signup_button()
    {
        if (!$this->is_login() || !$this->is_register()) {
            return;
        }

        $this->start_controls_section(
            'section_style_signup_button',
            [
                'label' => esc_html__('Signup', 'digits'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'signup_text',
            array(
                'label' => esc_html__('Signup Text', 'digits'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ));
        $this->add_control(
            'signup_text_color',
            [
                'label' => esc_html__('Text Color', 'digits'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .signdesc' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'signup_text_typography',
                'selector' => '{{WRAPPER}} .dig-elem .signdesc',

            ]
        );

        $this->add_responsive_control(
            'signup_text_position',
            [
                'label' => esc_html__('Position', 'digits'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'label_block' => false,
                'default' => 'center',
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'digits'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'digits'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'digits'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .signdesc' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'signup_text_spacing',
            [
                'label' => esc_html__('Spacing', 'digits'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .signdesc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_control(
            'signup_button_style',
            array(
                'label' => esc_html__('Signup Button', 'digits'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            )
        );

        $this->add_responsive_control(
            'signup_button_height',
            [
                'label' => esc_html__('Height', 'digits'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .dig-elem .signupbutton' => 'line-height: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'signup_button_spacing',
            [
                'label' => esc_html__('Spacing', 'digits'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .dig-elem .signupbutton' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('signup_button_tabs');

        $this->start_controls_tab(
            'signup_button_tab_normal',
            [
                'label' => esc_html__('Normal', 'digits'),
            ]
        );

        $this->add_control(
            'signup_button_tab_color_normal',
            [
                'label' => esc_html__('Text Color', 'digits'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dig-elem .signupbutton' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'signup_button_tab_typography_normal',
                'selector' => '{{WRAPPER}} .dig-elem .signupbutton',

            ]
        );

        $this->add_control(
            'signup_digits_background_normal',
            [
                'label' => esc_html__('Background Color', 'digits'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dig-elem .signupbutton' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'signup_button_tab_hover',
            [
                'label' => esc_html__('Hover', 'digits'),
            ]
        );

        $this->add_control(
            'signup_button_tab_color_hover',
            [
                'label' => esc_html__('Text Color', 'digits'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dig-elem .signupbutton:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'signup_button_tab_typography_hover',
                'selector' => '{{WRAPPER}} .dig-elem .signupbutton:hover',

            ]
        );

        $this->add_control(
            'signup_digits_background_hover',
            [
                'label' => esc_html__('Background Color', 'digits'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dig-elem .signupbutton:hover' => 'background: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'signup_button_border_heading',
            [
                'label' => esc_html__('Border', 'digits'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'signup_button_border',
                'selector' => '{{WRAPPER}} .dig-elem .signupbutton',
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

        $this->add_responsive_control(
            'signup_button_radius',
            [
                'label' => esc_html__('Border Radius', 'digits'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .dig-elem .signupbutton' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'signup_button_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .dig-elem .signupbutton',
            ]
        );

        $this->end_controls_section();
    }

    private function register_section_button()
    {
        $this->start_controls_section(
            'section_style_button',
            [
                'label' => esc_html__('Button', 'digits'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'button_height',
            [
                'label' => esc_html__('Height', 'digits'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 1000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .dig-elem .button' => 'line-height: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'button_spacing',
            [
                'label' => esc_html__('Spacing', 'digits'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .dig-elem .button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('button_tabs');

        $this->start_controls_tab(
            'button_tab_normal',
            [
                'label' => esc_html__('Normal', 'digits'),
            ]
        );

        $this->add_control(
            'button_tab_color_normal',
            [
                'label' => esc_html__('Text Color', 'digits'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dig-elem .button' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_tab_typography_normal',
                'selector' => '{{WRAPPER}} .dig-elem .button',

            ]
        );

        $this->add_control(
            'digits_background_normal',
            [
                'label' => esc_html__('Background Color', 'digits'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dig-elem .button' => 'background: {{VALUE}};',
                ],

            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'button_tab_hover',
            [
                'label' => esc_html__('Hover', 'digits'),
            ]
        );

        $this->add_control(
            'button_tab_color_hover',
            [
                'label' => esc_html__('Text Color', 'digits'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dig-elem .button:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'button_tab_typography_hover',
                'selector' => '{{WRAPPER}} .dig-elem .button:hover',

            ]
        );

        $this->add_control(
            'digits_background_hover',
            [
                'label' => esc_html__('Background Color', 'digits'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .dig-elem .button:hover' => 'background: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_control(
            'button_border_heading',
            [
                'label' => esc_html__('Border', 'digits'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'button_border',
                'selector' => '{{WRAPPER}} .dig-elem .button',
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

        $this->add_responsive_control(
            'button_radius',
            [
                'label' => esc_html__('Border Radius', 'digits'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'selectors' => [
                    '{{WRAPPER}} .dig-elem .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'button_box_shadow',
                'exclude' => [
                    'box_shadow_position',
                ],
                'separator' => 'before',
                'selector' => '{{WRAPPER}} .dig-elem .button',
            ]
        );

        $this->end_controls_section();
    }

    private function register_section_text()
    {
        $this->start_controls_section(
            'section_style_text',
            [
                'label' => esc_html__('Other Text', 'digits'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );
        $this->start_controls_tabs('text_tabs_state');

        $this->start_controls_tab(
            'text_tabs_state_normal',
            [
                'label' => esc_html__('Normal', 'digits'),
            ]
        );

        $this->add_control(
            'text_tabs_state_color_normal',
            [
                'label' => esc_html__('Text Color', 'digits'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}}, {{WRAPPER}} a, {{WRAPPER}} .dig_verify_code_text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'text_tabs_sate_typography_normal',
                'selector' => '{{WRAPPER}}, {{WRAPPER}} a, {{WRAPPER}} .dig_verify_code_text',
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'text_tabs_state_state_focus',
            [
                'label' => esc_html__('Hover', 'digits'),
            ]
        );

        $this->add_control(
            'text_tabs_state_color_hover',
            [
                'label' => esc_html__('Text Color', 'digits'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a:hover, {{WRAPPER}} .dig_verify_code_text:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'text_tabs_sate_typography_hover',
                'selector' => '{{WRAPPER}} a:hover, {{WRAPPER}} .dig_verify_code_text:hover',
            ]
        );

        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->end_controls_section();
    }

    private function register_section_error()
    {
        $this->start_controls_section(
            'section_advanced_error',
            [
                'label' => esc_html__('Required Field Error', 'digits'),
                'tab' => \Elementor\Controls_Manager::TAB_ADVANCED,
            ]
        );

        $this->add_control(
            'error_label_color',
            [
                'label' => esc_html__('Label Color', 'digits'),
                'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '#AA0000',
                'selectors' => [
                    '{{WRAPPER}} .minput.input-error label' => 'color: {{VALUE}};',
                ],
            ]
        );


        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'error_field_color',
                'selector' => '{{WRAPPER}} .minput.input-error input, {{WRAPPER}} .minput.input-error textarea, {{WRAPPER}} .minput.input-error .digits-form-select .select2-selection--single',
                'fields_options' => [
                    'border' => [
                        'label' => esc_html__('Field Border', 'digits'),
                        'default' => 'solid'
                    ],
                    'width' => [
                        'label' => esc_html__('Border Width', 'digits'),
                        'default' => [
                            'top' => '1',
                            'right' => '1',
                            'bottom' => '1',
                            'left' => '1',
                            'isLinked' => true,
                        ],
                    ],
                    'color' => [
                        'label' => esc_html__('Border Color', 'digits'),
                        'default' => '#AA0000',
                    ],
                ],
            ]
        );

        $this->end_controls_section();
    }
}
