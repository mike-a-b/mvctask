<?php
    function exlog_hash_password($password, $no_salt = false, $user_specific_salt = false) {
        $algorithm = exlog_get_option("external_login_option_hash_algorithm");
        $salt_location = exlog_get_option("external_login_option_db_salt_location");
        if ($user_specific_salt) {
            $salt = $user_specific_salt;
        } else {
            $salt = exlog_get_option("external_login_option_db_salt");
        }

        if ($no_salt) {
            return hash($algorithm, $password);
        } else if ($salt_location == "before") {
            return hash($algorithm, $salt . $password);
        } elseif ($salt_location == "after") {
            return hash($algorithm, $password . $salt);
        } else {
            return hash($algorithm, $password);
        }
    }

    function exlog_validate_password($password, $hash, $user_specific_salt) {
        $salt_method = exlog_get_option("external_login_option_db_salting_method");
        $algorithm = exlog_get_option("external_login_option_hash_algorithm");

        $hash = exlog_should_lowercase_hex_hash($algorithm, $hash);

        if ($algorithm == "none") {
            return $password == $hash;
        } else if ($algorithm == "phpass") {
            return wp_check_password($password, $hash);
        } else if ($algorithm == "phpcrypt") {
            return crypt($password, $hash) == $hash;
        } else if ($algorithm == "bcrypt") {
            return password_verify($password, $hash);
        } else {
            if ($salt_method == 'none') {
                return exlog_hash_password($password, true) == $hash;
            } else if ($salt_method == 'all') {
                return exlog_hash_password($password, false, $user_specific_salt) == $hash;
            } else {
                return exlog_hash_password($password) == $hash;
            }
        }
    }

//    Because a hash represented in hexidecimal could be represented in lower case or upper,
//    make it compatible with PHPs lowercase system
    function exlog_should_lowercase_hex_hash($algorithm, $hash) {
//        Case sensitive hashes and when no hash is used
            if ($algorithm == "bcrypt" || $algorithm == "phpass" || $algorithm == "phpcrypt" || $algorithm == "none") {
            return $hash;
//        Hex hashes that can be lower or upper case
        } else {
            return strtolower($hash);
        }

    }
