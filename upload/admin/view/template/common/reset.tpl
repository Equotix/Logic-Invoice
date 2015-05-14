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
        <label class="sr-only" for="input-password"><?php echo $entry_password; ?></label>
        <div class="input-group">
          <span class="input-group-addon"><i class="fa fa-key"></i></span>
          <input type="password" name="password" value="<?php echo $password; ?>" id="input-password" class="form-control" placeholder="<?php echo $entry_password; ?>" required autofocus />
        </div>
      </div>
      <div class="form-group">
        <label class="sr-only" for="input-confirm"><?php echo $entry_confirm; ?></label>
        <div class="input-group">
          <span class="input-group-addon"><i class="fa fa-key"></i></span>
          <input type="password" name="confirm" value="<?php echo $confirm; ?>" id="input-confirm" class="form-control" placeholder="<?php echo $entry_confirm; ?>" required />
        </div>
      </div>
      <button class="btn btn-lg btn-primary btn-block" type="submit"><?php echo $button_save; ?></button>
    </form>
  </div>
</div>
<?php echo $footer; ?>