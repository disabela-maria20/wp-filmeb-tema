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

          <?php if (!SwpmMemberUtils::is_member_logged_in()) { ?>
          <ul class="user">
            <li>
              <a href="<?php echo get_site_url(); ?>/minha-conta/">
                <span>Seu cadastro</span>
                <!-- <i class=" bi bi-person-circle"></i> -->
              </a>
            </li>
            <li><a href="<?php echo get_site_url(); ?>/anuncie/">Anuncie</a></li>
            <li>
              <a href="<?php echo esc_url(wc_logout_url()); ?>">
                <span>Sair</span>
              </a>
            </li>
          </ul>
          <?php } else { ?>
          <ul class="user">
            <li>
              <a href="<?php echo get_site_url(); ?>/minha-conta/">
                <span>Login</span>
              </a>
            </li>
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