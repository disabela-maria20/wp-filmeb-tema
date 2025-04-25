<?php
/*
Plugin Name: Gerenciador de Assinaturas
Description: Plugin para gerenciar conteúdo exclusivo para assinantes
Version: 1.1
Author: Seu Nome
Text Domain: gerenciador-assinaturas
*/

if (!defined('ABSPATH')) {
    exit; // Sai se acessado diretamente
}

class GerenciadorAssinaturas {
    public function __construct() {
        // Inicializa o plugin
        add_action('admin_menu', array($this, 'adicionar_menu_admin'));
        add_action('admin_init', array($this, 'registrar_configuracoes'));
        add_action('init', array($this, 'verificar_acesso_conteudo'));
        
        // Adiciona meta boxes para posts/páginas
        add_action('add_meta_boxes', array($this, 'adicionar_meta_boxes'));
        add_action('save_post', array($this, 'salvar_meta_dados'));
        
        // Filtros para conteúdo
        add_filter('the_content', array($this, 'filtrar_conteudo_assinante'));
        
        // Shortcode para verificação
        add_shortcode('conteudo_assinante', array($this, 'shortcode_conteudo_assinante'));
        
        // Verifica se WooCommerce está ativo
        if (!in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            add_action('admin_notices', array($this, 'aviso_woocommerce_faltando'));
        }
    }
    
    // Adiciona aviso se WooCommerce não estiver ativo
    public function aviso_woocommerce_faltando() {
        ?>
<div class="notice notice-error">
  <p>O plugin Gerenciador de Assinaturas requer que o WooCommerce esteja instalado e ativado.</p>
</div>
<?php
    }
    
    // Adiciona menu de administração
    public function adicionar_menu_admin() {
        add_menu_page(
            'Gerenciador de Assinaturas',
            'Assinaturas',
            'manage_options',
            'gerenciador-assinaturas',
            array($this, 'pagina_configuracoes'),
            'dashicons-lock',
            30
        );
        
        // Submenus para diferentes tipos de conteúdo
        add_submenu_page(
            'gerenciador-assinaturas',
            'Páginas',
            'Páginas',
            'manage_options',
            'gerenciador-assinaturas-paginas',
            array($this, 'pagina_paginas')
        );
        
        add_submenu_page(
            'gerenciador-assinaturas',
            'Posts',
            'Posts',
            'manage_options',
            'gerenciador-assinaturas-posts',
            array($this, 'pagina_posts')
        );
        
        add_submenu_page(
            'gerenciador-assinaturas',
            'Categorias',
            'Categorias',
            'manage_options',
            'gerenciador-assinaturas-categorias',
            array($this, 'pagina_categorias')
        );
    }
    
    // Registra configurações
    public function registrar_configuracoes() {
        register_setting('gerenciador_assinaturas_opcoes', 'ga_produto_assinatura');
    }
    
    // Página de configurações principal
    public function pagina_configuracoes() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        // Obtém todos os produtos do WooCommerce
        $produtos = $this->obter_produtos_woocommerce();
        $produto_selecionado = get_option('ga_produto_assinatura');
        ?>
<div class="wrap">
  <h1><?php echo esc_html(get_admin_page_title()); ?></h1>

  <form action="options.php" method="post">
    <?php
                settings_fields('gerenciador_assinaturas_opcoes');
                do_settings_sections('gerenciador_assinaturas_opcoes');
                ?>

    <table class="form-table">
      <tr valign="top">
        <th scope="row">Produto para Assinatura</th>
        <td>
          <select name="ga_produto_assinatura" id="ga_produto_assinatura" class="regular-text">
            <option value="">Selecione um produto</option>
            <?php foreach ($produtos as $produto) : ?>
            <option value="<?php echo esc_attr($produto->get_id()); ?>"
              <?php selected($produto_selecionado, $produto->get_id()); ?>>
              <?php echo esc_html($produto->get_name()); ?>
            </option>
            <?php endforeach; ?>
          </select>
          <p class="description">Selecione qual produto o usuário precisa comprar para ter acesso ao conteúdo exclusivo.
          </p>
        </td>
      </tr>
    </table>

    <?php submit_button('Salvar Configurações'); ?>
  </form>
</div>
<?php
    }
    
    // Página de listagem de páginas
    public function pagina_paginas() {
        $this->renderizar_listagem('page');
    }
    
    // Página de listagem de posts
    public function pagina_posts() {
        $this->renderizar_listagem('post');
    }
    
    // Página de listagem de categorias
    public function pagina_categorias() {
        $this->renderizar_listagem_categorias();
    }
    
    // Renderiza a listagem de posts/páginas
    private function renderizar_listagem($post_type) {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        $post_type_obj = get_post_type_object($post_type);
        $posts = get_posts(array(
            'post_type' => $post_type,
            'numberposts' => -1,
            'orderby' => 'title',
            'order' => 'ASC'
        ));
        
        // Processa o formulário se enviado
        if (isset($_POST['salvar_restricoes']) && check_admin_referer('salvar_restricoes')) {
            $restricoes = isset($_POST['restrito']) ? $_POST['restrito'] : array();
            
            foreach ($posts as $post) {
                $restrito = in_array($post->ID, $restricoes) ? 'sim' : 'nao';
                update_post_meta($post->ID, '_conteudo_restrito', $restrito);
            }
            
            echo '<div class="notice notice-success"><p>Configurações salvas com sucesso!</p></div>';
        }
        
        // Paginação
        $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
        $per_page = 20;
        $total_posts = count($posts);
        $total_pages = ceil($total_posts / $per_page);
        $offset = ($current_page - 1) * $per_page;
        $posts_paginados = array_slice($posts, $offset, $per_page);
        ?>
<div class="wrap">
  <h1><?php echo esc_html($post_type_obj->labels->name); ?> Restritos</h1>

  <form method="post">
    <?php wp_nonce_field('salvar_restricoes'); ?>

    <table class="wp-list-table widefat fixed striped">
      <thead>
        <tr>
          <th width="20px"></th>
          <th>Título</th>
          <th width="150px">Status</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($posts_paginados)) : ?>
        <tr>
          <td colspan="3">Nenhum <?php echo esc_html($post_type_obj->labels->singular_name); ?> encontrado.</td>
        </tr>
        <?php else : ?>
        <?php foreach ($posts_paginados as $post) : ?>
        <?php $restrito = get_post_meta($post->ID, '_conteudo_restrito', true) === 'sim'; ?>
        <tr>
          <td><input type="checkbox" name="restrito[]" value="<?php echo esc_attr($post->ID); ?>"
              <?php checked($restrito); ?>></td>
          <td>
            <a href="<?php echo esc_url(get_edit_post_link($post->ID)); ?>">
              <?php echo esc_html($post->post_title); ?>
            </a>
          </td>
          <td>
            <?php echo $restrito ? '<span style="color:red;">Restrito</span>' : '<span style="color:green;">Público</span>'; ?>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>

    <!-- Paginação -->
    <div class="tablenav bottom">
      <div class="tablenav-pages">
        <?php
                        echo paginate_links(array(
                            'base' => add_query_arg('paged', '%#%'),
                            'format' => '',
                            'prev_text' => '&laquo;',
                            'next_text' => '&raquo;',
                            'total' => $total_pages,
                            'current' => $current_page
                        ));
                        ?>
      </div>
    </div>

    <p class="submit">
      <input type="submit" name="salvar_restricoes" class="button button-primary" value="Salvar Alterações">
    </p>
  </form>
</div>
<?php
    }
    
    // Renderiza a listagem de categorias
    private function renderizar_listagem_categorias() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        $categories = get_categories(array(
            'hide_empty' => false
        ));
        
        // Processa o formulário se enviado
        if (isset($_POST['salvar_restricoes_categorias']) && check_admin_referer('salvar_restricoes_categorias')) {
            $restricoes = isset($_POST['restrito']) ? $_POST['restrito'] : array();
            
            foreach ($categories as $category) {
                $restrito = in_array($category->term_id, $restricoes) ? 'sim' : 'nao';
                update_term_meta($category->term_id, '_conteudo_restrito', $restrito);
            }
            
            echo '<div class="notice notice-success"><p>Configurações salvas com sucesso!</p></div>';
        }
        
        // Paginação
        $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
        $per_page = 20;
        $total_categories = count($categories);
        $total_pages = ceil($total_categories / $per_page);
        $offset = ($current_page - 1) * $per_page;
        $categories_paginadas = array_slice($categories, $offset, $per_page);
        ?>
<div class="wrap">
  <h1>Categorias Restritas</h1>

  <form method="post">
    <?php wp_nonce_field('salvar_restricoes_categorias'); ?>

    <table class="wp-list-table widefat fixed striped">
      <thead>
        <tr>
          <th width="20px"></th>
          <th>Nome</th>
          <th width="150px">Status</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($categories_paginadas)) : ?>
        <tr>
          <td colspan="3">Nenhuma categoria encontrada.</td>
        </tr>
        <?php else : ?>
        <?php foreach ($categories_paginadas as $category) : ?>
        <?php $restrito = get_term_meta($category->term_id, '_conteudo_restrito', true) === 'sim'; ?>
        <tr>
          <td><input type="checkbox" name="restrito[]" value="<?php echo esc_attr($category->term_id); ?>"
              <?php checked($restrito); ?>></td>
          <td>
            <a href="<?php echo esc_url(get_edit_term_link($category->term_id, 'category')); ?>">
              <?php echo esc_html($category->name); ?>
            </a>
          </td>
          <td>
            <?php echo $restrito ? '<span style="color:red;">Restrito</span>' : '<span style="color:green;">Pública</span>'; ?>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>

    <!-- Paginação -->
    <div class="tablenav bottom">
      <div class="tablenav-pages">
        <?php
                        echo paginate_links(array(
                            'base' => add_query_arg('paged', '%#%'),
                            'format' => '',
                            'prev_text' => '&laquo;',
                            'next_text' => '&raquo;',
                            'total' => $total_pages,
                            'current' => $current_page
                        ));
                        ?>
      </div>
    </div>

    <p class="submit">
      <input type="submit" name="salvar_restricoes_categorias" class="button button-primary" value="Salvar Alterações">
    </p>
  </form>
</div>
<?php
    }
    
    // Adiciona meta boxes para posts e páginas
    public function adicionar_meta_boxes() {
        add_meta_box(
            'conteudo_restrito_meta_box',
            'Acesso Restrito',
            array($this, 'renderizar_meta_box'),
            array('post', 'page'),
            'side',
            'high'
        );
    }
    
    // Renderiza a meta box
    public function renderizar_meta_box($post) {
        $restrito = get_post_meta($post->ID, '_conteudo_restrito', true) === 'sim';
        wp_nonce_field('salvar_meta_box', 'conteudo_restrito_nonce');
        ?>
<p>
  <label>
    <input type="checkbox" name="_conteudo_restrito" value="sim" <?php checked($restrito); ?>>
    Restringir acesso apenas para assinantes
  </label>
</p>
<p class="description">Marque esta opção para tornar este conteúdo acessível apenas para usuários que compraram o
  produto de assinatura.</p>
<?php
    }
    
    // Salva os dados da meta box
    public function salvar_meta_dados($post_id) {
        if (!isset($_POST['conteudo_restrito_nonce']) || !wp_verify_nonce($_POST['conteudo_restrito_nonce'], 'salvar_meta_box')) {
            return;
        }
        
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
        
        $restrito = isset($_POST['_conteudo_restrito']) ? 'sim' : 'nao';
        update_post_meta($post_id, '_conteudo_restrito', $restrito);
    }
    
    // Filtra o conteúdo para verificar acesso
    public function filtrar_conteudo_assinante($content) {
      global $post;
      
      if (is_admin() || !is_singular()) {
          return $content;
      }
      
      // Verifica se o conteúdo é restrito
      $restrito = get_post_meta($post->ID, '_conteudo_restrito', true) === 'sim';
      
      // Verifica categorias restritas
      $categorias = get_the_category($post->ID);
      $categoria_restrita = false;
      
      foreach ($categorias as $categoria) {
          if (get_term_meta($categoria->term_id, '_conteudo_restrito', true) === 'sim') {
              $categoria_restrita = true;
              break;
          }
      }
      
      if ($restrito || $categoria_restrita) {
          if (!$this->usuario_tem_acesso()) {
              $mensagem = '<div class="conteudo-restrito-alerta">';
              
              if (is_user_logged_in()) {
                  $mensagem .= '<h3>Conteúdo Exclusivo para Assinantes</h3>';
                  $mensagem .= '<p>Você está logado, mas não possui uma assinatura ativa para acessar este conteúdo.</p>';
              } else {
                  $mensagem .= '<h3>Conteúdo Exclusivo para Assinantes</h3>';
                  $mensagem .= '<p>Este conteúdo está disponível apenas para assinantes. Por favor, faça login e adquira o produto de assinatura para ter acesso.</p>';
              }
              
              $produto_id = get_option('ga_produto_assinatura');
              if ($produto_id) {
                  $mensagem .= '<p><a href="' . esc_url(get_permalink($produto_id)) . '" class="button button-primary">Assinar Agora</a></p>';
                  
                  if (!is_user_logged_in()) {
                      $mensagem .= '<p><a href="' . esc_url(wp_login_url(get_permalink($post->ID))) . '" class="button">Fazer Login</a></p>';
                  }
              }
              
              $mensagem .= '</div>';
              
              return $mensagem;
          }
      }
      
      return $content;
  }
    
    // Shortcode para conteúdo de assinante
    public function shortcode_conteudo_assinante($atts, $content = null) {
        if ($this->usuario_tem_acesso()) {
            return do_shortcode($content);
        } else {
            $produto_id = get_option('ga_produto_assinatura');
            $mensagem = '<div class="conteudo-restrito-alerta">';
            $mensagem .= '<p>Esta seção é exclusiva para assinantes.</p>';
            
            if ($produto_id) {
                $mensagem .= '<p><a href="' . esc_url(get_permalink($produto_id)) . '" class="button">Assinar Agora</a></p>';
            }
            
            $mensagem .= '</div>';
            
            return $mensagem;
        }
    }
    
    // Verifica se o usuário tem acesso (CORREÇÃO PRINCIPAL)
    public function usuario_tem_acesso() {
        // Se não há produto configurado, permite acesso
        $produto_id = get_option('ga_produto_assinatura');
        if (!$produto_id) {
            return true;
        }
        
        // Se não está logado, não tem acesso
        if (!is_user_logged_in()) {
            return false;
        }
        
        $user_id = get_current_user_id();
        
        // Verifica se o usuário tem o produto
        if (function_exists('wc_customer_bought_product')) {
            // Verifica pedidos completos
            $comprou = wc_customer_bought_product('', $user_id, $produto_id);
            
            // Verifica também pedidos em processamento, para evitar falsos negativos
            if (!$comprou) {
                $args = array(
                    'customer_id' => $user_id,
                    'status' => array('processing', 'completed'),
                    'limit' => -1,
                    'return' => 'ids',
                );
                
                $pedidos = wc_get_orders($args);
                
                foreach ($pedidos as $pedido_id) {
                    $pedido = wc_get_order($pedido_id);
                    $itens = $pedido->get_items();
                    
                    foreach ($itens as $item) {
                        if ($item->get_product_id() == $produto_id) {
                            return true;
                        }
                    }
                }
            }
            
            return $comprou;
        }
        
        return false;
    }
    
    // Redireciona usuários não logados para a página de login
    public function verificar_acesso_conteudo() {
      if (is_singular() && !is_admin()) {
          global $post;
          
          $restrito = get_post_meta($post->ID, '_conteudo_restrito', true) === 'sim';
          
          // Verifica categorias restritas
          $categorias = get_the_category($post->ID);
          $categoria_restrita = false;
          
          foreach ($categorias as $categoria) {
              if (get_term_meta($categoria->term_id, '_conteudo_restrito', true) === 'sim') {
                  $categoria_restrita = true;
                  break;
              }
          }
          
          // Apenas redireciona se for conteúdo restrito E usuário não estiver logado
          if (($restrito || $categoria_restrita) && !is_user_logged_in()) {
              wp_redirect(wp_login_url(get_permalink($post->ID)));
              exit;
          }
          
          // Se estiver logado mas não tem acesso, mostra mensagem mas não desloga
          if (($restrito || $categoria_restrita) && is_user_logged_in() && !$this->usuario_tem_acesso()) {
              // Não fazemos nada aqui, pois o filtro de conteúdo já mostrará a mensagem
              return;
          }
      }
  }
  
    
    // Obtém produtos do WooCommerce
    private function obter_produtos_woocommerce() {
        if (!class_exists('WC_Product')) {
            return array();
        }
        
        $args = array(
            'status' => 'publish',
            'limit' => -1,
            'orderby' => 'title',
            'order' => 'ASC'
        );
        
        return wc_get_products($args);
    }
}

// Inicializa o plugin
new GerenciadorAssinaturas();

// Adiciona estilos para o front-end
function gerenciador_assinaturas_estilos() {
    ?>
<style>
.conteudo-restrito-alerta {
  background: #fff8e5;
  border: 1px solid #ffd699;
  padding: 20px;
  margin: 20px 0;
  text-align: center;
  border-radius: 4px;
}

.conteudo-restrito-alerta h3 {
  margin-top: 0;
  color: #d26c22;
}
</style>
<?php
}
add_action('wp_head', 'gerenciador_assinaturas_estilos');