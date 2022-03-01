<?php

//////////////////////////////////////////////////////////////
//===========================================================
// PAGELAYER
// Inspired by the DESIRE to be the BEST OF ALL
// ----------------------------------------------------------
// Started by: Pulkit Gupta
// Date:	   23rd Jan 2017
// Time:	   23:00 hrs
// Site:	   http://pagelayer.com/wordpress (PAGELAYER)
// ----------------------------------------------------------
// Please Read the Terms of use at http://pagelayer.com/tos
// ----------------------------------------------------------
//===========================================================
// (c)Pagelayer Team
//===========================================================
//////////////////////////////////////////////////////////////

// Are we being accessed directly ?
if(!defined('PAGELAYER_VERSION')) {
	exit('Hacking Attempt !');
}

add_filter('wp_nav_menu_items', 'popularfx_add_woo_cart', 10, 2);
add_filter('wp_page_menu', 'popularfx_add_woo_cart', 10, 2);
function popularfx_add_woo_cart($items, $args) {

	if( get_theme_mod( 'enable_menu_cart' ) ) {
		$items .= '<li class="page-item pfx-menu-cart cart-customlocation menu-item menu-item-type-post_type menu-item-object-page"><a href=""><span class="dashicons dashicons-cart"></span><sup></sup></a></li>';
	}
	
	return $items;
	
}

// WooCommerce Checkout Fields Hook
add_filter('woocommerce_checkout_fields','popularfx_wc_checkout_fields_no_label',10);

// Our hooked in function - $fields is passed via the filter!
// Action: remove label from $fields
function popularfx_wc_checkout_fields_no_label($fields) {
	
	if( get_theme_mod( 'enable_label_placeholder' ) ) {
		
		// loop by category
		foreach ($fields as $category => $value) {
			// loop by fields
			foreach ($fields[$category] as $field => $property) {
				
				//Add label as placeholder
				if( $fields[$category][$field]['required'] == true ){
					//Add required * in placeholder
					$fields[$category][$field]['placeholder'] = $fields[$category][$field]['label'] .' *';
				}else{
					//Add (optional) in placeholder
					$fields[$category][$field]['placeholder'] = $fields[$category][$field]['label'] .'(optional)';
				}
				
				// remove label property
				unset($fields[$category][$field]['label']);
			}
		}
		
	}
	
	return $fields;
}

// CHange number of related product on single page
add_filter( 'woocommerce_output_related_products_args', 'popularfx_single_product_number_related_products', 9999 );
function popularfx_single_product_number_related_products( $args ) {
	
	$args['posts_per_page'] = get_theme_mod( 'number_related_product' ); // # of related products
	$args['columns'] = get_theme_mod( 'number_related_product' ); // # of columns per row
	
	return $args;

}

add_action( 'customize_controls_print_scripts', 'popularfx_wc_add_scripts'  );
function popularfx_wc_add_scripts(){
?>
<script>

// Script to load Shop page when user click woocommerce customizer
jQuery( function( $ ) {
	wp.customize.panel( 'woocommerce', function( panel ) {
		panel.expanded.bind( function( isExpanded ) {
			if ( isExpanded ) {
				wp.customize.previewer.previewUrl.set( '<?php echo esc_js( wc_get_page_permalink( 'shop' ) ); ?>' );
			}
		} );
	} );
	wp.customize.section( 'pfx_woo_cart_page', function( section ) {
		section.expanded.bind( function( isExpanded ) {
			if ( isExpanded ) {
				wp.customize.previewer.previewUrl.set( '<?php echo esc_js( wc_get_page_permalink( 'cart' ) ); ?>' );
			}
		} );
	} );
	wp.customize.section( 'pfx_woo_myaccount_page', function( section ) {
		section.expanded.bind( function( isExpanded ) {
			if ( isExpanded ) {
				wp.customize.previewer.previewUrl.set( '<?php echo esc_js( wc_get_page_permalink( 'myaccount' ) ); ?>' );
			}
		} );
	} );
	wp.customize.section( 'pfx_woo_shop_pagination', function( section ) {
		section.expanded.bind( function( isExpanded ) {
			if ( isExpanded ) {
				wp.customize.previewer.previewUrl.set( '<?php echo esc_js( wc_get_page_permalink( 'shop' ) ); ?>' );
			}
		} );
	} );
	wp.customize.section( 'pfx_woo_general', function( section ) {
		section.expanded.bind( function( isExpanded ) {
			if ( isExpanded ) {
				wp.customize.previewer.previewUrl.set( '<?php echo esc_js( wc_get_page_permalink( 'shop' ) ); ?>' );
			}
		} );
	} );
});
</script>
<?php
}

add_action( 'wp_head', 'popularfx_woocommerce_styles', 1000 );
function popularfx_woocommerce_styles(){
	
	$styles = '<style id="popularfx-woocommerce-styles" type="text/css">'.PHP_EOL;
	
	// Show / Hide on sale
	$disable_onsale = get_theme_mod('disable_onsale');
	if(!empty($disable_onsale)){
		$styles .= '.woocommerce .product span.onsale { display : none; } ';			
	}else{
		$styles .= '.woocommerce .product span.onsale { display : block; background-color: '. get_theme_mod('pfx_woo_onsale_bg_color', '#FF2626') .'; color: '. get_theme_mod('pfx_woo_onsale_color', '#FFFFFF') .'; border-radius: '. get_theme_mod('onsale_radius', 100) .'%;} ';
	}
	
	// Show / Hide star rating
	$disable_starrating = get_theme_mod('disable_starrating');
	$styles .= '.woocommerce ul.products li.product .star-rating.pfx-star-rating { display : '. (empty($disable_starrating) ? 'block' : 'none') .'; }'.PHP_EOL;
	
	// Show / Hide Related product
	$disable_related_product = get_theme_mod('disable_related_product');
	$styles .= '.product section.related.products{ display : '. (empty($disable_related_product) ? 'block' : 'none') .'; }'.PHP_EOL;
	
	// Show / Hide Upsells
	$disable_upsells = get_theme_mod('disable_upsells');
	$styles .= '.product section.up-sells.upsells.products { display : '. (empty($disable_upsells) ? 'block' : 'none') .';}'.PHP_EOL;
	
	// Show / Hide Cross sells
	$disable_cross_sells = get_theme_mod('disable_cross_sells');
	$styles .= '.woocommerce .cart-collaterals .cross-sells, .woocommerce-page .cart-collaterals .cross-sells{ display : '. (empty($disable_cross_sells) ? 'block' : 'none') .';}'.PHP_EOL;
	
	// Show / Hide Order Note
	$disable_order_note = get_theme_mod('disable_order_note');
	$styles .= '.woocommerce-additional-fields{ display : '. (empty($disable_order_note) ? 'block' : 'none') .';}'.PHP_EOL;
	
	// Show / Hide Coupon Field
	$disable_coupon_field = get_theme_mod('disable_coupon_field');
	$styles .= '.woocommerce-form-coupon-toggle{ display : '. (empty($disable_coupon_field) ? 'block' : 'none') .';}'.PHP_EOL;
	
	// Show / Hide Product breadcrumb
	$disable_product_breadcrumb = get_theme_mod('disable_product_breadcrumb');
	$styles .= '.single-product nav.woocommerce-breadcrumb{ display : '. (empty($disable_product_breadcrumb) ? 'block' : 'none') .';}'.PHP_EOL;
	
	// Show / Hide Product description
	$disable_product_description = get_theme_mod('disable_product_description');
	$styles .= '.single-product .woocommerce-tabs.wc-tabs-wrapper{ display : '. (empty($disable_product_description) ? 'block' : 'none') .';}'.PHP_EOL;
	
	$styles .= '.woocommerce div.product .woocommerce-tabs .panel .woocommerce-Reviews #comments .commentlist .star-rating, .woocommerce div.product .woocommerce-product-rating, .woocommerce ul.products li.product .star-rating, .woocommerce ul.products li.product .star-rating.pfx-star-rating, .woocommerce ul.product_list_widget li .star-rating { color: '. get_theme_mod('pfx_woo_starrating_color', '#5c7aea') . '; }'.PHP_EOL;
	$styles .= '.woocommerce ul.products li.product .star-rating:hover, .woocommerce ul.products li.product .star-rating.pfx-star-rating:hover { color: '. get_theme_mod('pfx_woo_starrating_hover_color', '#000000') . '; }'.PHP_EOL;

	$styles .= 'li.cart-customlocation span.dashicons.dashicons-cart { color: '. get_theme_mod('menu_cart_color', 'inherit') . '; }'.PHP_EOL;
	$styles .= 'li.cart-customlocation span.dashicons.dashicons-cart + sup{ color: '. get_theme_mod('menu_cart_number_color', 'inherit') . '; }'.PHP_EOL;

	$styles .= '.woocommerce #content div.product div.images, .woocommerce div.product div.images, .woocommerce-page #content div.product div.images, .woocommerce-page div.product div.images { width: '. get_theme_mod('product_image_width', 48) . '% !important; } 
	.woocommerce #content div.product div.summary, .woocommerce div.product div.summary, .woocommerce-page #content div.product div.summary, .woocommerce-page div.product div.summary { width: calc(96% - '. get_theme_mod('product_image_width', 48) . '%) !important; }'.PHP_EOL;
	
	$def_pad = get_theme_mod('pfx_woo_default_padding', 15);
	$styles .= ( !empty(get_theme_mod('pfx_woo_shop_padding')) ) ? 'body.post-type-archive-product .site-main { padding: '. get_theme_mod('pfx_woo_shop_padding', 15) . 'px ; }' : 'body.post-type-archive-product .site-main { padding: '. $def_pad . 'px ; } ';
	
	$styles .= ( !empty(get_theme_mod('pfx_woo_product_padding')) ) ? 'body.product-template-default.single-product main.site-main { padding: '. get_theme_mod('pfx_woo_product_padding', 15) . 'px ; }' : 'body.product-template-default.single-product main.site-main { padding: '. $def_pad . 'px ; } ';
	
	$styles .= ( !empty(get_theme_mod('pfx_woo_cart_padding')) ) ? 'body.woocommerce-cart main.site-main { padding: '. get_theme_mod('pfx_woo_cart_padding', 15) . 'px ; }' : 'body.woocommerce-cart main.site-main { padding: '.$def_pad . 'px ; } ';
	
	$styles .= ( !empty(get_theme_mod('pfx_woo_checkout_padding')) ) ? 'body.woocommerce-checkout main.site-main { padding: '. get_theme_mod('pfx_woo_checkout_padding', 15) . 'px ; }' : 'body.woocommerce-checkout main.site-main { padding: '.$def_pad . 'px ; } ';
	
	$styles .= ( !empty(get_theme_mod('pfx_woo_myaccount_padding')) ) ? 'body.woocommerce-account main.site-main { padding: '. get_theme_mod('pfx_woo_myaccount_padding', 15) . 'px ; }' : 'body.woocommerce-account main.site-main { padding: '.$def_pad . 'px ; } ';
	 
	$styles .= 'p.woocommerce-store-notice.demo_store { background-color: '. get_theme_mod('pfx_woo_storenotice_bg_color', '#5c7aea') . '; } p.woocommerce-store-notice.demo_store, p.woocommerce-store-notice.demo_store a{color: '. get_theme_mod('pfx_woo_storenotice_color', '#FFFFFF') . '; }'.PHP_EOL;	
	$styles .= '.woocommerce main nav.woocommerce-pagination ul li a { background-color: '. get_theme_mod('shoppagination_bg_color', '#FFFFFF') . '; color: '. get_theme_mod('shoppagination_color', '#000000') . '; border: '. get_theme_mod('shoppagination_borderwidth', 1) . 'px solid ; border-color: '. get_theme_mod('shoppagination_border_color', 'transparent') . '; border-radius: '. get_theme_mod('shoppagination_borderradius', 0) . 'px;}'.PHP_EOL;
	$styles .= '.woocommerce main nav.woocommerce-pagination ul li a:focus, .woocommerce main nav.woocommerce-pagination ul li a:hover, .woocommerce main nav.woocommerce-pagination ul li span.current { background-color: '. get_theme_mod('shoppagination_bg_hover_color', '#000000') . '; color: '. get_theme_mod('shoppagination_hover_color', '#ffffff') . '; border: '. get_theme_mod('shoppagination_borderwidth', 1) . 'px solid ; border-color: '. get_theme_mod('shoppagination_hover_border_color', 'transparent') . '; border-radius: '. get_theme_mod('shoppagination_borderradius', 0) . 'px; }'.PHP_EOL;
	
	//Woocommerce Button Styling from Customizer
	$styles .= '.woocommerce .product #respond input#submit, .woocommerce .product a.button, .woocommerce button.button, .woocommerce input.button, .woocommerce .product button.button, .woocommerce .product input.button, .woocommerce .product #respond input#submit.alt, .woocommerce .product a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt .woocommerce .product button.button.alt, .woocommerce .product input.button.alt, .woocommerce a.button.alt { padding: '. get_theme_mod('pfx_woo_btn_padding') . 'px; background-color: '. get_theme_mod('pfx_woo_btn_bg_color', '#5c7aea') . ';  color: '. get_theme_mod('pfx_woo_btn_color', '#FFFFFF') . '; border-width: '. get_theme_mod('pfx_woo_btn_border', 0).'px; border-color: '. get_theme_mod('pfx_woo_btn_border_color', '#FFFFFF').'; border-radius: '. get_theme_mod('pfx_woo_btn_border_radius', 5 ).'px;}'.PHP_EOL;
	$styles .= '.woocommerce .product #respond input#submit:hover, .woocommerce .product a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover, .woocommerce .product button.button:hover, .woocommerce .product input.button:hover, .woocommerce .product #respond input#submit.alt:hover, .woocommerce .product a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover .woocommerce .product button.button.alt:hover, .woocommerce .product input.button.alt:hover, .woocommerce a.button.alt:hover { background-color: '. get_theme_mod('pfx_woo_btn_hover_bg_color', '#000000') . ' !important;   color: '. get_theme_mod('pfx_woo_btn_hover_color', '#FFFFFF') . ' !important; border: '. get_theme_mod('pfx_woo_btn_hover_border', 0 ).'px; border-color: '. get_theme_mod('pfx_woo_btn_hover_border_color', '#FFFFFF').' !important; border-radius: '. get_theme_mod('pfx_woo_btn_hover_border_radius', 5).'px !important; }'.PHP_EOL;
	
	$styles .= '.single-product .woocommerce-breadcrumb, .single-product .woocommerce-breadcrumb * { color: '. get_theme_mod('product_breadcrumb_color', '#767676') . '; } ';
	$styles .= '.single-product div.product .woocommerce-product-details__short-description, .single-product div.product .woocommerce-product-details__short-description p, .single-product div.product .product_meta, .single-product div.product .entry-content { color: '. get_theme_mod('product_description_color', '#444444') . '; }'.PHP_EOL;
	$styles .= '.woocommerce ul.products li.product .price, .woocommerce div.product p.price, .woocommerce div.product span.price { color: '. get_theme_mod('product_price_color', '#111111') . '; }'.PHP_EOL;
	$styles .= '.single-product div.product .entry-title { color: '. get_theme_mod('product_title_color', '#000000') . '; }'.PHP_EOL;
	
	$styles .= PHP_EOL.'</style>';
	
	echo $styles;
}

// PopularFX + WooCommerce customizer
add_action( 'customize_register', 'popularfx_woocommerce_customizer', 100 );
function popularfx_woocommerce_customizer($wp_customize){
		
	//PopularFX + WooCommerce Panel
	$wp_customize->add_panel( 'woocommerce', array(
		'priority'       => 10,
		'title'          => __( 'PopularFX + WooCommerce', 'popularfx' ),
	) );
	
	//Woocommerce Sections
	$wp_customize->add_section( 'woocommerce_store_notice', array(
		'title'    => __( 'Store Notice', 'popularfx' ),
		'priority' => 1,
		'panel'    => 'woocommerce',
	) );
	
	$wp_customize->add_section( 'pfx_woo_general', array(
		'capability' => 'edit_theme_options',
		'priority'   => 2,
		'title'      => __( 'General','popularfx'),
		'panel'      => 'woocommerce',
		'description' => __( 'These options let you change the appearance of the basics WooCommerce design.', 'woocommerce' ),
	) );
		
	$wp_customize->add_section( 'pfx_woo_btn', array(
		'capability' => 'edit_theme_options',
		'priority'   => 3,
		'title'      => __( 'WooCommerce Buttons','popularfx'),
		'panel'      => 'woocommerce',
	) );

	$wp_customize->add_section( 'woocommerce_product_catalog', array(
		'title'    => __( 'Shop Page/Product Catalog', 'popularfx' ),
		'priority' => 4,
		'panel'    => 'woocommerce',
	) );
	
	$wp_customize->add_section( 'pfx_woo_single_product', array(
		'title'    => __( 'Single Product', 'popularfx' ),
		'priority' => 5,
		'panel'    => 'woocommerce',
		'description' => __( 'These options let you change the appearance of the WooCommerce single product page.', 'woocommerce' ),
	) );

	$wp_customize->add_section( 'pfx_woo_cart_page', array(
		'title'    => __( 'Cart Page Design', 'popularfx' ),
		'priority' => 6,
		'panel'    => 'woocommerce',
		'description' => __( 'These options let you change the appearance of the WooCommerce cart page.', 'woocommerce' ),
	) );

	$wp_customize->add_section( 'woocommerce_checkout', array(
		'title'       => __( 'Checkout Page Design', 'woocommerce' ),
		'priority'    => 7,
		'panel'       => 'woocommerce',
	) );

	$wp_customize->add_section( 'pfx_woo_myaccount_page', array(
		'title'    => __( 'Myaccount Page Design', 'popularfx' ),
		'priority' => 8,
		'panel'    => 'woocommerce',
		'description' => __( 'These options let you change the appearance of the WooCommerce myaccount page.', 'woocommerce' ),
	) );
	
	$wp_customize->add_section( 'pfx_woo_shop_pagination', array(
		'capability' => 'edit_theme_options',
		'priority'   => 9,
		'title'      => __( 'Shop Page Pagination Design','popularfx'),
		'panel'      => 'woocommerce',
	) );
	
	// WooCommerce default page padding
	$wp_customize->add_setting('pfx_woo_default_padding', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => 15,
		'sanitize_callback' => 'absint',
	) );
	
	$wp_customize->add_control( 'pfx_woo_default_padding', array(
		'type' => 'number',
		'priority' => 1,
		'section' => 'pfx_woo_general',
		'description' => 'Set container default padding in "px"',
		'settings' => 'pfx_woo_default_padding',
		'label' => __('Default Container Padding', 'popularfx' ),
		'input_attrs' => array(
			'min' => 0,
			'max' => 200,
			'step' => 1,
		),
	) );
	
	// Sale Notification(General Setting)
	$wp_customize->add_setting( 'disable_onsale', array(
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'default' => 0,
		'sanitize_callback' => 'popularfx_switch_sanitization',
	) );
	
	$wp_customize->add_control( 'disable_onsale', array(
		'type' => 'checkbox',
		'section' => 'pfx_woo_general',
		'priority' => 1,
		'settings' => 'disable_onsale',
		'label' => __('Disable Sale Notification', 'popularfx' )
	) );
	
	$wp_customize->add_setting( 'onsale_radius', array(
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'default' => 100,
		'sanitize_callback' => 'absint',
	) );
		
	$wp_customize->add_control( 'onsale_radius', array(
		'type' => 'number',
		'section' => 'pfx_woo_general',
		'settings' => 'onsale_radius',
		'label' => __( 'Sale Notification Radius', 'popularfx' ),
		'description' => __( 'Set the border radius for Sale Notification','popularfx'),
		'input_attrs' => array(
			'min' => 0,
			'max' => 100,
			'step' => 1,
		),
	) );
	
	$wp_customize->add_setting('pfx_woo_onsale_bg_color', array(
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'default' => '#FF2626',
		'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
	) );
	
	$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
		$wp_customize, 'pfx_woo_onsale_bg_color', array(
			'section' => 'pfx_woo_general',
			'description' => 'Set Background color for Sale Notification',
			'settings' => 'pfx_woo_onsale_bg_color',
			'label' => __('Sale Background', 'popularfx' ),
			'show_opacity' => true
		)
	) );
	
	$wp_customize->add_setting('pfx_woo_onsale_color', array(
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'default' => '#FFFFFF',
		'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
	) );
	
	$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
		$wp_customize, 'pfx_woo_onsale_color', array(
			'section' => 'pfx_woo_general',
			'description' => 'Set text color for Sale Notification',
			'settings' => 'pfx_woo_onsale_color',
			'label' => __('Sale Text Color', 'popularfx' ),
			'show_opacity' => true
		)
	) );
	
	//Shop Star Rating(General Setting)
	$wp_customize->add_setting( 'disable_starrating', array(
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'default' => 0,
		'sanitize_callback' => 'popularfx_switch_sanitization',
	) );
	
	$wp_customize->add_control( 'disable_starrating', array(
		'type' => 'checkbox',
		'section' => 'pfx_woo_general',
		'settings' => 'disable_starrating',
		'label' => __('Disable Star-Rating on Shop Page', 'popularfx' )
	) );
	
	$wp_customize->add_setting('pfx_woo_starrating_color', array(
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'default' => '#5c7aea',
		'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
	) );
	
	$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
		$wp_customize, 'pfx_woo_starrating_color', array(
			'section' => 'pfx_woo_general',
			'description' => 'Set color for star rating',
			'settings' => 'pfx_woo_starrating_color',
			'label' => __('Star Rating Color', 'popularfx' ),
			'show_opacity' => true
		)
	) );
	
	$wp_customize->add_setting('pfx_woo_starrating_hover_color', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => '#000000',
		'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
	) );
	
	$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
		$wp_customize, 'pfx_woo_starrating_hover_color', array(
			'section' => 'pfx_woo_general',
			'description' => 'Set hover color for star rating',
			'settings' => 'pfx_woo_starrating_hover_color',
			'label' => __('Star Rating Hover Color', 'popularfx' ),
			'show_opacity' => true
		)
	) );
	
	//Header Menu Cart(General Setting)
	$wp_customize->add_setting( 'enable_menu_cart', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => 0,
		'sanitize_callback' => 'popularfx_switch_sanitization',
	) );
	
	$wp_customize->add_control( 'enable_menu_cart', array(
		'type' => 'checkbox',
		'section' => 'pfx_woo_general',
		'settings' => 'enable_menu_cart',
		'label' => __('Show Cart Icon in Header Menu', 'popularfx' )
	) );
	
	$wp_customize->add_setting('menu_cart_color', array(
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'default' => '',
		'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
	) );
	
	$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
		$wp_customize, 'menu_cart_color', array(
			'section' => 'pfx_woo_general',
			'description' => 'Set menu cart icon color by default menu color is applied',
			'settings' => 'menu_cart_color',
			'label' => __('Header Menu Cart Color', 'popularfx' ),
			'show_opacity' => true
		)
	) );
	
	$wp_customize->add_setting('menu_cart_number_color', array(
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'default' => '',
		'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
	) );
	
	$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
		$wp_customize, 'menu_cart_number_color', array(
			'section' => 'pfx_woo_general',
			'description' => 'Set menu cart number color by default menu color is applied',
			'settings' => 'menu_cart_number_color',
			'label' => __('Header Menu Cart Numbers Color', 'popularfx' ),
			'show_opacity' => true
		)
	) );
		
	//WooCommerece Button
	$wp_customize->add_setting( 'pfx_woo_btn_padding', array(
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'sanitize_callback' => 'absint',
	) );
		
	$wp_customize->add_control( 'pfx_woo_btn_padding', array(
		'priority' => 1,
		'type' => 'number',
		'section' => 'pfx_woo_btn',
		'settings' => 'pfx_woo_btn_padding',
		'label' => __( 'Button Padding ', 'popularfx' ),
		'description' => __( 'Set button padding in px', 'popularfx' ),
			'input_attrs' => array(
					'min' => 0,
					'max' => 30,
					'step' => 1,
			),
		)
	);

	$wp_customize->add_setting('pfx_woo_btn_bg_color', array(
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'default' => '#5c7aea',
		'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
	) );

	$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
		$wp_customize, 'pfx_woo_btn_bg_color', array(
			'priority' => 2,
			'section' => 'pfx_woo_btn',
			'description' => 'Set background color for buttons',
			'settings' => 'pfx_woo_btn_bg_color',
			'label' => __('Button Background Color', 'popularfx' ),
			'show_opacity' => true
		)
	) );

	$wp_customize->add_setting('pfx_woo_btn_color', array(
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'default' => '#ffffff',
		'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
	) );

	$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
		$wp_customize, 'pfx_woo_btn_color', array(
			'priority' => 3,
			'section' => 'pfx_woo_btn',
			'description' => 'Set background color for buttons',
			'settings' => 'pfx_woo_btn_color',
			'label' => __('Button Text Color', 'popularfx' ),
			'show_opacity' => true
		)
	) );

	$wp_customize->add_setting('pfx_woo_btn_hover_bg_color', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => '#000000',
		'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
	) );

	$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
		$wp_customize, 'pfx_woo_btn_hover_bg_color', array(
			'priority' => 4,
			'section' => 'pfx_woo_btn',
			'description' => 'Set hover background color for buttons',
			'settings' => 'pfx_woo_btn_hover_bg_color',
			'label' => __('Button Hover Background Color', 'popularfx' ),
			'show_opacity' => true
		)
	) );

	$wp_customize->add_setting('pfx_woo_btn_hover_color', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => '#ffffff',
		'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
	) );

	$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
		$wp_customize, 'pfx_woo_btn_hover_color', array(
			'priority' => 5,
			'section' => 'pfx_woo_btn',
			'description' => 'Set hover text color for buttons',
			'settings' => 'pfx_woo_btn_hover_color',
			'label' => __('Button Hover Text Color', 'popularfx' ),
			'show_opacity' => true
		)
	) );

	$wp_customize->add_setting( 'pfx_woo_btn_border', array(
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'sanitize_callback' => 'absint',
		'default' => 0,
	) );
		
	$wp_customize->add_control( 'pfx_woo_btn_border', array(
		'priority' => 6,
		'type' => 'number',
		'section' => 'pfx_woo_btn',
		'settings' => 'pfx_woo_btn_border',
		'label' => __( 'Button Border ', 'popularfx' ),
		'description' => __( 'Set button borders in px', 'popularfx' ),
			'input_attrs' => array(
					'min' => 0,
					'max' => 30,
					'step' => 1,
			),
		)
	);

	$wp_customize->add_setting( 'pfx_woo_btn_border_radius', array(
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'sanitize_callback' => 'absint',
		'default' => 0,
	) );
		
	$wp_customize->add_control( 'pfx_woo_btn_border_radius', array(
		'priority' => 7,
		'type' => 'number',
		'section' => 'pfx_woo_btn',
		'settings' => 'pfx_woo_btn_border_radius',
		'label' => __( 'Button Border Radius', 'popularfx' ),
		'description' => __( 'Set button borders radius in px', 'popularfx' ),
			'input_attrs' => array(
					'min' => 0,
					'max' => 30,
					'step' => 1,
			),
		)
	);

	$wp_customize->add_setting('pfx_woo_btn_border_color', array(
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'default' => '#ffffff',
		'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
	) );

	$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
		$wp_customize, 'pfx_woo_btn_border_color', array(
			'priority' => 8,
			'section' => 'pfx_woo_btn',
			'description' => 'Set border color for buttons',
			'settings' => 'pfx_woo_btn_border_color',
			'label' => __('Button Border Color', 'popularfx' ),
			'show_opacity' => true
		)
	) );

	$wp_customize->add_setting( 'pfx_woo_btn_hover_border', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'sanitize_callback' => 'absint',
		'default' => 0,
	) );
		
	$wp_customize->add_control( 'pfx_woo_btn_hover_border', array(
		'priority' => 9,
		'type' => 'number',
		'section' => 'pfx_woo_btn',
		'settings' => 'pfx_woo_btn_hover_border',
		'label' => __( 'Button Hover Border ', 'popularfx' ),
		'description' => __( 'Set button hover borders in px', 'popularfx' ),
			'input_attrs' => array(
					'min' => 0,
					'max' => 30,
					'step' => 1,
			),
		)
	);

	$wp_customize->add_setting( 'pfx_woo_btn_hover_border_radius', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'sanitize_callback' => 'absint',
		'default' => 0,
	) );
		
	$wp_customize->add_control( 'pfx_woo_btn_hover_border_radius', array(
		'priority' => 10,
		'type' => 'number',
		'section' => 'pfx_woo_btn',
		'settings' => 'pfx_woo_btn_hover_border_radius',
		'label' => __( 'Button Hover Border Radius', 'popularfx' ),
		'description' => __( 'Set button hover borders radius in px', 'popularfx' ),
			'input_attrs' => array(
					'min' => 0,
					'max' => 30,
					'step' => 1,
			),
		)
	);

	$wp_customize->add_setting('pfx_woo_btn_hover_border_color', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => '#ffffff',
		'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
	) );

	$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
		$wp_customize, 'pfx_woo_btn_hover_border_color', array(
			'priority' => 11,
			'section' => 'pfx_woo_btn',
			'description' => 'Set hover border color for all buttons',
			'settings' => 'pfx_woo_btn_hover_border_color',
			'label' => __('Button Hover Border Color', 'popularfx' ),
			'show_opacity' => true
		)
	) );	
	
	//Array for woo_padding
	$woo_padding = [];
	$woo_padding[0] = 'Default( ' .get_theme_mod('pfx_woo_default_padding'). 'px )';
	for($i = 1; $i <= 100; $i++){
		$woo_padding[$i] = esc_attr($i.'px');
	}
	
	// WooCommerce Shop Page Settings
	$wp_customize->add_setting('pfx_woo_shop_padding', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => 0,
		'sanitize_callback' => 'absint',
	) );
	
	$wp_customize->add_control( 'pfx_woo_shop_padding', array(
		'type' => 'select',
		'priority' => 1,
		'section' => 'woocommerce_product_catalog',
		'description' => 'Set container padding for shop page in "px"',
		'settings' => 'pfx_woo_shop_padding',
		'label' => __('Shop Page Padding', 'popularfx' ),
		'choices' => $woo_padding,
	) );
	
	//Single Product Page Settings
	$wp_customize->add_setting('pfx_woo_product_padding', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => 0,
		'sanitize_callback' => 'absint',
	) );
	
	$wp_customize->add_control( 'pfx_woo_product_padding', array(
		'type' => 'select',
		'priority' => 1,
		'section' => 'pfx_woo_single_product',
		'description' => 'Set container padding for single product page in "px"',
		'settings' => 'pfx_woo_product_padding',
		'label' => __('Single Product Page Padding', 'popularfx' ),
		'choices' => $woo_padding,
	) );
	
	$wp_customize->add_setting('disable_product_breadcrumb', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => 0,
		'sanitize_callback' => 'popularfx_switch_sanitization',
	) );
	
	$wp_customize->add_control( 'disable_product_breadcrumb', array(
		'type' => 'checkbox',
		'section' => 'pfx_woo_single_product',
		'settings' => 'disable_product_breadcrumb',
		'label' => __('Disable Breadcrumb', 'popularfx' ),
	) );
	
	$wp_customize->add_setting('product_image_width', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => 48,
	) );
	
	$wp_customize->add_control( 'product_image_width', array(
		'type' => 'number',
		'section' => 'pfx_woo_single_product',
		'description' => 'Set image width of single product in "%" ',
		'settings' => 'product_image_width',
		'label' => __('Image Width', 'popularfx' ),
		'input_attrs' => array(
			'min' => 0,
			'max' => 70,
			'step' => 1,
		),
	) );
	
	$wp_customize->add_setting('enable_product_zoom', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => 1,
		'sanitize_callback' => 'popularfx_switch_sanitization',
	) );
	
	$wp_customize->add_control( 'enable_product_zoom', array(
		'type' => 'checkbox',
		'section' => 'pfx_woo_single_product',
		'settings' => 'enable_product_zoom',
		'label' => __('Enable Image Zoom Effect', 'popularfx' ),
	) );
	
	$wp_customize->add_setting('disable_product_description', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => 0,
		'sanitize_callback' => 'popularfx_switch_sanitization',
	) );
	
	$wp_customize->add_control( 'disable_product_description', array(
		'type' => 'checkbox',
		'section' => 'pfx_woo_single_product',
		'settings' => 'disable_product_description',
		'label' => __('Hide Products Description', 'popularfx' ),
	) );
	
	$wp_customize->add_setting('disable_upsells', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => 0,
		'sanitize_callback' => 'popularfx_switch_sanitization',
	) );
	
	$wp_customize->add_control( 'disable_upsells', array(
		'type' => 'checkbox',
		'section' => 'pfx_woo_single_product',
		'settings' => 'disable_upsells',
		'label' => __('Disable Products Up Sells', 'popularfx' ),
	) );
	
	$wp_customize->add_setting('disable_related_product', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => 0,
		'sanitize_callback' => 'popularfx_switch_sanitization',
	) );
	
	$wp_customize->add_control( 'disable_related_product', array(
		'type' => 'checkbox',
		'section' => 'pfx_woo_single_product',
		'settings' => 'disable_related_product',
		'label' => __('Disable Related Products', 'popularfx' ),
	) );
	
	$wp_customize->add_setting('number_related_product', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => 4,
		'sanitize_callback' => 'absint',
	) );
	
	$wp_customize->add_control( 'number_related_product', array(
		'type' => 'number',
		'section' => 'pfx_woo_single_product',
		'description' => 'Set number of product in related products',
		'settings' => 'number_related_product',
		'label' => __('No. of Related Products', 'popularfx' ),
		'input_attrs' => array(
			'min' => 1,
			'max' => 5,
			'step' => 1,
		),
	) );
	
	$wp_customize->add_setting('product_title_color', array(
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'default' => '',
		'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
	) );
	
	$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
		$wp_customize, 'product_title_color', array(
			'section' => 'pfx_woo_single_product',
			'settings' => 'product_title_color',
			'label' => __('Title Color', 'popularfx' ),
			'show_opacity' => true
		)
	) );
	
	$wp_customize->add_setting('product_price_color', array(
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'default' => '',
		'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
	) );
	
	$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
		$wp_customize, 'product_price_color', array(
			'section' => 'pfx_woo_single_product',
			'settings' => 'product_price_color',
			'label' => __('Price Color', 'popularfx' ),
			'show_opacity' => true
		)
	) );
	
	$wp_customize->add_setting('product_description_color', array(
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'default' => '',
		'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
	) );
	
	$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
		$wp_customize, 'product_description_color', array(
			'section' => 'pfx_woo_single_product',
			'settings' => 'product_description_color',
			'label' => __('Description Color', 'popularfx' ),
			'show_opacity' => true
		)
	) );
	
	$wp_customize->add_setting('product_breadcrumb_color', array(
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'default' => '',
		'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
	) );
	
	$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
		$wp_customize, 'product_breadcrumb_color', array(
			'section' => 'pfx_woo_single_product',
			'settings' => 'product_breadcrumb_color',
			'label' => __('Breadcrumb Color', 'popularfx' ),
			'show_opacity' => true
		)
	) );
	
	// Cart Page Settings
	$wp_customize->add_setting('pfx_woo_cart_padding', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => 0,
		'sanitize_callback' => 'absint',
	) );
	
	$wp_customize->add_control( 'pfx_woo_cart_padding', array(
		'type' => 'select',
		'priority' => 1,
		'section' => 'pfx_woo_cart_page',
		'description' => 'Set container padding for cart page in "px"',
		'settings' => 'pfx_woo_cart_padding',
		'label' => __('Cart Page Padding', 'popularfx' ),
		'choices' => $woo_padding,
	) );
	
	$wp_customize->add_setting('disable_cross_sells', array(
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'default' => 0,
		'sanitize_callback' => 'popularfx_switch_sanitization',
	) );
	
	$wp_customize->add_control( 'disable_cross_sells', array(
		'type' => 'checkbox',
		'section' => 'pfx_woo_cart_page',
		'settings' => 'disable_cross_sells',
		'label' => __('Disable Cross-sells', 'popularfx' ),
	) );
	
	//Checkout Page Settings
	$wp_customize->add_setting('pfx_woo_checkout_padding', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => 0,
		'sanitize_callback' => 'absint',
	) );
	
	$wp_customize->add_control( 'pfx_woo_checkout_padding', array(
		'type' => 'select',
		'priority' => 1,
		'section' => 'woocommerce_checkout',
		'description' => 'Set container padding for checkout page in "%"',
		'settings' => 'pfx_woo_checkout_padding',
		'label' => __('Checkout Page Padding', 'popularfx' ),
		'choices' => $woo_padding,
	) );
	
	$wp_customize->add_setting('disable_order_note', array(
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'default' => 0,
		'sanitize_callback' => 'popularfx_switch_sanitization',
	) );
	
	$wp_customize->add_control( 'disable_order_note', array(
		'type' => 'checkbox',
		'section' => 'woocommerce_checkout',
		'settings' => 'disable_order_note',
		'label' => __('Disable Order Note', 'popularfx' ),
	) );
	
	$wp_customize->add_setting('disable_coupon_field', array(
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'default' => 0,
		'sanitize_callback' => 'popularfx_switch_sanitization',
	) );
	
	$wp_customize->add_control( 'disable_coupon_field', array(
		'type' => 'checkbox',
		'section' => 'woocommerce_checkout',
		'settings' => 'disable_coupon_field',
		'label' => __('Disable Coupon Field', 'popularfx' ),
	) );
	
	$wp_customize->add_setting('enable_label_placeholder', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => 1,
		'sanitize_callback' => 'popularfx_switch_sanitization',
	) );
	
	$wp_customize->add_control( 'enable_label_placeholder', array(
		'type' => 'checkbox',
		'section' => 'woocommerce_checkout',
		'settings' => 'enable_label_placeholder',
		'label' => __('Show Label as Placeholder', 'popularfx' ),
	) );
	
	//My Account Page Settings
	$wp_customize->add_setting('pfx_woo_myaccount_padding', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => 0,
		'sanitize_callback' => 'absint',
	) );
	
	$wp_customize->add_control( 'pfx_woo_myaccount_padding', array(
		'type' => 'select',
		'priority' => 1,
		'section' => 'pfx_woo_myaccount_page',
		'description' => 'Set container padding for Myaccount page in "px"',
		'settings' => 'pfx_woo_myaccount_padding',
		'label' => __('Myaccount Page Padding', 'popularfx' ),
		'choices' => $woo_padding,
	) );
	
	// Store Notice design
	$wp_customize->add_setting('pfx_woo_storenotice_bg_color', array(
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'default' => '#5c7aea',
		'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
	) );
	
	$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
		$wp_customize, 'pfx_woo_storenotice_bg_color', array(
			'section' => 'woocommerce_store_notice',
			'description' => 'Set background color for store notice',
			'settings' => 'pfx_woo_storenotice_bg_color',
			'label' => __('Background color', 'popularfx' ),
			'show_opacity' => true
		)
	) );
	
	$wp_customize->add_setting('pfx_woo_storenotice_color', array(
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'default' => '#FFFFFF',
		'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
	) );
	
	$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
		$wp_customize, 'pfx_woo_storenotice_color', array(
			'section' => 'woocommerce_store_notice',
			'description' => 'Set text color for store notice',
			'settings' => 'pfx_woo_storenotice_color',
			'label' => __('Text Color', 'popularfx' ),
			'show_opacity' => true
		)
	) );
	
	//Shop Pagination setting
	$wp_customize->add_setting('shoppagination_bg_color', array(
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'default' => '#ffffff',
		'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
	) );
	
	$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
		$wp_customize, 'shoppagination_bg_color', array(
			'section' => 'pfx_woo_shop_pagination',
			'description' => 'Set background color for pagination',
			'settings' => 'shoppagination_bg_color',
			'label' => __('Background Color', 'popularfx' ),
			'show_opacity' => true
		)
	) );
	
	$wp_customize->add_setting('shoppagination_color', array(
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'default' => '#000000',
		'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
	) );
	
	$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
		$wp_customize, 'shoppagination_color', array(
			'section' => 'pfx_woo_shop_pagination',
			'description' => 'Set text color for pagination',
			'settings' => 'shoppagination_color',
			'label' => __('Text Color', 'popularfx' ),
			'show_opacity' => true
		)
	) );
	
	$wp_customize->add_setting('shoppagination_border_color', array(
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'default' => '#ffffff',
		'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
	) );
	
	$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
		$wp_customize, 'shoppagination_border_color', array(
			'section' => 'pfx_woo_shop_pagination',
			'description' => 'Set border color for pagination',
			'settings' => 'shoppagination_border_color',
			'label' => __('Border Color', 'popularfx' ),
			'show_opacity' => true
		)
	) );
	
	$wp_customize->add_setting( 'shoppagination_borderwidth', array(
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'default' => 1,
		'sanitize_callback' => 'absint',
	) );
		
	$wp_customize->add_control( 'shoppagination_borderwidth', array(
		'type' => 'number',
		'section' => 'pfx_woo_shop_pagination',
		'settings' => 'shoppagination_borderwidth',
		'label' => __( 'Pagination Border Width', 'popularfx' ),
		'description' => __( 'Set shop page pagination border width', 'popularfx' ),
		'input_attrs' => array(
			'min' => 0,
			'max' => 10,
			'step' => 1,
		),
	) );
	
	$wp_customize->add_setting( 'shoppagination_borderradius', array(
		'capability' => 'edit_theme_options',
		'transport' => 'postMessage',
		'default' => 0,
		'sanitize_callback' => 'absint',
	) );
		
	$wp_customize->add_control( 'shoppagination_borderradius', array(
		'type' => 'number',
		'section' => 'pfx_woo_shop_pagination',
		'settings' => 'shoppagination_borderradius',
		'label' => __( 'Border Radius', 'popularfx' ),
		'description' => __( 'Set pagination border radius in %', 'popularfx' ),
		'input_attrs' => array(
			'min' => 0,
			'max' => 100,
			'step' => 1,
		),
	) );
	
	$wp_customize->add_setting('shoppagination_bg_hover_color', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => '#000000',
		'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
	) );
	
	$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
		$wp_customize, 'shoppagination_bg_hover_color', array(
			'section' => 'pfx_woo_shop_pagination',
			'description' => 'Set hover background color for pagination',
			'settings' => 'shoppagination_bg_hover_color',
			'label' => __('Hover Background Color', 'popularfx' ),
			'show_opacity' => true
		)
	) );
	
	$wp_customize->add_setting('shoppagination_hover_color', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => '#ffffff',
		'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
	) );
	
	$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
		$wp_customize, 'shoppagination_hover_color', array(
			'section' => 'pfx_woo_shop_pagination',
			'description' => 'Set hover text color for pagination',
			'settings' => 'shoppagination_hover_color',
			'label' => __('Hover Text Color', 'popularfx' ),
			'show_opacity' => true
		)
	) );
	
	$wp_customize->add_setting('shoppagination_hover_border_color', array(
		'capability' => 'edit_theme_options',
		'transport' => 'refresh',
		'default' => '#000000',
		'sanitize_callback' => 'popularfx_hex_rgba_sanitization',
	) );
	
	$wp_customize->add_control( new Popularfx_Customize_Alpha_Color_Control(
		$wp_customize, 'shoppagination_hover_border_color', array(
			'section' => 'pfx_woo_shop_pagination',
			'description' => 'Set hover border color for pagination',
			'settings' => 'shoppagination_hover_border_color',
			'label' => __('Hover Border Color', 'popularfx' ),
			'show_opacity' => true
		)
	) );
		
}