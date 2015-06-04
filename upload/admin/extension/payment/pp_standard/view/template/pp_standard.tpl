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
      <button type="submit" form="form-pp-standard" title="<?php echo $button_save; ?>" data-toggle="tooltip" class="btn btn-success"><i class="fa fa-save"></i></button>
      <a href="<?php echo $cancel; ?>" title="<?php echo $button_cancel; ?>" data-toggle="tooltip" class="btn btn-danger"><i class="fa fa-times"></i></a>
    </div>
    <h1 class="panel-title"><i class="fa fa-pencil-square fa-lg"></i> <?php echo $heading_title; ?></h1>
  </div>
  <div class="panel-body">
    <form method="post" action="<?php echo $action; ?>" id="form-pp-standard" class="form-horizontal">
      <div class="form-group">
        <label class="required col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
        <div class="col-sm-10">
          <input type="text" name="pp_standard_email" value="<?php echo $pp_standard_email; ?>" id="input-email" class="form-control" placeholder="<?php echo $entry_email; ?>" required autofocus />
          <?php if ($error_email) { ?>
          <div class="text-danger"><?php echo $error_email; ?></div>
          <?php } ?>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-sandbox"><?php echo $entry_sandbox; ?></label>
        <div class="col-sm-10"><select name="pp_standard_sandbox" id="input-sandbox" class="form-control">
            <option value="1"<?php echo $pp_standard_sandbox ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
            <option value="0"<?php echo $pp_standard_sandbox ? '' : ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-transaction"><?php echo $entry_transaction; ?></label>
        <div class="col-sm-10"><select name="pp_standard_transaction" id="input-transaction" class="form-control">
            <option value="authorization"<?php echo $pp_standard_transaction == 'authorization' ? ' selected="selected"' : ''; ?>><?php echo $text_authorization; ?></option>
            <option value="sale"<?php echo $pp_standard_transaction == 'sale' ? ' selected="selected"' : ''; ?>><?php echo $text_sale; ?></option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-debug"><?php echo $entry_debug; ?></label>
        <div class="col-sm-10"><select name="pp_standard_debug" id="input-debug" class="form-control">
            <option value="1"<?php echo $pp_standard_debug ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
            <option value="0"<?php echo $pp_standard_debug ? '' : ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-cancelled"><?php echo $entry_cancelled_reversal; ?></label>
        <div class="col-sm-10"><select name="pp_standard_cancelled" id="input-cancelled" class="form-control">
            <?php foreach ($statuses as $status) { ?>
            <option value="<?php echo $status['status_id']; ?>"<?php echo $status['status_id'] == $pp_standard_cancelled_reversal ? ' selected="selected"' : ''; ?>><?php echo $status['name']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-completed"><?php echo $entry_completed; ?></label>
        <div class="col-sm-10"><select name="pp_standard_completed" id="input-completed" class="form-control">
            <?php foreach ($statuses as $status) { ?>
            <option value="<?php echo $status['status_id']; ?>"<?php echo $status['status_id'] == $pp_standard_completed ? ' selected="selected"' : ''; ?>><?php echo $status['name']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-denied"><?php echo $entry_denied; ?></label>
        <div class="col-sm-10"><select name="pp_standard_denied" id="input-denied" class="form-control">
            <?php foreach ($statuses as $status) { ?>
            <option value="<?php echo $status['status_id']; ?>"<?php echo $status['status_id'] == $pp_standard_denied ? ' selected="selected"' : ''; ?>><?php echo $status['name']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-expired"><?php echo $entry_expired; ?></label>
        <div class="col-sm-10"><select name="pp_standard_expired" id="input-expired" class="form-control">
            <?php foreach ($statuses as $status) { ?>
            <option value="<?php echo $status['status_id']; ?>"<?php echo $status['status_id'] == $pp_standard_expired ? ' selected="selected"' : ''; ?>><?php echo $status['name']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-failed"><?php echo $entry_failed; ?></label>
        <div class="col-sm-10"><select name="pp_standard_failed" id="input-failed" class="form-control">
            <?php foreach ($statuses as $status) { ?>
            <option value="<?php echo $status['status_id']; ?>"<?php echo $status['status_id'] == $pp_standard_failed ? ' selected="selected"' : ''; ?>><?php echo $status['name']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-pending"><?php echo $entry_pending; ?></label>
        <div class="col-sm-10"><select name="pp_standard_pending" id="input-pending" class="form-control">
            <?php foreach ($statuses as $status) { ?>
            <option value="<?php echo $status['status_id']; ?>"<?php echo $status['status_id'] == $pp_standard_pending ? ' selected="selected"' : ''; ?>><?php echo $status['name']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-processed"><?php echo $entry_processed; ?></label>
        <div class="col-sm-10"><select name="pp_standard_processed" id="input-processed" class="form-control">
            <?php foreach ($statuses as $status) { ?>
            <option value="<?php echo $status['status_id']; ?>"<?php echo $status['status_id'] == $pp_standard_processed ? ' selected="selected"' : ''; ?>><?php echo $status['name']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-refunded"><?php echo $entry_refunded; ?></label>
        <div class="col-sm-10"><select name="pp_standard_refunded" id="input-refunded" class="form-control">
            <?php foreach ($statuses as $status) { ?>
            <option value="<?php echo $status['status_id']; ?>"<?php echo $status['status_id'] == $pp_standard_refunded ? ' selected="selected"' : ''; ?>><?php echo $status['name']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-reversed"><?php echo $entry_reversed; ?></label>
        <div class="col-sm-10"><select name="pp_standard_reversed" id="input-reversed" class="form-control">
            <?php foreach ($statuses as $status) { ?>
            <option value="<?php echo $status['status_id']; ?>"<?php echo $status['status_id'] == $pp_standard_reversed ? ' selected="selected"' : ''; ?>><?php echo $status['name']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-voided"><?php echo $entry_voided; ?></label>
        <div class="col-sm-10"><select name="pp_standard_voided" id="input-voided" class="form-control">
            <?php foreach ($statuses as $status) { ?>
            <option value="<?php echo $status['status_id']; ?>"<?php echo $status['status_id'] == $pp_standard_voided ? ' selected="selected"' : ''; ?>><?php echo $status['name']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
        <div class="col-sm-10"><select name="pp_standard_status" id="input-status" class="form-control">
            <option value="1"<?php echo $pp_standard_status ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
            <option value="0"<?php echo $pp_standard_status ? '' : ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
        <div class="col-sm-10">
          <input type="text" name="pp_standard_sort_order" value="<?php echo $pp_standard_sort_order; ?>" id="input-sort-order" class="form-control" placeholder="<?php echo $entry_sort_order; ?>" />
        </div>
      </div>
    </form>
  </div>
</div>
<?php echo $footer; ?>