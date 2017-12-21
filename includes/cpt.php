<?php
// MM Mega menu CPT
$mmmega_menu_labels = array(
    'name'			=> __( 'Mega Menus', 'micemade-elements' ),
    'singular_name'	=> __( 'Mega Menu', 'micemade-elements' ),
    'add_new'		=> __( 'New Mega Menu', 'micemade-elements' ),
    'add_new_item'	=> __( 'Add New Mega Menu', 'micemade-elements' ),
    'edit_item'		=> __( 'Edit Mega Menu', 'micemade-elements' ),
    'new_item'		=> __( 'New Mega Menu', 'micemade-elements' ),
    'view_item'		=> __( 'View Mega Menu', 'micemade-elements' ),
    'search_items'	=> __( 'Search Mega Menus', 'micemade-elements' ),
    'not_found'		=>  __( 'No Mega Menus Found', 'micemade-elements' ),
    'not_found_in_trash' => __( 'No Mega Menus found in Trash', 'micemade-elements' ),
    );
$mmmega_menu_args = array(
    'labels'		        => $mmmega_menu_labels,
    'supports'              => array( 'title' ),
    'public'                => true,
    'rewrite'               => false,
    'show_ui'               => true,
    'show_in_menu'          => true,
    'show_in_nav_menus'     => false,
    'exclude_from_search'   => true,
    'capability_type'       => 'post',
    'hierarchical'          => false,
    'menu_icon'             => 'dashicons-menu'
);
register_post_type( 'MM Mega menu', $mmmega_menu_args );

// MM Footer CPT
$mm_footer_labels = array(
    'name'			=> __( 'Footers', 'micemade-elements' ),
    'singular_name'	=> __( 'Footer', 'micemade-elements' ),
    'add_new'		=> __( 'New Footer', 'micemade-elements' ),
    'add_new_item'	=> __( 'Add New Footer', 'micemade-elements' ),
    'edit_item'		=> __( 'Edit Footer', 'micemade-elements' ),
    'new_item'		=> __( 'New Footer', 'micemade-elements' ),
    'view_item'		=> __( 'View Footer', 'micemade-elements' ),
    'search_items'	=> __( 'Search Footers', 'micemade-elements' ),
    'not_found'		=>  __( 'No Footers Found', 'micemade-elements' ),
    'not_found_in_trash' => __( 'No Footers found in Trash', 'micemade-elements' ),
    );
$mm_footer_args = array(
    'labels'		        => $mm_footer_labels,
    'supports'              => array( 'title'),
    'public'                => true,
    'rewrite'               => false,
    'show_ui'               => true,
    'show_in_menu'          => true,
    'show_in_nav_menus'     => false,
    'exclude_from_search'   => true,
    'capability_type'       => 'post',
    'hierarchical'          => false,
    'menu_icon'             => 'dashicons-layout'
);
register_post_type( 'MM Footer', $mm_footer_args );


// Automatically activate Elementor support for MM Mega menu CPT (always active)
$elementor_cpt_support = get_option( 'elementor_cpt_support', [ 'page', 'post' ] );
if( ! in_array( 'mmmegamenu', $elementor_cpt_support ) ) {
    $elementor_cpt_support[] = 'mmmegamenu';
    update_option( 'elementor_cpt_support', $elementor_cpt_support );
}
if( ! in_array( 'mmfooter', $elementor_cpt_support ) ) {
    $elementor_cpt_support[] = 'mmfooter';
    update_option( 'elementor_cpt_support', $elementor_cpt_support );
}

add_filter( 'mm_megamenu_cpt', '__return_true' );
add_filter( 'mm_footer_cpt', '__return_true' );