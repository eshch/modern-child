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
  return (is_singular()) ? array() : $image_link;
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

define('MOZILLA_FIRA', '//code.cdn.mozilla.net/fonts/fira.css');

function my_filter_google_fonts_url($in_url) {
	return is_null($in_url) ? NULL : MOZILLA_FIRA;
}

add_filter('wmhook_wm_google_fonts_url_output', 'my_filter_google_fonts_url');

function my_filter_the_tags($list, $before, $sep, $after, $id = 0) {
	return get_the_term_list($id, 'post_tag', $before, ', ', $after);
}

add_filter('the_tags', 'my_filter_the_tags');

function my_get_the_tag_list_title($id = 0) {
	$tags = get_the_tags($id);

	if ( is_wp_error( $tags ) )
		return $tags;

	if ( empty( $tags ) )
		return false;

	$tag_names = array();

	foreach ( $tags as $tag ) {
		$tag_names[] = $tag->name;
	}

	return join(', ', $tag_names);
}

function my_filter_credits_output($credits)
{
	return '';
}

add_filter('wmhook_wm_credits_output', my_filter_credits_output);
?>
