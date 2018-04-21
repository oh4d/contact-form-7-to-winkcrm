<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class CF7_To_WinkCrm_Admin
{
    public function __construct()
    {
        add_action('wpcf7_editor_panels', array($this, 'add_admin_option_tab'));
        add_action('wpcf7_after_save', array($this, 'save_cf7_post'));
        add_action('wpcf7_admin_notices', array($this, 'admin_notices'));
    }

    /**
     * @param $panels
     * @return mixed
     */
    public function add_admin_option_tab($panels)
    {
        $panels['winkcrm-cf7-panel'] = array('title' => 'WinkCrm', 'callback' => array($this, 'admin_option_tab_init'));
        return $panels;
    }

    /**
     * @param $cf7
     */
    public function admin_option_tab_init($cf7)
    {
        wp_nonce_field('winkcrm_metabox', 'wpcf7-winkcrm-meta[winkcrm_metabox_nonce]');
        $token = get_post_meta($cf7->id(),'winkcrm_token_id', true);
        ?>
        <p class="description">
            <label>Token field<br>
                <input type="text" name="wpcf7-winkcrm[token]" class="large-text" size="70" value="<?php echo $token; ?>"/>
            </label>
        </p>
        <?php
    }

    /**
     * @param $cf7
     */
    public function save_cf7_post($cf7)
    {
        $cf7_id = (int) $cf7->id();

        if(!isset($_POST['wpcf7-winkcrm']) || !isset($_POST['wpcf7-winkcrm-meta'])){
            wp_die(__('Something went wrong'));
        }

        if (!wp_verify_nonce($_POST['wpcf7-winkcrm-meta']['winkcrm_metabox_nonce'], 'winkcrm_metabox')) {
            wp_die(__('Something went wrong'));
        }

        $token = sanitize_text_field($_POST['wpcf7-winkcrm']['token']);

        try {
            $this->test_auth_token($token);
            update_post_meta($cf7_id, 'winkcrm_token_id', $token);
        }
        catch (\Exception $e) {
            // TODO Add CF7 Validation Error, For Now Only IF Valid Will Update
            update_post_meta($cf7_id, 'winkcrm_token_id', '');
        }
    }

    /**
     *
     */
    public function admin_notices()
    {
        if (empty($_REQUEST['message'])) {
            return;
        }

        if ($_REQUEST['message'] !== 'saved') {
            return;
        }

        echo sprintf('<div class="notice notice-success is-dismissible"><p>%s</p></div>', esc_html('hey'));
        return;
    }

    /**
     * @param $token
     * @return bool
     * @throws Exception
     */
    private function test_auth_token($token)
    {
        $check = cf7_to_winkcrm_test_auth($token);

        if (wp_remote_retrieve_response_code($check['response']) === 404 || !wp_remote_retrieve_response_code($check['response'])) {
            throw new \Exception(__('WinkCRM Server Error, Something went wrong with WinkCrm server'));
        }

        if (wp_remote_retrieve_response_code($check['response']) === 401) {
            throw new \Exception(__('WinkCRM Token Is Invalid'));
        }

        return true;
    }
}