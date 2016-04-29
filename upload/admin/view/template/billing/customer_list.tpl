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
      <button type="button" title="<?php echo $button_delete; ?>" data-toggle="tooltip" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-customer').submit() : false;"><i class="fa fa-trash"></i></button>
    </div>
    <h1 class="panel-title"><i class="fa fa-list fa-lg"></i> <?php echo $heading_title; ?></h1>
  </div>
  <div class="panel-body">
    <form method="post" action="<?php echo $delete; ?>" id="form-customer">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
          <tr>
            <th class="text-center" width="1"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></th>
            <th class="text-left"><?php if ($sort == 'name') { ?>
              <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
              <?php } ?></th>
            <th class="text-left"><?php if ($sort == 'email') { ?>
              <a href="<?php echo $sort_email; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_email; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_email; ?>"><?php echo $column_email; ?></a>
              <?php } ?></th>
            <th class="text-left"><?php if ($sort == 'credit') { ?>
              <a href="<?php echo $sort_credit; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_credit; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_credit; ?>"><?php echo $column_credit; ?></a>
              <?php } ?></th>
            <th class="text-left"><?php if ($sort == 'invoice') { ?>
              <a href="<?php echo $sort_invoice; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_invoice; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_invoice; ?>"><?php echo $column_invoice; ?></a>
              <?php } ?></th>
            <th class="text-right"><?php if ($sort == 'status') { ?>
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
            <td class="text-left"><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" class="form-control input-sm" /></td>
            <td class="text-left"><input type="text" name="filter_email" value="<?php echo $filter_email; ?>" class="form-control input-sm" /></td>
            <td class="text-left"></td>
            <td class="text-left"></td>
            <td class="text-right"><select name="filter_status" class="form-control input-sm">
                <option value=""></option>
                <option value="1"<?php echo $filter_status ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
                <option value="0"<?php echo (!$filter_status && !is_null($filter_status)) ? ' selected="selected"' : ''; ?>><?php echo $text_disabled; ?></option>
              </select></td>
            <td class="text-right"><input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" class="form-control input-sm date" /></td>
            <td class="text-right"><input type="text" name="filter_date_modified" value="<?php echo $filter_date_modified; ?>" class="form-control input-sm date" /></td>
            <td class="text-right"><button type="button" title="<?php echo $button_search; ?>" data-toggle="tooltip" class="btn btn-primary btn-xs" onclick="filter();"><i class="fa fa-search"></i></button></td>
          </tr>
          <?php if ($customers) { ?>
          <?php foreach ($customers as $customer) { ?>
          <tr>
            <td class="text-center"><?php if (in_array($customer['customer_id'], $selected)) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $customer['customer_id']; ?>" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $customer['customer_id']; ?>" />
              <?php } ?></td>
            <td class="text-left"><?php echo $customer['name']; ?></td>
            <td class="text-left"><?php echo $customer['email']; ?></td>
            <td class="text-left"><?php echo $customer['credit']; ?></td>
            <td class="text-left"><?php echo $customer['invoice']; ?></td>
            <td class="text-right"><?php echo $customer['status']; ?></td>
            <td class="text-right"><?php echo $customer['date_added']; ?></td>
            <td class="text-right"><?php echo $customer['date_modified']; ?></td>
            <td class="text-right">
              <a href="<?php echo $customer['login']; ?>" target="_blank" title="<?php echo $button_login; ?>" data-toggle="tooltip" class="btn btn-primary btn-xs"><i class="fa fa-unlock-alt"></i></a>
              <a href="<?php echo $customer['edit']; ?>" title="<?php echo $button_edit; ?>" data-toggle="tooltip" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
            </td>
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
	url = 'index.php?load=billing/customer&token=<?php echo $token; ?>';

	var filter_name = $('input[name=\'filter_name\']').val();

	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

	var filter_email = $('input[name=\'filter_email\']').val();

	if (filter_email) {
		url += '&filter_email=' + encodeURIComponent(filter_email);
	}

	var filter_status = $('select[name=\'filter_status\']').val();

	if (filter_status) {
		url += '&filter_status=' + encodeURIComponent(filter_status);
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
	$('.filter input').on('keydown', function (e) {
		if (e.keyCode == 13) {
			filter();
		}
	});

	$('.date').datetimepicker({
		format: 'YYYY-MM-DD'
	});
});
//--></script>
<?php echo $footer; ?>