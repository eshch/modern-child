<?php
function my_theme_enqueue_styles() {

    $parent_style = 'parent-style';

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style ),
        wp_get_theme()->get('Version')
    );
}
add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );

function my_remove_single_image_link($image_link) {
  return (is_single()) ? array() : $image_link;
}
add_filter('wmhook_entry_image_link', 'my_remove_single_image_link');

function my_remove_meta($meta, $values) {
	$meta['meta'] = array_diff($meta['meta'], $values);
	return $meta;
}

function my_add_meta($meta, $values) {
	$meta['meta'] = array_merge($meta['meta'], $values);
	return $meta;
}

function my_filter_entry_top_meta($meta) {
	return my_add_meta(my_remove_meta($meta, ['edit', 'author']), ['tags']);
}

function my_filter_entry_bottom_meta($meta) {
	return my_remove_meta($meta, ['tags']);
}

add_filter('wmhook_wm_entry_top_meta', 'my_filter_entry_top_meta');
add_filter('wmhook_wm_entry_bottom_meta', 'my_filter_entry_bottom_meta');
?>
