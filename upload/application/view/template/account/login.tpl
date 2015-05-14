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
        <?php if ($success) { ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
        <?php } ?>
        <div class="form-group">
          <label class="sr-only" for="input-email"><?php echo $entry_email; ?></label>
          <input type="text" name="email" value="<?php echo $email; ?>" id="input-email" class="form-control" placeholder="<?php echo $entry_email; ?>" required autofocus />
        </div>
        <div class="form-group">
          <label class="sr-only" for="input-password"><?php echo $entry_password; ?></label>
          <input type="password" name="password" value="<?php echo $password; ?>" id="input-password" class="form-control" placeholder="<?php echo $entry_password; ?>" required />
        </div>
        <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
        <label class="forgotten">
          <?php echo $text_forgotten; ?>
        </label>
        <button class="btn btn-lg btn-primary btn-block" type="submit"><?php echo $button_login; ?></button>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>