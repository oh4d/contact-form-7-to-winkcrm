<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class CF7_To_WinkCrm
{
    public $file;

    public $plugin_path;

    public $includes_path;

    public $includes_url;

    public $cf7_winkcrm_admin;

    public $cf7_winkcrm_actions;

    public $bootstrap_warning_message;

    public function __construct($file)
    {
        $this->file = $file;
        $this->plugin_path = plugin_dir_path($this->file);
        $this->includes_path = plugin_dir_path($this->file) . 'includes';
        $this->includes_url = plugin_dir_url($this->file);
    }

    /**
     *
     */
    public function bootstrap()
    {
        try
        {
            $this->dependencies();
            $this->includes();
        }
        catch (\Exception $e)
        {
            $this->bootstrap_warning_message = $e->getMessage();

            add_action('admin_notices', array($this, 'bootstrap_warning'));
        }
    }

    /**
     * Load
     */
    public function load()
    {
        register_deactivation_hook(__FILE__, array($this, 'deactivate_cf7_to_winkcrm'));
        register_activation_hook(__FILE__, array($this, 'activate_cf7_to_winkcrm'));
        add_action('plugins_loaded', array($this, 'bootstrap'));
    }

    /**
     *
     */
    public function includes()
    {
        require_once("{$this->includes_path}/constants-winkcrm.php");
        require_once("{$this->includes_path}/class-cf7-to-winkcrm-api.php");
        require_once("{$this->includes_path}/class-cf7-to-winkcrm-actions.php");
        require_once("{$this->includes_path}/admin/class-cf7-to-winkcrm-admin.php");

        $this->cf7_winkcrm_admin = new CF7_To_WinkCrm_Admin();
        $this->cf7_winkcrm_actions = new CF7_To_WinkCrm_Actions();
    }

    /**
     * Echo Bootstrap Warning Message
     *
     * @return void
     */
    public function bootstrap_warning()
    {
        if (!empty($this->bootstrap_warning_message)) :
            ?>
            <div class="error fade">
                <p>
                    <strong><?php echo $this->bootstrap_warning_message; ?></strong>
                </p>
            </div>
        <?php
        endif;
    }

    /**
     * Check Plugin Dependencies
     *
     * @throws \Exception
     * @return void
     */
    public function dependencies()
    {
        if (!function_exists('WPCF7')) {
            throw new \Exception(__('Contact Form 7 To WinkCRM requires Contact Form 7 to be activated', 'contact-form-7-to-winkcrm'));
        }
    }

    public function activate_cf7_to_winkcrm() {}

    public function deactivate_cf7_to_winkcrm() {}
}