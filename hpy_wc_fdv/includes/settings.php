<?php 


/**
 * Create the section beneath the products tab
 **/
add_filter( 'woocommerce_get_sections_products', 'hpy_woo_add_section' );
function hpy_woo_add_section( $sections ) {
	$sections['hpy_variants'] = __( 'Variants', 'hpy_wdv' );
	return $sections;	
}

/**
 * Add settings to the specific section we created before
 */
add_filter( 'woocommerce_get_settings_products', 'hpy_woo_all_settings', 10, 2 );
function hpy_woo_all_settings( $settings, $current_section ) {
	/**
	 * Check the current section is what we want
	 **/
	if ( $current_section == 'hpy_variants' ) {
		$settings_variant = array();

		$settings_variant[] = array( 'name' => __( 'Default Variant Settings', 'hpy_wdv' ), 'type' => 'title', 'id' => 'hpy_variants' );

		$settings_variant[] = array(
			'name'     => __( 'Sort by:', 'hpy_wdv' ),
			'desc_tip' => __( 'Change how you want the default variant to be sorted.', 'hpy_wdv' ),
			'id'       => 'hpy_variant_sort',
			'type'     => 'select',
			'options'  => array(
				'id'		 => __( 'ID', 'hpy_wdv' ),
				'price-low'  => __( 'Price Low -> High', 'hpy_wdv' ),
				'price-high' => __( 'Price High -> Low', 'hpy_wdv' ),
			),
			'css'      => 'min-width:300px;',
			'desc'     => __( '<p>Sort by Price (low/high) or ID</p>', 'hpy_wdv' ),
		);
		
		$settings_variant[] = array( 'type' => 'sectionend', 'id' => 'hpy_variants' );
		return $settings_variant;
	
	/**
	 * If not, return the standard settings
	 **/
	} else {
		return $settings;
	}
}

?>