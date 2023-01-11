<?php

defined( 'ABSPATH' ) or die();

$dt_colums_settings = gs_wc_bulk_edit_dt_colums_settings();

?>

<div class="wrap">

	<div>
		<h3>GS WC Bulk Edit</h3>  
	</div>

	<div>
		<span id="gs_show_processsing">Processing</span>
	</div>

	<!--Filetr-->
	<div>
		<div id="gs_wc_bulk_edit_filter_section_toggle_body" class="gs_wc_bulk_edit_filter_section_toggle_body">
			<form id="bs-filter-form" class="bs-filter-form" action="" method="post">
				<div class="bs-row">

					<div class="bs-col-2">
						<div class="bs-field">
							<input type="text" class="bs-text-field" name="posts[ID]" placeholder="ID" value="<?php echo gs_wc_bulk_edit_get_filter_text_val('posts', 'ID');?>">
						</div>
						<div class="bs-field">
							<input type="text" class="bs-text-field" name="posts[post_title]" placeholder="Title" value="<?php echo gs_wc_bulk_edit_get_filter_text_val('posts', 'post_title');?>">
						</div>
						<div class="bs-field">
							<input type="text" class="bs-text-field" name="posts[post_name]" placeholder="Slug" value="<?php echo gs_wc_bulk_edit_get_filter_text_val('posts', 'post_name');?>">
						</div>
						<div class="bs-field">
							<input type="text" class="bs-text-field" name="metadata[_sku]" placeholder="SKU" value="<?php echo gs_wc_bulk_edit_get_filter_text_val('metadata', '_sku');?>">
						</div>
					</div>

					<div class="bs-col-2">
						<div class="bs-field">
							<select class="bs-select-field" id="bs-select-field-product_type" multiple="multiple" name="taxonomy[product_type][]" data-taxonomy="product_type" data-title="Product type">
								<?php gs_wc_bulk_edit_get_filter_select_val('product_type'); ?>
							</select>
						</div>
						<div class="bs-field">
							<select class="bs-select-field" id="bs-select-field-product_cat" multiple="multiple" name="taxonomy[product_cat][]" data-taxonomy="product_cat" data-title="Categories">
								<?php gs_wc_bulk_edit_get_filter_select_val('product_cat'); ?>
							</select>
						</div>
						<div class="bs-field">
							<select class="bs-select-field" id="bs-select-field-product_tag" multiple="multiple" name="taxonomy[product_tag][]" data-taxonomy="product_tag" data-title="Tags">
								<?php gs_wc_bulk_edit_get_filter_select_val('product_tag'); ?>
							</select>
						</div>
						<!-- <div class="bs-field">
							<select class="bs-select-field" id="bs-select-field-pa_color" multiple="multiple" name="taxonomy[pa_color][]" data-taxonomy="pa_color" data-title="Color(attr)">
								<?php gs_wc_bulk_edit_get_filter_select_val('pa_color'); ?>
							</select>
						</div> -->
					</div>
				</div>

				<div class="bs-field-submit-btn">
					<button type="button" id="bs-filter-form-btn" class="button button-primary button-small">Filter</button>
					<button type="button" id="bs-filter-form-clear-btn" class="button button-primary button-small">Clear</button>
					<input type="hidden" name="action" value="gs_wc_bulk_edit_filter_action">
				</div>
			</form>
		</div>
		<!-- <button type="button" id="gs_wc_bulk_edit_filter_section_toggle_event" class="button button-primary button-small">Filter Show</button> -->
	</div>


	<div>
		<!--bs model-->
		<!-- <a href="javascript://" id="btn_bs_open">Open</a> -->
		<div id="bs_model_editor" class="bs-model" style="display: none;">
			<div class="bs-modal-content">
				<div class="bs-header">
					<span class="bs-close"><a href="javascript://" class="btn_bs_close">close</a></span>
				</div>
				<div class="bs-body">
					<h1>Hellow world</h1>
				</div>
				<div class="bs-footer"></div>
			</div>
		</div>
		<!--bs model end-->

		<style type="text/css">
			.bs-switch-sections {
			    display: inline-flex;
			    padding: 5px;
			}
			.bs-switch-section {
			    margin: 5px;
			}
		</style>
		<div class="bs-switch-sections">

			<div class="bs-switch-section">
				<button type="button" id="gs_wc_bulk_edit_filter_section_toggle_event" class="button button-primary button-small">Filter Show</button>
			</div>
			<div class="bs-switch-section">
				<label>Binded Editing</label>
				<input class="js_switch_main_page" type="checkbox" name="bs_bulk_edit_action_switch" id="bs_bulk_edit_action_switch" value="">
			</div>
			<div class="bs-switch-section">
				<label>Variations</label>
				<input class="js_switch_main_page" type="checkbox" name="bs_bulk_edit_action_switch_variation" id="bs_bulk_edit_action_switch_variation" value="">
			</div>
			<div class="bs-switch-section">
				<label>Binded Queue</label>
				<input class="js_switch_main_page" type="checkbox" name="bs_bulk_edit_action_switch_queue" id="bs_bulk_edit_action_switch_queue" value="">
			</div>

			<div class="bs-switch-section">
				<label>
					<span id="gs-wc-bulk-edit-progressbar" style="display: none;"><span>
				</label>
			</div>
			<div class="bs-switch-section" style="display: none;">
				<label>
					<strong>Page: </strong>
					<input type="text" name="page" id="page" value="1" disabled>
				</label>
			</div>
			<div class="bs-switch-section" style="display: none;">
				<label>
					<strong>Limit: </strong>
					<input type="text" name="limit" id="limit" value="100" disabled>
				</label>
			</div>

			<div class="bs-switch-section">
				<snap id="tb_loader" style="color:orange;">Loading...</snap>
			</div>
			
		</div>
		
	    <div>
	    	<div id="bsOverlayLoader"></div>

	        <table id='empTable' class='display dataTable'>
	            <thead>
	                <tr>
	                <?php
	                foreach ($dt_colums_settings as $key => $value) {
	                    if ($value['column_option']) {
	                        if ($value['column_type'] == 'post_table_id') {
	                            ?>
	                            <th data-ppp="123" class="fix-col"><input type="checkbox" id="checkAll"></th>
	                            <?php
	                        }else{
	                            ?><th data-ppp="123"><?php echo $value['column_label']?></th><?php
	                        }
	                    } 
	                }
	                ?>
	            </tr>
	            </thead>
	        </table>
	    </div>


	    <!--scroll-->
		<div class="external-scroll_wrapper">
		    <div class="external-scroll_x">
		        <div class="scroll-element_outer">
		            <div class="scroll-element_size"></div>
		            <div class="scroll-element_track"></div>
		            <div class="scroll-bar"></div>
		        </div>
		    </div>
		    <div class="external-scroll_y">
		        <div class="scroll-element_outer">
		            <div class="scroll-element_size"></div>
		            <div class="scroll-element_track"></div>
		            <div class="scroll-bar"></div>
		        </div>
		    </div>
		</div>
		<!--scroll-->

	</div>

</div>