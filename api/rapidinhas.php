<?php

function registrar_cpt_rapidinhas()
{
    register_post_type('rapidinhas', array(
        'labels' => array(
            'name' => _x('Rapidinhas', 'Post type general name', 'textdomain'),
            'singular_name' => _x('Rapidinha', 'Post type singular name', 'textdomain'),
            'add_new_item' => _x('Adicionar Nova Rapidinha', 'Post type singular name', 'textdomain'),
            'new_item' => _x('Nova Rapidinha', 'Post type singular name', 'textdomain'),
            'edit_item' => _x('Editar Rapidinha', 'Post type singular name', 'textdomain'),
            'view_item' => _x('Ver Rapidinha', 'Post type singular name', 'textdomain'),
        ),
        'description' => 'Gerenciar Rapidinhas',
        'public' => true,
        'show_ui' => true,
        'capability_type' => 'post',
        'rewrite' => array('slug' => 'rapidinhas', 'with_front' => true),
        'query_var' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'publicly_queryable' => true,
        'has_archive' => true,
        'show_in_rest' => true,
    ));
}
add_action('init', 'registrar_cpt_rapidinhas');


function registrar_cpt_edicoes()
{
    register_post_type('edicoes', array(
        'labels' => array(
            'name' => _x('Edições', 'Post type general name', 'textdomain'),
            'singular_name' => _x('Edição', 'Post type singular name', 'textdomain'),
            'add_new_item' => _x('Adicionar Edição', 'Post type singular name', 'textdomain'),
            'new_item' => _x('Nova Edição', 'Post type singular name', 'textdomain'),
            'edit_item' => _x('Editar Edição', 'Post type singular name', 'textdomain'),
            'view_item' => _x('Ver Edição', 'Post type singular name', 'textdomain'),
        ),
        'description' => 'Gerenciar Edições',
        'public' => true,
        'show_ui' => true,
        'capability_type' => 'post',
        'rewrite' => array('slug' => 'edicao', 'with_front' => true),
        'query_var' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'publicly_queryable' => true,
        'has_archive' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-book-alt',
        'show_in_rest' => true,
    ));
}
add_action('init', 'registrar_cpt_edicoes');