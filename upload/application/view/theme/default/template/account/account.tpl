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
      <?php if ($success) { ?>
      <div class="alert alert-success"><?php echo $success; ?></div>
      <?php } ?>
      <div class="row">
        <div class="col-xs-6 col-sm-3 text-center bottom-20">
          <a href="<?php echo $update; ?>"><i class="fa fa-edit fa-4x"></i><br /><?php echo $text_update; ?></a>
        </div>
        <div class="col-xs-6 col-sm-3 text-center bottom-20">
          <a href="<?php echo $password; ?>"><i class="fa fa-lock fa-4x"></i><br /><?php echo $text_password; ?></a>
        </div>
        <div class="col-xs-6 col-sm-3 text-center bottom-20">
          <a href="<?php echo $invoice; ?>"><i class="fa fa-envelope fa-4x"></i><br /><?php echo $text_invoice; ?></a>
        </div>
        <div class="col-xs-6 col-sm-3 text-center bottom-20">
          <a href="<?php echo $recurring; ?>"><i class="fa fa-refresh fa-4x"></i><br /><?php echo $text_recurring; ?></a>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-6 col-sm-3 text-center bottom-20">
          <a href="<?php echo $credit; ?>"><i class="fa fa-usd fa-4x"></i><br /><?php echo $text_credit; ?></a>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>