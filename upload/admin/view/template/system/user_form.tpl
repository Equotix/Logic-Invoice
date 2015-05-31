<?php echo $header; ?>
<ol class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ol>
<?php if ($error_warning) { ?>
<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
  <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<?php } ?>
<div class="panel panel-default">
  <div class="panel-heading">
    <div class="pull-right">
      <button type="submit" form="form-user" title="<?php echo $button_save; ?>" data-toggle="tooltip" class="btn btn-success"><i class="fa fa-save"></i></button>
      <a href="<?php echo $cancel; ?>" title="<?php echo $button_cancel; ?>" data-toggle="tooltip" class="btn btn-danger"><i class="fa fa-times"></i></a>
    </div>
    <h1 class="panel-title"><i class="fa fa-pencil-square fa-lg"></i> <?php echo $heading_title; ?></h1>
  </div>
  <div class="panel-body">
    <form method="post" action="<?php echo $action; ?>" id="form-user" class="form-horizontal">
      <div class="form-group">
        <label class="required col-sm-2 control-label" for="input-username"><?php echo $entry_username; ?></label>
        <div class="col-sm-10">
          <input type="text" name="username" value="<?php echo $username; ?>" id="input-username" class="form-control" placeholder="<?php echo $entry_username; ?>" required />
          <?php if ($error_username) { ?>
          <div class="text-danger"><?php echo $error_username; ?></div>
          <?php } ?>
        </div>
      </div>
	  <div class="form-group">
        <label class="required col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
        <div class="col-sm-10">
          <input type="text" name="name" value="<?php echo $name; ?>" id="input-name" class="form-control" placeholder="<?php echo $entry_name; ?>" required />
          <?php if ($error_name) { ?>
          <div class="text-danger"><?php echo $error_name; ?></div>
          <?php } ?>
        </div>
      </div>
      <div class="form-group">
        <label class="required col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
        <div class="col-sm-10">
          <input type="text" name="email" value="<?php echo $email; ?>" id="input-email" class="form-control" placeholder="<?php echo $entry_email; ?>" required />
          <?php if ($error_email) { ?>
          <div class="text-danger"><?php echo $error_email; ?></div>
          <?php } ?>
        </div>
      </div>
      <div class="form-group">
        <label class="required col-sm-2 control-label" for="input-user-group"><?php echo $entry_user_group; ?></label>
        <div class="col-sm-10">
          <select name="user_group_id" id="input-user-group" class="form-control">
            <?php foreach ($user_groups as $user_group) { ?>
            <option value="<?php echo $user_group['user_group_id']; ?>"<?php echo $user_group_id == $user_group['user_group_id'] ? ' selected="selected"' : ''; ?>><?php echo $user_group['name']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-api-key"><?php echo $entry_api_key; ?></label>
        <div class="col-sm-10">
          <div class="input-group">
            <input type="text" name="key" value="<?php echo $key; ?>" id="input-api-key" class="form-control" placeholder="<?php echo $entry_api_key; ?>" />
            <span class="input-group-btn">
              <button type="button" title="<?php echo $button_generate; ?>" data-toggle="tooltip" class="btn btn-primary" id="button-generate-key"><i class="fa fa-refresh"></i></button>
            </span>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-api-secret"><?php echo $entry_api_secret; ?></label>
        <div class="col-sm-10">
          <div class="input-group">
            <input type="text" name="secret" value="<?php echo $secret; ?>" id="input-api-secret" class="form-control" placeholder="<?php echo $entry_api_secret; ?>" />
            <span class="input-group-btn">
              <button type="button" title="<?php echo $button_generate; ?>" data-toggle="tooltip" class="btn btn-primary" id="button-generate-secret"><i class="fa fa-refresh"></i></button>
            </span>
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-password"><?php echo $entry_password; ?></label>
        <div class="col-sm-10">
          <input type="password" name="password" value="<?php echo $password; ?>" id="input-password" class="form-control" placeholder="<?php echo $entry_password; ?>" />
          <?php if ($error_password) { ?>
          <div class="text-danger"><?php echo $error_password; ?></div>
          <?php } ?>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-confirm"><?php echo $entry_confirm; ?></label>
        <div class="col-sm-10">
          <input type="password" name="confirm" value="<?php echo $confirm; ?>" id="input-confirm" class="form-control" placeholder="<?php echo $entry_confirm; ?>" />
          <?php if ($error_confirm) { ?>
          <div class="text-danger"><?php echo $error_confirm; ?></div>
          <?php } ?>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
        <div class="col-sm-10">
          <select name="status" id="input-status" class="form-control">
            <option value="1"<?php echo $status ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
            <option value="0"<?php echo $status ? '' : ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
          </select>
        </div>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript"><!--
$('#button-generate-key').on('click', function () {
	rand = '';

	string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

	for (i = 0; i < 64; i++) {
		rand += string[Math.floor(Math.random() * (string.length - 1))];
	}

	$('input[name=\'key\']').val(rand);
});

$('#button-generate-secret').on('click', function () {
	rand = '';

	string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

	for (i = 0; i < 64; i++) {
		rand += string[Math.floor(Math.random() * (string.length - 1))];
	}

	$('input[name=\'secret\']').val(rand);
});
//--></script>
<?php echo $footer; ?>