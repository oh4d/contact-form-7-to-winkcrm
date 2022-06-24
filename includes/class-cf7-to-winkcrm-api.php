<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class CF7_To_WinkCrm_Api
{
    private static $credentialsToken;

    /**
     * @param null $credentialsToken
     * @throws Exception
     */
    private static function initialize($credentialsToken = null)
    {
        if (!$credentialsToken){
            throw new \Exception(__('Error Initializing WinkCrm API Credentials'));
        }

        self::$credentialsToken = $credentialsToken;
    }

    /**
     * Check Auth Token
     *
     * @param $credentialsToken
     * @return array|string
     */
    public static function check_auth_token($credentialsToken)
    {
        try
        {
            self::initialize($credentialsToken);

            $url = CF7_TO_WINKCRM_CHECK_AUTH;
            return self::send($url, 'get');
        }
        catch (\Exception $e)
        {
            return $e->getMessage();
        }
    }

    /**
     * Import New Lead
     *
     * @param $credentialsToken
     * @param $data
     * @return array|string
     */
    public static function import_lead($credentialsToken, $data)
    {
        try
        {
            self::initialize($credentialsToken);

            $url = CF7_TO_WINKCRM_NEW_LEAD;
            return self::send($url, 'post', $data);
        }
        catch (\Exception $e)
        {
            return $e->getMessage();
        }
    }

    /**
     * Send Request To API
     *
     * @param $url
     * @param $method
     * @param null $params
     * @return array
     */
    private static function send($url, $method, $params = null)
    {
        $params_string = '';

        if ($params)
            $params_string = json_encode($params);

        $args = array(
            'headers' => array(
                'X-PROJECT-TOKEN' => self::$credentialsToken,
                'Content-Type' => 'application/json; charset=utf-8',
                'Content-Length' => strlen($params_string)
            ),
            'method' => strtoupper($method),
            'body' => $params_string,
            'timeout' => '5',
            'redirection' => '5',
            'httpversion' => '1.0',
            'blocking' => true,
            'cookies' => array()
        );

        $response = wp_remote_post($url, $args);
        $body = wp_remote_retrieve_body($response);

        return ['body' => json_decode($body, true), 'response' => $response];
    }
}
