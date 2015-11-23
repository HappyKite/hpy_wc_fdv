<?php /* Variant DropDown menu changes */
if ( ! function_exists( 'hpy_dropdown_variation_attribute_options' ) ) {

    function hpy_dropdown_variation_attribute_options( $args = array() ) {
        $_pf = new WC_Product_Factory();

        $args = wp_parse_args( apply_filters( 'woocommerce_dropdown_variation_attribute_options_args', $args ), array(
            'options'          => false,
            'attribute'        => false,
            'product'          => false,
            'selected'         => false,
            'name'             => '',
            'id'               => '',
            'class'            => ''
        ) );

        $sortby = get_option( 'hpy_variant_sort' );

        $options   = $args['options'];
        $product   = $args['product'];
        $attribute = $args['attribute'];
        $name      = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute );
        $id        = $args['id'] ? $args['id'] : sanitize_title( $attribute );
        $class     = $args['class'];

        if ( empty( $options ) && ! empty( $product ) && ! empty( $attribute ) ) {
            $attributes = $product->get_variation_attributes();
            $options    = $attributes[ $attribute ];
        }

        echo '<select id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '" name="' . esc_attr( $name ) . '" data-attribute_name="attribute_' . esc_attr( sanitize_title( $attribute ) ) . '">';

        if ( $sortby == 'price-low' || $sortby == 'price-high' ) {
            $variantIDs = $product->children['visible'];
            $variant_list = array();
            foreach ($variantIDs as $variants) {
                $variant_product = $_pf->get_product($variants);
                $variant_list[] = array( $variant_product->get_regular_price() => $variants );
            }
            $varcount = count($variant_list);
            $i = 0;
            $v = 0;
            $tmp = array();
            while ( $i < $varcount ){
                $keys[] = key($variant_list[$i]);
                $i++;
            }

            if ( count( array_unique( $keys ) ) == 1 ) {
                if ( $product && taxonomy_exists( $attribute ) ) {
                    // Get terms if this is a taxonomy - ordered. We need the names too.
                    $terms = wc_get_product_terms( $product->id, $attribute, array( 'fields' => 'all' ) );

                    foreach ( $terms as $term ) {
                        if ( in_array( $term->slug, $options ) ) {
                            echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . '>' . apply_filters( 'woocommerce_variation_option_name', $term->name ) . '</option>';
                        }
                    }
                } else {
                    foreach ( $options as $option ) {

                        // This handles < 2.4.0 bw compatibility where text attributes were not sanitized.
                        $selected = sanitize_title( $args['selected'] ) === $args['selected'] ? selected( $args['selected'], sanitize_title( $option ), false ) : selected( $args['selected'], $option, false );
                        echo '<option value="' . esc_attr( $option ) . '" ' . $selected . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</option>';
                    }
                }
            } else {
                
                foreach ( $variant_list as $list ){
                    $tmp = array_merge( $tmp, $list );
                }

                if ( $sortby == 'price-low' ) {
                    ksort($tmp);
                    foreach( $tmp as $variant ) {
                        $current_variant = get_post_meta($variant);
                        $option = $current_variant['attribute_'. strtolower($attribute)][0];

                        if ( $v == 0 ) {
                            $selected = ' selected="selected"';
                            $v++;
                        } else {
                            $selected = '';
                        }

                        echo '<option value="' . esc_attr( $option ) . '" ' . $selected . '>' . ucwords( esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) ) . '</option>';
                    }
                } else if ( $sortby == 'price-high' ) {
                    krsort($tmp);
                    foreach( $tmp as $variant ) {
                        $current_variant = get_post_meta($variant);
                        $option = $current_variant['attribute_'. strtolower($attribute)][0];

                        if ( $v == 0 ) {
                            $selected = ' selected="selected"';
                            $v++;
                        } else {
                            $selected = '';
                        }

                        echo '<option value="' . esc_attr( $option ) . '" ' . $selected . '>' . ucwords( esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) ) . '</option>';
                    }
                } else {
                    if ( $product && taxonomy_exists( $attribute ) ) {
                        // Get terms if this is a taxonomy - ordered. We need the names too.
                        $terms = wc_get_product_terms( $product->id, $attribute, array( 'fields' => 'all' ) );

                        foreach ( $terms as $term ) {
                            if ( in_array( $term->slug, $options ) ) {
                                echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . '>' . apply_filters( 'woocommerce_variation_option_name', $term->name ) . '</option>';
                            }
                        }
                    } else {
                        foreach ( $options as $option ) {

                            // This handles < 2.4.0 bw compatibility where text attributes were not sanitized.
                            $selected = sanitize_title( $args['selected'] ) === $args['selected'] ? selected( $args['selected'], sanitize_title( $option ), false ) : selected( $args['selected'], $option, false );
                            echo '<option value="' . esc_attr( $option ) . '" ' . $selected . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</option>';
                        }
                    }
                }
            }
        } else if ( ! empty( $options ) ) {
            if ( $product && taxonomy_exists( $attribute ) ) {
                // Get terms if this is a taxonomy - ordered. We need the names too.
                $terms = wc_get_product_terms( $product->id, $attribute, array( 'fields' => 'all' ) );

                foreach ( $terms as $term ) {
                    if ( in_array( $term->slug, $options ) ) {
                        echo '<option value="' . esc_attr( $term->slug ) . '" ' . selected( sanitize_title( $args['selected'] ), $term->slug, false ) . '>' . apply_filters( 'woocommerce_variation_option_name', $term->name ) . '</option>';
                    }
                }
            } else {
                foreach ( $options as $option ) {

                    // This handles < 2.4.0 bw compatibility where text attributes were not sanitized.
                    $selected = sanitize_title( $args['selected'] ) === $args['selected'] ? selected( $args['selected'], sanitize_title( $option ), false ) : selected( $args['selected'], $option, false );
                    echo '<option value="' . esc_attr( $option ) . '" ' . $selected . '>' . esc_html( apply_filters( 'woocommerce_variation_option_name', $option ) ) . '</option>';
                }
            }
        }

        echo '</select>';
    }
}

?>