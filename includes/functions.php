<?php

defined( 'ABSPATH' ) or die();

/*
* Admin enqueue scripts
*/
function gs_wc_bulk_edit_admin_enqueue_scripts(){

	//wp_enqueue_script( 'thickbox' ); //thickbox
    //wp_enqueue_style( 'thickbox' ); //thickbox
    wp_enqueue_editor(); //js wp.tinymce
    wp_enqueue_script( 'common' );
  	wp_enqueue_script( 'wp-lists' );
  	wp_enqueue_script( 'postbox' );
  	/*if ( ! did_action( 'wp_enqueue_media' ) ) {
      wp_enqueue_media();
  	}*/

	//select2 cdn
    wp_enqueue_style('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css' );
    wp_enqueue_script('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js', array('jquery') );

    //datatable
    wp_enqueue_script( 'cus-datatable-js',GS_WC_BULK_EDIT_URL . 'assets/dataTables/datatables.min.js', array( 'jquery' ), '1.0', true );
    wp_enqueue_style('cus-datatable-style', GS_WC_BULK_EDIT_URL . 'assets/dataTables/datatables.min.css', array(), '1.0', 'all' );
    wp_enqueue_script( 'cus-datatable-fixedColumns-js',GS_WC_BULK_EDIT_URL . 'assets/dataTables/dataTables.fixedColumns.min.js', array( 'jquery' ), '1.0', true );
    wp_enqueue_style('cus-datatable-fixedColumns-style', GS_WC_BULK_EDIT_URL . 'assets/dataTables/fixedColumns.dataTables.min.css', array(), '1.0', 'all' );

    //switchery
    wp_enqueue_script( 'cus-switchery-js',GS_WC_BULK_EDIT_URL . 'assets/switchery/switchery.min.js', array( 'jquery' ), '1.0', true );
    wp_enqueue_style('cus-switchery-style', GS_WC_BULK_EDIT_URL . 'assets/switchery/switchery.min.css', array(), '1.0', 'all' );

    //jquery-toast-plugin
    wp_enqueue_script( 'cus-jquery-toast-plugin-js',GS_WC_BULK_EDIT_URL . 'assets/jquery-toast-plugin/jquery.toast.min.js', array( 'jquery' ), '1.0', true );
    wp_enqueue_style('cus-jquery-toast-plugin-style', GS_WC_BULK_EDIT_URL . 'assets/jquery-toast-plugin/jquery.toast.min.css', array(), '1.0', 'all' );

    //jquery.scrollbar
    wp_enqueue_script( 'cus-jquery-scrollbar-js',GS_WC_BULK_EDIT_URL . 'assets/jquery-scrollbar/jquery.scrollbar.min.js', array( 'jquery' ), '1.0', true );
    wp_enqueue_style('cus-jquery-scrollbar-style', GS_WC_BULK_EDIT_URL . 'assets/jquery-scrollbar/jquery.scrollbar.css', array(), '1.0', 'all' );
    wp_enqueue_style( 'cus-jquery-scrollbar-custom2-style',GS_WC_BULK_EDIT_URL . 'assets/jquery-scrollbar/jquery.scrollbar.custom2.css', array(), '1.0', 'all' );
    
    //
	//type = colums, columnDefs, meta_keys
	//formate = php/json
	$dt_colums = gs_wc_bulk_edit_get_columns_settings('colums', 'json');
	$columnDefs = gs_wc_bulk_edit_get_columns_settings('columnDefs', 'json');

    wp_register_script( 'cus_bs_bulk_edit_js',GS_WC_BULK_EDIT_URL . 'assets/js/bs-bulk-edit.js', array( 'jquery' ), '1.0', true );
    $data = array(
        'ajaxurl'=> admin_url( 'admin-ajax.php'),
        'posturl'=> admin_url( 'admin-post.php'),
        'dt_colums' => $dt_colums,
        'columnDefs' => $columnDefs
    );
    wp_localize_script( 'cus_bs_bulk_edit_js', 'datab', $data );
    wp_enqueue_script( 'cus_bs_bulk_edit_js');
    wp_enqueue_style('cus_bs_bulk_edit_style', GS_WC_BULK_EDIT_URL . 'assets/css/bs-bulk-edit.css', array(), '1.0', 'all' );
    //
}

/*
*  Admin menu
*/
function gs_wc_bulk_edit_admin_menu(){
	$title = "GS WC bulk edit";
	add_menu_page( $title, $title, 'manage_options', 'gs-wc-bulk-edit', 'gs_wc_bulk_edit_menu_page');
	$title = "Columns";
	add_submenu_page( 'gs-wc-bulk-edit', $title, $title, 'manage_options', 'wc-bulk-edit-columns', 'gs_wc_bulk_edit_columns_menu_page');
}

/*
*  Admin menu body
*/
function gs_wc_bulk_edit_menu_page(){
	require_once 'views/gs_wc_bulk_edit_menu_page.php';
}

/*
* Admin sub menu body
*/
function gs_wc_bulk_edit_columns_menu_page(){
	require_once 'views/gs_wc_bulk_edit_columns_menu_page.php';
}

/*
* Ajax Column sort ajax
*/
function gs_wc_bulk_edit_column_sort_action(){
	update_option("dt_colums_settings", wc_clean( wp_unslash( $_POST['data'] ) ));
    $myJSON = json_encode(wc_clean( wp_unslash( $_POST['data'] ) )); 
    echo $myJSON;
    die();
}

/*
* Ajax Column reset sort
*/
function gs_wc_bulk_edit_column_sort_reset_action(){
	global $wpdb;
	$dt_colums_settings = gs_wc_bulk_edit_default_colums_settings(); 
	$default_column_count = count($dt_colums_settings);


	// attribute taxonomy
	$q_attr = "SELECT * FROM `{$wpdb->prefix}woocommerce_attribute_taxonomies`";
	$q_attr_result = $wpdb->get_results($q_attr, ARRAY_A);
	$attribute_taxonomies_arr = [];
	if (!empty($q_attr_result)) {
		$c = $default_column_count;
		foreach ($q_attr_result as $key => $value) {
			$attribute_taxonomies_arr[$c] = [   
		        'column_type' => 'taxonomy',
		        'column_label' => $value['attribute_label'],
		        'column_name' => 'pa_'.$value['attribute_name'],
		        'column_option' => '',
		        'column_orderable' => '',
		    ];
		    $c++;	
		}
		
	}
	$dt_colums_settings_X = array_merge($dt_colums_settings, $attribute_taxonomies_arr);

	update_option("dt_colums_settings", $dt_colums_settings_X); 
    $myJSON = json_encode([ 'res' => 'Reset Done!', 'dt_colums_settings' => $dt_colums_settings_X]); 
    echo $myJSON;
    die();
}

/*
* Ajax Filter
*/
function gs_wc_bulk_edit_filter_action(){

	$taxonomy_arr = wc_clean( wp_unslash( $_POST['taxonomy'] ) );
	$metadata_arr =  wc_clean( wp_unslash( $_POST['metadata'] ) );
	$posts_arr = wc_clean( wp_unslash( $_POST['posts'] ) );
	$bs_filter_query = wc_clean( wp_unslash( $_POST ) );
	$gs_wc_bulk_edit_filter_query_result = gs_wc_bulk_edit_filter_query_result($taxonomy_arr, $metadata_arr, $posts_arr, 0, 15);
	update_option("bs_filter_query", $bs_filter_query);
    $myJSON = json_encode($gs_wc_bulk_edit_filter_query_result); 
    echo $myJSON;
    die();
}

/*
* Ajax Clear Filter
*/
function gs_wc_bulk_edit_clear_filter_action(){
	update_option("bs_filter_query", []);
    $myJSON = json_encode(['clear']); 
    echo $myJSON;
    die();
}

/*
* Ajax select taxonomy terms
*/
function gs_wc_bulk_edit_taxonomy_action_select2(){

	global $wpdb;

	$return = array();

	$taxonomy_name = wc_clean( wp_unslash( $_GET['taxonomy_name'] ) );
	$_search_key = wc_clean( wp_unslash( $_GET['q'] ) );

	/*$terms = get_terms( $taxonomy_name, array(
		'name__like' => $_GET['q'],
		'hide_empty' => true  
	));*/

	//
	$q_term = "
	SELECT t1.term_id, t1.name, t1.slug FROM wp_terms as t1 
		LEFT JOIN wp_term_taxonomy as t2 ON t1.term_id = t2.term_id 
		WHERE t2.taxonomy = '{$taxonomy_name}'
		AND t1.name LIKE '%{$_search_key}%'
		LIMIT 0, 10
	";
	$terms = $wpdb->get_results($q_term);
	//

	if ( count($terms) > 0 ){

		foreach ( $terms as $term ) {

			$return[] = array( $term->term_id, $term->name );

		}
	}
	echo json_encode( $return );
	die;
}

/*
* Ajax page load
*/
function gs_wc_bulk_edit_load_row_action(){

	global $wpdb;

	$dt_colums_settings = gs_wc_bulk_edit_dt_colums_settings();

	## Read value
	$draw = wc_clean( wp_unslash( $_POST['draw'] ) ); //datatable draw

	$row = wc_clean( wp_unslash( $_POST['start'] ) ); //offset
	$rowperpage = wc_clean( wp_unslash( $_POST['length'] ) ); // limit

	$columnIndex = wc_clean( wp_unslash( $_POST['order'][0]['column'] ) ); // Column index
	$columnName = ($columnIndex == 0) ? 'post_date' : wc_clean( wp_unslash( $_POST['columns'][$columnIndex]['data'] ) ); //$_POST['columns'][$columnIndex]['data']; // Column name
	$columnSortOrder = wc_clean( wp_unslash( $_POST['order'][0]['dir'] ) ); // asc or desc

	$searchValue = wc_clean( wp_unslash( $_POST['search']['value'] ) ); // Search value

	$bs_bulk_edit_action_switch_variation = wc_clean( wp_unslash( $_POST['bs_bulk_edit_action_switch_variation'] ) );

	//new
	$bs_filter_query = get_option("bs_filter_query");
	$taxonomy_arr = $bs_filter_query['taxonomy'];
	$metadata_arr = $bs_filter_query['metadata'];
	$posts_arr = $bs_filter_query['posts'];
	$gs_wc_bulk_edit_filter_query_result = gs_wc_bulk_edit_filter_query_result($taxonomy_arr, $metadata_arr, $posts_arr, $row, $rowperpage, $columnName, $columnSortOrder);
	$empRecords = $gs_wc_bulk_edit_filter_query_result['data'];
	$totalRecords = $gs_wc_bulk_edit_filter_query_result['total_count'][0];
	$totalRecordwithFilter =  $gs_wc_bulk_edit_filter_query_result['count_with_filter'][0];
	//new end

	$data = array();
	$product_arr_data = array();
	foreach ($empRecords as $cc => $row) {

		$product_type = get_the_terms($row['ID'], 'product_type')[0]->slug;

		if($product_type == 'variable'){

			$display_output = gs_wc_bulk_edit_get_display_output($cc, $row, $product_type);
			$data[] = $display_output['data'][$cc];
			$product_arr_data[$row['ID']] = $display_output['row'];
			
			if ($bs_bulk_edit_action_switch_variation) {
				$gs_wc_bulk_edit_get_variations = gs_wc_bulk_edit_get_variations($row['ID']);
				if (!empty($gs_wc_bulk_edit_get_variations)) {
					foreach ($gs_wc_bulk_edit_get_variations as $var_key => $var_row) { 
						$display_output = gs_wc_bulk_edit_get_display_output($var_key, $var_row);
						$data[] = $display_output['data'][$var_key];
						$product_arr_data[$var_row['ID']] = $display_output['row'];
					}
				}
			}

		}else{
			$display_output = gs_wc_bulk_edit_get_display_output($cc, $row, $product_type);
			$data[] = $display_output['data'][$cc];
			$product_arr_data[$row['ID']] = $display_output['row'];
		}

	}

	## Response
	$response = array(
	    "draw" => intval($draw), 
	    "iTotalRecords" => $totalRecords, //total item count
	    "iTotalDisplayRecords" => $totalRecordwithFilter, //total item count with query 
	    "aaData" => $data,

	    "bs_filter_query" => $bs_filter_query,
	    "product_arr_data" => $product_arr_data,
	    "POST_DATA" => wc_clean( wp_unslash( $_POST ) ),
	);


	wp_send_json( $response );
	wp_die();
}

/*
* Ajax save data changes
*/
function gs_wc_bulk_edit_save_chages_action(){

	$post_idx = wc_clean( wp_unslash( $_POST['post_id'] ) );
	$column_type =  wc_clean( wp_unslash( $_POST['column_type'] ) );
	$column_label = wc_clean( wp_unslash( $_POST['column_label'] ) );
	$column_name = wc_clean( wp_unslash( $_POST['column_name'] ) );
	$column_val = wc_clean( wp_unslash( $_POST['column_val'] ) );
	$input_val = wc_clean( wp_unslash( $_POST['input_val'] ) );
	$selectedValues = wc_clean( wp_unslash( $_POST['selectedValues'] ) );
	$bs_bulk_edit_action_switch = wc_clean( wp_unslash( $_POST['bs_bulk_edit_action_switch'] ) );
	$bs_bulk_edit_action_switch_variation = wc_clean( wp_unslash( $_POST['bs_bulk_edit_action_switch_variation'] ) );
	$bs_bulk_edit_action_switch_queue = wc_clean( wp_unslash( $_POST['bs_bulk_edit_action_switch_queue'] ) );
	

	if ($bs_bulk_edit_action_switch_queue == 1) {

		//queue
		$page = wc_clean( wp_unslash( $_POST['page'] ) );
		$limit = wc_clean( wp_unslash( $_POST['limit'] ) );
		$offset = ($page - 1) * $limit;
		$bs_filter_query = get_option("bs_filter_query");
		if (!empty($bs_filter_query)) {
			$taxonomy_arr = $bs_filter_query['taxonomy'];
			$metadata_arr = $bs_filter_query['metadata'];
			$posts_arr = $bs_filter_query['posts'];
		}else{
			$taxonomy_arr = [];
			$metadata_arr = [];
			$posts_arr = [];
		}

		$gs_wc_bulk_edit_filter_query_result = gs_wc_bulk_edit_filter_query_result($taxonomy_arr, $metadata_arr, $posts_arr, $offset, $limit);
		foreach ($gs_wc_bulk_edit_filter_query_result['data'] as $key => $value) {
			$post_id = $value['ID'];
			gs_wc_bulk_edit_update_values($post_id, $column_type, $column_name, $input_val, $bs_bulk_edit_action_switch_variation);
		}
		$myJSON = $gs_wc_bulk_edit_filter_query_result;


		//
		/*set_time_limit(1000);

		$paged = 1;
		$limit = 100;

		do {
			//fetach recored
		    $offset = ($paged - 1) * $limit;
		    $gs_wc_bulk_edit_filter_query_result = gs_wc_bulk_edit_filter_query_result([], [], [], $offset, $limit);
			
			//break
		    if (count($gs_wc_bulk_edit_filter_query_result['data']) == 0) {
		        break;
		    }

		    //loop
		    $records = [];
		    foreach ($gs_wc_bulk_edit_filter_query_result['data'] as $key => $value) {
		    	$records[] = $value;
				$post_id = $value['ID'];
				gs_wc_bulk_edit_update_values($post_id, $column_type, $column_name, $input_val, $bs_bulk_edit_action_switch_variation);
			}

		    $paged++;

		} while (true);

		//success
		$myJSON = $records;
		*/
		//


		//queue end
	}else{
		gs_wc_bulk_edit_update_values($post_idx, $column_type, $column_name, $input_val, $bs_bulk_edit_action_switch_variation);

		if ($bs_bulk_edit_action_switch == 1) {

			foreach ($selectedValues as $post_id) {

				if ($post_id!=$post_idx) {

					gs_wc_bulk_edit_update_values($post_id, $column_type, $column_name, $input_val, $bs_bulk_edit_action_switch_variation);
				}
				
			}
			
		}
	
		$myJSON = wc_clean( wp_unslash( $_POST ) );
	}

    
	wp_send_json( $myJSON );
	wp_die();
}


/*
* filter select val
*/
function gs_wc_bulk_edit_get_filter_select_val($taxonomy_name = ''){
	$bs_filter_query = get_option("bs_filter_query");
	//echo "<pre>bs_filter_query: "; print_r($bs_filter_query); echo "</pre>";
	if (!empty($bs_filter_query['taxonomy']) && !empty($taxonomy_name)) {
		foreach ($bs_filter_query['taxonomy'][$taxonomy_name] as $term_id) {
			$termData = get_term($term_id, $taxonomy_name);
			//echo $termData->name; echo "<br>";
			?>
			<option value="<?php echo $term_id;?>" selected><?php echo $termData->name;?></option>
			<?php
		}
	}
}

/*
* filter text val
*/
function gs_wc_bulk_edit_get_filter_text_val($type = '', $name = ''){
	$bs_filter_query = get_option("bs_filter_query");
	//echo "<pre>bs_filter_query: "; print_r($bs_filter_query); echo "</pre>";
	return $bs_filter_query[$type][$name];
}

/*
* Update values
*/
function gs_wc_bulk_edit_update_values($post_id, $column_type, $column_name, $input_val, $switch_variation){


	/*echo "post_id: ".$post_id; echo "<br>";
	echo "column_name: ".$column_name; echo "<br>";
	echo "input_val: ".$input_val; echo "<br>";*/

	//post_table_attribute
	if ($column_type == "post_table_attribute") {

		if ($column_name == 'post_title') { 
			$post_update = array(
			    'ID'         => $post_id,
			    'post_title' => $input_val
			);
			wp_update_post( $post_update );
		}

		if ($column_name == 'post_name') {
			$post_update = array(
			    'ID'         => $post_id,
			    'post_name' => $input_val
			);
			wp_update_post( $post_update );
		}

		if ($column_name == 'post_content') {
			$post_update = array(
			    'ID'         => $post_id,
			    'post_content' => $input_val
			);
			wp_update_post( $post_update );
		}

		if ($column_name == 'post_excerpt') {
			$post_update = array(
			    'ID'         => $post_id,
			    'post_excerpt' => $input_val
			);
			wp_update_post( $post_update );
		}
		
	}

	//"meta_key"
	if ($column_type == "meta_key") {

		
		$product_type = get_the_terms($post_id, 'product_type')[0]->slug;

		if($product_type == 'simple'){

			if ($column_name == '_sale_price') {

				if ($input_val == 'delete') {
					//delete_post_meta($post_id, $column_name);
					$product = new WC_Product( $post_id );
					$product->set_sale_price("");
					$product->save();
				}else{
					$_regular_price = get_post_meta($post_id, '_regular_price', true);
					if (!empty($_regular_price)) {
						$_dis_sale_price = $_regular_price * $input_val / 100;
						$_dis_sale_price_c = $_regular_price - $_dis_sale_price;
						$_dis_sale_price_c = round($_dis_sale_price_c, 2);
						//update_post_meta($post_id, $column_name, $_dis_sale_price_c);
						$product = new WC_Product( $post_id );
						$product->set_sale_price($_dis_sale_price_c);
						$product->save();
					}
				}
				
			}else if($column_name == '_regular_price'){
				//update_post_meta($post_id, $column_name, $input_val);
				$product = new WC_Product( $post_id );
				$product->set_regular_price($input_val);
				$product->save();
			}else{
				update_post_meta($post_id, $column_name, $input_val);
			}

		}else if($product_type == 'variable'){

			if(!in_array($column_name, ['_regular_price', '_sale_price'])){
				update_post_meta($post_id, $column_name, $input_val);
			}

			if ($switch_variation == 1) {
				$gs_wc_bulk_edit_get_variations = gs_wc_bulk_edit_get_variations($post_id);
				if (!empty($gs_wc_bulk_edit_get_variations)) {
					foreach ($gs_wc_bulk_edit_get_variations as $var_key => $var_row) { 

						$_variations_id = $var_row['ID'];
						if ($column_name == '_sale_price') {

							if ($input_val == 'delete') {
								//delete_post_meta($_variations_id, $column_name);
								$variation = new WC_Product_Variation( $_variations_id );
			                    $variation->set_sale_price("");
			                    $variation->save();
							}else{
								$_regular_price = get_post_meta($_variations_id, '_regular_price', true);
								if (!empty($_regular_price)) {
									$_dis_sale_price = $_regular_price * $input_val / 100;
									$_dis_sale_price_c = $_regular_price - $_dis_sale_price;
									$_dis_sale_price_c = round($_dis_sale_price_c, 2);
									//update_post_meta($_variations_id, $column_name, $_dis_sale_price_c);
									$variation = new WC_Product_Variation( $_variations_id );
			                        $variation->set_sale_price($_dis_sale_price_c);
			                        $variation->save();
								}
							}
							
						}else if($column_name == '_regular_price'){
							//update_post_meta($_variations_id, $column_name, $input_val);
							$variation = new WC_Product_Variation( $_variations_id );
			                $variation->set_regular_price($input_val);
			                $variation->save();
						}else{
							update_post_meta($_variations_id, $column_name, $input_val);
						}
						
					}
				}
			}


		}else if($product_type == 'grouped'){
			if(!in_array($column_name, ['_regular_price', '_sale_price'])){
				update_post_meta($post_id, $column_name, $input_val);
			}
		}else if($product_type == 'external'){
			if ($column_name == '_sale_price') {

				if ($input_val == 'delete') {
					//delete_post_meta($post_id, $column_name);
					$product = new WC_Product( $post_id );
					$product->set_sale_price("");
					$product->save();
				}else{
					$_regular_price = get_post_meta($post_id, '_regular_price', true);
					if (!empty($_regular_price)) {
						$_dis_sale_price = $_regular_price * $input_val / 100;
						$_dis_sale_price_c = $_regular_price - $_dis_sale_price;
						$_dis_sale_price_c = round($_dis_sale_price_c, 2);
						//update_post_meta($post_id, $column_name, $_dis_sale_price_c);
						$product = new WC_Product( $post_id );
						$product->set_sale_price($_dis_sale_price_c);
						$product->save();
					}
				}
				
			}else if($column_name == '_regular_price'){
				//update_post_meta($post_id, $column_name, $input_val);
				$product = new WC_Product( $post_id );
				$product->set_regular_price($input_val);
				$product->save();
			}else{
				update_post_meta($post_id, $column_name, $input_val);
			}
		}
		
	}

	//"taxonomy"
	if ($column_type == "taxonomy") {
		$terms = get_the_terms($post_id, $column_name);
		$terms = array_column($terms, 'term_id');

		//remove
		$result=array_diff($terms,$input_val);
		if ($result) {
			foreach ($result as $i) {
				wp_remove_object_terms( $post_id, $i, $column_name);
			}
		}

		//add
		if (!empty($input_val)) { 
			foreach ($input_val as $term_id) {
				wp_set_object_terms( $post_id, (int) $term_id, $column_name, true);
			}
		}else{ 
			$default_product_cat = get_option('default_product_cat');
			if (!empty($default_product_cat)) {
				wp_set_object_terms( $post_id, (int) $default_product_cat, $column_name, true);
			}
		}
		
	}

}

/*
* Display output
*/
function gs_wc_bulk_edit_get_display_output($cc, $row, $product_type = ""){

	global $wpdb;

	$data = [];
	//$product_arr_data = [];

	$dt_colums_settings = gs_wc_bulk_edit_dt_colums_settings();

	foreach ($dt_colums_settings as $key => $value) {

        if ($value['column_option']) {

        	foreach ($dt_colums_settings as $key => $value) {

		        if ($value['column_option']) {

		        	 $data[$cc][$value['column_name']] = $row[$value['column_name']];
		            
		            if ($value['column_type'] == 'post_table_id' AND $value['column_name'] == 'id_checkbox') { 

		                //post_table_id
		                $data[$cc][$value['column_name']] = '<div><input type="checkbox" name="post_id[]" class="checkItem" value="'.$row['ID'].'"></div>';

		                $row[$value['column_name']] = $row[$value['column_name']];
		            	//$product_arr_data[$row['ID']] = $row;
		                
		            }else if($value['column_type'] == 'post_thumbnail'){ 

		                //post_table_attribute
		                $metaDataValue = get_post_meta($row['ID'], $value['column_name'], true);
		                if (!empty($metaDataValue)) {

		                	$image_size = 'medium'; // (thumbnail, medium, large, full or custom size)
	                    	$img_url_arr = wp_get_attachment_image_src( $metaDataValue, $image_size );
	                    	$img_url = $img_url_arr[0];

		                	$data[$cc][$value['column_name']] = '<div data-post_id="'.$row['ID'].'" data-column_type="'.$value['column_type'].'" data-column_label="'.$value['column_label'].'" data-column_name="'.$value['column_name'].'" data-column_val="'.$row[$value['column_name']].'"><img src="'.$img_url.'" width="50"></div>';


		                	$row[$value['column_name']] = $img_url;
		            		//$product_arr_data[$row['ID']] = $row;

		                }else{
		                	$data[$cc][$value['column_name']] = '<div data-post_id="'.$row['ID'].'" data-column_type="'.$value['column_type'].'" data-column_label="'.$value['column_label'].'" data-column_name="'.$value['column_name'].'" data-column_val="">N/A</div>';

		                	$row[$value['column_name']] = "";
		            		//$product_arr_data[$row['ID']] = $row;
		                }

		            }else if($value['column_type'] == 'post_table_attribute'){ 

		                //post_table_attribute
		                if (!empty($row[$value['column_name']])) { 

		                	if ($value['column_name'] == 'post_content' || $value['column_name'] == 'post_excerpt') {

		                		$content = 'Open Editor';

		                		$data[$cc][$value['column_name']] = '<div class="be_td_cell bs-open-editor" data-post_id="'.$row['ID'].'" data-column_type="'.$value['column_type'].'" data-column_label="'.$value['column_label'].'" data-column_name="'.$value['column_name'].'" data-column_val="">'.$content.'</div><div class="be-model"></div>';

		                	}else if($value['column_name'] == 'ID'){

		                		$data[$cc][$value['column_name']] = '<div class="be_td_cell_ID" data-post_id="'.$row['ID'].'" data-column_type="'.$value['column_type'].'" data-column_label="'.$value['column_label'].'" data-column_name="'.$value['column_name'].'" data-column_val="'.$row[$value['column_name']].'"><a href="'.get_permalink($row['ID']).'" target="_blank" style="text-decoration: none;">'.$row[$value['column_name']].'</a></div><div class="be-model"></div>';

		                	}else{
		                		$data[$cc][$value['column_name']] = '<div class="be_td_cell" data-post_id="'.$row['ID'].'" data-column_type="'.$value['column_type'].'" data-column_label="'.$value['column_label'].'" data-column_name="'.$value['column_name'].'" data-column_val="'.$row[$value['column_name']].'">'.$row[$value['column_name']].'</div><div class="be-model"></div>';
		                	}

		                	$row[$value['column_name']] = $row[$value['column_name']];
		            		//$product_arr_data[$row['ID']] = $row;

		                }else{
		                	$data[$cc][$value['column_name']] = '<div class="be_td_cell" data-post_id="'.$row['ID'].'" data-column_type="'.$value['column_type'].'" data-column_label="'.$value['column_label'].'" data-column_name="'.$value['column_name'].'" data-column_val="">N/A</div><div class="be-model">';

		                	$row[$value['column_name']] = "";
		            		//$product_arr_data[$row['ID']] = $row;
		                }

		            }else if($value['column_type'] == 'meta_key'){ 

		            	if ( ($product_type == 'variable' || $product_type == 'grouped') && in_array($value['column_name'], ['_price', '_regular_price', '_sale_price'])) { //not editable

							$data[$cc][$value['column_name']] = '<div class="be_td_cell_not_editable" data-post_id="'.$row['ID'].'" data-column_type="'.$value['column_type'].'" data-column_label="'.$value['column_label'].'" data-column_name="'.$value['column_name'].'" data-column_val="">Not Editable</div><div class="be-model">';

							$row[$value['column_name']] = '';
							//$product_arr_data[$row['ID']] = $row;
		            		
		            	}else{
		            		//meta_key
			                $metaDataValue = get_post_meta($row['ID'], $value['column_name'], true);
			                if (!empty($metaDataValue)) {
			                	$data[$cc][$value['column_name']] = '<div class="be_td_cell" data-post_id="'.$row['ID'].'" data-column_type="'.$value['column_type'].'" data-column_label="'.$value['column_label'].'" data-column_name="'.$value['column_name'].'" data-column_val="'.$metaDataValue.'">'.$metaDataValue.'</div><div class="be-model">';

			                	$row[$value['column_name']] = $metaDataValue;
			            		//$product_arr_data[$row['ID']] = $row;

			                }else{
			                	$data[$cc][$value['column_name']] = '<div class="be_td_cell" data-post_id="'.$row['ID'].'" data-column_type="'.$value['column_type'].'" data-column_label="'.$value['column_label'].'" data-column_name="'.$value['column_name'].'" data-column_val="">N/A</div><div class="be-model">';

			                	$row[$value['column_name']] = '';
			            		//$product_arr_data[$row['ID']] = $row;
			                }
		            	}


		            }else if($value['column_type'] == 'taxonomy'){ 


		            	if ($row['post_type'] == 'product_variation') { //not editable

		            		$data[$cc][$value['column_name']] = '<div class="be_td_cell_not_editable" data-post_id="'.$row['ID'].'" data-column_type="'.$value['column_type'].'" data-column_label="'.$value['column_label'].'" data-column_name="'.$value['column_name'].'" data-column_val="">Not Editable</div><div class="be-model">';
			             
			                $row[$value['column_name']] = [];
			            	//$product_arr_data[$row['ID']] = $row;
		            		
		            	}else{

		            		 //taxonomy
		            		//$terms = get_the_terms($row['ID'] , $value['column_name']);
			                /*$q_term = "SELECT term_id, name, slug FROM wp_terms WHERE term_id IN(
										SELECT term_id FROM wp_term_taxonomy WHERE term_taxonomy_id IN(
											SELECT term_taxonomy_id FROM wp_term_relationships WHERE object_id IN({$row['ID']})
										)
										AND taxonomy = '{$value['column_name']}'
									)";
							$terms = $wpdb->get_results($q_term, ARRAY_A);*/
							$terms = gs_wc_bulk_edit_get_the_terms($row['ID'], $value['column_name'], true, ['term_id', 'name', 'slug']);
			                if (!empty($terms)) {
			                	$term_names = array_column($terms, 'name');
			                	$term_names = implode(', ', $term_names);
			                	$terms_json_data = json_encode($terms );

			                	if ($value['column_name'] == 'product_type') { //not editable

			                		$data[$cc][$value['column_name']] = '<div class="be_td_cell_not_editable_only_display" data-post_id="'.$row['ID'].'" data-column_type="'.$value['column_type'].'" data-column_label="'.$value['column_label'].'" data-column_name="'.$value['column_name'].'" data-column_val="">'.$term_names.'</div><div class="be-model">';
			                		
			                	}else{ 

			                		//editable

			                		$data[$cc][$value['column_name']] = '<div class="be_td_cell" data-post_id="'.$row['ID'].'" data-column_type="'.$value['column_type'].'" data-column_label="'.$value['column_label'].'" data-column_name="'.$value['column_name'].'" data-column_val="">'.$term_names.'</div><div class="be-model">';
			                		
			                	}

			                	
			                	$row[$value['column_name']] = $terms;
			            		//$product_arr_data[$row['ID']] = $row;
			                	

			                }else{
			                	$data[$cc][$value['column_name']] = '<div class="be_td_cell" data-post_id="'.$row['ID'].'" data-column_type="'.$value['column_type'].'" data-column_label="'.$value['column_label'].'" data-column_name="'.$value['column_name'].'" data-column_val="">N/A</div><div class="be-model">';
			             
			                	$row[$value['column_name']] = [];
			            		//$product_arr_data[$row['ID']] = $row;
			                }

		            	}
		            	
		            }else{ 

		                //
		            }
		        } 
		    }
        	
            
        } 
	}

	return [
		'data' => $data,
		'row' => $row
	];

}

/*
* Get the terms
*/
function gs_wc_bulk_edit_get_the_terms($post_id, $taxonomy, $output_type = false, $columns = []){

	global $wpdb;

	$select_names = '*';
	if (!empty($columns)) {
		$select_names = implode(', ', $columns);
	}

	$q_term = "
			SELECT {$select_names} FROM wp_terms WHERE term_id IN(
				SELECT term_id FROM wp_term_taxonomy WHERE term_taxonomy_id IN(
					SELECT term_taxonomy_id FROM wp_term_relationships WHERE object_id IN({$post_id})
				)
				AND taxonomy = '{$taxonomy}'
			)";
	if ($output_type) {
		$terms = $wpdb->get_results($q_term, ARRAY_A);
	}else{
		$terms = $wpdb->get_results($q_term);
	}
	
	return $terms;

}

/*
* Get variations
*/
function gs_wc_bulk_edit_get_variations($parent_id){
	global $wpdb;
	$q = "SELECT * FROM `wp_posts` WHERE `post_parent` = {$parent_id}";
	$result = $wpdb->get_results($q, ARRAY_A);
	return $result;
}

/*
* Filter query result
*/
function gs_wc_bulk_edit_filter_query_result($taxonomy_arr = array(), $metadata_arr = array(), $posts_arr = array(), $offset = 0, $limit = 10, $orderby = "post_date", $order="desc"){
	
	global $wpdb;

	$q_taxonomy_join = '';
	$q_metadata_join = '';
	$q_taxonomy_where = '';
	$q_metadata_where = '';
	$q_post_where = '';
	$where_q = "";

	if (!empty($taxonomy_arr)) {
		$c = 0;
		foreach ($taxonomy_arr as $key => $value) {
			$q_taxonomy_join .= " LEFT JOIN wp_term_relationships AS tr{$c} ON (p.ID = tr{$c}.object_id) ";
			$value = implode(', ', $value);
			if ($c  == 0) {
				$q_taxonomy_where .= " tr{$c}.term_taxonomy_id IN ({$value}) ";
			}else{
				$q_taxonomy_where .= " AND tr{$c}.term_taxonomy_id IN ({$value}) ";
			}
			$c++;
		}
	}

	if (!empty($metadata_arr)) {
		$c = 0;
		foreach ($metadata_arr as $meta_key => $meta_value) {
			if (!empty($meta_value)) {
				$q_metadata_join .= " INNER JOIN wp_postmeta AS mt{$c} ON ( p.ID = mt{$c}.post_id ) ";
				if ($c == 0) {
					$q_metadata_where .= "  ( mt{$c}.meta_key = '{$meta_key}' AND mt{$c}.meta_value = '{$meta_value}' ) ";
				}else{
					$q_metadata_where .= " AND ( mt{$c}.meta_key = '{$meta_key}' AND mt{$c}.meta_value = '{$meta_value}' )";
				}
				$c++;
			}
			
		}
	}

	
	if (!empty($posts_arr)) {
		foreach ($posts_arr as $post_key => $post_value) {
			if (!empty($post_value)) {
				$q_post_where .= " AND p.{$post_key} LIKE '%{$post_value}%' ";
			}
		}
	}

	$meta_keys = gs_wc_bulk_edit_get_columns_settings('meta_keys');
	if (in_array($orderby, $meta_keys)) { 
		$q_orderby_meta_key_join .= " INNER JOIN wp_postmeta AS mto ON ( p.ID = mto.post_id ) ";
		$where_q .= " AND mto.meta_key = '{$orderby}' ";
		$q_orderby .= " ORDER BY mto.meta_value+0 {$order}  ";
	}else{
		$q_orderby .= " ORDER BY p.{$orderby} {$order}  ";
	}

	/*if (!empty($q_post_where)) {
		$where_q .= " AND({$q_post_where})";
	}*/

	if (!empty($q_taxonomy_where)) {
		$where_q .= " AND({$q_taxonomy_where})";
	}

	if (!empty($q_metadata_where)) {
		$where_q .= " AND({$q_metadata_where})";
	}

	// AND p.post_type IN('product', 'product_variation'), AND p.post_type = 'product'
	$result_q = "
		SELECT * FROM wp_posts as p
			{$q_taxonomy_join} 
			{$q_metadata_join}
			{$q_orderby_meta_key_join}
			WHERE 1=1   
				{$where_q} 
				AND p.post_type = 'product'
				AND p.post_status = 'publish'
				{$q_post_where}
				{$q_orderby}
				LIMIT {$offset}, {$limit}
	";
	$result = $wpdb->get_results($result_q, ARRAY_A);

	$count_with_filter_q = "
		SELECT count(*) AS allcount FROM wp_posts as p
			{$q_taxonomy_join} 
			{$q_metadata_join}
			{$q_orderby_meta_key_join}
			WHERE 1=1   
				{$where_q} 
				AND p.post_type = 'product'
				AND p.post_status = 'publish'
				{$q_post_where}
				{$q_orderby}
	";
	$count_with_filter = $wpdb->get_results($count_with_filter_q, ARRAY_A);
	$count_with_filter = array_column($count_with_filter, 'allcount');

	$total_count_q = "
		SELECT count(*) AS allcount FROM wp_posts as p
			WHERE 1=1   
				AND p.post_type = 'product'
				AND p.post_status = 'publish'
	";
	$total_count = $wpdb->get_results($total_count_q, ARRAY_A);
	$total_count = array_column($total_count, 'allcount');

	return [
		'data' => $result,
		'total_count' => $total_count,
		'count_with_filter' => $count_with_filter
	];
	
}




/*
* Register activation hook
*/
function gs_wc_bulk_edit_register_activation_hook(){    
	$dt_colums_settings = gs_wc_bulk_edit_default_colums_settings(); 
	update_option("dt_colums_settings", $dt_colums_settings); 
	update_option("bs_filter_query", []); 
} 

/*
* Default column
*/
function gs_wc_bulk_edit_default_colums_settings(){

	$dt_colums_settings = [

	    [   
	        'column_type' => 'post_table_id',
	        'column_label' => 'Checkbox',
	        'column_name' => 'id_checkbox',
	        'column_option' => true,
	        'column_orderable' => false,
	    ],

	    [   
	        'column_type' => 'post_table_attribute',
	        'column_label' => 'ID',
	        'column_name' => 'ID',
	        'column_option' => true,
	        'column_orderable' => true,
	    ],

	    [   
	        'column_type' => 'post_thumbnail',
	        'column_label' => 'Image',
	        'column_name' => '_thumbnail_id',
	        'column_option' => true,
	        'column_orderable' => false,
	    ],

	    [   
	        'column_type' => 'post_table_attribute',
	        'column_label' => 'Title',
	        'column_name' => 'post_title',
	        'column_option' => true,
	        'column_orderable' => true,
	    ],

	    [   
	        'column_type' => 'post_table_attribute',
	        'column_label' => 'Slug',
	        'column_name' => 'post_name',
	        'column_option' => true,
	        'column_orderable' => true,
	    ],

	    [    
	        'column_type' => 'post_table_attribute',
	        'column_label' => 'Description',
	        'column_name' => 'post_content',
	        'column_option' => true,
	        'column_orderable' => false,
	    ],

	    [    
	        'column_type' => 'post_table_attribute',
	        'column_label' => 'Short description',
	        'column_name' => 'post_excerpt',
	        'column_option' => true,
	        'column_orderable' => false,
	    ],

	    // Price
	    [   
	        'column_type' => 'meta_key',
	        'column_label' => 'Regular price',
	        'column_name' => '_regular_price',
	        'column_option' => true,
	        'column_orderable' => true,
	    ],

	    [   
	        'column_type' => 'meta_key',
	        'column_label' => 'Sale price',
	        'column_name' => '_sale_price',
	        'column_option' => true,
	        'column_orderable' => true,
	    ],
	    // Price


	    //invetory
	    [   
	        'column_type' => 'meta_key',
	        'column_label' => 'SKU',
	        'column_name' => '_sku',
	        'column_option' => true,
	        'column_orderable' => true,
	    ],

	    [   
	        'column_type' => 'meta_key',
	        'column_label' => 'Manage stock',
	        'column_name' => '_manage_stock',
	        'column_option' => true,
	        'column_orderable' => true,
	    ],

	    [   
	        'column_type' => 'meta_key',
	        'column_label' => 'Stock',
	        'column_name' => '_stock',
	        'column_option' => true,
	        'column_orderable' => true,
	    ],

	    [   
	        'column_type' => 'meta_key',
	        'column_label' => 'Backorders',
	        'column_name' => '_backorders',
	        'column_option' => true,
	        'column_orderable' => true,
	    ],

	    [   
	        'column_type' => 'meta_key',
	        'column_label' => 'Stock status',
	        'column_name' => '_stock_status',
	        'column_option' => true,
	        'column_orderable' => true,
	    ],
	     //invetory

	    //Shipping
	    [   
	        'column_type' => 'meta_key',
	        'column_label' => 'Weight',
	        'column_name' => '_weight',
	        'column_option' => true,
	        'column_orderable' => true,
	    ],
	    [   
	        'column_type' => 'meta_key',
	        'column_label' => 'Length',
	        'column_name' => '_length',
	        'column_option' => true,
	        'column_orderable' => true,
	    ],
	    [   
	        'column_type' => 'meta_key',
	        'column_label' => 'Width',
	        'column_name' => '_width',
	        'column_option' => true,
	        'column_orderable' => true,
	    ],
	    [   
	        'column_type' => 'meta_key',
	        'column_label' => 'Height',
	        'column_name' => '_height',
	        'column_option' => true,
	        'column_orderable' => true,
	    ],

	    [   
	        'column_type' => 'taxonomy',
	        'column_label' => 'Shipping class',
	        'column_name' => 'product_shipping_class',
	        'column_option' => true,
	        'column_orderable' => false,
	    ],
	    //Shipping


	    //product_cat
	    [ 	  
	        'column_type' => 'taxonomy',
	        'column_label' => 'Category',
	        'column_name' => 'product_cat',
	        'column_option' => true,
	        'column_orderable' => false,
	    ],
	    //product_cat

	    //product_tag
	    [   
	        'column_type' => 'taxonomy',
	        'column_label' => 'Tag',
	        'column_name' => 'product_tag',
	        'column_option' => true,
	        'column_orderable' => false,
	    ],
	    //product_tag

	    //product_tag
	    [   
	        'column_type' => 'taxonomy',
	        'column_label' => 'Product Type',
	        'column_name' => 'product_type',
	        'column_option' => true,
	        'column_orderable' => false,
	    ],
	    //product_tag

	    /*//pa_color
	    [   
	        'column_type' => 'taxonomy',
	        'column_label' => 'Color',
	        'column_name' => 'pa_color',
	        'column_option' => true,
	        'column_orderable' => false,
	    ],
	    //pa_color

	    //pa_size
	    [   
	        'column_type' => 'taxonomy',
	        'column_label' => 'Size',
	        'column_name' => 'pa_size',
	        'column_option' => true,
	        'column_orderable' => false,
	    ]
	    //pa_size*/

	];

	return $dt_colums_settings;
}

/*
* Column settings
*/
function gs_wc_bulk_edit_dt_colums_settings(){

	$gs_wc_bulk_edit_default_colums_settings = gs_wc_bulk_edit_default_colums_settings();
	$dt_colums_settings = get_option("dt_colums_settings");
	if (!empty($dt_colums_settings)) {
		$bs_columns_arr = $dt_colums_settings;
	}else{
		$bs_columns_arr = $gs_wc_bulk_edit_default_colums_settings;
	}
	return $bs_columns_arr;
}

/*
* $formate = php, json
* $entity_tyle = colums, columnDefs, meta_keys
*/
function gs_wc_bulk_edit_get_columns_settings($entity_type = '', $formate = 'php'){

	$dt_colums_settings = gs_wc_bulk_edit_dt_colums_settings();

	$data = [];

	$colums = [];
	$columnDefs = [];
	$meta_keys = [];
	$c = 0;
	foreach ($dt_colums_settings as $key => $value) {

		$column_option = ($value['column_option']) ? true : false;

	    if ($column_option) {

	       	$colums[] = ['data' => $value['column_name'], 'mData' => $value['column_name']];

	       	$column_orderable = ($value['column_orderable']) ? true : false;
			$columnDefs[$c] = [
				'orderable' => $column_orderable, //$value['column_orderable'],
				'targets' => $c
			];
	       
			if ($value['column_type'] == 'meta_key') {
				$meta_keys[] = $value['column_name'];
			}
	    } 
	    $c++;
	}

	$data = [
		'colums' => $colums,
		'columnDefs' => $columnDefs,
		'meta_keys' => $meta_keys
	];

	//return json_encode($data[$entity_type], JSON_PRETTY_PRINT);

	if ($entity_type == '') {
		return ($formate == 'php') ? $data : json_encode($data);
	}else{
		return ($formate == 'php') ? $data[$entity_type] : json_encode($data[$entity_type]);
	}
}