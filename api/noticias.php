<?php
function api_noticia_post($request)
{
  $data = $request->get_json_params();

  $titulo = !empty($data['titulo']) ? sanitize_text_field($data['titulo']) : '';
  $descricao = !empty($data['descricao']) ? sanitize_textarea_field($data['descricao']) : '';
  $post_excerpt = !empty($data['post_excerpt']) ? sanitize_text_field($data['post_excerpt']) : '';
  $post_category = !empty($data['post_category']) ? sanitize_text_field($data['post_category']) : '';
  $post_author = !empty($data['post_author']) ? intval($data['post_author']) : get_current_user_id();
  $cartaz = !empty($data['cartaz']) ? esc_url_raw($data['cartaz']) : '';
  $data_filme = !empty($data['data_filme']) ? sanitize_text_field($data['data_filme']) : '';
  $chapel = !empty($data['chapel']) ? sanitize_text_field($data['chapel']) : '';
  $edicao = !empty($data['edicao']) ? sanitize_text_field($data['edicao']) : '';

  $category_id = [];
  if (!empty($post_category)) {
    $category = get_term_by('name', $post_category, 'category');

    if (!$category) {
      $new_category = wp_insert_term($post_category, 'category');

      if (!is_wp_error($new_category)) {
        $category_id[] = $new_category['term_id'];
      }
    } else {
      $category_id[] = $category->term_id;
    }
  }

  $cartaz_id = '';
  if (!empty($cartaz)) {
    $cartaz_id = upload_image_from_url($cartaz);
    if (is_wp_error($cartaz_id)) {
      return new WP_Error('upload_error', 'Erro ao baixar a imagem do cartaz.', ['status' => 500, 'data' => $cartaz]);
    }
  }

  $post_data = [
    'post_type' => 'post',
    'post_title' => $titulo,
    'post_status' => 'publish',
    'post_content' => $descricao,
    'post_excerpt' => $post_excerpt,
    'post_category' => $category_id,
    'post_author' => $post_author,
    'meta_input' => [
      'data_filme' => $data_filme,
    ],
  ];

  $post_id = wp_insert_post($post_data);

  if ($post_id) {
    if (!empty($cartaz_id)) {
      set_post_thumbnail($post_id, $cartaz_id);
    }

    if (!empty($cartaz_id)) {
      $field_data = ['imagem' => wp_get_attachment_url($cartaz_id)];
      CFS()->save($field_data, ['ID' => $post_id]);
    }

    // Verifica se a categoria é "plus" e salva os campos personalizados
    if ($post_category === 'plus') {
      $custom_fields = [
        'chapeu' => $chapel,
        'edicao' => $edicao,
        'data' => $data_filme
      ];
      CFS()->save($custom_fields, ['ID' => $post_id]);
    }

    return rest_ensure_response([
      'message' => 'Noticia criado com sucesso!',
      'post_id' => $post_id,
      'category_id' => $category_id,
      'data_filme' => $data_filme,
      'chapel' => $chapel,
      'edicao' => $edicao,
    ]);
  }

  return new WP_Error('Noticia_nao_criado', 'Erro ao criar o Noticia', ['status' => 500]);
}

function register_noticias_api_endpoints()
{
  register_rest_route('api/v1', '/noticias', [
    'methods' => 'POST',
    'callback' => 'api_noticia_post',
  ]);
}
add_action('rest_api_init', 'register_noticias_api_endpoints');
?>