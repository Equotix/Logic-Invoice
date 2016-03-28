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
      <button type="submit" form="form-inventory" title="<?php echo $button_save; ?>" data-toggle="tooltip" class="btn btn-success">
        <i class="fa fa-save"></i></button>
      <a href="<?php echo $cancel; ?>" title="<?php echo $button_cancel; ?>" data-toggle="tooltip" class="btn btn-danger"><i class="fa fa-times"></i></a>
    </div>
    <h1 class="panel-title"><i class="fa fa-pencil-square fa-lg"></i> <?php echo $heading_title; ?></h1>
  </div>
  <div class="panel-body">
    <form method="post" action="<?php echo $action; ?>" id="form-inventory" class="form-horizontal">
	  <div class="form-group">
		<label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>
		<div class="col-sm-10">
		  <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb ? $thumb : $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
		  <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
		</div>
	  </div>
	  <div class="form-group">
        <label class="required col-sm-2 control-label" for="input-sku"><?php echo $entry_sku; ?></label>
        <div class="col-sm-10">
          <input type="text" name="sku" value="<?php echo $sku; ?>" id="input-sku" class="form-control" placeholder="<?php echo $entry_sku; ?>" required autofocus />
          <?php if ($error_sku) { ?>
          <div class="text-danger"><?php echo $error_sku; ?></div>
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
        <label class="col-sm-2 control-label" for="input-description"><?php echo $entry_description; ?></label>
        <div class="col-sm-10">
          <textarea name="description" id="input-description" class="form-control" rows="5" placeholder="<?php echo $entry_description; ?>"><?php echo $description; ?></textarea>
        </div>
      </div>
	  <div class="form-group">
        <label class="col-sm-2 control-label" for="input-quantity"><?php echo $entry_quantity; ?></label>
        <div class="col-sm-10">
          <input type="text" name="quantity" value="<?php echo $quantity; ?>" id="input-quantity" class="form-control" placeholder="<?php echo $entry_quantity; ?>" />
        </div>
      </div>
	  <div class="form-group">
        <label class="col-sm-2 control-label" for="input-cost"><?php echo $entry_cost; ?></label>
        <div class="col-sm-10">
          <input type="text" name="cost" value="<?php echo $cost; ?>" id="input-cost" class="form-control" placeholder="<?php echo $entry_cost; ?>" />
        </div>
      </div>
	  <div class="form-group">
        <label class="col-sm-2 control-label" for="input-sell"><?php echo $entry_sell; ?></label>
        <div class="col-sm-10">
          <input type="text" name="sell" value="<?php echo $sell; ?>" id="input-sell" class="form-control" placeholder="<?php echo $entry_sell; ?>" />
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