<?php 
/*
/**
 * Plugin Name: Custom Content Posts
 * Plugin URI: https://github.com/kamela-peppylish/WP-Custom-Posts-Plugin
 * Description: This plugin creates dedicated custom posts to publish site content and services. This plugin create two types of custom post: Content Post and Service Page post. Install this plugin and enjoy creating site seperated form your main content and pages! 
 * Version: 1.0.0
 * Author: Kamela Chowdhury
 * Author URI: https://github.com/kamela-peppylish/
 * License: GPL2
 */

function create_service_post_type(){
  // Set up labels
  $labels = array(
    'name' => 'Services',
    'singular_name' => 'Service',
    'add_new' => 'Add New Service',
    'add_new_item' => 'Add new Service',
    'edit_item' => 'Edit service details',
		'new_item' => 'New Service',
		'all_items' => 'All Services',
		'view_item' => 'View Service',
		'search_items' => 'Seach for Service',
		'not_found' => 'No service found',
		'not_found_in_trash' => 'No service found in trash',
		'parent_item_colon' => '',
		'menu_item' => 'Services'
    );
  // register post types
	register_post_type('services',array(
		'labels' => $labels,
		'has_archive' =>true,
		'public' =>true,
		'supports' => array('title', 'editor', 'excerpt','custom_fields','thumbnail','page_attributes','revisions','post_formats'),
		'taxonomies' => array('post_tag','category'),
		'exclude_from_search' => false,
		'capability_type' => 'post',
		'rewrite' => array('slug' => 'services','with_front' => FALSE)
		));
}
add_action('init', 'create_service_post_type');
function register_service_taxonomy(){
	// set up labels
	$labels = array(
		'name' => 'Service Categories',
		'singular_name' => 'Service Cateogry',
		'search_items' => 'Search Service Categories',
		'all_items' => 'All Service Categories',
		'edit_item' => 'Edit service cateogry',
		'update_item' => 'Update Service Cateogry',
		'add_new_item' => 'Add New Service Cateogry',
		'new_item_name' => 'New Service Category',
		'menu_name' => 'Service Categories'
		);
	// register taxonomy
	register_taxonomy('servicecat','services',array(
		'hierarchical' =>true,
		'labels' => $labels,
		'query_var' => true,
		'show_admin_column'=>true
		));
}
add_action('init','register_service_taxonomy');

function create_content_post_type(){
  // Set up labels
  $labels = array(
    'name' => 'Content',
    'singular_name' => 'Content',
    'add_new' => 'Add New Content',
    'add_new_item' => 'Add new Content',
    'edit_item' => 'Edit content details',
		'new_item' => 'New Content',
		'all_items' => 'All Content',
		'view_item' => 'View Content',
		'search_items' => 'Seach for Content',
		'not_found' => 'No content found',
		'not_found_in_trash' => 'No content found in trash',
		'parent_item_colon' => '',
		'menu_item' => 'Content'
    );
  // register post types
	register_post_type('content',array(
		'labels' => $labels,
		'has_archive' =>true,
		'public' =>true,
		'supports' => array('title', 'editor', 'excerpt','custom_fields','thumbnail','page_attributes','revisions','post_formats'),
		'taxonomies' => array('post_tag','category'),
		'exclude_from_search' => false,
		'capability_type' => 'post',
		'rewrite' => array('slug' => 'content','with_front' => FALSE)
		));
}
add_action('init', 'create_content_post_type');
function register_content_taxonomy(){
	// set up labels
	$labels = array(
		'name' => 'Content Categories',
		'singular_name' => 'Content Cateogry',
		'search_items' => 'Search Content Categories',
		'all_items' => 'All Content Categories',
		'edit_item' => 'Edit content cateogry',
		'update_item' => 'Update Content Cateogry',
		'add_new_item' => 'Add New Content Cateogry',
		'new_item_name' => 'New Content Category',
		'menu_name' => 'Content Categories'
		);
	// register taxonomy
	register_taxonomy('contentcat','content',array(
		'hierarchical' =>true,
		'labels' => $labels,
		'query_var' => true,
		'show_admin_column'=>true
		));
}
add_action('init','register_content_taxonomy');
// Insert image URL metabox 
function meta_keyword($post){
  $custom = get_post_custom($post->ID);
  $meta_keyword = $custom["meta_keyword"][0];

  wp_nonce_field(plugin_basename(__FILE__), 'meta_keyword_nonce', true);

  echo '<label for="meta_keyword"></label>';
  echo '<input type="text" id="meta_keyword" name="meta_keyword" value="'.$meta_keyword.' "style="width:100%; height:50px;" placeholder="Meta Keywords">';
}
// Service Banner content meta box
function banner_content($post){
  $custom = get_post_custom($post->ID);
  $banner_content = $custom["banner_content"][0];
  echo '<label for="banner_content"></label>';
  echo '<textarea name="banner_content" id="banner_content" style="width:100%; height:350px;" placeholder="Here you can enter content for Banner Section">'.$banner_content.'</textarea>';
}
// Service Banner subsidiary content meta box
function meta_description($post){
  $custom = get_post_custom($post->ID);
  $meta_description = $custom["meta_description"][0];
  echo '<label for="meta_description"></label>';
  echo '<textarea name="meta_description" id="meta_description" style="width:100%; height:100px;" placeholder="Enter your meta description here">'.$meta_description.'</textarea>';
}
function custom_meta_box() {
$postypes = array('services', 'content','post','page');
foreach ( $postypes as $postype) {
    add_meta_box(
        'meta_keyword_box',
        __('Meta Keywords', 'myplugin_textdomain'),
        'meta_keyword',
        $postype,
        'side',
        'low'
            );
    add_meta_box(
        'banner_content_box',
        __('Banner Content', 'myplugin_textdomain'),
        'banner_content',
        $postype,
        'normal',
        'high'
            );
    add_meta_box(
        'meta_description_box',
        __('Meta Description', 'myplugin_textdomain'),
        'meta_description',
        $postype,
        'normal',
        'low'
            );
	}
}
add_action( 'add_meta_boxes', 'custom_meta_box' );
// save meta fields value
function save_details($post_id){
if (( 'content' == $_POST['post_type'] ) || ( 'services' == $_POST['post_type'] ) || ( 'post' == $_POST['post_type'] ) || ('page' == $_POST['post_type'] ))
  {
    if ( !current_user_can( 'edit_post', $post_id ) )
        return $post_id;
  }
  else
  {
    if ( !current_user_can( 'edit_post', $post_id ) )
        return $post_id;
  }
update_post_meta($post_id, "meta_keyword", $_POST["meta_keyword"]);
update_post_meta($post_id, "banner_content", $_POST["banner_content"]);
update_post_meta($post_id, "meta_description", $_POST["meta_description"]);

}

add_action('save_post', 'save_details', 10, 2);
// END APP POST


?>
