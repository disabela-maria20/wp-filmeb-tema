<?php
function registrar_endpoint_taxonomias_massa()
{
  register_rest_route('filmes/v1', '/inserir-termos', array(
    'methods' => 'POST',
    'callback' => 'inserir_termos_em_massa',
    'permission_callback' => '__return_true'
  ));
}
add_action('rest_api_init', 'registrar_endpoint_taxonomias_massa');

function inserir_termos_em_massa(WP_REST_Request $request)
{
  $parametros = $request->get_json_params();

  if (empty($parametros['taxonomia']) || empty($parametros['termos'])) {
    return new WP_Error('dados_invalidos', 'Taxonomia ou termos não fornecidos', array('status' => 400));
  }

  $taxonomia = sanitize_text_field($parametros['taxonomia']);
  $termos = $parametros['termos'];
  $resultados = array();
  $taxonomias_validas = ['distribuidoras', 'paises', 'generos', 'classificacoes', 'tecnologias', 'feriados'];

  if (!in_array($taxonomia, $taxonomias_validas)) {
    return new WP_Error('taxonomia_invalida', 'Taxonomia não reconhecida', array('status' => 400));
  }

  foreach ($termos as $termo) {
    if (empty($termo['nome'])) {
      $resultados[] = array(
        'termo' => 'Não especificado',
        'resultado' => 'Erro: Nome do termo é obrigatório'
      );
      continue;
    }

    $args = array();
    $nome = sanitize_text_field($termo['nome']);

    if (!empty($termo['slug'])) {
      $args['slug'] = sanitize_title($termo['slug']);
    }

    if (!empty($termo['descricao'])) {
      $args['description'] = sanitize_text_field($termo['descricao']);
    }

    $resultado = wp_insert_term($nome, $taxonomia, $args);

    $resultados[] = array(
      'termo' => $nome,
      'resultado' => is_wp_error($resultado) ? $resultado->get_error_message() : 'Termo inserido com sucesso (ID: ' . $resultado['term_id'] . ')'
    );
  }

  return new WP_REST_Response(array(
    'taxonomia' => $taxonomia,
    'total_inseridos' => count($termos),
    'resultados' => $resultados
  ), 200);
}