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
    $nome_completo = get_user_meta($user_id, 'nome_completo', true);
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

// 6. Salva os dados quando o usuário edita a conta
add_action('woocommerce_save_account_details', 'custom_save_account_details_fields');
function custom_save_account_details_fields($user_id) {

    if (isset($_POST['categoria_profissional'])) {
        update_user_meta($user_id, 'categoria_profissional', sanitize_text_field($_POST['categoria_profissional']));
    }
}