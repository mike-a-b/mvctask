<?php

if (!defined('ABSPATH')) {
    exit;
}


function digbuilder_get_digits_fields()
{
    $reg_custom_fields = stripslashes(base64_decode(get_option("dig_reg_custom_field_data", "e30=")));
    $reg_custom_fields = json_decode($reg_custom_fields, true);

    $dig_reg_details = digit_get_reg_fields();

    $default_fields = array();
    $basic_fields = array(
        'dig_reg_name' => array('type' => 'wp_predefined', 'field_wp_type' => 'first_name', 'label' => esc_attr__('First Name', 'digits')),
        'dig_reg_uname' => array('type' => 'username'),
        'dig_reg_mobilenumber' => array('type' => 'mobile_number'),
        'dig_reg_email' => array('type' => 'email'),
        'dig_reg_password' => array('type' => 'password')
    );
    if ($dig_reg_details['dig_reg_mobilenumber'] == 1 && $dig_reg_details['dig_reg_email'] == 1) {
        unset($dig_reg_details['dig_reg_email']);
        $basic_fields['dig_reg_mobilenumber'] = array('type' => 'mobmail');
    }

    foreach ($basic_fields as $key => $basic_field) {
        $is_optional = $dig_reg_details[$key];
        if ($is_optional == 0) continue;

        $required = $is_optional == 2 ? 'true' : '';
        $basic_field['required'] = $required;
        $default_fields[] = $basic_field;
    }

    $digit_fields = digbuilder_digits_field_categories();
    $wp_predefined = $digit_fields['wp_predefined'];
    $wc_predefined = $digit_fields['wc_predefined'];

    foreach ($reg_custom_fields as $label => $values) {
        $type = strtolower($values['type']);
        $meta_key = cust_dig_filter_string($values['meta_key']);
        $custom_field = array();

        $custom_field['label'] = $label;
        if (in_array($type, $wp_predefined)) {
            $custom_field['type'] = 'wp_predefined';
            $custom_field['field_wp_type'] = $meta_key;
        } else if (in_array($type, $wc_predefined)) {
            $custom_field['type'] = 'wc_predefined';
            $custom_field['field_wc_type'] = $meta_key;
        } else {
            $custom_field['type'] = 'custom';
            $custom_field['sub_type'] = $type;
        }

        $custom_field['meta_key'] = $meta_key;
        if (!empty($values['options'])) {
            $custom_field['field_options'] = implode("\n", dig_sanitize_options($values['options']));
        }
        $custom_field['required'] = $values['required'] == 1 ? 'true' : '';
        $default_fields[] = $custom_field;
    }

    return $default_fields;
}


/*
 * 'username'      => __( 'Username', 'digits' ),
			'mobmail'       => __( 'Email/Mobile Number', 'digits' ),
			'mobile_number' => __( 'Mobile Number', 'digits' ),
			'email'         => __( 'Email', 'digits' ),
			'password'      => __( 'Password', 'digits' ),
*/
function digbuilder_show_elem_fields($settings, $button_texts, $fields, $show_asterisk, $widget_type)
{
    $elem_reg_fields = $settings['fields'];
    $userCountryCode = getUserCountryCode();
    $theme = 'dark';
    $bgtype = 'bgdark';
    $themee = 'lighte';
    $bgtransbordertype = "bgtransborderdark";

    $default_reg_fields = digpage_get_registration_fields_data($elem_reg_fields, 2);

    $usernameaccep = $default_reg_fields['dig_reg_uname'];
    $mobileaccp = $default_reg_fields['dig_reg_mobilenumber'];
    $emailaccep = $default_reg_fields['dig_reg_email'];
    $passaccep = $default_reg_fields['dig_reg_password'];

    if($emailaccep==1 && $mobileaccp==0){
        $emailaccep = 2;
    }
    if($emailaccep==0 && $mobileaccp==1){
        $mobileaccp = 2;
    }
    if($mobileaccp==0 && $passaccep==1){
        $passaccep = 2;
    }

    ?>
    <div class="digits_fields_wrapper digits_register_fields">
        <?php
        foreach ($fields as $field_key => $field) {
            $type = strtolower($field['type']);
            if ($type == 'username') {
                ?>

                <div id="dig_cs_username" class="minput">
                    <div class="minput_inner">
                        <div class="digits-input-wrapper">
                            <input type="text" name="digits_reg_username" id="digits_reg_username"
                                   value="<?php if (isset($username)) {
                                       echo $username;
                                   } ?>" <?php if ($usernameaccep == 1) {
                                echo "required";
                            } ?>/>
                        </div>
                        <label><?php _e("Username", "digits"); ?></label>
                        <span class="<?php echo $bgtype; ?>"></span>
                    </div>
                </div>
                <?php
                unset($fields['Username']);
            } else if ($type == 'password') {
                ?>
                <div id="dig_cs_password" class="minput" <?php if ($passaccep == 1) {
                    echo 'style="display: none;"';
                } ?>>
                    <div class="minput_inner">
                        <div class="digits-input-wrapper">
                            <input type="password" name="digits_reg_password"
                                   class="digits_reg_password" <?php if ($passaccep == 2) {
                                echo "required";
                            } ?>/>
                        </div>
                        <label><?php _e("Password", "digits"); ?></label>
                        <span class="<?php echo $bgtype; ?>"></span>
                    </div>
                </div>

                <?php
            } else if (in_array($type, array('mobmail', 'mobile_number', 'email'))) {

                $data_accept = 1;

                if ($emailaccep == 1 && $mobileaccp == 1 && $type != 'mobile_number') {
                    $emailmob = __("Email/Mobile Number", "digits");
                } else if ($mobileaccp > 0 || $type == 'mobile_number') {
                    $data_accept = 2;
                    $emailmob = __("Mobile Number", "digits");
                } else if ($emailaccep > 0) {
                    $data_accept = 3;
                    $emailmob = __("Email", "digits");
                }

                $reqoropt = '';
                if ($type != 'email') {
                    ?>
                    <div id="dig_cs_mobilenumber" class="minput">
                        <div class="minput_inner">
                            <div class="countrycodecontainer registercountrycodecontainer">
                                <input type="text" name="digregcode"
                                       class="input-text countrycode registercountrycode  <?php echo $theme; ?>"
                                       value="<?php echo $userCountryCode; ?>" maxlength="6" size="3"
                                       placeholder="<?php echo $userCountryCode; ?>" <?php if ($emailaccep == 2 || $mobileaccp == 2) {
                                    echo 'required';
                                } ?> autocomplete="none"/>
                            </div>

                            <div class="digits-input-wrapper">
                                <input type="text" class="mobile_field mobile_format digits_reg_email"
                                       name="digits_reg_mail"
                                       data-type="<?php echo $data_accept; ?>"
                                       value="<?php if (isset($mob) || $emailaccep == 2 || $mobileaccp == 2) {
                                           if ($mobileaccp == 1) {
                                               $reqoropt = "(" . __("Optional", 'digits') . ")";
                                           }

                                       } else if (isset($mail)) {
                                           echo $mail;
                                       } ?>" <?php if (empty($reqoropt))
                                    echo 'required' ?>/>
                            </div>

                            <label><?php if ($emailaccep == 2 && $mobileaccp == 2) {
                                    echo __('Mobile Number', 'digits');
                                } else {
                                    echo $emailmob;
                                } ?><?php echo $reqoropt; ?></label>
                            <span class="<?php echo $bgtype; ?>"></span>
                        </div>
                    </div>

                    <?php
                }


                if (($emailaccep > 0 || $mobileaccp > 0) && $type != 'mobile_number') {

                    $emailmob = __('Email/Mobile Number', 'digits');

                    $reqoropt = "";
                    if ($type != 'email') {
                        if ($emailaccep == 1) {
                            $reqoropt = "(" . __("Optional", 'digits') . ")";
                        }
                    }
                    if ($emailaccep == 2 || $mobileaccp == 2 || $type == 'email') {
                        $emailmob = __('Email', 'digits');

                    }

                    ?>
                    <div id="dig_cs_email"
                         class="minput dig-mailsecond" <?php if ($emailaccep != 2 && $mobileaccp != 2 && $type != 'email') {
                        echo 'style="display: none;"';
                    } else echo 'data-always-show="true"'; ?>>
                        <div class="minput_inner">
                            <div class="countrycodecontainer secondregistercountrycodecontainer">
                                <input type="text" name="digregscode2"
                                       class="input-text countrycode registersecondcountrycode  <?php echo $theme; ?>"
                                       value="<?php echo $userCountryCode; ?>" maxlength="6" size="3"
                                       placeholder="<?php echo $userCountryCode; ?>" autocomplete="none"/>
                            </div>
                            <div class="digits-input-wrapper">
                                <input type="text" class="mobile_field mobile_format dig-secondmailormobile"
                                       name="mobmail2"
                                       data-mobile="<?php echo $mobileaccp; ?>"
                                       data-mail="<?php echo $emailaccep; ?>"
                                    <?php if ($emailaccep == 2) {
                                        echo "required";
                                    } ?> />
                            </div>
                            <label><span
                                        class="dig_secHolder"><?php echo $emailmob; ?></span> <?php echo $reqoropt; ?>
                            </label>
                            <span class="<?php echo $bgtype; ?>"></span>
                        </div>
                    </div>
                    <?php

                }

            } else {

                dig_show_fields(array($field_key => $field), $show_asterisk, 1, 'bgdark', 0);
            }
        }
        ?>

        <input type="hidden" name="code" class="register_code"/>
        <input type="hidden" name="csrf" class="register_csrf"/>
        <input type="hidden" name="dig_reg_mail" class="dig_reg_mail">
        <input type="hidden" name="dig_nounce" class="dig_nounce"
               value="<?php echo wp_create_nonce('dig_form') ?>">
        <?php
        if ($mobileaccp > 0) {
            ?>
            <div class="minput dig_register_otp" style="display: none;">
                <div class="minput_inner">
                    <div class="digits-input-wrapper">
                        <input type="text" name="dig_otp" id="dig-register-otp"
                               value="<?php if (isset($_POST['dig_otp'])) {
                                   echo dig_filter_string($_POST['dig_otp']);
                               } ?>"/>
                    </div>
                    <label><?php echo $button_texts['dig_signup_otp_text']; ?></label>
                    <span class="<?php echo $bgtype; ?>"></span>
                </div>
            </div>

            <?php
        }

        ?>
    </div>
    <div class="dig_spacer"></div><?php

    echo '<input type="hidden" class="digits_form_reg_fields" value="' . esc_html__(json_encode($default_reg_fields)) . '" />';

    if ($mobileaccp > -1 || $passaccep > -1) {
        if (($passaccep == 0 && $mobileaccp == 0) || $passaccep == 2 || ($passaccep == 0 && $mobileaccp > 0)) {
            $subVal = $button_texts['dig_signup_button_text'];
        } else {
            $subVal = $button_texts['dig_signup_via_otp'];
        }
        ?>

        <button class="<?php echo $themee . ' ' . $bgtype; ?> button dig-signup-otp registerbutton"
                value="<?php echo $subVal; ?>" type="submit"><?php echo $subVal; ?></button>
        <?php if (dig_isWhatsAppEnabled()) { ?>
            <button class="<?php echo $themee . ' ' . $bgtype; ?> button dig-signup-otp registerbutton dig_use_whatsapp"
                    value="<?php echo $subVal; ?>" type="submit">
                <?php _e('Signup With WhatsApp', 'digits'); ?>
            </button>
            <?php
        }
        ?>
        <?php echo "<div  class=\"dig_resendotp dig_logof_reg_resend\" id=\"dig_lo_resend_otp_btn\" dis='1'>" . $button_texts['dig_signup_resend_otp'] . " <span>(00:<span>" . dig_getOtpTime() . "</span>)</span></div>"; ?>

        <input type="hidden" class="dig_submit_otp_text"
               value="<?php echo $button_texts['dig_signup_submit_otp']; ?>"/>
    <?php } ?>

    <?php if ($passaccep == 1) {
    $signup_pass_text = $button_texts['dig_signup_via_password'];
    ?>
    <button class="dig_reg_btn_password <?php echo $themee . ' ' . $bgtype; ?> button registerbutton"
            attr-dis="1"
            value="<?php echo $signup_pass_text; ?>" type="submit">
        <?php echo $signup_pass_text; ?>
    </button>

<?php }

    $redirect_to = isset($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : '';
    ?>

    <input type="hidden" name="digits_redirect_page"
           value="<?php echo esc_attr($redirect_to); ?>"/>
    <?php
    if ($widget_type != 2) {
        ?>
        <div class="backtoLoginContainer"><a
                    class="backtoLogin"><?php _e("Back to login", "digits"); ?></a>
        </div>
        <?php
    }
}

function digbuilder_find_element_recursive($data, $form_id)
{
    foreach ($data as $element) {
        if ($form_id == $element['id']) {
            return $element;
        }

        if (!empty($element['elements'])) {
            $element = digbuilder_find_element_recursive($element['elements'], $form_id);

            if ($element) {
                return $element;
            }
        }
    }

    return false;
}