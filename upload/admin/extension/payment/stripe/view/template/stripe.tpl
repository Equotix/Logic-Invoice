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
      <button type="submit" form="form-stripe" title="<?php echo $button_save; ?>" data-toggle="tooltip" class="btn btn-success"><i class="fa fa-save"></i></button>
      <a href="<?php echo $cancel; ?>" title="<?php echo $button_cancel; ?>" data-toggle="tooltip" class="btn btn-danger"><i class="fa fa-times"></i></a>
    </div>
    <h1 class="panel-title"><i class="fa fa-pencil-square fa-lg"></i> <?php echo $heading_title; ?></h1>
  </div>
  <div class="panel-body">
    <form method="post" action="<?php echo $action; ?>" id="form-stripe" class="form-horizontal">
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-test"><?php echo $entry_test; ?></label>
        <div class="col-sm-10"><select name="stripe_testmode" id="input-test" class="form-control">
            <option value="1"<?php echo $stripe_testmode ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
            <option value="0"<?php echo $stripe_testmode ? '' : ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
        <div class="col-sm-10"><select name="stripe_status" id="input-status" class="form-control">
            <option value="1"<?php echo $stripe_status ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
            <option value="0"<?php echo $stripe_status ? '' : ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="required col-sm-2 control-label" for="input-test-public-key"><?php echo $entry_test_public_key; ?></label>
        <div class="col-sm-10">
          <input type="text" name="stripe_test_public_key" value="<?php echo $stripe_test_public_key; ?>" id="input-test-public-key" class="form-control" placeholder="<?php echo $entry_test_public_key; ?>" required />
        </div>
      </div>
      <div class="form-group">
        <label class="required col-sm-2 control-label" for="input-test-secret-key"><?php echo $entry_test_secret_key; ?></label>
        <div class="col-sm-10">
          <input type="text" name="stripe_test_secret_key" value="<?php echo $stripe_test_secret_key; ?>" id="input-test-secret-key" class="form-control" placeholder="<?php echo $entry_test_secret_key; ?>" required />
        </div>
      </div>
      <div class="form-group">
        <label class="required col-sm-2 control-label" for="input-public-key"><?php echo $entry_public_key; ?></label>
        <div class="col-sm-10">
          <input type="text" name="stripe_public_key" value="<?php echo $stripe_public_key; ?>" id="input-public-key" class="form-control" placeholder="<?php echo $entry_public_key; ?>" required />
        </div>
      </div>
      <div class="form-group">
        <label class="required col-sm-2 control-label" for="input-secret-key"><?php echo $entry_secret_key; ?></label>
        <div class="col-sm-10">
          <input type="text" name="stripe_secret_key" value="<?php echo $stripe_secret_key; ?>" id="input-secret-key" class="form-control" placeholder="<?php echo $entry_secret_key; ?>" required />
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-success"><?php echo $entry_success; ?></label>
        <div class="col-sm-10"><select name="stripe_success" id="input-success" class="form-control">
            <?php foreach ($statuses as $status) { ?>
            <option value="<?php echo $status['status_id']; ?>"<?php echo $status['status_id'] == $stripe_success ? ' selected="selected"' : ''; ?>><?php echo $status['name']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-carderror"><?php echo $entry_carderror; ?></label>
        <div class="col-sm-10"><select name="stripe_carderror" id="input-carderror" class="form-control">
            <?php foreach ($statuses as $status) { ?>
            <option value="<?php echo $status['status_id']; ?>"<?php echo $status['status_id'] == $stripe_carderror ? ' selected="selected"' : ''; ?>><?php echo $status['name']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-invalidrequest"><?php echo $entry_invalidrequest; ?></label>
        <div class="col-sm-10"><select name="stripe_invalidrequest" id="input-invalidrequest" class="form-control">
            <?php foreach ($statuses as $status) { ?>
            <option value="<?php echo $status['status_id']; ?>"<?php echo $status['status_id'] == $stripe_invalidrequest ? ' selected="selected"' : ''; ?>><?php echo $status['name']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-authentication"><?php echo $entry_authentication; ?></label>
        <div class="col-sm-10"><select name="stripe_authentication" id="input-authentication" class="form-control">
            <?php foreach ($statuses as $status) { ?>
            <option value="<?php echo $status['status_id']; ?>"<?php echo $status['status_id'] == $stripe_authentication ? ' selected="selected"' : ''; ?>><?php echo $status['name']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-apiconnection"><?php echo $entry_apiconnection; ?></label>
        <div class="col-sm-10"><select name="stripe_apiconnection" id="input-apiconnection" class="form-control">
            <?php foreach ($statuses as $status) { ?>
            <option value="<?php echo $status['status_id']; ?>"<?php echo $status['status_id'] == $stripe_apiconnection ? ' selected="selected"' : ''; ?>><?php echo $status['name']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-genericerror"><?php echo $entry_genericerror; ?></label>
        <div class="col-sm-10"><select name="stripe_genericerror" id="input-genericerror" class="form-control">
            <?php foreach ($statuses as $status) { ?>
            <option value="<?php echo $status['status_id']; ?>"<?php echo $status['status_id'] == $stripe_genericerror ? ' selected="selected"' : ''; ?>><?php echo $status['name']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-other"><?php echo $entry_other; ?></label>
        <div class="col-sm-10"><select name="stripe_other" id="input-other" class="form-control">
            <?php foreach ($statuses as $status) { ?>
            <option value="<?php echo $status['status_id']; ?>"<?php echo $status['status_id'] == $stripe_other ? ' selected="selected"' : ''; ?>><?php echo $status['name']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
        <div class="col-sm-10">
          <input type="text" name="stripe_sort_order" value="<?php echo $stripe_sort_order; ?>" id="input-sort-order" class="form-control" placeholder="<?php echo $entry_sort_order; ?>" />
        </div>
      </div>
    </form>
  </div>
</div>
<?php echo $footer; ?>