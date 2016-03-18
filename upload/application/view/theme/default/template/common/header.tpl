<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $language_code; ?>">
<head>
  <meta charset="UTF-8" />
  <title><?php echo $title; ?></title>
  <base href="<?php echo $base; ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php if ($description) { ?>
  <meta name="description" content="<?php echo $description; ?>" />
  <?php } ?>
  <?php if ($keywords) { ?>
  <meta name="keywords" content="<?php echo $keywords; ?>" />
  <?php } ?>
  <?php if ($icon) { ?>
  <link href="<?php echo $icon; ?>" type="image/png" rel="icon" />
  <?php } ?>
  <?php foreach ($links as $link) { ?>
  <link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
  <?php } ?>
  <script type="text/javascript" src="vendor/jquery/jquery-2.1.4.min.js"></script>
  <script type="text/javascript" src="vendor/bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="application/view/javascript/system.js"></script>
  <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css" />
  <link rel="stylesheet" type="text/css" href="vendor/font-awesome/css/font-awesome.min.css" />
  <link rel="stylesheet" type="text/css" href="application/view/theme/default/stylesheet/stylesheet.css" />
  <?php foreach ($scripts as $script) { ?>
  <script type="text/javascript" src="<?php echo $script; ?>"></script>
  <?php } ?>
  <?php foreach ($styles as $style) { ?>
  <link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
  <?php } ?>
  <?php echo $google_analytics; ?>
</head>
<body>
<!-- Powered by Logic Invoice www.logicinvoice.com -->
<header class="navbar navbar-custom navbar-fixed-top" role="navigation">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <?php if ($logo) { ?>
      <a class="navbar-brand" href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" alt="<?php echo $name; ?>" title="<?php echo $name; ?>" /></a>
      <?php } else { ?>
      <a class="navbar-brand" href="<?php echo $home; ?>"><?php echo $name; ?></a>
      <?php } ?>
    </div>
    <nav class="collapse navbar-collapse">
      <ul class="nav navbar-nav navbar-right">
        <li><a href="<?php echo $home; ?>"><?php echo $text_home; ?></a></li>
        <?php foreach ($articles as $article) { ?>
        <?php if ($article['children']) { ?>
        <li class="dropdown">
          <a href="<?php echo $article['href']; ?>" class="dropdown-toggle" data-toggle="dropdown"><?php echo $article['title']; ?>
            <b class="caret"></b></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="<?php echo $article['href']; ?>"><?php echo $article['title']; ?></a></li>
            <?php foreach ($article['children'] as $child) { ?>
            <li><a href="<?php echo $child['href']; ?>"><?php echo $child['title']; ?></a></li>
            <?php } ?>
          </ul>
          <?php } else { ?>
        <li><a href="<?php echo $article['href']; ?>"><?php echo $article['title']; ?></a>
          <?php } ?>
        </li>
        <?php } ?>
        <li><a href="<?php echo $blog; ?>"><?php echo $text_blog; ?></a></li>
        <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="<?php echo $account; ?>"><?php echo $text_client; ?>
            <b class="caret"></b></a>
          <ul class="dropdown-menu" role="menu">
            <?php if ($logged) { ?>
            <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
            <li><a href="<?php echo $invoice; ?>"><?php echo $text_invoice; ?></a></li>
            <li><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
              <?php } else { ?>
            <li><a href="<?php echo $login; ?>"><?php echo $text_login; ?></a></li>
              <?php if ($register) { ?>
            <li><a href="<?php echo $register; ?>"><?php echo $text_register; ?></a></li>
              <?php } ?>
              <?php } ?>
          </ul>
        </li>
      </ul>
    </nav>
  </div>
</header>