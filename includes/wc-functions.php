<?php
/**
 *  WOOCOMMERCE QUERY ARGUMENTS
 *  
 *  @return (array) $args
 *  
 *  for filtering WooCommerce posts
 * 	with a little help from: https://github.com/woocommerce/woocommerce/blob/master/includes/widgets/class-wc-widget-products.php
 */
function micemade_elements_wc_query_args_func( $posts_per_page, $categories = array(), $filters = 'latest', $offset = 0 ) {

	if( !'MICEMADE_ELEMENTS_WOO_ACTIVE' ) return; // if WooCommerce is not active
	
	// Default variables
	$args		= array(
		'posts_per_page'	=> $posts_per_page,
		'post_type'			=> 'product',
		'offset'			=> $offset,
	);
	
	$args['orderby'] = 'menu_order date';
		
	if ( $filters == 'featured' ) {
		$args['tax_query'][] = array(
			'taxonomy' => 'product_visibility',
			'field'    => 'name',
			'terms'    => 'featured',
		);
	}
	
	if( !empty( $categories ) ) {
		$args['tax_query'][] = array(
			'taxonomy'	=> 'product_cat',
			'field'		=> 'slug',
			'operator'	=> 'IN',
			'terms'		=> $categories,
			'include_children' => true
		);
	}
	
	if( $filters == 'best_sellers' ) {
		
		$args['meta_key']	= 'total_sales';
		$args['orderby']	= 'meta_value_num';
	
	}elseif( $filters == 'best_rated' ) {
		
		$args['meta_key']	= '_wc_average_rating';
		$args['orderby']	= 'meta_value_num';
		
	}elseif( $filters == 'random' ) {
	
		$args['orderby'] = 'rand menu_order date';
		
	}elseif( $filters == 'on_sale' ) {
		
		$product_ids_on_sale    = wc_get_product_ids_on_sale();
		if( ! empty( $product_ids_on_sale ) ) {
			$args['post__in'] = $product_ids_on_sale;
		}
	
	}
	
	return $args;
	
}
add_filter( 'micemade_elements_wc_query_args','micemade_elements_wc_query_args_func', 10, 4 );
/**
 *  PRODUCT FOR LOOP
 *  
 *  @return (html)
 *  
 *  DRY effort ...
 */
function micemade_elements_loop_product_func( $style = 'style_1', $img_format = 'thumbnail', $posted_in = true, $short_desc = false, $price = true, $add_to_cart = true, $css_class = '' ) {
	?>
	<div class="post swiper-slide">
		
		<div class="inner-wrap">
		
			<?php
			if( $style == 'style_3' || $style == 'style_4' ) {
				echo do_action( 'micemade_elements_thumb_back', $img_format );
				echo '<div class="post-overlay"></div>';
			}else{
				do_action( 'micemade_elements_thumb', $img_format );  
			}
			?>

			<div class="post-text">
				
				<h4><a href="<?php the_permalink();?>" title="<?php the_title_attribute(); ?>"><?php the_title();?></a></h4>
			
				<?php if( $posted_in ) { ?>
				<div class="meta">
				
					<?php echo apply_filters( 'micemade_elements_posted_in','product_cat' ); ?>
				
				</div>
				<?php } ?>

				<div class="product-details">
					<?php 
					if( $price ) {
						echo '<span class="price-wrap">';
						woocommerce_template_loop_price();
						echo '</span>'; 
					}
					?>
					
					<?php
					if( $add_to_cart ) {
						echo '<span class="add-to-cart-wrap">';
						woocommerce_template_loop_add_to_cart();
						echo '</span>';
					}
					?>
					
					<?php 
					if( $short_desc ) {
						
						the_excerpt(); 
						
						echo '<a href="'. get_permalink() .'" title="'.the_title_attribute("echo=0").'" class="micemade-elements-readmore '. esc_attr( $css_class ) .' ">'. apply_filters( 'micemade-elements-prod-details', esc_html__( 'Product details','micemade-elements' ) ).'</a>';
					}
					?>
				</div>
			
			</div>
		
		</div>
	
	</div>
	<?php 
}
add_filter( 'micemade_elements_loop_product','micemade_elements_loop_product_func', 10, 7 );
/**
 * SIMPLE PRODUCT DATA (as in WC catalog)
 *
 * @return html
 */
function micemade_elements_simple_prod_data_func( $short_desc = true ){
	
	echo '<h3 class="product_title"><a href="'. esc_attr( get_permalink() ) .'" title="'. the_title_attribute (array('echo' => 0)).'"> ' . get_the_title() .'</a></h3>';

	if( $short_desc ) {
		wc_get_template_part( 'single-product/short', 'description' );
	}	
	woocommerce_template_loop_price();
	
	echo '<div class="add-to-cart-wrapper">';
		woocommerce_template_loop_add_to_cart();
	echo '</div>';

}
add_filter( 'micemade_elements_simple_prod_data', 'micemade_elements_simple_prod_data_func', 10, 1);
/**
 *  PRODUCT COUNT PER CATEGORY
 *  
 *  @param [int] $term_id Parameter_Description
 *  @return html
 *  
 *  @details html with count of products in category
 */
function micemade_elements_product_count_f( $term_id ) {
	
	$products_count	= get_woocommerce_term_meta( intval($term_id),'product_count_product_cat' );
			
	if ( is_wp_error( $products_count  ) || !$products_count ) return;
	
	$prod_count = '<span class="category__product-count">';
	
	$prod_count .= sprintf( _n( '%s product', '%s products', $products_count, 'micemade-elements' ), $products_count );
	
	$prod_count .= '</span>';
	
	return $prod_count;
}
add_filter( 'micemade_elements_product_count', 'micemade_elements_product_count_f', 100, 3 );