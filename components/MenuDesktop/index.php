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
              <a href="<?php echo get_site_url(); ?>/minha-conta/">
                <span>Minha Conta</span>
              </a>
            </li>
            <li><a href="<?php echo get_site_url(); ?>/anuncie/">Anuncie</a></li>
            <li>
              <a href="<?php echo wp_logout_url( home_url() ); ?>"><span>Sair</span></a>
            </li>
          </ul>
          <?php } else { ?>
          <ul class="user">
            <li>
              <a href="<?php echo get_site_url(); ?>/assine/">
                <span>Assine</span>
              </a>
            </li>
            <li>
              <a href="<?php echo get_site_url(); ?>/anuncie/">
                <span>Anuncie</span>
              </a>
            </li>
            <li>
              <a href="<?php echo get_site_url(); ?>/minha-conta/">
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
        )); ?>
      </nav>
    </div>
  </div>
  </div>
</header>