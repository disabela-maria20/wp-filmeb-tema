<?php
// Registrar Custom Post Type "Banner"
// function registrar_cpt_banner()
// {
//   register_post_type('banner', array(
//     'labels' => array(
//       'name' => _x('Banner Gerais', 'Post type general name', 'textdomain'),
//       'singular_name' => _x('Banner', 'Post type singular name', 'textdomain'),
//       'add_new_item' => _x('Novo Banner', 'Post type singular name', 'textdomain'),
//       'new_item' => _x('Novo Banner', 'Post type singular name', 'textdomain'),
//       'edit_item' => _x('Editar Banner', 'Post type singular name', 'textdomain'),
//       'view_item' => _x('Ver Banner', 'Post type singular name', 'textdomain'),
//     ),
//     'description' => 'Gerenciar Banners',
//     'public' => true,
//     'show_ui' => true,
//     'capability_type' => 'post',
//     'rewrite' => array('slug' => 'banner-post', 'with_front' => true),
//     'query_var' => true,
//     'supports' => array('title'),
//     'publicly_queryable' => true,
//   ));
// }
// add_action('init', 'registrar_cpt_banner');

// function registrar_cpt_banner_Filmes()
// {
//   register_post_type('banner-estreias', array(
//     'labels' => array(
//       'name' => _x('Banner Estreias', 'Post type general name', 'textdomain'),
//       'singular_name' => _x('Banner', 'Post type singular name', 'textdomain'),
//       'add_new_item' => _x('Novo Banner', 'Post type singular name', 'textdomain'),
//       'new_item' => _x('Novo Banner', 'Post type singular name', 'textdomain'),
//       'edit_item' => _x('Editar Banner', 'Post type singular name', 'textdomain'),
//       'view_item' => _x('Ver Banner', 'Post type singular name', 'textdomain'),
//     ),
//     'description' => 'Gerenciar Banners',
//     'public' => true,
//     'show_ui' => true,
//     'capability_type' => 'post',
//     'rewrite' => array('slug' => 'banner-estreias', 'with_front' => true),
//     'query_var' => true,
//     'supports' => array('title'),
//     'publicly_queryable' => true,
//   ));
// }
// add_action('init', 'registrar_cpt_banner_Filmes');