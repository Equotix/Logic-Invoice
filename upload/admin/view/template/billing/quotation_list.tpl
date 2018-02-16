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
      <button type="button" title="<?php echo $button_generate_invoice; ?>" data-toggle="tooltip" class="btn btn-primary" onclick="$('#form-quotation').attr('action', '<?php echo $generate; ?>');
              confirm('<?php echo $text_confirm; ?>') ? $('#form-quotation').submit() : false;"><i class="fa fa-check"></i></button>
      <a href="<?php echo $insert; ?>" title="<?php echo $button_add; ?>" data-toggle="tooltip" class="btn btn-success"><i class="fa fa-plus"></i></a>
      <button type="button" title="<?php echo $button_delete; ?>" data-toggle="tooltip" class="btn btn-danger" onclick="$('#form-quotation').attr('action', '<?php echo $delete; ?>');
              confirm('<?php echo $text_confirm; ?>') ? $('#form-quotation').submit() : false;"><i class="fa fa-trash"></i></button>
    </div>
    <h1 class="panel-title"><i class="fa fa-list fa-lg"></i> <?php echo $heading_title; ?></h1>
  </div>
  <div class="panel-body">
    <form method="post" action="<?php echo $delete; ?>" id="form-quotation">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
          <tr>
            <th class="text-center" width="1"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>
            <th class="text-left"><?php if ($sort == 'quotation_id') { ?>
              <a href="<?php echo $sort_quotation_id; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_quotation_id; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_quotation_id; ?>"><?php echo $column_quotation_id; ?></a>
              <?php } ?></th>
            <th class="text-left"><?php if ($sort == 'name') { ?>
              <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
              <?php } ?></th>
            <th class="text-left"><?php if ($sort == 'total') { ?>
              <a href="<?php echo $sort_total; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_total; ?>"><?php echo $column_total; ?></a>
              <?php } ?></th>
            <th class="text-left"><?php if ($sort == 'status') { ?>
              <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
              <?php } ?></th>
            <th class="text-right"><?php if ($sort == 'date_due') { ?>
              <a href="<?php echo $sort_date_due; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_due; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_date_due; ?>"><?php echo $column_date_due; ?></a>
              <?php } ?></th>
            <th class="text-right"><?php if ($sort == 'date_issued') { ?>
              <a href="<?php echo $sort_date_issued; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_issued; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_date_issued; ?>"><?php echo $column_date_issued; ?></a>
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
            <td class="text-left col-sm-1"><input type="text" name="filter_quotation_id" value="<?php echo $filter_quotation_id; ?>" class="form-control input-sm" /></td>
            <td class="text-left col-sm-3"><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" class="form-control input-sm" /></td>
            <td class="text-left col-sm-1"><input type="text" name="filter_total" value="<?php echo $filter_total; ?>" class="form-control input-sm" /></td>
            <td class="text-right col-sm-2"><select name="filter_status_id" class="form-control input-sm">
                <option value=""></option>
                <?php foreach ($statuses as $status) { ?>
                <option value="<?php echo $status['status_id']; ?>"<?php echo $filter_status_id == $status['status_id'] ? ' selected="selected"' : ''; ?>><?php echo $status['name']; ?></option>
                <?php } ?>
              </select></td>
            <td class="text-right col-sm-1"><input type="text" name="filter_date_due" value="<?php echo $filter_date_due; ?>" class="form-control input-sm date" /></td>
            <td class="text-right col-sm-1"><input type="text" name="filter_date_issued" value="<?php echo $filter_date_issued; ?>" class="form-control input-sm date" /></td>
            <td class="text-right col-sm-1"><input type="text" name="filter_date_modified" value="<?php echo $filter_date_modified; ?>" class="form-control input-sm date" /></td>
            <td class="text-right col-sm-1"><button type="button" title="<?php echo $button_search; ?>" data-toggle="tooltip" class="btn btn-primary btn-xs" onclick="filter();"><i class="fa fa-search"></i></button></td>
          </tr>
          <?php if ($quotations) { ?>
          <?php foreach ($quotations as $quotation) { ?>
          <tr>
            <td class="text-center"><?php if (in_array($quotation['quotation_id'], $selected)) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $quotation['quotation_id']; ?>" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $quotation['quotation_id']; ?>" />
              <?php } ?></td>
            <td class="text-left"><?php echo $quotation['quotation_id']; ?></td>
            <td class="text-left"><a href="<?php echo $quotation['customer']; ?>"><?php echo $quotation['name']; ?></a></td>
            <td class="text-left"><?php echo $quotation['total']; ?></td>
            <td class="text-left"><select name="<?php echo $quotation['quotation_id']; ?>" class="form-control input-sm status"><?php foreach ($statuses as $status) { ?>
                <option value="<?php echo $status['status_id']; ?>"<?php echo $status['status_id'] == $quotation['status_id'] ? ' selected="selected"' : ''; ?>><?php echo $status['name']; ?></option>
                <?php } ?></select></td>
            <td class="text-right"><?php echo $quotation['date_due']; ?></td>
            <td class="text-right"><?php echo $quotation['date_issued']; ?></td>
            <td class="text-right"><?php echo $quotation['date_modified']; ?></td>
            <td class="text-right"><a href="<?php echo $quotation['edit']; ?>" title="<?php echo $button_edit; ?>" data-toggle="tooltip" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a> <a href="<?php echo $quotation['quotation']; ?>" title="<?php echo $button_quotation; ?>" data-toggle="tooltip" class="btn btn-primary btn-xs" target="_blank"><i class="fa fa-clipboard"></i></a> <a href="<?php echo $quotation['view']; ?>" title="<?php echo $button_view; ?>" data-toggle="tooltip" class="btn btn-primary btn-xs"><i class="fa fa-search"></i></a></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="text-center" colspan="9"><?php echo $text_no_results; ?></td>
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
	url = 'index.php?load=billing/quotation&token=<?php echo $token; ?>';

	var filter_quotation_id = $('input[name=\'filter_quotation_id\']').val();

	if (filter_quotation_id) {
		url += '&filter_quotation_id=' + encodeURIComponent(filter_quotation_id);
	}

	var filter_name = $('input[name=\'filter_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

	var filter_total = $('input[name=\'filter_total\']').val();

	if (filter_total) {
		url += '&filter_total=' + encodeURIComponent(filter_total);
	}

	var filter_status_id = $('select[name=\'filter_status_id\']').val();

	if (filter_status_id) {
		url += '&filter_status_id=' + encodeURIComponent(filter_status_id);
	}

	var filter_date_due = $('input[name=\'filter_date_due\']').val();

	if (filter_date_due) {
		url += '&filter_date_due=' + encodeURIComponent(filter_date_due);
	}

	var filter_date_issued = $('input[name=\'filter_date_issued\']').val();

	if (filter_date_issued) {
		url += '&filter_date_issued=' + encodeURIComponent(filter_date_issued);
	}

	var filter_date_modified = $('input[name=\'filter_date_modified\']').val();

	if (filter_date_modified) {
		url += '&filter_date_modified=' + encodeURIComponent(filter_date_modified);
	}

	location = url;
}

$(document).ready(function () {
	$('.date').datetimepicker({
		format: 'YYYY-MM-DD'
	});

	$('.filter input').on('keydown', function (e) {
		if (e.keyCode == 13) {
			filter();
		}
	});

	$('.status').change(function () {
		var field = $(this);

		$.ajax({
			url: 'index.php?load=billing/quotation/update&token=<?php echo $token; ?>',
			type: 'post',
			data: {quotation_id: field.attr('name'), status_id: field.val()},
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