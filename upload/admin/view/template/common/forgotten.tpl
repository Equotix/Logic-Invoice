<?php echo $header; ?>
<div class="row">
  <div class="col-lg-12">
    <form class="center-form" action="<?php echo $action; ?>" method="post">
      <h2><?php echo $heading_title; ?></h2>
      <?php if ($error_warning) { ?>
      <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
      <?php } ?>
      <div class="form-group">
        <label class="sr-only" for="input-email"><?php echo $entry_email; ?></label>
        <div class="input-group">
          <span class="input-group-addon"><i class="fa fa-at"></i></span>
          <input type="text" name="email" value="<?php echo $email; ?>" id="input-email" class="form-control" placeholder="<?php echo $entry_email; ?>" required autofocus />
        </div>
      </div>
      <button class="btn btn-lg btn-primary btn-block" type="submit"><?php echo $button_reset; ?></button>
    </form>
  </div>
</div>
<?php echo $footer; ?>