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
<?php if ($success) { ?>
<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
  <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<?php } ?>
<div class="panel panel-default">
  <div class="panel-heading">
    <div class="pull-right">
      <a href="<?php echo $insert; ?>" title="<?php echo $button_add; ?>" data-toggle="tooltip" class="btn btn-success"><i class="fa fa-plus"></i></a>
      <button type="button" title="<?php echo $button_delete; ?>" data-toggle="tooltip" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-journal').submit() : false;"><i class="fa fa-trash"></i></button>
    </div>
    <h1 class="panel-title"><i class="fa fa-list fa-lg"></i> <?php echo $heading_title; ?></h1>
  </div>
  <div class="panel-body">
    <form method="post" action="<?php echo $delete; ?>" id="form-journal">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
          <tr>
            <th class="text-center" width="1"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>
            <th class="text-center" width="1"><?php echo $column_image; ?></th>
			<th class="text-left"><?php if ($sort == 'sku') { ?>
              <a href="<?php echo $sort_sku; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_sku; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_sku; ?>"><?php echo $column_sku; ?></a>
              <?php } ?></th>
            <th class="text-left"><?php if ($sort == 'name') { ?>
              <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
              <?php } ?></th>
            <th class="text-left"><?php if ($sort == 'quantity') { ?>
              <a href="<?php echo $sort_quantity; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_quantity; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_quantity; ?>"><?php echo $column_quantity; ?></a>
              <?php } ?></th>
			<th class="text-left"><?php if ($sort == 'cost') { ?>
              <a href="<?php echo $sort_cost; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_cost; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_cost; ?>"><?php echo $column_cost; ?></a>
              <?php } ?></th>
			<th class="text-left"><?php if ($sort == 'sell') { ?>
              <a href="<?php echo $sort_sell; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_sell; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_sell; ?>"><?php echo $column_sell; ?></a>
              <?php } ?></th>
			<th class="text-left"><?php if ($sort == 'status') { ?>
              <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
              <?php } ?></th>
            <th class="text-right"><?php if ($sort == 'date_added') { ?>
              <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
              <?php } ?></th>
            <th class="text-right"><?php if ($sort == 'date_modified') { ?>
              <a href="<?php echo $sort_date_modified; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_modified; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_date_modified; ?>"><?php echo $column_date_modified; ?></a>
              <?php } ?></th>
            <th class="text-right"><?php echo $column_action; ?></th>
          </tr>
          <tr class="filter">
            <td></td>
            <td></td>
            <td class="text-left"><input type="text" name="filter_sku" value="<?php echo $filter_sku; ?>" class="form-control input-sm" /></td>
            <td class="text-left"><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" class="form-control input-sm" /></td>
            <td class="text-left"><input type="text" name="filter_quantity" value="<?php echo $filter_quantity; ?>" class="form-control input-sm" /></td>
            <td class="text-left"><input type="text" name="filter_cost" value="<?php echo $filter_cost; ?>" class="form-control input-sm" /></td>
            <td class="text-left"><input type="text" name="filter_sell" value="<?php echo $filter_cost; ?>" class="form-control input-sm" /></td>
            <td class="text-left"><select name="filter_status" class="form-control input-sm">
			  <option value=""></option>
			  <option value="1"<?php echo $filter_status ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
              <option value="0"<?php echo (!$filter_status && !is_null($filter_status)) ? ' selected="selected"' : ''; ?>><?php echo $text_disabled; ?></option>
			  </select></td>
            <td></td>
            <td></td>
			<td class="text-right"><button type="button" title="<?php echo $button_search; ?>" data-toggle="tooltip" class="btn btn-primary btn-xs" onclick="filter();"><i class="fa fa-search"></i></button></td>
          </tr>
          <?php if ($inventories) { ?>
          <?php foreach ($inventories as $inventory) { ?>
          <tr>
            <td class="text-center"><?php if (in_array($inventory['inventory_id'], $selected)) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $inventory['inventory_id']; ?>" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $inventory['inventory_id']; ?>" />
              <?php } ?></td>
            <td class="text-center"><img src="<?php echo $inventory['image']; ?>" alt="<?php echo $inventory['name']; ?>" /></td>
            <td class="text-left editable" data-id="<?php echo $inventory['inventory_id']; ?>" data-column="sku" data-value="<?php echo $inventory['sku']; ?>"><?php echo $inventory['sku']; ?></td>
            <td class="text-left editable" data-id="<?php echo $inventory['inventory_id']; ?>" data-column="name" data-value="<?php echo $inventory['name']; ?>"><?php echo $inventory['name']; ?></td>
            <td class="text-left editable" data-id="<?php echo $inventory['inventory_id']; ?>" data-column="quantity" data-value="<?php echo $inventory['quantity']; ?>"><?php echo $inventory['quantity']; ?></td>
            <td class="text-left editable" data-id="<?php echo $inventory['inventory_id']; ?>" data-column="cost" data-value="<?php echo $inventory['cost_raw']; ?>"><?php echo $inventory['cost']; ?></td>
            <td class="text-left editable" data-id="<?php echo $inventory['inventory_id']; ?>" data-column="sell" data-value="<?php echo $inventory['sell_raw']; ?>"><?php echo $inventory['sell']; ?></td>
            <td class="text-left"><select name="<?php echo $inventory['inventory_id']; ?>" class="form-control input-sm status">
			  <option value="1"<?php echo $inventory['status'] ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
			  <option value="0"<?php echo !$inventory['status'] ? ' selected="selected"' : ''; ?>><?php echo $text_disabled; ?></option>
			  </select></td>
            <td class="text-right"><?php echo $inventory['date_added']; ?></td>
            <td class="text-right"><?php echo $inventory['date_modified']; ?></td>
            <td class="text-right"><a href="<?php echo $inventory['edit']; ?>" title="<?php echo $button_edit; ?>" data-toggle="tooltip" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="text-center" colspan="11"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </table>
      </div>
    </form>
    <?php echo $pagination; ?>
  </div>
</div>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?load=accounting/inventory&token=<?php echo $token; ?>';
	
	var filter_sku = $('input[name=\'filter_sku\']').val();

	if (filter_sku) {
		url += '&filter_sku=' + encodeURIComponent(filter_sku);
	}

	var filter_name = $('input[name=\'filter_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

	var filter_quantity = $('input[name=\'filter_quantity\']').val();

	if (filter_quantity) {
		url += '&filter_quantity=' + encodeURIComponent(filter_quantity);
	}
	
	var filter_cost = $('input[name=\'filter_cost\']').val();

	if (filter_cost) {
		url += '&filter_cost=' + encodeURIComponent(filter_cost);
	}
	
	var filter_sell = $('input[name=\'filter_sell\']').val();

	if (filter_sell) {
		url += '&filter_sell=' + encodeURIComponent(filter_sell);
	}

	var filter_status = $('select[name=\'filter_status\']').val();

	if (filter_status) {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

	location = url;
}

$(document).ready(function () {
	$('.filter input').on('keydown', function (e) {
		if (e.keyCode == 13) {
			filter();
		}
	});
	
	$(document).on('click', '.editable', function() {
		var html = '<input type="text" name="' + $(this).attr('data-column') + '" value="' + $(this).attr('data-value') + '" class="form-control editing" data-id="' + $(this).attr('data-id') + '" />';
	
		$(this).html(html).removeClass('editable');
	});
	
	$(document).on('focusout', '.editing', function () {
		var field = $(this);

		$.ajax({
			url: 'index.php?load=accounting/inventory/update&token=<?php echo $token; ?>',
			type: 'post',
			data: {inventory_id: field.attr('data-id'), column: field.attr('name'), value: field.val()},
			dataType: 'json',
			beforeSend: function () {
				field.after('<i class="fa fa-spinner"></i>');
			},
			complete: function () {
				$('.fa-spinner').remove();
			},
			success: function (json) {
				$('.alert').remove();

				if (json['warning']) {
					field.css('border', '1px solid #a94442');

					$('.breadcrumb').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				} else {
					field.parent().addClass('editable').html(json['value']).attr('data-value', field.val());

					$('.breadcrumb').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});
	
	$('.status').change(function () {
		var field = $(this);

		$.ajax({
			url: 'index.php?load=accounting/inventory/update&token=<?php echo $token; ?>',
			type: 'post',
			data: {inventory_id: field.attr('name'), column: 'status', value: field.val()},
			dataType: 'json',
			beforeSend: function () {
				field.after('<i class="fa fa-spinner"></i>');
			},
			complete: function () {
				$('.fa-spinner').remove();
			},
			success: function (json) {
				$('.alert-danger').remove();
				$('.alert-success').remove();

				if (json['warning']) {
					field.css('border', '1px solid #a94442');

					$('.breadcrumb').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				} else {
					field.css('border', '1px solid #3c763d');

					$('.breadcrumb').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});
});
//--></script>
<?php echo $footer; ?>