<?php


add_action('woocommerce_register_form_start', 'custom_wc_register_form_fields');
function custom_wc_register_form_fields() {
?>
<p class="form-row form-row-wide">
  <label for="reg_nome_completo"><?php _e('Nome completo', 'woocommerce'); ?> <span class="required">*</span></label>
  <input type="text" class="input-text" name="nome_completo" id="reg_nome_completo"
    value="<?php if (!empty($_POST['nome_completo'])) echo esc_attr($_POST['nome_completo']); ?>" required />
</p>

<p class="form-row form-row-wide">
  <label for="reg_categoria_profissional"><?php _e('Categoria Profissional', 'woocommerce'); ?> <span
      class="required">*</span></label>
  <select name="categoria_profissional" id="reg_categoria_profissional" required>
    <option value="">Selecione uma opção</option>
    <?php
            $categorias = [
                'Advogado', 'Agência', 'Assessoria imprensa', 'Banco', 'Cineasta', 'Corretora', 'Distribuidor', 'Estudante',
                'Exibidor', 'Exibidor-distribuidor', 'Festival', 'Imprensa', 'Infraestrutura', 'Investidor', 'Mercado',
                'Órgão público', 'Portal internet', 'Produtor', 'Professor', 'Roteirista', 'Shopping', 'TV', 'Universidade', 'Vídeo', 'Outros'
            ];
            foreach ($categorias as $categoria) {
                echo '<option value="' . esc_attr($categoria) . '">' . esc_html($categoria) . '</option>';
            }
            ?>
  </select>
</p>

<p class="form-row form-row-wide">
  <label for="reg_password2"><?php _e('Confirmar senha', 'woocommerce'); ?> <span class="required">*</span></label>
  <input type="password" class="input-text" name="password2" id="reg_password2" required />
</p>
<?php


// 2. Validação dos campos no envio
add_action('woocommerce_register_post', 'custom_wc_validate_register_fields', 10, 3);
function custom_wc_validate_register_fields($username, $email, $validation_errors) {
    if (empty($_POST['nome_completo'])) {
        $validation_errors->add('nome_completo_erro', __('Por favor, informe seu nome completo.', 'woocommerce'));
    }

    if (empty($_POST['categoria_profissional'])) {
        $validation_errors->add('categoria_profissional_erro', __('Por favor, selecione uma categoria profissional.', 'woocommerce'));
    }

    if ($_POST['password'] !== $_POST['password2']) {
        $validation_errors->add('senha_confirma_erro', __('As senhas não coincidem.', 'woocommerce'));
    }

    return $validation_errors;
}

// 3. Salva os campos personalizados
add_action('woocommerce_created_customer', 'custom_wc_save_register_fields');
function custom_wc_save_register_fields($customer_id) {
    if (!empty($_POST['nome_completo'])) {
        update_user_meta($customer_id, 'nome_completo', sanitize_text_field($_POST['nome_completo']));
    }

    if (!empty($_POST['categoria_profissional'])) {
        update_user_meta($customer_id, 'categoria_profissional', sanitize_text_field($_POST['categoria_profissional']));
    }
}

// 4. Exibe os campos no painel admin (opcional)
add_action('show_user_profile', 'custom_show_user_fields');
add_action('edit_user_profile', 'custom_show_user_fields');
function custom_show_user_fields($user) {
    ?>
<h3>Informações Profissionais</h3>
<table class="form-table">
  <tr>
    <th><label for="nome_completo">Nome completo</label></th>
    <td>
      <input type="text" name="nome_completo"
        value="<?php echo esc_attr(get_user_meta($user->ID, 'nome_completo', true)); ?>" class="regular-text" />
    </td>
  </tr>
  <tr>
    <th><label for="categoria_profissional">Categoria Profissional</label></th>
    <td>
      <input type="text" name="categoria_profissional"
        value="<?php echo esc_attr(get_user_meta($user->ID, 'categoria_profissional', true)); ?>"
        class="regular-text" />
    </td>
  </tr>
</table>
<?php
}
}

// 5. Adiciona os campos personalizados na página "Detalhes da conta"
add_action('woocommerce_edit_account_form', 'custom_edit_account_form_fields');
function custom_edit_account_form_fields() {
    $user_id = get_current_user_id();
    $categoria_profissional = get_user_meta($user_id, 'categoria_profissional', true);

    ?>
<fieldset>
  <legend>Informações Profissionais</legend>

  <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
    <label for="categoria_profissional">Categoria Profissional</label>
    <select name="categoria_profissional" id="categoria_profissional">
      <option value="">Selecione uma opção</option>
      <?php
                $categorias = [
                    'Advogado', 'Agência', 'Assessoria imprensa', 'Banco', 'Cineasta', 'Corretora', 'Distribuidor', 'Estudante',
                    'Exibidor', 'Exibidor-distribuidor', 'Festival', 'Imprensa', 'Infraestrutura', 'Investidor', 'Mercado',
                    'Órgão público', 'Portal internet', 'Produtor', 'Professor', 'Roteirista', 'Shopping', 'TV', 'Universidade', 'Vídeo', 'Outros'
                ];
                foreach ($categorias as $categoria) {
                    echo '<option value="' . esc_attr($categoria) . '" ' . selected($categoria_profissional, $categoria, false) . '>' . esc_html($categoria) . '</option>';
                }
                ?>
    </select>
  </p>
</fieldset>
<?php
}


add_action('woocommerce_save_account_details', 'custom_save_account_details_fields');
function custom_save_account_details_fields($user_id) {

    if (isset($_POST['categoria_profissional'])) {
        update_user_meta($user_id, 'categoria_profissional', sanitize_text_field($_POST['categoria_profissional']));
    }
}

add_action('woocommerce_save_account_details', 'custom_save_account_details_fields');
add_action('woocommerce_checkout_update_user_meta', 'custom_save_account_details_fields');

add_action( 'init', 'adicionar_endpoint_assinaturas' );
function adicionar_endpoint_assinaturas() {
    add_rewrite_endpoint( 'assinaturas', EP_ROOT | EP_PAGES );
}

add_filter ( 'woocommerce_account_menu_items', 'adicionar_item_assinaturas_menu', 999 );
function adicionar_item_assinaturas_menu( $menu_links ) {
    unset( $menu_links['dashboard'] );
    unset( $menu_links['downloads'] );
    
    // Adiciona Assinaturas como primeiro item
    $new_menu = array( 'assinaturas' => 'Assinaturas' ) + $menu_links;
    
    return $new_menu;
}

add_action( 'woocommerce_account_assinaturas_endpoint', 'conteudo_pagina_assinaturas' );
add_action( 'woocommerce_account_assinaturas_endpoint', 'conteudo_pagina_assinaturas' );

function conteudo_pagina_assinaturas() {
    if ( ! is_user_logged_in() ) {
        echo '<p>Por favor, faça login para visualizar suas assinaturas.</p>';
        return;
    }

    $user_id = get_current_user_id();

    // Obtém todas as memberships do usuário
    $memberships = wc_memberships_get_user_memberships( $user_id );

    if ( empty( $memberships ) ) {
        echo '<div class="woocommerce-message woocommerce-info">';
        echo '<p>Você não possui assinaturas ativas.</p>';
        echo '</div>';
        return;
    }

    foreach ( $memberships as $membership ) {
        $plan = $membership->get_plan();

        $status = $membership->get_status(); // active, paused, cancelled, etc.
        $start_date = $membership->get_start_date( 'timestamp' );
        $end_date = $membership->get_end_date( 'timestamp' );

        echo '<div class="woocommerce-message-user-account">';
        echo '<h4>Assinatura: ' . esc_html( $plan->get_name() ) . '</h4>';
        echo '<p><strong>Data de início:</strong> ' . date_i18n( 'd/m/Y', $start_date ) . '</p>';

        if ( $end_date ) {
            echo '<p><strong>Data de término:</strong> ' . date_i18n( 'd/m/Y', $end_date ) . '</p>';

            $today = current_time( 'timestamp' );
            $remaining_days = floor( ( $end_date - $today ) / DAY_IN_SECONDS );

            if ( $remaining_days < 0 ) {
                echo '<p><strong>Status:</strong> <span style="color:red;">Expirada</span></p>';
                echo '<p>Sua assinatura expirou em ' . date_i18n( 'd/m/Y', $end_date ) . '.</p>';
            } else {
                echo '<p><strong>Status:</strong> <span style="color:green;">Ativa</span></p>';
                echo '<p><strong>Dias restantes:</strong> ' . $remaining_days . ' dias</p>';
            }
        } else {
            echo '<p><strong>Status:</strong> ' . ucfirst( $status ) . '</p>';
            echo '<p>Esta assinatura não possui data de término definida.</p>';
        }

        echo '</div>';
    }
}



// // Redireciona para Assinaturas ao acessar Minha Conta
// add_action( 'template_redirect', 'redirect_my_account_to_subscriptions' );
// function redirect_my_account_to_subscriptions() {
//     if ( is_account_page() && ! is_wc_endpoint_url() ) {
//         wp_safe_redirect( wc_get_account_endpoint_url( 'assinaturas' ) );
//         exit;
//     }
// }

add_filter('woocommerce_account_menu_items', 'customizar_menu_minha_conta_final', 999);

function customizar_menu_minha_conta_final($items) {
    // Remove "Associações" baseado nos slugs mais comuns
    unset($items['members-area']);
    unset($items['my-membership']);
    unset($items['memberships']);

    // Retorna apenas os itens desejados
    return [
        'assinaturas'     => __('Assinaturas', 'assinaturas'),
        'edit-account'      => __('Detalhes da conta', 'woocommerce'),
        'customer-logout'   => __('Sair', 'woocommerce'),
    ];
}

add_filter('woocommerce_account_menu_items','custom_menu_items');
function custom_menu_items($items){
    $items = array('assinaturas' => __('Assinaturas','woocommerce')) + $items;
    return $items;
}


add_filter('woocommerce_checkout_fields', 'remover_campos_endereco_checkout');

function remover_campos_endereco_checkout($fields) {
    // Remove todos os campos de endereço de cobrança
   
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_address_1']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_city']);
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_country']);
    unset($fields['billing']['billing_state']);
    unset($fields['billing']['billing_phone']);
     unset($fields['billing']['billing_neighborhood']);
    unset($fields['billing']['billing_number']);

    // Remove todos os campos de endereço de entrega
    unset($fields['shipping']['shipping_first_name']);
    unset($fields['shipping']['shipping_last_name']);
    unset($fields['shipping']['shipping_company']);
    unset($fields['shipping']['shipping_address_1']);
    unset($fields['shipping']['shipping_address_2']);
    unset($fields['shipping']['shipping_city']);
    unset($fields['shipping']['shipping_postcode']);
    unset($fields['shipping']['shipping_country']);
    unset($fields['shipping']['shipping_state']);
     unset($fields['shipping']['shipping_neighborhood']);
    unset($fields['shipping']['shipping_number']);

    return $fields;
}