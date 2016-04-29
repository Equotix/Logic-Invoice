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
            <th class="text-left"><?php if ($sort == 'description') { ?>
              <a href="<?php echo $sort_description; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_description; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_description; ?>"><?php echo $column_description; ?></a>
              <?php } ?></th>
            <th class="text-left"><?php if ($sort == 'invoice_id') { ?>
              <a href="<?php echo $sort_invoice_id; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_invoice; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_invoice_id; ?>"><?php echo $column_invoice; ?></a>
              <?php } ?></th>
			 <th class="text-left"><?php if ($sort == 'amount') { ?>
              <a href="<?php echo $sort_amount; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_amount; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_amount; ?>"><?php echo $column_amount; ?></a>
              <?php } ?></th>
            <th class="text-left"><?php if ($sort == 'date') { ?>
              <a href="<?php echo $sort_date; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_date; ?>"><?php echo $column_date; ?></a>
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
            <td class="text-left"><input type="text" name="filter_description" value="<?php echo $filter_description; ?>" class="form-control input-sm" /></td>
            <td class="text-left"><input type="text" name="filter_invoice_id" value="<?php echo $filter_invoice_id; ?>" class="form-control input-sm" /></td>
			<td class="text-left"></td>
            <td class="text-left"><input type="text" name="filter_date" value="<?php echo $filter_date; ?>" class="form-control input-sm date" /></td>
            <td class="text-right"><input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" class="form-control input-sm date" /></td>
            <td class="text-right"><input type="text" name="filter_date_modified" value="<?php echo $filter_date_modified; ?>" class="form-control input-sm date" /></td>
            <td class="text-right"><button type="button" title="<?php echo $button_search; ?>" data-toggle="tooltip" class="btn btn-primary btn-xs" onclick="filter();"><i class="fa fa-search"></i></button></td>
          </tr>
          <?php if ($transactions) { ?>
          <?php foreach ($transactions as $transaction) { ?>
          <tr>
            <td class="text-center"><?php if (in_array($transaction['transaction_id'], $selected)) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $transaction['transaction_id']; ?>" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $transaction['transaction_id']; ?>" />
              <?php } ?></td>
            <td class="text-left"><?php echo $transaction['description']; ?></td>
            <td class="text-left">
              <?php if ($transaction['invoice']) { ?>
              <a href="<?php echo $transaction['invoice']; ?>"><?php echo $transaction['invoice_id']; ?></a>
              <?php } else { ?>
              <?php echo $text_none; ?>
              <?php } ?>
            </td>
            <td class="text-left"><?php echo $transaction['amount']; ?></td>
            <td class="text-left"><?php echo $transaction['date']; ?></td>
            <td class="text-right"><?php echo $transaction['date_added']; ?></td>
            <td class="text-right"><?php echo $transaction['date_modified']; ?></td>
            <td class="text-right"><a href="<?php echo $transaction['edit']; ?>" title="<?php echo $button_edit; ?>" data-toggle="tooltip" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
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
	url = 'index.php?load=accounting/journal&token=<?php echo $token; ?>';

	var filter_description = $('input[name=\'filter_description\']').val();

	if (filter_description) {
		url += '&filter_description=' + encodeURIComponent(filter_description);
	}

	var filter_invoice_id = $('input[name=\'filter_invoice_id\']').val();

	if (filter_invoice_id) {
		url += '&filter_invoice_id=' + encodeURIComponent(filter_invoice_id);
	}

	var filter_date = $('input[name=\'filter_date\']').val();

	if (filter_date) {
		url += '&filter_date=' + encodeURIComponent(filter_date);
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