<?php 

function use_css() {
  wp_register_style('base-style', get_template_directory_uri() . '/assets/css/base.min.css', array(), '1.0.0', 'all');
  wp_enqueue_style('base-style');

  wp_register_style('reset-style', get_template_directory_uri() . '/assets/css/reset.css', array(), '1.0.0', 'all');
  wp_enqueue_style('reset-style');

  wp_register_script('main-script', get_template_directory_uri() . '/assets/js/main.js', array(), null, true);
  wp_enqueue_script('main-script');
}
add_action('wp_enqueue_scripts', 'use_css');

function remover() {
  remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'start_post_rel_link', 10, 0 );
	remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
	remove_action('wp_head', 'feed_links_extra', 3);
	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'print_emoji_detection_script', 7);
	remove_action('admin_print_scripts', 'print_emoji_detection_script');
	remove_action('wp_print_styles', 'print_emoji_styles');
	remove_action('admin_print_styles', 'print_emoji_styles');
}
add_action('wp_loaded', 'remover');

add_theme_support('menus');

function register_my_menu() {
  register_nav_menu('menu-principal', __('Menu Principal'));
}
add_action('init', 'register_my_menu');

function custom_sizes() {
  add_image_size('large', 1400, 380, true);
  add_image_size('medium', 768, 380, true);
}
add_action('after_setup_theme', 'custom_sizes');

?>
