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
      <button type="submit" form="form-authorizenet-aim" title="<?php echo $button_save; ?>" data-toggle="tooltip" class="btn btn-success"><i class="fa fa-save"></i></button>
      <a href="<?php echo $cancel; ?>" title="<?php echo $button_cancel; ?>" data-toggle="tooltip" class="btn btn-danger"><i class="fa fa-times"></i></a>
    </div>
    <h1 class="panel-title"><i class="fa fa-pencil-square fa-lg"></i> <?php echo $heading_title; ?></h1>
  </div>
  <div class="panel-body">
    <form method="post" action="<?php echo $action; ?>" id="form-authorizenet-aim" class="form-horizontal">
      <div class="form-group">
        <label class="required col-sm-2 control-label" for="input-login"><?php echo $entry_login; ?></label>
        <div class="col-sm-10">
          <input type="text" name="authorizenet_aim_login" value="<?php echo $authorizenet_aim_login; ?>" id="input-login" class="form-control" placeholder="<?php echo $entry_login; ?>" required autofocus />
          <?php if ($error_login) { ?>
          <div class="text-danger"><?php echo $error_login; ?></div>
          <?php } ?>
        </div>
      </div>
	  <div class="form-group">
        <label class="required col-sm-2 control-label" for="input-key"><?php echo $entry_key; ?></label>
        <div class="col-sm-10">
          <input type="text" name="authorizenet_aim_key" value="<?php echo $authorizenet_aim_key; ?>" id="input-key" class="form-control" placeholder="<?php echo $entry_key; ?>" required />
          <?php if ($error_key) { ?>
          <div class="text-danger"><?php echo $error_key; ?></div>
          <?php } ?>
        </div>
      </div>
	  <div class="form-group">
        <label class="col-sm-2 control-label" for="input-hash"><?php echo $entry_hash; ?></label>
        <div class="col-sm-10">
          <input type="text" name="authorizenet_aim_hash" value="<?php echo $authorizenet_aim_hash; ?>" id="input-hash" class="form-control" placeholder="<?php echo $entry_hash; ?>" />
        </div>
      </div>
	  <div class="form-group">
        <label class="col-sm-2 control-label" for="input-server"><?php echo $entry_server; ?></label>
        <div class="col-sm-10"><select name="authorizenet_aim_server" id="input-server" class="form-control">
            <option value="live"<?php echo $authorizenet_aim_server == 'live' ? ' selected="selected"' : ''; ?>><?php echo $text_live; ?></option>
			<option value="test"<?php echo $authorizenet_aim_server == 'test' ? ' selected="selected"' : ''; ?>><?php echo $text_test; ?></option>
          </select>
        </div>
      </div>
	  <div class="form-group">
        <label class="col-sm-2 control-label" for="input-mode"><?php echo $entry_mode; ?></label>
        <div class="col-sm-10"><select name="authorizenet_aim_mode" id="input-mode" class="form-control">
            <option value="live"<?php echo $authorizenet_aim_mode == 'live' ? ' selected="selected"' : ''; ?>><?php echo $text_live; ?></option>
			<option value="test"<?php echo $authorizenet_aim_mode == 'test' ? ' selected="selected"' : ''; ?>><?php echo $text_test; ?></option>
          </select>
        </div>
      </div>
	  <div class="form-group">
        <label class="col-sm-2 control-label" for="input-method"><?php echo $entry_method; ?></label>
        <div class="col-sm-10"><select name="authorizenet_aim_method" id="input-method" class="form-control">
            <option value="authorization"<?php echo $authorizenet_aim_method == 'authorization' ? ' selected="selected"' : ''; ?>><?php echo $text_authorization; ?></option>
			<option value="capture"<?php echo $authorizenet_aim_method == 'capture' ? ' selected="selected"' : ''; ?>><?php echo $text_capture; ?></option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-completed"><?php echo $entry_completed; ?></label>
        <div class="col-sm-10"><select name="authorizenet_aim_completed_status_id" id="input-completed" class="form-control">
            <?php foreach ($statuses as $status) { ?>
            <option value="<?php echo $status['status_id']; ?>"<?php echo $status['status_id'] == $authorizenet_aim_completed_status_id ? ' selected="selected"' : ''; ?>><?php echo $status['name']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
	  <div class="form-group">
        <label class="col-sm-2 control-label" for="input-denied"><?php echo $entry_denied; ?></label>
        <div class="col-sm-10"><select name="authorizenet_aim_denied_status_id" id="input-denied" class="form-control">
            <?php foreach ($statuses as $status) { ?>
            <option value="<?php echo $status['status_id']; ?>"<?php echo $status['status_id'] == $authorizenet_aim_denied_status_id ? ' selected="selected"' : ''; ?>><?php echo $status['name']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
        <div class="col-sm-10"><select name="authorizenet_aim_status" id="input-status" class="form-control">
            <option value="1"<?php echo $authorizenet_aim_status ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
            <option value="0"<?php echo $authorizenet_aim_status ? '' : ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
        <div class="col-sm-10">
          <input type="text" name="authorizenet_aim_sort_order" value="<?php echo $authorizenet_aim_sort_order; ?>" id="input-sort-order" class="form-control" placeholder="<?php echo $entry_sort_order; ?>" />
        </div>
      </div>
    </form>
  </div>
</div>
<?php echo $footer; ?>