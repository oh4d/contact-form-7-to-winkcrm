<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class CF7_To_WinkCrm_Api
{
    protected $credentialsToken;

    public function __construct($credentialsToken = null)
    {
        if (!$credentialsToken){
            wp_die('Error Initializing WinkCrm API Credentials');
        }

        $this->credentialsToken = $credentialsToken;
    }

    public function import_contacts($data)
    {
        $url = CF7_TO_WINKCRM_API_URL . CF7_TO_WINKCRM_AT_ENDPOINT_CONTACT_IMPORT;

        $data_string = '';
        if($data)
            $data_string = json_encode($data);

        $args = array(
            'headers' => array(
                'X_PROJECT_TOKEN' => $this->credentialsToken,
                'Content-Type' => 'application/json; charset=utf-8',
                'Content-Length' => strlen($data_string)
            ),
            'method' => 'POST',
            'body' => $data_string,
            'timeout' => '5',
            'redirection' => '5',
            'httpversion' => '1.0',
            'blocking' => true,
            'cookies' => array()
        );

        $response = wp_remote_post( $url, $args );
        $body = wp_remote_retrieve_body( $response );

        return $body;
    }
}