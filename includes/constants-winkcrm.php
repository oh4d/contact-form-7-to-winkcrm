<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

define('CF7_TO_WINKCRM_API_URL', 'http://localhost:8000/api/leads/');

define('CF7_TO_WINKCRM_NEW_LEAD', CF7_TO_WINKCRM_API_URL . 'new');
define('CF7_TO_WINKCRM_CHECK_AUTH', CF7_TO_WINKCRM_API_URL . 'auth');