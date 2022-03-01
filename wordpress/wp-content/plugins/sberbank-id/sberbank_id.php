<?php
/*
Plugin Name: Сбер ID
Description: Сбер ID - единый аккаунт для доступа к сервисам Сбер и наших партнеров! Для заключения договора оставьте  <a href="https://www.sberbank.ru/ru/person/dist_services/sberbankid/forbusiness">заявку</a>.
Author: Сбер ID
Author URI: http://www.rbspayment.ru/
Version: 1.0.1
License: GPL2
*/



session_start();

include_once( dirname( __FILE__ ) . '/sberbank_id_config.php' );

add_filter('plugin_row_meta', 'sberbank_id_register_plugin_links', 10, 2);
function sberbank_id_register_plugin_links($links, $file)
{
    $base = plugin_basename(__FILE__);
    if ($file == $base) {
        $links[] = '<a href="admin.php?page=sberbank_id">' . __('Настройки', 'sberbank_id') . '</a>';
    }
    return $links;
}


add_action( 'admin_init', 'sberbank_id_activation_date' );

function sberbank_id_activation_date() {
	$date = get_option( 'sberbank_id_activation_date' );

	if ( ! $date ) {
		$date = date( 'Y-m-d H:i:s', current_time( 'timestamp' ) );
		add_option( 'sberbank_id_activation_date', $date );
	}

	return $date;
}

register_activation_hook(__FILE__, 'sberbank_id_activate');

function sberbank_id_activate()
{
    $use_smilies = get_option('use_smilies');
    if ($use_smilies) {
        update_option('use_smilies', false);
    }
}


class sberbank_id  {

    private $PLUGIN_VERSION = '1.0';
    private $sberbank_id;
    function __construct() {
        require_once( SBERBANK_ID_PLUGIN_DIR . '/lib/handler.php' );
        $this->sberbank_id = new Sberbank\sberbank_id_handler();
        $this->add_actions();
    }

    protected function get_default_options() {
        return Array(
            'enabled' => 'off',
            'module_version' => $this->PLUGIN_VERSION, 
            'client_id' => '', 
            'client_secret' => '', 
            'sert_password' => '',
            'pkce' => 'on',
            'web_to_app' => 'on',
            'scopes' => array('name' => 'on', 'email' => 'on', 'phone' => 'off'),
            'button_size' => 'MEDIUM',
            'button_form' => 'BOX',
            'button_style' => 'GREEN',
        );
    }

    public function wp_admin_menu()
    {
        add_menu_page(
            'Сбер ID',
            'Сбер ID',
            'manage_options',
            'sberbank_id',
            array($this, 'viewAdminPageSettings'),
            plugins_url('img/admin-logo.png', __FILE__), '99.026345');

    }
    public function wp_admin_init() {
        register_setting( 'sberbank_id_options', 'sberbank_id');
    }

    public function wp_get_plugin_options() {
        $default_options = $this->get_default_options();
        $options = get_site_option('sberbank_id', Array());
        $result = $default_options;
        foreach ($options as $key => $value) {
          if(is_array($value)) {
            foreach ($value as $k => $v) {
              $result[$key][$k] = $v;
            }
          } else {
            $result[$key] = $value;
          }
        }
        return $result;
    }
    public function viewAdminPageSettings() {
        if(isset($_POST['sberbank_id'])) {
          $post_data = array(
            'enabled' => sanitize_text_field($_POST['sberbank_id']['enabled']),
            'client_id' => sanitize_text_field($_POST['sberbank_id']['client_id']),
            'client_secret' => sanitize_text_field($_POST['sberbank_id']['client_secret']),
            'pkce' => sanitize_text_field($_POST['sberbank_id']['pkce']),
            'web_to_app' => sanitize_text_field($_POST['sberbank_id']['web_to_app']),
            'button_size' => sanitize_text_field($_POST['sberbank_id']['button_size']),
            'button_form' => sanitize_text_field($_POST['sberbank_id']['button_form']),
            'button_style' => sanitize_text_field($_POST['sberbank_id']['button_style']),
            'sert_password' => sanitize_text_field($_POST['sberbank_id']['sert_password']),
          );
          if(isset($_POST['sberbank_id']['scopes'])) {
            $post_data['scopes']['email'] = sanitize_text_field($_POST['sberbank_id']['scopes']['email']);
            $post_data['scopes']['name'] = sanitize_text_field($_POST['sberbank_id']['scopes']['name']);
            $post_data['scopes']['phone'] = sanitize_text_field($_POST['sberbank_id']['scopes']['phone']);
          }
          update_option('sberbank_id', $post_data);
        }
        if(isset($_FILES['sertificate']) && $_FILES['sertificate']['size'] > 0) {
          $file_parts = pathinfo($_FILES['sertificate']['name']);
          if($file_parts['extension'] == 'p12') {       
            $this->sberbank_id->setSertificatePassword($post_data['sert_password']);
            $this->sberbank_id->sertificate_crop($_FILES['sertificate']['tmp_name']);
          } else {
            $sert_upload_error = true;
          }
        }

        $options = $this->wp_get_plugin_options();

        require( SBERBANK_ID_PLUGIN_DIR . '/templates/admin_settings.php');
    }
    public function viewLoginPage() {
      $options = $this->wp_get_plugin_options();
      if($options['enabled'] != 'on') {
        return;
      }
      $scopes = array();

      if($options['scopes']) {
        foreach ($options['scopes'] as $key => $value) {
          if($value == 'on') {
            $scopes[] = $key;
          }
        }
      }
      $scopes = 'openid+' . implode("+", $scopes);

      $this->sberbank_id->setClientId($options['client_id']);
      $this->sberbank_id->setClientSecret($options['client_secret']);
      $this->sberbank_id->setSertificatePassword($options['sert_password']);
      $this->sberbank_id->setProperty('pkce',$options['pkce'] == 'on' ? true : false);
      $this->sberbank_id->setProperty('scope',$scopes);
      
      $sberbank_id_url = $this->sberbank_id->redirect_to_auth_url();
      
      $_SESSION['sberbankid_code_verifier'] = $this->sberbank_id->getProperty('code_verifier');
      
      ob_start(); 
      require( SBERBANK_ID_PLUGIN_DIR . '/templates/wp_login_button.php');
      $out = ob_get_clean(); 
      echo $out;
    }

    public function auth_request() {
      if(!isset($_REQUEST['oauth'])) {
        return;
      }
      if(isset($_REQUEST['oauth']) && $_REQUEST['oauth'] !== 'sberbank_id') {
        return;
      }

      $options = $this->wp_get_plugin_options();
      $this->sberbank_id->setClientId($options['client_id']);
      $this->sberbank_id->setClientSecret($options['client_secret']);
      $this->sberbank_id->setSertificatePassword($options['sert_password']);

      $this->sberbank_id->setProperty('code_verifier', isset($_SESSION['sberbankid_code_verifier']) ? $_SESSION['sberbankid_code_verifier'] : false );
      unset($_SESSION['sberbankid_code_verifier']);


      if(isset($_REQUEST['error'])) {
          $this->sberbank_id->authSetError(array(
              'METHOD' => 'AUTH_REDIRECT',
              'REQUEST_DATA' => $_REQUEST, 
          ));
      } else if(isset($_REQUEST['code'])) {
          $this->sberbank_id->setProperty( 'code', $_REQUEST['code'] );
      } else if($_REQUEST['login'] == 1) {

        $auth = wp_authenticate( sanitize_email($_POST['email']), $_POST['password'] );
        if(is_wp_error( $auth )) {
            $this->wp_show_login_form(array(
            'email' => sanitize_email($_POST['email']),
            'phone' => isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '',
            'username' => sanitize_text_field($_POST['username']),
            'sitename' => sanitize_text_field($_POST['sitename']),
            'auth_error' => 1,
            'sberbank_user' => [
              'phone' => sanitize_text_field($_POST['sberbank_user_phone']),
              'sub' => sanitize_text_field($_POST['sberbank_user_sub']),
              'aud' => sanitize_text_field($_POST['sberbank_user_aud']),
              'family_name' => sanitize_text_field($_POST['sberbank_user_family_name']),
              'given_name' => sanitize_text_field($_POST['sberbank_user_given_name']),
              'middle_name' => sanitize_text_field($_POST['sberbank_user_middle_name']),
            ],
          ));
        } else {
          update_user_meta($auth->ID, 'sberbank_id_sub',  $_POST['sberbank_user_sub']);
          update_user_meta($auth->ID, 'sberbank_id_aud',  $_POST['sberbank_user_aud']);
          $this->wp_user_auth_finish( $auth->ID);
        }
        die;
      } else {
        return;
      }

      $this->sberbank_id->reqGetAccessToken();
      $this->sberbank_id->reqGetUserInfo();
      $sberbankUser = $this->sberbank_id->getUserinfo();

        
      // $sberbankUser['email'] = 'test@test.ru'; 
      if(!isset($sberbankUser['email'])) { 
          $this->sberbank_id->authSetError(array(
              'METHOD' => 'EMAIL_IS_EMPTY',
              'USER_DATA' => $sberbankUser, 
          ));
      }
      if($this->sberbank_id->authSuccess()) {
          
          $wp_user = false;

          if(!email_exists( $sberbankUser['email'])) {
            $wp_user = $this->wp_create_user($sberbankUser);
            if ($wp_user) {
              $this->wp_user_auth_finish($wp_user);
            }
          } else {
            $wp_user = get_user_by( 'email', $sberbankUser['email'] );
            $wp_user_sub = get_user_meta($wp_user->ID, 'sberbank_id_sub');
            if($wp_user_sub && $wp_user_sub[0] == $sberbankUser['sub']){
              $this->wp_user_auth_finish($wp_user->ID);
            }
            else {
              $wp_userdata = get_userdata($wp_user->ID);
              $this->wp_show_login_form(array(
                'email' => $wp_userdata->user_email,
                'username' => $wp_userdata->user_login,
                'sitename' => $_SERVER['HTTP_HOST'],
                'auth_error' => isset($_REQUEST['auth_error']) ? $_POST['auth_error'] : false,
                'sberbank_user' => $sberbankUser,
              ));
            }
          }
        } else {
          $this->sberbank_id->logger();
          $this->sberbank_id->showError();
        } 
      die;

    }
    private function wp_create_user($sberbankUser) {
      $user_data = array();
      
      $user_data['first_name'] = $sberbankUser['given_name'];
      $user_data['last_name'] = $sberbankUser['family_name'];
      $user_data['display_name'] = $sberbankUser['family_name'] . ' ' . $sberbankUser['given_name'];
      $user_data['user_email'] = $sberbankUser['email'];
      $user_data['user_pass'] = wp_generate_password();

      if (!empty($sberbankUser['email']) && preg_match('/^(.+)\@/i', $sberbankUser['email'], $nickname)) {
        $user_data['user_nicename'] = $user_data['nickname'] = $nickname[1];
      } else {
        $user_data['user_nicename'] = "sberid_" . substr($sberbankUser['sub'], 0,6);
      }
      $user_data['user_login'] = "sberid_" . substr($sberbankUser['sub'], 0,6);
      
      $wp_user = wp_insert_user($user_data);
      if ( !is_wp_error($wp_user) && is_int($wp_user) ) {
        update_user_meta($wp_user, 'sberbank_id_sub', $sberbankUser['sub']);
        update_user_meta($wp_user, 'sberbank_id_aud', $sberbankUser['aud']);

      }
        
      return $wp_user;
    }
    private function wp_user_auth_finish($wp_user) {
       wp_set_auth_cookie($wp_user, true, false);
       wp_set_current_user($wp_user);
       wp_redirect('/');
    }
 
    public function wp_show_login_form($data) {
      $data['entry__page_title'] = 'Sberbank ID Авторизация';
      $data['entry_title'] = 'Вы успешно вошли по Сбер ID.<br>Мы нашли вашу учетную запись на сайте<br>';
      $data['entry_footer'] = 'Вы можете привязать ee к аккаунту Сбер ID. Для этого подтвердите эту учетную запись <b>паролем</b>';
      $data['entry_password_error'] = 'Ошибка, поле пароль заполнено неверно!';
      $data['entry_text_auth'] = 'Авторизоваться';
      $data['entry_text_return'] = 'Вернуться на сайт';
      require( SBERBANK_ID_PLUGIN_DIR . '/templates/login_form.php');
    }

    public function add_actions() {
        add_action('admin_menu', array($this, 'wp_admin_menu'));
        add_action('admin_init', array($this, 'wp_admin_init'), 5, 0);
        add_action('login_form', array($this,'viewLoginPage'));
        add_action('parse_request', array($this, 'auth_request')); 
    }
}

$sber_id = new sberbank_id();