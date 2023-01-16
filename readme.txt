=== GS WC BULK EDIT PRODUCT ===
Contributors: gauravin213
Tags: wp-bulk, wp-bulk-edit, light bulk editor
Requires at least: 4.2
Tested up to: 6.1.1
Requires PHP: 5.3.0
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


== Description ==

WordPress plugin for woocommerce bulk edit and managing woocommerce products

**Support and Requests please in Github:** https://github.com/gauravin213/gs-wc-bulk-edit

### REQUIREMENTS

### PHP

**Minimum PHP version: 5.3.0**


### Product Filter 
	product_cat (terms)
	product_tag (terms)
	product_type (terms) (ignore)
	ID
	title
	slug
	sku


### FIELDS EDIT IN FREE VERSION OF THE PLUGIN

### product table attributes
	post_title
	post_name
	post_content
	post_excerpt

### product taxomony
	product_cat (terms)
	product_tag (terms)
	pa_color (terms)
	product taxomony `product_shipping_class` (terms)

### product meta 
	
	Sku meta keys: 
		_sku

	Price meta keys: 
		_price
		_regular_price
		_sale_price

	Inventory meta keys
		_stock_status
		_manage_stock
			_stock
			_backorders
			_low_stock_amount
		_sold_individually

	Shipping meta keys
		_weight
		_length
		_width
		_height


### SORT COLUMNS 

Here you are able to sort columns and sync attributes