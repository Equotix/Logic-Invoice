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
      <form class="center-form" action="<?php echo $action; ?>" method="post">
        <h2><?php echo $heading_title; ?></h2>
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><?php echo $error_warning; ?></div>
        <?php } ?>
        <div class="form-group">
          <label class="sr-only" for="input-verify"><?php echo $entry_verify; ?></label>
          <input type="password" name="verify" value="<?php echo $verify; ?>" id="input-verify" class="form-control" placeholder="<?php echo $entry_verify; ?>" required autofocus />
        </div>
        <div class="form-group">
          <label class="sr-only" for="input-password"><?php echo $entry_password; ?></label>
          <input type="password" name="password" value="<?php echo $password; ?>" id="input-password" class="form-control" placeholder="<?php echo $entry_password; ?>" required />
          <?php if ($error_password) { ?>
          <span class="text-danger"><?php echo $error_password; ?></span>
          <?php } ?>
        </div>
        <div class="form-group">
          <label class="sr-only" for="input-confirm"><?php echo $entry_confirm; ?></label>
          <input type="password" name="confirm" value="<?php echo $confirm; ?>" id="input-confirm" class="form-control" placeholder="<?php echo $entry_confirm; ?>" required />
          <?php if ($error_confirm) { ?>
          <span class="text-danger"><?php echo $error_confirm; ?></span>
          <?php } ?>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit"><?php echo $button_update; ?></button>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>