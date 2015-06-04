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
      <button type="submit" form="form-currency" title="<?php echo $button_save; ?>" data-toggle="tooltip" class="btn btn-success">
        <i class="fa fa-save"></i></button>
      <a href="<?php echo $cancel; ?>" title="<?php echo $button_cancel; ?>" data-toggle="tooltip" class="btn btn-danger"><i class="fa fa-times"></i></a>
    </div>
    <h1 class="panel-title"><i class="fa fa-pencil-square fa-lg"></i> <?php echo $heading_title; ?></h1>
  </div>
  <div class="panel-body">
    <form method="post" action="<?php echo $action; ?>" id="form-currency" class="form-horizontal">
      <div class="form-group">
        <label class="required col-sm-2 control-label" for="input-title"><?php echo $entry_title; ?></label>

        <div class="col-sm-10">
          <input type="text" name="title" value="<?php echo $title; ?>" id="input-title" class="form-control" placeholder="<?php echo $entry_title; ?>" required autofocus />
          <?php if ($error_title) { ?>
          <div class="text-danger"><?php echo $error_title; ?></div>
          <?php } ?>
        </div>
      </div>
      <div class="form-group">
        <label class="required col-sm-2 control-label" for="input-code"><?php echo $entry_code; ?></label>

        <div class="col-sm-10">
          <input type="text" name="code" value="<?php echo $code; ?>" id="input-code" class="form-control" placeholder="<?php echo $entry_code; ?>" required />
          <?php if ($error_code) { ?>
          <div class="text-danger"><?php echo $error_code; ?></div>
          <?php } ?>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-symbol-left"><?php echo $entry_symbol_left; ?></label>

        <div class="col-sm-10">
          <input type="text" name="symbol_left" value="<?php echo $symbol_left; ?>" id="input-symbol-left" class="form-control" placeholder="<?php echo $entry_symbol_left; ?>" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-symbol-right"><?php echo $entry_symbol_right; ?></label>

        <div class="col-sm-10">
          <input type="text" name="symbol_right" value="<?php echo $symbol_right; ?>" id="input-symbol-right" class="form-control" placeholder="<?php echo $entry_symbol_right; ?>" />
        </div>
      </div>
      <div class="form-group">
        <label class="required col-sm-2 control-label" for="input-decimal-place"><?php echo $entry_decimal_place; ?></label>

        <div class="col-sm-10">
          <input type="text" name="decimal_place" value="<?php echo $decimal_place; ?>" id="input-decimal-place" class="form-control" placeholder="<?php echo $entry_decimal_place; ?>" required />
          <?php if ($error_decimal_place) { ?>
          <div class="text-danger"><?php echo $error_decimal_place; ?></div>
          <?php } ?>
        </div>
      </div>
      <div class="form-group">
        <label class="required col-sm-2 control-label" for="input-value"><?php echo $entry_value; ?></label>

        <div class="col-sm-10">
          <input type="text" name="value" value="<?php echo $value; ?>" id="input-value" class="form-control" placeholder="<?php echo $entry_value; ?>" required />
          <?php if ($error_value) { ?>
          <div class="text-danger"><?php echo $error_value; ?></div>
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
<?php echo $footer; ?>