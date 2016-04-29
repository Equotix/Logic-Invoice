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
      <button type="button" title="<?php echo $button_delete; ?>" data-toggle="tooltip" class="btn btn-danger" onclick="$('#form-recurring').attr('action', '<?php echo $delete; ?>');
              confirm('<?php echo $text_confirm; ?>') ? $('#form-recurring').submit() : false;"><i class="fa fa-trash"></i></button>
    </div>
    <h1 class="panel-title"><i class="fa fa-list fa-lg"></i> <?php echo $heading_title; ?></h1>
  </div>
  <div class="panel-body">
    <form method="post" action="<?php echo $delete; ?>" id="form-recurring">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
          <tr>
            <th class="text-center" width="1"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>
            <th class="text-left"><?php if ($sort == 'recurring_id') { ?>
              <a href="<?php echo $sort_recurring_id; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_recurring_id; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_recurring_id; ?>"><?php echo $column_recurring_id; ?></a>
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
            <th class="text-left"><?php if ($sort == 'cycle') { ?>
              <a href="<?php echo $sort_cycle; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_cycle; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_cycle; ?>"><?php echo $column_cycle; ?></a>
              <?php } ?></th>
            <th class="text-right"><?php if ($sort == 'date_due') { ?>
              <a href="<?php echo $sort_date_due; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_due; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_date_due; ?>"><?php echo $column_date_due; ?></a>
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
            <td class="text-left col-sm-1"><input type="text" name="filter_recurring_id" value="<?php echo $filter_recurring_id; ?>" class="form-control input-sm" /></td>
            <td class="text-left col-sm-2"><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" class="form-control input-sm" /></td>
            <td class="text-left col-sm-1"><input type="text" name="filter_total" value="<?php echo $filter_total; ?>" class="form-control input-sm" /></td>
            <td class="text-right col-sm-2"><select name="filter_status" class="form-control input-sm">
                <option value=""></option>
                <option value="1"<?php echo $filter_status ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
                <option value="0"<?php echo (!$filter_status && !is_null($filter_status)) ? ' selected="selected"' : ''; ?>><?php echo $text_disabled; ?></option>
              </select></td>
            <td class="text-right col-sm-2"><select name="filter_cycle" class="form-control input-sm">
                <option value=""></option>
                <option value="monthly"<?php echo $filter_cycle == 'monthly' ? 'selected="selected"' : ''; ?>><?php echo $text_monthly; ?></option>
                <option value="quarterly"<?php echo $filter_cycle == 'quarterly' ? 'selected="selected"' : ''; ?>><?php echo $text_quarterly; ?></option>
                <option value="semi_annually"<?php echo $filter_cycle == 'semi_annually' ? 'selected="selected"' : ''; ?>><?php echo $text_semi_annually; ?></option>
                <option value="annually"<?php echo $filter_cycle == 'annually' ? 'selected="selected"' : ''; ?>><?php echo $text_annually; ?></option>
                <option value="biennally"<?php echo $filter_cycle == 'biennally' ? 'selected="selected"' : ''; ?>><?php echo $text_biennally; ?></option>
                <option value="triennally"<?php echo $filter_cycle == 'triennally' ? 'selected="selected"' : ''; ?>><?php echo $text_triennally; ?></option>
              </select></td>
            <td class="text-right col-sm-1"><input type="text" name="filter_date_due" value="<?php echo $filter_date_due; ?>" class="form-control input-sm date" /></td>
            <td class="text-right col-sm-1"><input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" class="form-control input-sm date" /></td>
            <td class="text-right col-sm-1"><input type="text" name="filter_date_modified" value="<?php echo $filter_date_modified; ?>" class="form-control input-sm date" /></td>
            <td class="text-right col-sm-1"><button type="button" title="<?php echo $button_search; ?>" data-toggle="tooltip" class="btn btn-primary btn-xs" onclick="filter();"><i class="fa fa-search"></i></button></td>
          </tr>
          <?php if ($recurrings) { ?>
          <?php foreach ($recurrings as $recurring) { ?>
          <tr>
            <td class="text-center"><?php if (in_array($recurring['recurring_id'], $selected)) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $recurring['recurring_id']; ?>" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $recurring['recurring_id']; ?>" />
              <?php } ?></td>
            <td class="text-left"><?php echo $recurring['recurring_id']; ?></td>
            <td class="text-left"><a href="<?php echo $recurring['customer']; ?>"><?php echo $recurring['name']; ?></a></td>
            <td class="text-left"><?php echo $recurring['total']; ?></td>
            <td class="text-left"><select name="<?php echo $recurring['recurring_id']; ?>" class="form-control input-sm status">
                <option value="1"<?php echo $recurring['status'] ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
                <option value="0"<?php echo $recurring['status'] ? '' : ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
              </select></td>
            <td class="text-left"><?php echo $recurring['cycle']; ?></td>
            <td class="text-right"><?php echo $recurring['date_due']; ?></td>
            <td class="text-right"><?php echo $recurring['date_added']; ?></td>
            <td class="text-right"><?php echo $recurring['date_modified']; ?></td>
            <td class="text-right"><a href="<?php echo $recurring['edit']; ?>" title="<?php echo $button_edit; ?>" data-toggle="tooltip" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a> <a href="<?php echo $recurring['view']; ?>" title="<?php echo $button_view; ?>" data-toggle="tooltip" class="btn btn-primary btn-xs"><i class="fa fa-search"></i></a></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="text-center" colspan="10"><?php echo $text_no_results; ?></td>
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
	url = 'index.php?load=billing/recurring&token=<?php echo $token; ?>';

	var filter_recurring_id = $('input[name=\'filter_recurring_id\']').val();

	if (filter_recurring_id) {
		url += '&filter_recurring_id=' + encodeURIComponent(filter_recurring_id);
	}

	var filter_name = $('input[name=\'filter_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

	var filter_total = $('input[name=\'filter_total\']').val();

	if (filter_total) {
		url += '&filter_total=' + encodeURIComponent(filter_total);
	}

	var filter_status = $('select[name=\'filter_status\']').val();

	if (filter_status) {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

	var filter_cycle = $('select[name=\'filter_cycle\']').val();

	if (filter_cycle) {
		url += '&filter_cycle=' + encodeURIComponent(filter_cycle);
	}

	var filter_date_due = $('input[name=\'filter_date_due\']').val();

	if (filter_date_due) {
		url += '&filter_date_due=' + encodeURIComponent(filter_date_due);
	}

	var filter_date_added = $('input[name=\'filter_date_added\']').val();

	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
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

	$('.status').change(function () {
		var field = $(this);

		$.ajax({
			url: 'index.php?load=billing/recurring/update&token=<?php echo $token; ?>',
			type: 'post',
			data: {recurring_id: field.attr('name'), status: field.val()},
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