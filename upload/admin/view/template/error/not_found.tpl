<?php echo $header; ?>
<ol class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ol>
<div class="panel panel-default">
  <div class="panel-heading">
    <h1 class="panel-title"><i class="fa fa-exclamation-circle fa-lg"></i> <?php echo $heading_title; ?></h1>
  </div>
  <div class="panel-body">
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $text_not_found; ?></div>
  </div>
</div>
<?php echo $footer; ?>