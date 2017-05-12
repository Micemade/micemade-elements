<?php
/**
 *  COLUMN CSS SELECTORS FOR FLEX GRID
 *  
 *  @param [in] $items_desktop
 *  @param [in] $items_mobile
 *  @param [in] $no_margin
 *  @return selector
 *  
 */
function micemade_elemets_grid_class( $items_desktop = 3, $items_mobile = 1,  $no_margin = false) {

    $style_class = $desktop_class = $mobiles_class = '';

    $no_margin = micemade_elements_to_boolean($no_margin); // make sure it is not string

    $column_desktop = array(
        1	=> 'mme-col-lg-12',
        2	=> 'mme-col-lg-6',
        3	=> 'mme-col-lg-4',
        4	=> 'mme-col-lg-3',
        6	=> 'mme-col-lg-2',
        12	=> 'mme-col-lg-1'
    );
	$column_mobile = array(
        1	=> 'mme-col-md-12',
        2	=> 'mme-col-md-6',
        3	=> 'mme-col-md-4',
        4	=> 'mme-col-md-3',
        6	=> 'mme-col-md-2',
        12	=> 'mme-col-md-1'
    );
	
	// generate class selector for desktop
    if ( array_key_exists( $items_desktop, $column_desktop ) && !empty( $column_desktop[$items_desktop] ) ) {
        $desktop_class = $column_desktop[$items_desktop];
    }
	// generate class selector for mobiles
    if ( array_key_exists( $items_mobile, $column_mobile ) && !empty( $column_mobile[$items_mobile] ) ) {
        $mobiles_class = $column_mobile[$items_mobile];
    }

    $style_class = $no_margin ? ( $desktop_class . ' ' . $mobiles_class . ' mme-zero-margin') : ( $desktop_class . ' ' . $mobiles_class );

    return $style_class;
}
/*
 * Converting string to boolean
 */
function micemade_elements_to_boolean($value) {
    if (!isset($value))
        return false;
    if ($value == 'true' || $value == '1')
        $value = true;
    elseif ($value == 'false' || $value == '0')
        $value = false;
    return (bool)$value; // Make sure you do not touch the value if the value is not a string
}
/**
 *  POSTED META ( posted in / post date / post author )
 *  
 *  @param [string] $taxonomy Parameter_Description
 *  @return $posted_in string with term links list for current post/taxonomy, html formatted
 *  
 */
add_filter( 'micemade_elements_posted_in','micemade_elements_posted_in_f', 10, 1 );
function micemade_elements_posted_in_f( $taxonomy ) {
    
	$terms		= get_the_terms( get_the_ID(), $taxonomy );
	$posted_in	= "";
	
	if ( $terms && ! is_wp_error( $terms ) ) {
	 
		$posted_in = '<span class="posted_in micemade_elements-terms">';
		
		$posted_in_terms = array();
		$lastTerm	= end($terms);
		foreach ( $terms as $term ) {
			/* 
			$term_color		= micemade_elements_get_term_color( $term->term_id, true );
			$text_color		= $term_color ? micemade_elements_contrast( $term_color ) : '#333333';
			$back_color		= $term_color ? $term_color : '#ffffff';
			$style			= ' style="background-color: '. $back_color .'; color: '.$text_color.'"'; 
			 */
			$separator = ( $term == $lastTerm ) ? '' : ', ';
			$posted_in .= '<a href="' . esc_url( get_term_link( $term->slug, $taxonomy ) ) . '" rel="tag" tabindex="0"'.$style.'>' . esc_html( $term->name .' ' . $term->ID ) . '</a>' . wp_kses_post( $separator );
			
		}
		
		$posted_in .= '</span>';
		
	 }
	
	return $posted_in;
	
}
add_filter( 'micemade_elements_date', 'micemade_elements_date_f', 10, 1 );
function micemade_elements_date_f($date) {

    $date = '<span class="published"><time datetime="' . sprintf(get_the_time(esc_html__('Y-m-d', 'micemade_elements'))) . '">' . sprintf(get_the_time(get_option('date_format',"M d, Y" ))) . '</time></span>';

    return $date;
}
add_filter( 'micemade_elements_author','micemade_elements_author_f', 10, 1 );
function micemade_elements_author_f($author) {
    $author = '<span class="author vcard">' . esc_html__('By ', 'micemade_elements') . '<a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '" title="' . esc_attr(get_the_author_meta('display_name')) . '">' . esc_html(get_the_author_meta('display_name')) . '</a></span>';
    
	return $author;
}
// end post meta
/**
 *  PLACEHOLDER IMAGE
 */
add_filter( 'micemade_elements_no_image','micemade_elements_no_image_f' );
function micemade_elements_no_image_f( $placeholder_img_url ) {
	
	$placeholder_img_url = MICEMADE_ELEMENTS_URL .'assets/images/no-image.svg';
	
	return $placeholder_img_url;
}
/**
 *  POST THUMB
 */
add_action( 'micemade_elements_thumb','micemade_elements_thumb_f' , 10, 1 ); 
function micemade_elements_thumb_f( $img_format = "thumbnail" ) {
	
	$gallery_shortcode = apply_filters( 'micemade_elements_gallery_ids','' );
	
	$atts = array(
		'alt' => get_the_title()
	);
	
	
	echo '<div class="post-thumb">';
	if ( has_post_thumbnail() ) { 
		
		the_post_thumbnail( $img_format, $atts ); 
		
	}elseif( !empty( $gallery_shortcode) ) {
		
		$gall_image_ID	= $gallery_shortcode[0]; // get first image from WP gallery
		$img_atts		= wp_get_attachment_image_src( $gall_image_ID, $img_format, $atts );
		$img_url		= $img_atts[0];
		
		echo '<img src="'. esc_url( $img_url ).'" class="no-image" alt="'. esc_attr__( 'No image','micemade_elements' ) .'" >';
		
	}else{ 
		
		echo '<img src="'. esc_url( apply_filters('micemade_elements_no_image','') ).'" class="no-image" alt="'. esc_attr__( 'No image','micemade_elements' ) .'" >';
	}
	echo '</div>';
}
/**
 * GET GALLERY IMAGES ID's
 * get id's from WP gallery shortcode
 * 
 * @return array $ids 
 */
function micemade_elements_gallery_ids_f() {
	global $post;
	if( ! $post ) return;
	$attachment_ids = array();
	$pattern = get_shortcode_regex();
	$ids = array();
	//finds the "gallery" shortcode and puts the image ids in an associative array at $matches[3]
	if (preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches ) ) {  
		$count=count($matches[3]);      //in case there is more than one gallery in the post.
		for ($i = 0; $i < $count; $i++){
			$atts = shortcode_parse_atts( $matches[3][$i] );
			if ( isset( $atts['ids'] ) ){
				$attachment_ids = explode( ',', $atts['ids'] );
				$ids = array_merge($ids, $attachment_ids);
			}
		}
		
	}
	return $ids;	
}
add_filter( 'micemade_elements_gallery_ids', 'micemade_elements_gallery_ids_f' );
?>