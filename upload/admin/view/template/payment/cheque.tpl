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
      <button type="submit" form="form-cheque" title="<?php echo $button_save; ?>" data-toggle="tooltip" class="btn btn-success"><i class="fa fa-save"></i></button>
      <a href="<?php echo $cancel; ?>" title="<?php echo $button_cancel; ?>" data-toggle="tooltip" class="btn btn-danger"><i class="fa fa-times"></i></a>
    </div>
    <h1 class="panel-title"><i class="fa fa-pencil-square fa-lg"></i> <?php echo $heading_title; ?></h1>
  </div>
  <div class="panel-body">
    <form method="post" action="<?php echo $action; ?>" id="form-cheque" class="form-horizontal">
      <div class="form-group">
        <label class="required col-sm-2 control-label" for="input-payable"><?php echo $entry_payable; ?></label>
        <div class="col-sm-10">
          <input type="text" name="cheque_payable" value="<?php echo $cheque_payable; ?>" id="input-payable" class="form-control" placeholder="<?php echo $entry_payable; ?>" required autofocus />
          <?php if ($error_payable) { ?>
          <div class="text-danger"><?php echo $error_payable; ?></div>
          <?php } ?>
        </div>
      </div>
      <?php foreach ($languages as $language) { ?>
      <div class="form-group">
        <label class="required col-sm-2 control-label" for="input-details-<?php echo $language['language_id']; ?>"><?php echo $entry_details; ?></label>
        <div class="col-sm-10">
          <div class="input-group">
            <span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>" /></span>
            <textarea name="cheque_details[<?php echo $language['language_id']; ?>]" id="input-details-<?php echo $language['language_id']; ?>" class="form-control" placeholder="<?php echo $entry_details; ?>" rows="5"><?php echo !empty($cheque_details[$language['language_id']]) ? $cheque_details[$language['language_id']] : ''; ?></textarea>
          </div>
        </div>
      </div>
      <?php } ?>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-completed"><?php echo $entry_completed; ?></label>
        <div class="col-sm-10"><select name="cheque_completed_status_id" id="input-completed" class="form-control">
            <?php foreach ($statuses as $status) { ?>
            <option value="<?php echo $status['status_id']; ?>"<?php echo $status['status_id'] == $cheque_completed_status_id ? ' selected="selected"' : ''; ?>><?php echo $status['name']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
        <div class="col-sm-10"><select name="cheque_status" id="input-status" class="form-control">
            <option value="1"<?php echo $cheque_status ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
            <option value="0"<?php echo $cheque_status ? '' : ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
        <div class="col-sm-10">
          <input type="text" name="cheque_sort_order" value="<?php echo $cheque_sort_order; ?>" id="input-sort-order" class="form-control" placeholder="<?php echo $entry_sort_order; ?>" />
        </div>
      </div>
    </form>
  </div>
</div>
<?php echo $footer; ?>