<?php

defined( 'ABSPATH' ) or die();

require_once 'functions.php';

//Register activation hook
register_activation_hook( GS_WC_BULK_EDIT_PLUGIN_FILE, 'gs_wc_bulk_edit_register_activation_hook' );

//Admin enqueue scripts
add_action('admin_enqueue_scripts', 'gs_wc_bulk_edit_admin_enqueue_scripts', 10, 1);

//Admin menu
add_action( 'admin_menu', 'gs_wc_bulk_edit_admin_menu');

//Column sort ajax
add_action( 'wp_ajax_gs_wc_bulk_edit_column_sort_action', 'gs_wc_bulk_edit_column_sort_action');
add_action( 'wp_ajax_nopriv_gs_wc_bulk_edit_column_sort_action', 'gs_wc_bulk_edit_column_sort_action');

//Column reset sort ajax
add_action( 'wp_ajax_gs_wc_bulk_edit_column_sort_reset_action', 'gs_wc_bulk_edit_column_sort_reset_action');
add_action( 'wp_ajax_nopriv_gs_wc_bulk_edit_column_sort_reset_action', 'gs_wc_bulk_edit_column_sort_reset_action');

//Filter ajax
add_action( 'wp_ajax_gs_wc_bulk_edit_filter_action', 'gs_wc_bulk_edit_filter_action');
add_action( 'wp_ajax_nopriv_gs_wc_bulk_edit_filter_action', 'gs_wc_bulk_edit_filter_action');

//Clear Filter ajax
add_action( 'wp_ajax_gs_wc_bulk_edit_clear_filter_action', 'gs_wc_bulk_edit_clear_filter_action');
add_action( 'wp_ajax_nopriv_gs_wc_bulk_edit_clear_filter_action', 'gs_wc_bulk_edit_clear_filter_action');

//Ajax select taxonomy terms
add_action( 'wp_ajax_gs_wc_bulk_edit_taxonomy_action_select2', 'gs_wc_bulk_edit_taxonomy_action_select2');
add_action( 'wp_ajax_nopriv_gs_wc_bulk_edit_taxonomy_action_select2', 'gs_wc_bulk_edit_taxonomy_action_select2');

//Ajax page load
add_action( 'wp_ajax_gs_wc_bulk_edit_load_row_action', 'gs_wc_bulk_edit_load_row_action');
add_action( 'wp_ajax_nopriv_gs_wc_bulk_edit_load_row_action', 'gs_wc_bulk_edit_load_row_action');

//Ajax save data changes
add_action( 'wp_ajax_gs_wc_bulk_edit_save_chages_action', 'gs_wc_bulk_edit_save_chages_action');
add_action( 'wp_ajax_nopriv_gs_wc_bulk_edit_save_chages_action', 'gs_wc_bulk_edit_save_chages_action');