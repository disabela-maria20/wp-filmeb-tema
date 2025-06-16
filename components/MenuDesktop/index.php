<header id="desktop">
  <div class="container">
    <div class="grid_institucinal">
      <div>
        <a href="<?php echo get_site_url(); ?>">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.png" alt="logo cine B" />
        </a>
      </div>
      <div>
        <?php get_template_part('components/Search/index'); ?>
      </div>
      <div>
        <nav>
          <?php if (is_user_logged_in()) { ?>
          <ul class="user">
            <li>
              <a href="<?php echo get_site_url(); ?>/minha-conta/assinaturas/">
                <span>Minha Conta</span>
              </a>
            </li>
            <li><a href="<?php echo get_site_url(); ?>/anuncie/">Anuncie</a></li>
            <li>
              <a href="<?php echo wp_logout_url(home_url()); ?>"><span>Sair</span></a>
            </li>
          </ul>
          <?php } else { ?>
          <ul class="user">
            <li>
              <!-- Botão com span que será alterado -->
              <a href="#" id="assinar-filmeb" data-product-id="106">
                <span id="texto-assinar">Assine</span>
              </a>
            </li>
            <li>
              <a href="<?php echo get_site_url(); ?>/anuncie/">
                <span>Anuncie</span>
              </a>
            </li>
            <li>
              <a href="<?php echo get_site_url(); ?>/minha-conta/assinaturas/">
                <span>Minha Conta</span>
              </a>
            </li>
          </ul>
          <?php } ?>
        </nav>
      </div>
    </div>

    <div class="grid_menu">
      <nav>
        <?php
        wp_nav_menu(array(
          'menu' => 'principal',
          'theme_location' => 'menu-principal',
          'container' => false
        ));
        ?>
      </nav>
    </div>
  </div>
</header>

<!-- Script para trocar o texto ao clicar -->
<script>
document.getElementById('assinar-filmeb').addEventListener('click', function(e) {
  e.preventDefault();

  const botao = this;
  const spanTexto = document.getElementById('texto-assinar');
  const productId = botao.getAttribute('data-product-id');

  // Altera o texto do botão
  spanTexto.textContent = 'Adicionando ao carrinho...';
  botao.classList.add('loading');

  // Faz a requisição AJAX para adicionar ao carrinho
  fetch(`<?php echo admin_url('admin-ajax.php'); ?>?action=add_to_cart_ajax&product_id=${productId}`)
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        window.location.href = "<?php echo get_site_url(); ?>/finalizar-compra/";
      } else {
        alert('Erro ao adicionar ao carrinho.');
        spanTexto.textContent = 'Assine';
        botao.classList.remove('loading');
      }
    })
    .catch(() => {
      alert('Erro de conexão.');
      spanTexto.textContent = 'Assine';
      botao.classList.remove('loading');
    });
});
</script>

<!-- Estilo opcional para o botão enquanto carrega -->
<style>
#assinar-filmeb.loading {
  opacity: 0.6;
  pointer-events: none;
}
</style>