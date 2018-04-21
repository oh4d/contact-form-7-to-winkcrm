<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class CF7_To_WinkCrm_Actions
{
    /**
     * CF7_To_WinkCrm_Actions constructor.
     */
    public function __construct()
    {
        add_action('wpcf7_before_send_mail', array($this, 'cf7_lead_post'));
    }

    /**
     * @param $cf7
     */
    public function cf7_lead_post($cf7)
    {
        $submission = WPCF7_Submission::get_instance();
        $token = get_post_meta($cf7->id(),'winkcrm_token_id', true);

        if (!$token)
            return;

        $data = $this->clean_private_keys($submission->get_posted_data());

        $api = new CF7_To_WinkCrm_Api($token);
        $response = $api->import_contacts($data);
    }

    /**
     * @param $fields
     * @return mixed
     */
    private function clean_private_keys($fields)
    {
        foreach ($fields as $key => $field) {
            if (substr($key,0,6) === "_wpcf7")
                unset($fields[$key]);
        }

        return $fields;
    }
}