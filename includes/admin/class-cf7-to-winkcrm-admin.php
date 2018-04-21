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
            wp_die('Invalid POST');
        }

        if (!wp_verify_nonce($_POST['wpcf7-winkcrm-meta']['winkcrm_metabox_nonce'], 'winkcrm_metabox')) {
            wp_die('Security check');
        }

        update_post_meta($cf7_id, 'winkcrm_token_id', sanitize_text_field($_POST['wpcf7-winkcrm']['token']));
    }
}