<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if (!function_exists('cf7_to_winkcrm_test_auth')) {
    /**
     * @param string $credentialsToken
     * @return array
     */
    function cf7_to_winkcrm_test_auth($credentialsToken)
    {
        return CF7_To_WinkCrm_Api::check_auth_token($credentialsToken);
    }
}

if (!function_exists('cf7_to_winkcrm_new_lead')) {
    /**
     * @param string $credentialsToken
     * @param array $data
     * @return array
     */
    function cf7_to_winkcrm_new_lead($credentialsToken, $data)
    {
        return CF7_To_WinkCrm_Api::import_lead($credentialsToken, $data);
    }
}