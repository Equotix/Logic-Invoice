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
          <label for="input-firstname"><?php echo $entry_firstname; ?></label>
          <input type="text" name="firstname" value="<?php echo $firstname; ?>" id="input-firstname" class="form-control" placeholder="<?php echo $entry_firstname; ?>" required autofocus />
          <?php if ($error_firstname) { ?>
          <div class="text-danger"><?php echo $error_firstname; ?></div>
          <?php } ?>
        </div>
        <div class="form-group">
          <label for="input-lastname"><?php echo $entry_lastname; ?></label>
          <input type="text" name="lastname" value="<?php echo $lastname; ?>" id="input-lastname" class="form-control" placeholder="<?php echo $entry_lastname; ?>" required />
          <?php if ($error_lastname) { ?>
          <div class="text-danger"><?php echo $error_lastname; ?></div>
          <?php } ?>
        </div>
        <div class="form-group">
          <label for="input-company"><?php echo $entry_company; ?></label>
          <input type="text" name="company" value="<?php echo $company; ?>" id="input-company" class="form-control" placeholder="<?php echo $entry_company; ?>" />
        </div>
        <div class="form-group">
          <label for="input-website"><?php echo $entry_website; ?></label>
          <input type="text" name="website" value="<?php echo $website; ?>" id="input-website" class="form-control" placeholder="<?php echo $entry_website; ?>" />
        </div>
        <div class="form-group">
          <label for="input-email"><?php echo $entry_email; ?></label>
          <input type="text" name="email" value="<?php echo $email; ?>" id="input-email" class="form-control" placeholder="<?php echo $entry_email; ?>" required />
          <?php if ($error_email) { ?>
          <div class="text-danger"><?php echo $error_email; ?></div>
          <?php } ?>
        </div>
		<div class="form-group">
          <label for="input-captcha"><?php echo $entry_captcha; ?></label>
          <input type="text" name="captcha" value="<?php echo $captcha; ?>" id="input-captcha" class="form-control" placeholder="<?php echo $entry_captcha; ?>" autocomplete="off" required />
          <?php if ($error_captcha) { ?>
          <div class="text-danger"><?php echo $error_captcha; ?></div>
          <?php } ?><br />
		  <img src="<?php echo $captcha_image; ?>" />
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit"><?php echo $button_register; ?></button>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>