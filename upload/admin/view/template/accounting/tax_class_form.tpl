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
      <button type="submit" form="form-tax-class" title="<?php echo $button_save; ?>" data-toggle="tooltip" class="btn btn-success"><i class="fa fa-save"></i></button>
      <a href="<?php echo $cancel; ?>" title="<?php echo $button_cancel; ?>" data-toggle="tooltip" class="btn btn-danger"><i class="fa fa-times"></i></a>
    </div>
    <h1 class="panel-title"><i class="fa fa-pencil-square fa-lg"></i> <?php echo $heading_title; ?></h1>
  </div>
  <div class="panel-body">
    <form method="post" action="<?php echo $action; ?>" id="form-tax-class" class="form-horizontal">
      <div class="form-group">
        <label class="required col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
        <div class="col-sm-10">
          <input type="text" name="name" value="<?php echo $name; ?>" id="input-name" class="form-control" placeholder="<?php echo $entry_name; ?>" required />
          <?php if (!empty($error_name)) { ?>
          <div class="text-danger"><?php echo $error_name; ?></div>
          <?php } ?>
        </div>
      </div>
      <div class="form-group">
        <label class="required col-sm-2 control-label" for="input-description"><?php echo $entry_description; ?></label>
        <div class="col-sm-10">
          <input type="text" name="description" value="<?php echo $description; ?>" id="input-description" class="form-control" placeholder="<?php echo $entry_description; ?>" required />
          <?php if (!empty($error_description)) { ?>
          <div class="text-danger"><?php echo $error_description; ?></div>
          <?php } ?>
        </div>
      </div>
      <table id="tax-rates" class="table table-striped table-bordered table-hover">
        <tr>
          <th class="text-left"><?php echo $entry_tax_rate; ?></th>
          <th class="text-left"><?php echo $entry_priority; ?></th>
          <th class="text-right"></th>
        </tr>
        <?php $tax_rate_row = 0; ?>
        <?php foreach ($tax_rates as $tax_rate) { ?>
        <tr>
          <td class="text-left"><select name="tax_rates[<?php echo $tax_rate_row; ?>][tax_rate_id]" class="form-control">
              <?php foreach ($rates as $rate) { ?>
              <option value="<?php echo $rate['tax_rate_id']; ?>"<?php echo $tax_rate['tax_rate_id'] == $rate['tax_rate_id'] ? ' selected="selected"' : ''; ?>><?php echo $rate['name']; ?></option>
              <?php } ?>
            </select></td>
          <td class="text-left"><input type="text" name="tax_rates[<?php echo $tax_rate_row; ?>][priority]" value="<?php echo $tax_rate['priority']; ?>" class="form-control" /></td>
          <td class="text-right"><button title="<?php echo $button_remove; ?>" data-toggle="tooltip" class="btn btn-danger btn-xs" onclick="$(this).parents('tr').remove();"><i class="fa fa-minus-circle"></i></button></td>
        </tr>
        <?php $tax_rate_row++; ?>
        <?php } ?>
        <tr>
          <td class="text-right" colspan="3"><button type="button" title="<?php echo $button_add; ?>" data-toggle="tooltip" class="btn btn-success btn-xs" onclick="addRow();"><i class="fa fa-plus-circle"></i></button></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript"><!--
var tax_rate_row = <?php echo $tax_rate_row; ?>;

function addRow() {
	html = '';
	html += '<tr>';
	html += '  <td class="text-left"><select name="tax_rates[' + tax_rate_row + '][tax_rate_id]" class="form-control">';
	<?php foreach ($rates as $rate) { ?>
	html += '    <option value="<?php echo $rate['tax_rate_id']; ?>"><?php echo $rate['name']; ?></option>';
	<?php } ?>
	html += '  </select></td>';
	html += '  <td class="text-left"><input type="text" name="tax_rates[' + tax_rate_row + '][priority]" value="" class="form-control" /></td>';
	html += '  <td class="text-right"><button title="<?php echo $button_remove; ?>" data-toggle="tooltip" class="btn btn-danger btn-xs" onclick="$(this).parents(\'tr\').remove();"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';

	$('#tax-rates tr:last').before(html);

	$('[data-toggle=\'tooltip\']').tooltip();

	tax_rate_row++;
}
//--></script>
<?php echo $footer; ?>