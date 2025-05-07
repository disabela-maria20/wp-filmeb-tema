<?php 
// Template Name: Box Office - Assine
get_header(); ?>

<a href="<?php echo CFS()->get('link_banner_moldura');?>">
  <img src="<?php echo CFS()->get('banner_moldura');?>" class="w-full p-35 img-banner bannerMobile" alt="banner">
</a>

<div class="container bannerDesktop">
  <div class="grid-banner-superior">
    <a href="<?php echo CFS()->get('link_mega_banner'); ?>">
      <img src="<?php echo CFS()->get('mega_banner'); ?>" class="img-banner " alt="banner">
    </a>
  </div>
</div>

<?php get_template_part('components/MenuMobile/index'); ?>
<?php get_template_part('components/MenuDesktop/index'); ?>

<section class="bg-gray">
  <div class="bannerMobile bg-gray padding-banner ">
    <div class="grid-banner-superior">
      <a href="<?php echo CFS()->get('link_mega_banner'); ?>">
        <img src="<?php echo CFS()->get('mega_banner'); ?>" class="img-banner " alt="banner">
      </a>
    </div>
  </div>
</section>

<?php if (have_posts()): while (have_posts()): the_post(); ?>

<main>
  <div class="container page-box-office">
    <img class="banner-box" src="<?php echo get_template_directory_uri(); ?>/assets/images/banner-box-office.png"
      alt="">

    <a href="<?php echo CFS()->get('link_super_banner'); ?>">
      <img src="<?php echo CFS()->get('super_banner'); ?>" class="img-banner " alt="banner">
    </a>
    <section class="area-form">
      <h1>Assine o Box Office Brasil</h1>
      <div id="box-office-form-container">
        <form id="box-office-form" method="post">
          <div id="box-office-message" class="box-office-message" style="display: none;"></div>

          <label for="nome">
            <input type="text" name="nome" id="nome" placeholder="Nome" required>
          </label>
          <label for="sobreNome">
            <input type="text" name="sobreNome" id="sobreNome" placeholder="Sobre Nome">
          </label>
          <label for="email">
            <input type="email" name="email" id="email" placeholder="E-mail" required>
          </label>
          <label for="telefone">
            <input type="text" name="telefone" id="telefone" placeholder="Telefone">
          </label>

          <fieldset>
            <legend>Selecione os produtos do seu interesse</legend>
            <div class="flex">
              <div class="radio">
                <input type="radio" name="produto_interesse" id="Dados" value="Banco de Dados">
                <label for="Dados">Banco de Dados</label>
              </div>
              <div class="radio">
                <input type="radio" name="produto_interesse" id="Report" value="Filme B Report">
                <label for="Report">Filme B Report</label>
              </div>
              <div class="radio">
                <input type="radio" name="produto_interesse" id="Ontime" value="Filme B Ontime">
                <label for="Ontime">Filme B Ontime</label>
              </div>
              <div class="radio">
                <input type="radio" name="produto_interesse" id="Sadis" value="Sadis ANCINE">
                <label for="Sadis">Sadis ANCINE</label>
              </div>
            </div>
            <div class="msg">
              <label for="mensagem">
                <textarea name="mensagem" id="mensagem" placeholder="Mensagem"></textarea>
              </label>
            </div>
          </fieldset>
          <div class="area-btn-form">
            <button type="submit" id="box-office-submit">Enviar</button>
          </div>
          <?php wp_nonce_field('box_office_lead_nonce', 'security'); ?>
        </form>
      </div>
    </section>
    <h2 class="titulo">Outros produtos Filme B | Box Office Brasil</h2>
    <div class="grid-box-produtos">
      <div class="item">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/report.png" alt="">
        <h3>FilmeB Report</h3>
        <p>Coleta diária dos dados de público e renda junto aos exibidores, com envios diários por email ou FTP de
          relatórios padronizados para a importação automática no sistema do distribuidor.</p>
      </div>
      <div class="item">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/progressao-grafica.png" alt="">
        <h3>FilmeB Ontime</h3>
        <p>Ranking top 10 estimado de filmes no fim de semana, com uma projeção nos valores de público, renda, pmi,
          média de público por cinema, média de público por sessão, variação (%) e acumulados.</p>
      </div>
      <div class="item">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/ancine.webp" alt="">
        <h3>Sadis ANCINE</h3>
        <p>Exportação e envio mensal do relatório SADIS em XML para upload no Sistema Ancine Digital, contendo os dados
          de público e renda dos filmes exibidos pelo distribuidor.
        </p>
      </div>
    </div>
    <a href="<?php echo CFS()->get('link_modulo'); ?>">
      <img src="<?php echo CFS()->get('modulo'); ?>" class="img-banner " alt="banner">
    </a>
  </div>

</main>

<?php endwhile; else:endif; ?>
<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>

<script>
jQuery(document).ready(function($) {
  // Máscara de telefone (XX) XXXXX-XXXX
  function formatPhone(phone) {
    const digits = phone.replace(/\D/g, '');
    let formatted = digits;

    if (digits.length > 2) {
      formatted = `(${digits.substring(0, 2)}) ${digits.substring(2)}`;
    }
    if (digits.length > 7) {
      formatted = `(${digits.substring(0, 2)}) ${digits.substring(2, 7)}-${digits.substring(7, 11)}`;
    }

    return formatted.substring(0, 15);
  }

  // Aplicar máscara e validação em tempo real
  $('#telefone').on('input', function(e) {
    const cursorPosition = this.selectionStart;
    const inputValue = $(this).val();
    const isDeleting = (inputValue.length < $(this).data('prevLength'));

    $(this).val(formatPhone(inputValue));
    $(this).data('prevLength', $(this).val().length);

    // Ajustar posição do cursor
    if (!isDeleting) {
      if (cursorPosition === 1 && inputValue.length === 1) this.setSelectionRange(3, 3);
      else if (cursorPosition === 4 && inputValue.length === 4) this.setSelectionRange(6, 6);
      else if (cursorPosition === 10 && inputValue.length === 10) this.setSelectionRange(11, 11);
      else this.setSelectionRange(cursorPosition, cursorPosition);
    }

    validateField($(this));
  });

  // Validação em tempo real
  $('#box-office-form input, #box-office-form textarea').on('blur', function() {
    validateField($(this));
  }).on('input', function() {
    $(this).removeClass('error-field');
    $(this).next('.error-message').remove();
  });

  // Validação dos radios
  $('input[name="produto_interesse"]').on('change', function() {
    $('.radio-options').removeClass('error-field');
  });

  // Submit do formulário
  $('#box-office-form').on('submit', function(e) {
    e.preventDefault();

    // Validar todos os campos
    let formIsValid = true;

    // Validar campos individuais
    $('#box-office-form input[required], #box-office-form textarea[required]').each(function() {
      if (!validateField($(this))) formIsValid = false;
    });

    // Validar produto
    if (!$('input[name="produto_interesse"]:checked').val()) {
      $('.radio-options').addClass('error-field');
      formIsValid = false;
    }

    if (!formIsValid) {
      showMessage('Por favor, preencha todos os campos corretamente', 'error');
      $('html, body').animate({
        scrollTop: $('.error-field:first').offset().top - 100
      }, 500);
      return;
    }

    // Enviar formulário
    submitForm();
  });

  // Função para validar campo individual
  function validateField(field) {
    const value = field.val().trim();
    let isValid = true;
    let errorMessage = '';

    const fieldName = field.attr('name');

    if (fieldName === 'nome' || fieldName === 'sobreNome') {
      isValid = /^[a-zA-ZÀ-ú\s]{2,}$/.test(value);
      errorMessage = 'Mínimo 2 caracteres (apenas letras)';
    } else if (fieldName === 'email') {
      isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
      errorMessage = 'Digite um e-mail válido';
    } else if (fieldName === 'telefone') {
      const digits = value.replace(/\D/g, '');
      isValid = digits.length === 11;
      errorMessage = 'Digite 11 dígitos (XX) XXXXX-XXXX';
    } else if (fieldName === 'mensagem') {
      isValid = value.length >= 10;
      errorMessage = 'Mínimo 10 caracteres';
    }

    // Atualizar UI
    if (!isValid) {
      field.addClass('error-field');
      field.next('.error-message').remove();
      field.after(`<span class="error-message">${errorMessage}</span>`);
      return false;
    }

    return true;
  }

  // Função para enviar o formulário
  function submitForm() {
    $('#box-office-submit').prop('disabled', true).text('Enviando...');
    $('#box-office-message').hide().removeClass('success error');

    $.ajax({
      url: '/wp-json/boxoffice/v1/submit',
      type: 'POST',
      data: $('#box-office-form').serialize(),
      dataType: 'json',
      success: function(response) {
        if (response.success) {
          showMessage(response.message, 'success');
          $('#box-office-form')[0].reset();
          $('html, body').animate({
            scrollTop: $('#box-office-message').offset().top - 100
          }, 500);
        } else {
          if (response.errors) {
            $.each(response.errors, function(field, message) {
              $(`[name="${field}"]`).addClass('error-field')
                .after(`<span class="error-message">${message}</span>`);
            });
          }
          showMessage(response.message || 'Erro ao enviar formulário', 'error');
        }
      },
      error: function() {
        showMessage('Erro na comunicação com o servidor', 'error');
      },
      complete: function() {
        $('#box-office-submit').prop('disabled', false).text('Enviar');
      }
    });
  }

  // Mostrar mensagens
  function showMessage(message, type) {
    const messageDiv = $('#box-office-message');
    messageDiv.html(message).addClass(type).show();
  }
});
</script>