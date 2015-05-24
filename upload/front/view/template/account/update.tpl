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
          <label class="sr-only" for="input-firstname"><?php echo $entry_firstname; ?></label>
          <input type="text" name="firstname" value="<?php echo $firstname; ?>" id="input-firstname" class="form-control" placeholder="<?php echo $entry_firstname; ?>" required autofocus />
          <?php if ($error_firstname) { ?>
          <span class="text-danger"><?php echo $error_firstname; ?></span>
          <?php } ?>
        </div>
        <div class="form-group">
          <label class="sr-only" for="input-lastname"><?php echo $entry_lastname; ?></label>
          <input type="text" name="lastname" value="<?php echo $lastname; ?>" id="input-lastname" class="form-control" placeholder="<?php echo $entry_lastname; ?>" required />
          <?php if ($error_lastname) { ?>
          <span class="text-danger"><?php echo $error_lastname; ?></span>
          <?php } ?>
        </div>
        <div class="form-group">
          <label class="sr-only" for="input-company"><?php echo $entry_company; ?></label>
          <input type="text" name="company" value="<?php echo $company; ?>" id="input-company" class="form-control" placeholder="<?php echo $entry_company; ?>" />
        </div>
        <div class="form-group">
          <label class="sr-only" for="input-website"><?php echo $entry_website; ?></label>
          <input type="text" name="website" value="<?php echo $website; ?>" id="input-website" class="form-control" placeholder="<?php echo $entry_website; ?>" />
        </div>
        <div class="form-group">
          <label class="sr-only" for="input-email"><?php echo $entry_email; ?></label>
          <input type="text" name="email" value="<?php echo $email; ?>" id="input-email" class="form-control" placeholder="<?php echo $entry_email; ?>" required />
          <?php if ($error_email) { ?>
          <span class="text-danger"><?php echo $error_email; ?></span>
          <?php } ?>
        </div>
        <button class="btn btn-lg btn-primary btn-block" type="submit"><?php echo $button_update; ?></button>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>