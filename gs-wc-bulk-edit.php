<?php

/*
Plugin Name: GS Bulk Product Editor for WooCommerce
Description: This is the Gs wc bulk edit Product plugin
Author: Gaurav 
Version: 1.0.0
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain: gs-wc-bulk-edit 
*/



//prefix: GS_WC_BULK_EDIT gs_wc_bulk_edit

defined( 'ABSPATH' ) or die();

define( 'GS_WC_BULK_EDIT_VERSION', '1.0.0' );
define( 'GS_WC_BULK_EDIT_URL', plugin_dir_url( __FILE__ ) );
define( 'GS_WC_BULK_EDIT_PATH', plugin_dir_path( __FILE__ ) );
define( 'GS_WC_BULK_EDIT_PLUGIN_FILE', __FILE__ );

require_once 'includes/main.php';