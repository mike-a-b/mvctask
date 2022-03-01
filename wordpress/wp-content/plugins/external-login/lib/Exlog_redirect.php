<?php

class Exlog_redirect {
    private $internal_option = 'local';
    private $external_option = 'external';

    public function exlog_setup_redirection()  {
        $redirection_option = exlog_get_option('external_login_option_redirection_type');

        if ($redirection_option == $this->external_option || $redirection_option == $this->internal_option) {
            if ($redirection_option == $this->external_option) {
                $redirect_value = exlog_get_option('external_login_option_redirection_location_external');

                if (strpos($redirect_value, '//') === false) {
                    $redirect_value = '//' . $redirect_value;
                }

                if(!($this->exlog_allow_external_redirect($redirect_value))) {
                    return false;
                }
            } else {
                $redirect_value = exlog_get_option('external_login_option_redirection_location_internal');
            }
            $this->exlog_register_redirect($redirect_value);
        }
    }

    private function exlog_allow_external_redirect($external_url) {
        $host = parse_url($external_url)['host'];
        if ($host) {
            add_filter('allowed_redirect_hosts', function (Array $hosts, $check) use ($host) {
                array_push($hosts, $host);
                return $hosts;
            }, 10, 2);
            return true;
        } else {
            error_log('EXLOG: Unable to redirect to external URL as host could not be determined.');
            return false;
        }
    }

    private function exlog_register_redirect($redirect_value) {
        add_filter('login_redirect', function () use ($redirect_value) {
            return $redirect_value;
        });
    }
}