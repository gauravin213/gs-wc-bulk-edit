<?php
defined( 'ABSPATH' ) or die();
global $wpdb;
$dt_colums_settings = gs_wc_bulk_edit_dt_colums_settings();
?>

<div class="wrap">

	<form id="bs-sort-columns" action="" method="post">

		<?php if (!empty($dt_colums_settings)) { ?>

			<ul class="bs-sort-columns">

				<?php foreach ($dt_colums_settings as $key => $value) { ?> 

					<li class="ui-sortable-handle bs-sort-column-item">
						<?php echo esc_html($value['column_label']); ?>
						<input type="hidden" name="data[<?php echo esc_attr($key); ?>][column_type]" value="<?php echo esc_attr($value['column_type']); ?>">
						<input type="hidden" name="data[<?php echo esc_attr($key); ?>][column_label]" value="<?php echo esc_attr($value['column_label']); ?>">
						<input type="hidden" name="data[<?php echo esc_attr($key); ?>][column_name]" value="<?php echo esc_attr($value['column_name']); ?>">
						<input type="hidden" class="column_option" name="data[<?php echo esc_attr($key); ?>][column_option]" value="<?php echo esc_attr($value['column_option']); ?>">
						<input type="hidden" name="data[<?php echo esc_attr($key); ?>][column_orderable]" value="<?php echo esc_attr($value['column_orderable']); ?>">
						<input type="checkbox" class="js-switch" value="1" <?php if ($value['column_option']){ echo esc_html("checked");}?>>
					</li>
				
				<?php } ?>

			</ul>

		<?php } ?>

		<input type="hidden" name="action" value="gs_wc_bulk_edit_column_sort_action">
		<button type="button" id="bs-save-column-order" name="bs-save-column-order" class="button button-primary button-larg">Save changes</button>
		<button type="button" id="bs-save-column-order-reset" name="bs-save-column-order" class="button button-primary button-larg">Re set</button>
		
	</form>

</div>