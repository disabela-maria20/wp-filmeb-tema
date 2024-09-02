<?php
// Registrar Custom Post Type "Banner"
function registrar_cpt_banner()
{
  register_post_type('banner', array(
    'labels' => array(
      'name' => _x('Banner', 'Post type general name', 'textdomain'),
      'singular_name' => _x('Banner', 'Post type singular name', 'textdomain'),
      'add_new_item' => _x('Novo Banner', 'Post type singular name', 'textdomain'),
      'new_item' => _x('Novo Banner', 'Post type singular name', 'textdomain'),
      'edit_item' => _x('Editar Banner', 'Post type singular name', 'textdomain'),
      'view_item' => _x('Ver Banner', 'Post type singular name', 'textdomain'),
    ),
    'description' => 'Gerenciar Banners',
    'public' => true,
    'show_ui' => true,
    'capability_type' => 'post',
    'rewrite' => array('slug' => 'banner-post', 'with_front' => true),
    'query_var' => true,
    'supports' => array('title'),
    'publicly_queryable' => true,
  ));
}
add_action('init', 'registrar_cpt_banner');

// Registrar Metadados do Banner
function registrar_meta_banner()
{
  $campos = array(
    'banner_moldura',
    'link_banner_moldura',
    'mega_banner',
    'link_mega_banner',
    'full_banner',
    'link_full_banner',
    'skyscraper',
    'link_skyscraper',
    'super_banner',
    'link_super_banner'
  );

  foreach ($campos as $campo) {
    register_post_meta('banner', $campo, array(
      'type' => 'string',
      'description' => ucfirst(str_replace('_', ' ', $campo)),
      'single' => true,
      'show_in_rest' => true, 
    ));
  }
}
add_action('init', 'registrar_meta_banner');

// Formatar Dados do Banner
function banner_scheme($post)
{
  $banner = new stdClass();
  $banner->title = get_the_title($post->ID);
  $banner->banner_moldura = wp_get_attachment_url(get_post_meta($post->ID, 'banner_moldura', true));
  $banner->link_banner_moldura = get_post_meta($post->ID, 'link_banner_moldura', true);
  $banner->mega_banner = wp_get_attachment_url(get_post_meta($post->ID, 'mega_banner', true));
  $banner->link_mega_banner = get_post_meta($post->ID, 'link_mega_banner', true);
  $banner->full_banner = wp_get_attachment_url(get_post_meta($post->ID, 'full_banner', true));
  $banner->link_full_banner = get_post_meta($post->ID, 'link_full_banner', true);
  $banner->skyscraper = wp_get_attachment_url(get_post_meta($post->ID, 'skyscraper', true));
  $banner->link_skyscraper = get_post_meta($post->ID, 'link_skyscraper', true);
  $banner->super_banner = wp_get_attachment_url(get_post_meta($post->ID, 'super_banner', true));
  $banner->link_super_banner = get_post_meta($post->ID, 'link_super_banner', true);

  return $banner;
}

// Callback da API REST para Banners
function api_banner_get($request)
{
  $q = isset($request['q']) ? sanitize_text_field($request['q']) : '';
  $page = isset($request['page']) ? absint($request['page']) : 1;
  $limit = isset($request['limit']) ? absint($request['limit']) : 20;

  $query = array(
    'post_type' => 'banner',
    'posts_per_page' => $limit,
    'paged' => $page,
    's' => $q,
  );

  $loop = new WP_Query($query);

  if ($loop->have_posts()) {
    $posts = $loop->posts;
    $total = $loop->found_posts;

    $banners_by_title = array(
      'Post' => array(),
      'Categoria' => array(),
    );

    foreach ($posts as $post) {
      $banner = banner_scheme($post);
      
      // Adicione o banner ao array correto com base no título
      if (array_key_exists($banner->title, $banners_by_title)) {
        $banners_by_title[$banner->title][] = $banner;
      }
    }

    $response = rest_ensure_response($banners_by_title);
    $response->header('X-Total-Count', $total);
    return $response;
  } else {
    return new WP_Error('no_results', 'No banners found', array('status' => 404));
  }
}

// Registrar Rota da API REST para Banners
function register_api_banner_endpoints()
{
  register_rest_route('api/v1', '/banner', array(
    'method' => 'GET',
    'callback' => 'api_banner_get',
  ));
}
add_action('rest_api_init', 'register_api_banner_endpoints');
