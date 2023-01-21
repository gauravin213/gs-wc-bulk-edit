jQuery(document).ready(function(){

	//global 
	var table_row_data_fetach = "";

	var oTable = jQuery('#empTable');

	var ocolumns = datab.dt_colums;
	var columnDefs = datab.columnDefs;

	ocolumns = jQuery.parseJSON(ocolumns);
	columnDefs = jQuery.parseJSON(columnDefs);
	//console.log('ocolumns: ', ocolumns);
	//console.log('columnDefs: ', columnDefs);

	jQuery.fn.draw_empty_table = function(){ 
	    oTable.DataTable().destroy();
	    oTable.DataTable({
	        'columns': ocolumns,
	        "searching": false, 
	    });
	    //update global var
	    table_row_data_fetach = [];
	}

	jQuery.fn.draw_data_table = function(){

		var bs_bulk_edit_action_switch = (jQuery('#bs_bulk_edit_action_switch').is(':checked')) ? 1 : 0;
		var bs_bulk_edit_action_switch_variation = (jQuery('#bs_bulk_edit_action_switch_variation').is(':checked')) ? 1 : 0;
		
	    //oTable.DataTable().destroy();
	    oTable.DataTable({

	    	'retrieve': true, //reload table without reinitialise
	    	//'stateSave': true,

	        'columns': ocolumns,
	        'columnDefs': columnDefs,

	        "searching": false, 
	        "aLengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
	        "iDisplayLength": 10,
	        
	        //'scrollY': 500,
			//'scrollX': true,

			'scrollY':        "300px",
	        'scrollX':        true,
	        'scrollCollapse': true,
	        'paging':         true,
	        'fixedColumns':   {
	            left: 4,
	            //right: 1
	        },

	        'processing': true,
	        'serverSide': true,
	        'serverMethod': 'post',
	        'ajax': {
	            url: datab.ajaxurl,
	            data : { 
	            	action: 'gs_wc_bulk_edit_load_row_action', 
	            	bs_bulk_edit_action_switch: bs_bulk_edit_action_switch,
	            	bs_bulk_edit_action_switch_variation: bs_bulk_edit_action_switch_variation
	            },
	            beforeSend: function(){  
	            	//console.log("beforeSend");
	            	jQuery('#tb_loader').show();
	            	jQuery('#bsOverlayLoader').addClass('bsOverlayLoaderClass');
		        },
		        complete: function(){
		        	//console.log("complete: ", table_row_data_fetach);
		        	jQuery('#tb_loader').hide();
		        	jQuery('#bsOverlayLoader').removeClass('bsOverlayLoaderClass');

		        	//scrollbar
					jQuery('.dataTables_scrollBody').scrollbar({
					    autoScrollSize: false,
					    scrollx: jQuery('.external-scroll_x'),
					    scrolly: jQuery('.external-scroll_y')
					});
					//scrollbar end 

		        },
	            dataFilter: function (data) {
	                var result = jQuery.parseJSON(data);
	                console.log(result);
	                //update global var
	                table_row_data_fetach = result;
	                //return JSON.stringify(result); 
	                return data;
	            }.bind(this),
	        },

	    });
	}

	//jQuery(this).draw_empty_table();
	jQuery(this).draw_data_table();


	//Select all
	jQuery("#checkAll").click(function () {
	     jQuery('.checkItem').not(this).prop('checked', this.checked);
	});

	
	//tb cell open model
	jQuery(document).on('click', '.be_td_cell', function(e){ 
		e.preventDefault();
		var target = jQuery(this);
		var post_id = target.attr('data-post_id');
		var column_type = target.attr('data-column_type');
		var column_label = target.attr('data-column_label');
		var column_name = target.attr('data-column_name');
		var column_val = target.attr('data-column_val');
		//alert("be_td_cell");
		console.log('post_id: ', post_id);
		console.log('column_type: ', column_type);
		console.log('column_label: ', column_label);
		console.log('column_name: ', column_name);
		console.log('column_val: ', column_val);
		if (column_type == 'post_table_attribute') {
		
			if (column_name == 'post_content' || column_name == 'post_excerpt') {

				var post_content = table_row_data_fetach.product_arr_data[post_id]['post_content'];
				var post_excerpt = table_row_data_fetach.product_arr_data[post_id]['post_excerpt'];
				var post_description = (column_name == 'post_content') ? post_content : post_excerpt;

				console.log('post_content: ', post_content);
				console.log('post_excerpt: ', post_excerpt);

				//cell edit
				jQuery('#bs_model_editor').find('.bs-body').html(`
					<div>
						<textarea 
							name="n_post_table_attribute" 
							id="n_post_table_attribute" 
							data-post_id="${post_id}" 
							data-column_type="${column_type}" 
							data-column_label="${column_label}" 
							data-column_name="${column_name}"
							data-column_val="${column_val}" 
							style="width: 100%;"
						></textarea>
						
						<div class="bs-footer">
							<a href="javascript://" class="button button-primary button-small" id="gs_save_description">Save</a>
						</div>

					</div>
				`);

				setTimeout(function(){

						jQuery('#n_post_table_attribute').val(post_description);

						wp.editor.remove('n_post_table_attribute', {
							tinymce: {
								wpautop: true,
								//width : "800",
								height : "300",
								plugins : 'charmap colorpicker compat3x directionality fullscreen hr image lists media paste tabfocus textcolor wordpress wpautoresize wpdialogs wpeditimage wpemoji wpgallery wplink wptextpattern wpview',
								toolbar1: 'bold italic underline strikethrough | bullist numlist | blockquote hr wp_more | alignleft aligncenter alignright | link unlink | fullscreen | wp_adv',
								toolbar2: 'formatselect alignjustify forecolor | pastetext removeformat charmap | outdent indent | undo redo | wp_help'
							},
							quicktags: true,
							mediaButtons: true,
						});

						wp.editor.initialize('n_post_table_attribute', {
							tinymce: {
								wpautop: true,
								//width : "800",
								height : "300",
								plugins : 'charmap colorpicker compat3x directionality fullscreen hr image lists media paste tabfocus textcolor wordpress wpautoresize wpdialogs wpeditimage wpemoji wpgallery wplink wptextpattern wpview',
								toolbar1: 'bold italic underline strikethrough | bullist numlist | blockquote hr wp_more | alignleft aligncenter alignright | link unlink | fullscreen | wp_adv',
								toolbar2: 'formatselect alignjustify forecolor | pastetext removeformat charmap | outdent indent | undo redo | wp_help'
							},
							quicktags: true,
							mediaButtons: true,
						});

					jQuery('#bs_model_editor').show();

				}, 100);
				//cell edit end

			}else{

				//cell edit
				jQuery('#empTable').find('.be_td_cell').show();
				jQuery('#empTable').find('.be-model').html("");
				target.hide();
				target.next().html(`
					<div>
					<input 
						type="text" 
						class="gs-enter-event"
						name="n_post_table_attribute" 
						id="n_post_table_attribute" 
						data-post_id="${post_id}" 
						data-column_type="${column_type}" 
						data-column_label="${column_label}" 
						data-column_name="${column_name}"
						data-column_val="${column_val}" 
					 	value="${column_val}" />
					</div>
				`);
				//cell edit end

			}
		
		}else if(column_type == 'meta_key'){

			//cell edit
			jQuery('#empTable').find('.be_td_cell').show();
			jQuery('#empTable').find('.be-model').html("");
			if (column_name == '_manage_stock') {
				target.hide();
				target.next().html(`
					<div>
					 	<select
							class="gs-meta_key-save-event"
							name="n_post_meta_key" 
							id="n_post_meta_key" 
							data-post_id="${post_id}" 
							data-column_type="${column_type}" 
							data-column_label="${column_label}" 
							data-column_name="${column_name}"
							data-column_val="${column_val}" 
						>
							<option value="yes">Yes</option>
							<option value="no">No</option>
						</select>
						<button class="button button-primary button-small" id="gs-meta_key-save-btn-event">Save</button>
					</div>
				`);
				jQuery('#n_post_meta_key').val(column_val).change();
			}else if(column_name == '_backorders'){
				target.hide();
				target.next().html(`
					<div>
					 	<select
							class="gs-meta_key-save-event"
							name="n_post_meta_key" 
							id="n_post_meta_key" 
							data-post_id="${post_id}" 
							data-column_type="${column_type}" 
							data-column_label="${column_label}" 
							data-column_name="${column_name}"
							data-column_val="${column_val}" 
						>
							<option value="no">Do not allow</option>
							<option value="notify">Allow, but notify customer</option>
							<option value="yes">Allow</option>		
						</select>
						<button class="button button-primary button-small" id="gs-meta_key-save-btn-event">Save</button>
					</div>
				`);
				jQuery('#n_post_meta_key').val(column_val).change();
			}else if(column_name == '_stock_status'){
				target.hide();
				target.next().html(`
					<div>
					 	<select
							class="gs-meta_key-save-event"
							name="n_post_meta_key" 
							id="n_post_meta_key" 
							data-post_id="${post_id}" 
							data-column_type="${column_type}" 
							data-column_label="${column_label}" 
							data-column_name="${column_name}"
							data-column_val="${column_val}" 
						>
							<option value="instock">In stock</option>
							<option value="outofstock">Out of stock</option>
							<option value="onbackorder">On backorder</option>			
						</select>
						<button class="button button-primary button-small" id="gs-meta_key-save-btn-event">Save</button>
					</div>
				`);
				jQuery('#n_post_meta_key').val(column_val).change();
			}else{
				target.hide();
				target.next().html(`
					<div>
					<input 
						type="text" 
						class="gs-meta_key-enter-event"
						name="n_post_meta_key" 
						id="n_post_meta_key" 
						data-post_id="${post_id}" 
						data-column_type="${column_type}" 
						data-column_label="${column_label}" 
						data-column_name="${column_name}"
						data-column_val="${column_val}" 
					 	value="${column_val}" />
					</div>
				`);
			}
			
			//cell edit end

		}else if(column_type == 'taxonomy'){


			//var column_val = table_row_data_fetach.taxonomy_arr_data[post_id][column_name]; //jQuery.parseJSON(column_val);
			var column_val = table_row_data_fetach.product_arr_data[post_id][column_name];
			var column_val_options = [];
			var select_htm = '<option value="0">select</option>';

			if (column_val.length > 0) {
				jQuery.each( column_val, function( index, text ) { // do not forget that "index" is just auto incremented value
	              column_val_options.push(text.term_id);
	              select_htm +=`<option value="${text.term_id}">${text.name}</option>`;
	        	});
			}

			console.log('column_val: ', column_val);
		
			//cell edit
			var htm = `
				<div>
				    <div>
				        <label>${column_label}</label>
				        <select name='taxonomy_action_select2[]' id="taxonomy_action_select2" multiple="multiple" data-post_id="${post_id}" data-column_type="${column_type}" data-column_label="${column_label}" data-column_name="${column_name}" data-column_val="">${select_htm}</select>
				    </div>
				    <input type="button" id="taxonomy_save_event" class="button button-primary insert-tag" value="Submit" />
			   </div>
			`;

			jQuery('#empTable').find('.be_td_cell').show();
			jQuery('#empTable').find('.be-model').html("");
			target.hide();
			target.next().html(`
				<div>
				${htm}
				</div>
			`);
			//cell edit end

			//select2 ajax
			jQuery('#taxonomy_action_select2').select2({
	            ajax: {
	              url: datab.ajaxurl, //"https://api.github.com/search/repositories",
	              dataType: 'json',
	              delay: 250, // delay in ms while typing when to perform a AJAX search
	              data: function (params) {  
	                  return {
	                    q: params.term, // search query
	                    action: 'gs_wc_bulk_edit_taxonomy_action_select2', // AJAX action for admin-ajax.php
	                    taxonomy_name: column_name,
	                  };
	              },
	              processResults: function( data ) {  
	                //console.log(data);
	                var options = [];
	                if ( data ) {
	                  	// data is the array of arrays, and each of them contains ID and the Label of the option
	                    jQuery.each( data, function( index, text ) { // do not forget that "index" is just auto incremented value
	                      options.push( { id: text[0], text: text[1]  } );
	                    });
	                }
	                return {
	                  results: options
	                };
	            },
	            cache: true
	            },
	            minimumInputLength: 3, // the minimum of symbols to input before perform a search
	            width: '100%',
	            placeholder : "select me" 
	        });
	        jQuery("#taxonomy_action_select2").val(column_val_options).trigger('change');
			
		}else{
			//
		}


	});


	jQuery.fn.gs_save_chages_ajax_fun = function(post_id, column_type, column_label, column_name, column_val, input_val){

		var page = jQuery('#page').val();
		var limit = jQuery('#limit').val();

		var bs_bulk_edit_action_switch = (jQuery('#bs_bulk_edit_action_switch').is(':checked')) ? 1 : 0;
		var bs_bulk_edit_action_switch_variation = (jQuery('#bs_bulk_edit_action_switch_variation').is(':checked')) ? 1 : 0;
		var bs_bulk_edit_action_switch_queue = (jQuery('#bs_bulk_edit_action_switch_queue').is(':checked')) ? 1 : 0;

		if (bs_bulk_edit_action_switch_queue == 1) {
			//start process
			jQuery(this).gs_processing_effect(500, 20, false);
			jQuery('#tb_loader').show();
			jQuery('#bsOverlayLoader').addClass('bsOverlayLoaderClass');
			jQuery('#gs-wc-bulk-edit-progressbar').text(`${page}%`).show();
		}
		
		const selectedValues = jQuery('.checkItem:checkbox:checked').map( function () { 
	        return jQuery(this).val(); 
	    })
	    .get();
	    console.log('selectedValues', selectedValues);

		//Ajax
		jQuery.ajax({
	        url: datab.ajaxurl,
	        type: "POST",
	        data: {
	        	'action': 'gs_wc_bulk_edit_save_chages_action',
	        	'post_id': post_id,
	        	'column_type': column_type,
	        	'column_label': column_label,
	        	'column_name': column_name,
	        	'column_val': column_val,
	        	'input_val': input_val,
	        	'selectedValues': selectedValues,
	        	'bs_bulk_edit_action_switch': bs_bulk_edit_action_switch,
	        	'bs_bulk_edit_action_switch_variation': bs_bulk_edit_action_switch_variation,
	        	'bs_bulk_edit_action_switch_queue': bs_bulk_edit_action_switch_queue,
	        	'page': page, 
	        	'limit':limit
	        },
	        cache: false,
	        dataType: 'json',
	        beforeSend: function(){
	        	if (bs_bulk_edit_action_switch_queue!=1) {
	        		jQuery('#tb_loader').show();
	            	jQuery('#bsOverlayLoader').addClass('bsOverlayLoaderClass');
	        	}
	        },
	        complete: function(){
	        	if (bs_bulk_edit_action_switch_queue!=1) {
	        		jQuery('#tb_loader').hide();
		        	jQuery('#bsOverlayLoader').removeClass('bsOverlayLoaderClass');
	        	}
	        },
	        success: function (response) { 

				if (bs_bulk_edit_action_switch_queue == 1) {

					//queue
	
					/*console.log('response: ', response);
					//reload table
					//oTable.DataTable().ajax.reload();
					oTable.DataTable().ajax.reload( null, false ); // user paging is not reset on reload
					jQuery('#checkAll').removeAttr('checked');
					jQuery("#bs_bulk_edit_action_switch").prop('checked', true).trigger("click");
					jQuery("#bs_bulk_edit_action_switch_queue").prop('checked', true).trigger("click");
					//end process
					jQuery(this).gs_processing_effect_clear();
					jQuery('#tb_loader').hide();
	        		jQuery('#bsOverlayLoader').removeClass('bsOverlayLoaderClass');*/
						
					console.log('page: ',page, ' response: ', response);
					if (response['data'].length > 0) {

						//columns: post_title, product_name, FirstName, Order ID
						//var htm = '';
						//jQuery.each(response['data'], function( index, value ) { 
						  	//htm += `<li>post_name: ${value['post_name']}</li>`;
						//});
						//jQuery('#wc_bulk_edit_output_diaplay').html(htm);

						//Step1 update page no.
						var pageNo = parseInt(page) + 1;
					    jQuery('#page').val(pageNo);
					    jQuery('#gs-wc-bulk-edit-progressbar').text(`${pageNo}%`);
						
						//Step2 ajax call back
						setTimeout(function(){
							jQuery(this).gs_save_chages_ajax_fun(post_id, column_type, column_label, column_name, column_val, input_val);
						}, 500);

					}else{
						//alert('Task completed');
						jQuery('#page').val(1);
						jQuery('#gs-wc-bulk-edit-progressbar').text("100%");
						setTimeout(function(){
							jQuery('#gs-wc-bulk-edit-progressbar').text("").hide();
						}, 8000);

						//reload table
						//oTable.DataTable().ajax.reload();
						oTable.DataTable().ajax.reload( null, false ); // user paging is not reset on reload

						jQuery('#checkAll').removeAttr('checked');
						jQuery("#bs_bulk_edit_action_switch").prop('checked', true).trigger("click");
						jQuery("#bs_bulk_edit_action_switch_queue").prop('checked', true).trigger("click");

						//end process
						jQuery(this).gs_processing_effect_clear();
						jQuery('#tb_loader').hide();
		        		jQuery('#bsOverlayLoader').removeClass('bsOverlayLoaderClass');
					}
					//queue end

				}else{

					console.log('save changes response: ', response);
					//jQuery(this).draw_data_table();

					//reload table
					//oTable.DataTable().ajax.reload();
					oTable.DataTable().ajax.reload( null, false ); // user paging is not reset on reload

					jQuery('#checkAll').removeAttr('checked');
					jQuery("#bs_bulk_edit_action_switch").prop('checked', true).trigger("click");

				}

	        }
	    });
		//Ajax end

	}

	//post_table_attribute save enter event
	jQuery(document).on('keyup', '.gs-enter-event', function(e){ 
		e.preventDefault();
		if (e.which === 13) {

			var target = jQuery('#n_post_table_attribute');
			var input_val = target.val();
			var post_id = target.attr('data-post_id');
			var column_type = target.attr('data-column_type');
			var column_label = target.attr('data-column_label');
			var column_name = target.attr('data-column_name');
			var column_val = target.attr('data-column_val');
			console.log('post_id: ', post_id);
			console.log('column_type: ', column_type);
			console.log('column_label: ', column_label);
			console.log('column_name: ', column_name);
			console.log('column_val: ', column_val);
			console.log('input_val: ', input_val);

			//var pp = jQuery(this).parent().parent().parent().text();
			//console.log('pp: ', pp);

			jQuery('#empTable').find('.be_td_cell').show();
			jQuery('#empTable').find('.be-model').html("");
	    
			jQuery(this).gs_save_chages_ajax_fun(post_id, column_type, column_label, column_name, column_val, input_val);


	    }
	});

	//post_table_attribute save description event
	jQuery(document).on('click', '#gs_save_description', function(e){ 
		e.preventDefault();

		var target = jQuery('#n_post_table_attribute');
		var post_id = target.attr('data-post_id');
		var column_type = target.attr('data-column_type');
		var column_label = target.attr('data-column_label');
		var column_name = target.attr('data-column_name');
		var column_val = target.attr('data-column_val');
		console.log('post_id: ', post_id);
		console.log('column_type: ', column_type);
		console.log('column_label: ', column_label);
		console.log('column_name: ', column_name);
		console.log('column_val: ', column_val);
		

		var editor_id = 'n_post_table_attribute';
		var mce_editor = tinymce.get(editor_id);
		var input_val = "";
		if(mce_editor) {
			input_val = wp.editor.getContent(editor_id); // Visual tab is active
		} else {
			input_val = jQuery('#'+editor_id).val(); // HTML tab is active
		}
		console.log('input_val: ', input_val);

		jQuery('.btn_bs_close').click();

		jQuery(this).gs_save_chages_ajax_fun(post_id, column_type, column_label, column_name, column_val, input_val);



	});


	//post_meta_key save enter keyup event
	jQuery(document).on('keyup', '.gs-meta_key-enter-event', function(e){ 
		e.preventDefault();
		if (e.which === 13) {

			var target = jQuery('#n_post_meta_key');
			var input_val = target.val();
			var post_id = target.attr('data-post_id');
			var column_type = target.attr('data-column_type');
			var column_label = target.attr('data-column_label');
			var column_name = target.attr('data-column_name');
			var column_val = target.attr('data-column_val');
			console.log('post_id: ', post_id);
			console.log('column_type: ', column_type);
			console.log('column_label: ', column_label);
			console.log('column_name: ', column_name);
			console.log('column_val: ', column_val);
			console.log('input_val: ', input_val);

			jQuery('#empTable').find('.be_td_cell').show();
			jQuery('#empTable').find('.be-model').html("");

	        jQuery(this).gs_save_chages_ajax_fun(post_id, column_type, column_label, column_name, column_val, input_val);

	    }
	});

	//post_meta_key save enter click event
	jQuery(document).on('click', '#gs-meta_key-save-btn-event', function(e){
		e.preventDefault();
		var target = jQuery('#n_post_meta_key');
		var input_val = target.val();
		var post_id = target.attr('data-post_id');
		var column_type = target.attr('data-column_type');
		var column_label = target.attr('data-column_label');
		var column_name = target.attr('data-column_name');
		var column_val = target.attr('data-column_val');
		console.log('post_id: ', post_id);
		console.log('column_type: ', column_type);
		console.log('column_label: ', column_label);
		console.log('column_name: ', column_name);
		console.log('column_val: ', column_val);
		console.log('input_val: ', input_val);

		jQuery('#empTable').find('.be_td_cell').show();
		jQuery('#empTable').find('.be-model').html("");

        jQuery(this).gs_save_chages_ajax_fun(post_id, column_type, column_label, column_name, column_val, input_val);
	});



	//taxonomy save event
	jQuery(document).on('click', '#taxonomy_save_event', function(e){ 
		e.preventDefault();

		var target = jQuery('#taxonomy_action_select2');
		var input_val = target.val();
		var post_id = target.attr('data-post_id');
		var column_type = target.attr('data-column_type');
		var column_label = target.attr('data-column_label');
		var column_name = target.attr('data-column_name');
		var column_val = target.attr('data-column_val');
		console.log('post_id: ', post_id);
		console.log('column_type: ', column_type);
		console.log('column_label: ', column_label);
		console.log('column_name: ', column_name);
		console.log('column_val: ', column_val);
		console.log('input_val: ', input_val);

		jQuery('#empTable').find('.be_td_cell').show();
		jQuery('#empTable').find('.be-model').html("");

		jQuery(this).gs_save_chages_ajax_fun(post_id, column_type, column_label, column_name, column_val, input_val);

	});



	



	


	/*
	* bs model
	*/
	jQuery(document).on('click', '#btn_bs_open', function(e){
		e.preventDefault();
		jQuery('#bs_model_editor').show();
	});
	jQuery(document).on('click', '.btn_bs_close', function(e){
		e.preventDefault();
		 wp.editor.remove('n_post_table_attribute', {
			tinymce: {
				wpautop: true,
				plugins : 'charmap colorpicker compat3x directionality fullscreen hr image lists media paste tabfocus textcolor wordpress wpautoresize wpdialogs wpeditimage wpemoji wpgallery wplink wptextpattern wpview',
				toolbar1: 'bold italic underline strikethrough | bullist numlist | blockquote hr wp_more | alignleft aligncenter alignright | link unlink | fullscreen | wp_adv',
				toolbar2: 'formatselect alignjustify forecolor | pastetext removeformat charmap | outdent indent | undo redo | wp_help'
			},
			quicktags: true,
			mediaButtons: true,
		});
		jQuery('#bs_model_editor').find('.bs-body').html("");
		jQuery('#bs_model_editor').hide();
	});
	/*
	* bs model
	*/


	//filter taxonomy select2 function
	jQuery.fn.gs_wc_bulk_edit_filter_taxonomy_select2_function = function(){

		jQuery('.bs-select-field').each(function(i, j){

			var target = jQuery(this);
			var taxonomy_name = target.attr('data-taxonomy');
			var title = target.attr('data-title');

			jQuery(`#bs-select-field-${taxonomy_name}`).select2({
			    ajax: {
			      url: datab.ajaxurl, //"https://api.github.com/search/repositories",
			      dataType: 'json',
			      delay: 250, // delay in ms while typing when to perform a AJAX search
			      data: function (params) {  
			      	var taxonomy_name = jQuery(this).attr('data-taxonomy');
			        return {
			            q: params.term, // search query
			            action: 'gs_wc_bulk_edit_taxonomy_action_select2', // AJAX action for admin-ajax.php
			            taxonomy_name: taxonomy_name, //column_name,
			        };
			      },
			      processResults: function( data ) {  
			        var options = [];
			        if ( data ) {
			          	// data is the array of arrays, and each of them contains ID and the Label of the option
			            jQuery.each( data, function( index, text ) { // do not forget that "index" is just auto incremented value
			              options.push( { id: text[0], text: text[1]  } );
			            });
			        }
			        return {
			          results: options
			        };
			    },
			    cache: true
			    },
			    //minimumInputLength: 3, // the minimum of symbols to input before perform a search
			    width: '99%',
			    placeholder : title
			});
			
		});

	}
	jQuery(this).gs_wc_bulk_edit_filter_taxonomy_select2_function();
	
	//Apply filter 
	jQuery(document).on('click', '#bs-filter-form-btn', function(){

	  	var fd = new FormData();
	  	var other_data = jQuery('#bs-filter-form').serializeArray();
	  	console.log('filter form data: ', other_data);
	  	jQuery.each(other_data,function(key,input){ //_method
	      	fd.append(input.name,input.value);
	  	});
	  
	  	jQuery.ajax({
		    url: datab.ajaxurl,
		    type: "POST",
		    data:  fd,
		    contentType: false,
		    processData:false,
		    dataType: 'json',
			beforeSend: function(){  
				jQuery('#tb_loader').show();
				jQuery('#bsOverlayLoader').addClass('bsOverlayLoaderClass');
			},
			complete: function(){
				jQuery('#tb_loader').hide();
				jQuery('#bsOverlayLoader').removeClass('bsOverlayLoaderClass');
			},
		    success: function(response){
		      	console.log('filter response: ', response);

		      	//oTable.DataTable().destroy();
		      	//jQuery(this).draw_data_table();

		      	//reload table
				//oTable.DataTable().ajax.reload();
				oTable.DataTable().ajax.reload( null, false ); // user paging is not reset on reload
				

		    },
		    error: function(xhr, status, error) {
		      	var err = eval("(" + xhr.responseText + ")");
		      	alert(err.Message);
		    }          
	  	});

	});

	//Clear filter 
	jQuery(document).on('click', '#bs-filter-form-clear-btn', function(){ //alert("clear");

	  	jQuery.ajax({
	        url: datab.ajaxurl,
	        type: "POST",
	        data: {'action': 'gs_wc_bulk_edit_clear_filter_action', 'productId': '123'},
	        cache: false,
	        dataType: 'json',
	        beforeSend: function(){  
				jQuery('#tb_loader').show();
			},
			complete: function(){
				jQuery('#tb_loader').hide();
			},
	        success: function (response) { 
	           console.log('response clear: ', response);
	           
	           	//oTable.DataTable().destroy();
		      	//jQuery(this).draw_data_table();

		      	//reload table
				//oTable.DataTable().ajax.reload();
				oTable.DataTable().ajax.reload( null, false ); // user paging is not reset on reload

	           //clear input text
	           jQuery('.bs-text-field').each(function(i, j){
		           	var target = jQuery(this);
		           	target.val("");
	           });

	           //clear select2 box
	           jQuery('.bs-select-field').each(function(i, j){
		           	var target = jQuery(this);
					var taxonomy_name = target.attr('data-taxonomy');
					var title = target.attr('data-title');
		           	jQuery(`#bs-select-field-${taxonomy_name}`).val("").trigger('change');
	           });


	        }
	    });

	});
	/*
	*  Filter
	*/


	//column sort settings
	jQuery("ul.bs-sort-columns").sortable();  

	//chnage order 
	jQuery(document).on('click', '#bs-save-column-order', function(){

	  	var fd = new FormData();
	  	var other_data = jQuery('#bs-sort-columns').serializeArray();
	  	console.log('other_data: ', other_data);
	  	jQuery.each(other_data,function(key,input){ //_method
	      	fd.append(input.name,input.value);
	  	});
	  
	  	jQuery.ajax({
		    url: datab.ajaxurl,
		    type: "POST",
		    data:  fd,
		    contentType: false,
		    processData:false,
		    dataType: 'json',
			beforeSend: function(){  
				jQuery('#tb_loader').show();
			},
			complete: function(){
				jQuery('#tb_loader').hide();
			},
		    success: function(response){ alert("Done!");
		      	console.log('sort response: ', response);
		    },
		    error: function(xhr, status, error) {
		      	var err = eval("(" + xhr.responseText + ")");
		      	alert(err.Message);
		    }          
	  	});

	});

	//reset order
	jQuery(document).on('click', '#bs-save-column-order-reset', function(){

		jQuery.ajax({
		    url: datab.ajaxurl,
		    type: "POST",
		    data:  { 
		    	'action': 'gs_wc_bulk_edit_column_sort_reset_action'
			},
		    dataType: 'json',
			beforeSend: function(){  
				jQuery('#tb_loader').show();
			},
			complete: function(){
				jQuery('#tb_loader').hide();
			},
		    success: function(response){ alert("Reset Done!");
		      	console.log('reset sort response: ', response);
		    }        
	  	});

	});

	//switchery
	//var elem = document.querySelector('.js-switch');
	//var init = new Switchery(elem);
	//var switchery = new Switchery(jQuery('.js-switch'), { size: 'small' });
	var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
	elems.forEach(function(html) {
	  var switchery = new Switchery(html);
	});
	jQuery('.js-switch').on("change" , function() {
		var target = jQuery(this);
		var column_option = target.parent().find('.column_option');
		if (target.is(':checked')) {
			column_option.val(1);
		}else{
			column_option.val("");
		}
	});

	//js-switch-main_page
	var js_switch_main_page = Array.prototype.slice.call(document.querySelectorAll('.js_switch_main_page'));
	js_switch_main_page.forEach(function(html) {
	  var js_switch_main_page = new Switchery(html);
	});

	jQuery('#bs_bulk_edit_action_switch_variation').on("change" , function() {
		var target = jQuery(this);
		oTable.DataTable().destroy();
		jQuery().draw_data_table();
	});
	// column sort settings end


	//gs_wc_bulk_edit processing effect

	var interval1 = '';
	var interval2 = '';

	jQuery.fn.gs_processing_change_size = function(size){
		jQuery('#gs_show_processsing')
		.css('color', '#fff')
		.css('background-color', '#228722')
		.css('border-radius', '2px')
		.css('padding', '4px')
		.css('margin', '4px')
		.css('font-weight', 'bold')
		.css('font-size', size);
	}

	jQuery.fn.gs_processing_effect = function(seconds, size){

		var half_seconds = seconds / 2;
		var half_size = parseInt(size) - 2;
		
		interval1 = setInterval(function () {
			jQuery(this).gs_processing_change_size(half_size);
		}, half_seconds);

		interval2 = setInterval(function () {
			jQuery(this).gs_processing_change_size(size);
		}, seconds);

		jQuery('#gs_show_processsing').show();
		
	}

	jQuery.fn.gs_processing_effect_clear = function(){
		clearInterval(interval1);
		clearInterval(interval2);
		jQuery('#gs_show_processsing').removeAttr('style').hide();
	}

	/*//start process
	jQuery(this).gs_processing_effect(500, 20, false);

	//end process
	setTimeout(function(){
		jQuery(this).gs_processing_effect_clear();
	}, 2000);*/

	//gs_wc_bulk_edit processing effect end


	//gs_wc_bulk_edit_filter_section_toggle_event
	var gs_wc_bulk_edit_filter_show_hide_state = localStorage.getItem("gs_wc_bulk_edit_filter_show_hide_state");
	jQuery("#gs_wc_bulk_edit_filter_section_toggle_body").hide();
	if (gs_wc_bulk_edit_filter_show_hide_state == 1) {
		jQuery("#gs_wc_bulk_edit_filter_section_toggle_body").show();
		jQuery("#gs_wc_bulk_edit_filter_section_toggle_event").text('Filter Hide');
	}else{
		jQuery("#gs_wc_bulk_edit_filter_section_toggle_body").hide();
		jQuery("#gs_wc_bulk_edit_filter_section_toggle_event").text('Filter Show');
	}
	jQuery(document).on('click', '#gs_wc_bulk_edit_filter_section_toggle_event', function(e){
		e.preventDefault();
		var target = jQuery(this);
		jQuery("#gs_wc_bulk_edit_filter_section_toggle_body").slideToggle(function() {
	        if (jQuery("#gs_wc_bulk_edit_filter_section_toggle_body").is(':hidden')) {
	        	target.text('Filter Show');
	        	localStorage.setItem("gs_wc_bulk_edit_filter_show_hide_state", 0);
	        } else {
	        	localStorage.setItem("gs_wc_bulk_edit_filter_show_hide_state", 1);
	        	target.text('Filter Hide');
	        }
	    });
	    //call
	    jQuery(this).gs_wc_bulk_edit_filter_taxonomy_select2_function();
	});
	//gs_wc_bulk_edit_filter_section_toggle_event end



});

/*
'retrieve': true, //reload table without reinitialise

//reload table
oTable.DataTable().ajax.reload();
oTable.DataTable().ajax.reload( null, false ); // user paging is not reset on reload
oTable.DataTable().destroy();

//var myTable = oTable.DataTable();
//myTable.clear().rows.add(myTable.data).draw();
*/