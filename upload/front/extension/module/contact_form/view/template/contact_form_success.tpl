<?php echo $header; ?>
<div class="header">
  <div class="container">
    <h1><?php echo $heading_title; ?></h1>
    <p><?php echo $text_info; ?></p>
  </div>
</div>
<div id="content" class="container">
  <ol class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ol>
  <div class="row">
    <div class="col-lg-12">
      <h2><?php echo $text_success; ?>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12 text-right">
	  <a href="<?php echo $home; ?>" class="btn btn-primary"><?php echo $button_home; ?></a>
	</div>
  </div>
</div>
<?php echo $footer; ?>