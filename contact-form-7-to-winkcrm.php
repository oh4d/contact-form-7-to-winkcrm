<?php
/**
 * Contact Form 7 To WinkCRM
 *
 * @package     contact-form-7-to-winkcrm
 * @author      Ohad Goldstein
 * @copyright   2018 Ohad Goldstein
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: Contact Form 7 To WinkCRM
 * Plugin URI:  https://github.com/oh4d
 * Description: Send Lead To WinkCRM From Contact Form 7.
 * Version:     1.0.0
 * Author:      Ohad Goldstein
 * Author URI:  https://www.ohadg.com
 * Text Domain: contact-form-7-to-winkcrm
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if (!function_exists('contact_form_7_to_winkcrm')) {
    /**
     * Initialize The Plugin
     *
     * @return CF7_To_WinkCrm
     */
    function contact_form_7_to_winkcrm()
    {
        static $plugin;

        if (!isset($plugin)) {
            require_once('includes/class-cf7-to-winkcrm.php');
            $plugin = new CF7_To_WinkCrm(__FILE__);
        }

        return $plugin;
    }

    contact_form_7_to_winkcrm()->load();
}