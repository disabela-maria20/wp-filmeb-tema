<?php

require_once(get_template_directory() . "/api/banner.php");

add_filter('wp_image_editors', 'wpb_image_editor_default_to_gd');

function wpb_image_editor_default_to_gd($editors)
{
  $gd_editor = 'WP_Image_Editor_GD';
  $editors = array_diff($editors, array($gd_editor));
  array_unshift($editors, $gd_editor);
  return $editors;
}

function use_scripts()
{
  // Carrega a folha de estilos base do tema
  wp_enqueue_style('base-style', get_template_directory_uri() . '/assets/css/base.min.css', array(), '1.0.0', 'all');

  // Carrega o jQuery padrão do WordPress
  wp_enqueue_script('jquery');

  // Carrega o script do Owl Carousel com dependência de jQuery
  wp_enqueue_script('owl-carousel', get_template_directory_uri() . '/assets/js/lib/owl.carousel.min.js', array('jquery'), '2.3.4', true);

  // Carrega fontes do Google e ícones do Bootstrap
  wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap', array(), null, 'all');
  wp_enqueue_style('bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css', array(), null, 'all');

  // Carrega o script principal do tema com dependências
  wp_enqueue_script('main-script', get_template_directory_uri() . '/assets/js/main.js', array('jquery', 'owl-carousel'), null, true);
}
add_action('wp_enqueue_scripts', 'use_scripts');

function add_meta_tags()
{
  echo '<meta name="viewport" content="width=device-width, initial-scale=1.0" />';
}
add_action('wp_head', 'add_meta_tags');

add_theme_support('menus');

function register_my_menu()
{
  register_nav_menu('menu-principal', __('Menu Principal'));
  register_nav_menu('institucional', __('Menu Institucional'));
}
add_action('init', 'register_my_menu');

function custom_category_rewrite_rules()
{
  add_filter('term_link', function ($link, $term, $taxonomy) {
    if ($taxonomy === 'category') {
      return home_url('/' . $term->slug . '/');
    }
    return $link;
  }, 10, 3);

  add_rewrite_rule('^([^/]+)/?', 'index.php?category_name=$matches[1]', 'top');
}
add_action('init', 'custom_category_rewrite_rules');

function flush_rewrite_rules_on_theme_activation()
{
  flush_rewrite_rules();
}
add_action('after_switch_theme', 'flush_rewrite_rules_on_theme_activation');
