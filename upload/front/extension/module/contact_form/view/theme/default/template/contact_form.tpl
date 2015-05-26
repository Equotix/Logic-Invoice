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
		<div>
		  <?php echo $description; ?>
		</div>
        <div class="form-group">
          <label class="required" for="input-name"><?php echo $entry_name; ?></label>
          <input type="text" name="name" value="<?php echo $name; ?>" id="input-name" class="form-control" placeholder="<?php echo $entry_name; ?>" required autofocus />
          <?php if ($error_name) { ?>
          <div class="text-danger"><?php echo $error_name; ?></div>
          <?php } ?>
        </div>
		<div class="form-group">
          <label class="required" for="input-email"><?php echo $entry_email; ?></label>
          <input type="text" name="email" value="<?php echo $email; ?>" id="input-email" class="form-control" placeholder="<?php echo $entry_email; ?>" required />
          <?php if ($error_email) { ?>
          <div class="text-danger"><?php echo $error_email; ?></div>
          <?php } ?>
        </div>
		<div class="form-group">
          <label class="required" for="input-message"><?php echo $entry_message; ?></label>
          <textarea name="message" id="input-message" class="form-control" placeholder="<?php echo $entry_message; ?>" rows="6"><?php echo $message; ?></textarea>
          <?php if ($error_message) { ?>
          <div class="text-danger"><?php echo $error_message; ?></div>
          <?php } ?>
        </div>
		<div class="form-group">
          <label class="required" for="input-email"><?php echo $entry_captcha; ?></label>
          <input type="text" name="captcha" value="<?php echo $captcha; ?>" id="input-captcha" class="form-control" placeholder="<?php echo $entry_captcha; ?>" autocomplete="off" required />
          <?php if ($error_captcha) { ?>
          <div class="text-danger"><?php echo $error_captcha; ?></div>
          <?php } ?><br />
		  <img src="<?php echo $captcha_image; ?>" />
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit"><?php echo $button_send_email; ?></button>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>