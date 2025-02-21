<header class="header" id="mobile">
  <div class="area_menu">
    <div class="area_logo">
      <div class="menu_burguer">
        <span></span>
        <span></span>
        <span></span>
      </div>
      <a href="<?php echo get_template_directory_uri(); ?>">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.png" alt="logo cine B" />
      </a>
      <?php if (!is_user_logged_in()) { ?>
      <a href="<?php echo get_site_url(); ?>/minha-conta/">
        <i class=" bi bi-person-circle"></i>
      </a>
      <?php } else { ?>
      <a href="<?php echo get_site_url(); ?>/minha-conta/">
        <i class="bi bi-person-circle"></i>
      </a>
      <?php } ?>

    </div>
    <div>
      <nav class="menu">
        <?php 
          $args = array(
						'menu' =>'principal', 
            'theme_location' => 'menu-principal', 
            'container' =>false); 
            wp_nav_menu($args); ?>
        <?php get_template_part('components/Search/index'); ?>
      </nav>
    </div>
  </div>
</header>