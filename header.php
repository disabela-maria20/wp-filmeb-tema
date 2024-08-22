<!DOCTYPE html>
<html lang="pt-br">
  <head><?php wp_head(); ?>
    <meta charset="utf-8" />
    <title><?php bloginfo('name'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  </head>

  <body>
    <?php get_template_part('components/MenuMobile/index'); ?>
    <?php get_template_part('components/MenuDesktop/index'); ?>

