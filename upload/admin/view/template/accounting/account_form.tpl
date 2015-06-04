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
      <button type="submit" form="form-account" title="<?php echo $button_save; ?>" data-toggle="tooltip" class="btn btn-success">
        <i class="fa fa-save"></i></button>
      <a href="<?php echo $cancel; ?>" title="<?php echo $button_cancel; ?>" data-toggle="tooltip" class="btn btn-danger"><i class="fa fa-times"></i></a>
    </div>
    <h1 class="panel-title"><i class="fa fa-pencil-square fa-lg"></i> <?php echo $heading_title; ?></h1>
  </div>
  <div class="panel-body">
    <form method="post" action="<?php echo $action; ?>" id="form-account" class="form-horizontal">
      <div class="form-group">
        <label class="required col-sm-2 control-label" for="input-account-id"><?php echo $entry_account_id; ?></label>
        <div class="col-sm-10">
          <input type="text" name="account_id" value="<?php echo $account_id; ?>" id="input-account-id" class="form-control" placeholder="<?php echo $entry_account_id; ?>" required autofocus />
          <?php if ($error_account_id) { ?>
          <div class="text-danger"><?php echo $error_account_id; ?></div>
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
          <textarea name="description" id="input-description" class="form-control" placeholder="<?php echo $entry_description; ?>"><?php echo $description; ?></textarea>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-type"><?php echo $entry_type; ?></label>
        <div class="col-sm-10">
          <select name="type" id="input-type" class="form-control">
            <optgroup label="<?php echo $text_assets; ?>">
              <option value="current_asset"<?php echo $type == 'current_asset' ? ' selected="selected"' : ''; ?>><?php echo $text_current_asset; ?></option>
              <option value="fixed_asset"<?php echo $type == 'fixed_asset' ? ' selected="selected"' : ''; ?>><?php echo $text_fixed_asset; ?></option>
              <option value="non_current_asset"<?php echo $type == 'non_current_asset' ? ' selected="selected"' : ''; ?>><?php echo $text_non_current_asset; ?></option>
              <option value="prepayment"<?php echo $type == 'prepayment' ? ' selected="selected"' : ''; ?>><?php echo $text_prepayment; ?></option>
            </optgroup>
            <optgroup label="<?php echo $text_equity; ?>">
              <option value="equity"<?php echo $type == 'equity' ? ' selected="selected"' : ''; ?>><?php echo $text_equity; ?></option>
            </optgroup>
            <optgroup label="<?php echo $text_expenses; ?>">
              <option value="depreciation"<?php echo $type == 'depreciation' ? ' selected="selected"' : ''; ?>><?php echo $text_depreciation; ?></option>
              <option value="direct_cost"<?php echo $type == 'direct_cost' ? ' selected="selected"' : ''; ?>><?php echo $text_direct_cost; ?></option>
              <option value="expense"<?php echo $type == 'expense' ? ' selected="selected"' : ''; ?>><?php echo $text_expense; ?></option>
              <option value="overhead"<?php echo $type == 'overhead' ? ' selected="selected"' : ''; ?>><?php echo $text_overhead; ?></option>
            </optgroup>
            <optgroup label="<?php echo $text_liabilities; ?>">
              <option value="current_liability"<?php echo $type == 'current_liability' ? ' selected="selected"' : ''; ?>><?php echo $text_current_liability; ?></option>
              <option value="liability"<?php echo $type == 'liability' ? ' selected="selected"' : ''; ?>><?php echo $text_liability; ?></option>
              <option value="non_current_liability"<?php echo $type == 'non_current_liability' ? ' selected="selected"' : ''; ?>><?php echo $text_non_current_liability; ?></option>
            </optgroup>
            <optgroup label="<?php echo $text_revenue; ?>">
              <option value="other_income"<?php echo $type == 'other_income' ? ' selected="selected"' : ''; ?>><?php echo $text_other_income; ?></option>
              <option value="revenue"<?php echo $type == 'revenue' ? ' selected="selected"' : ''; ?>><?php echo $text_revenue; ?></option>
              <option value="sale"<?php echo $type == 'sale' ? ' selected="selected"' : ''; ?>><?php echo $text_sale; ?></option>
            </optgroup>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-parent"><?php echo $entry_parent; ?></label>
        <div class="col-sm-10">
          <select name="parent_id" id="input-parent" class="form-control"></select>
          <?php if ($error_parent) { ?>
          <div class="text-danger"><?php echo $error_parent; ?></div>
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
<script type="text/javascript"><!--
$(document).ready(function () {
	$('select[name=\'type\']').change(function () {
		$.ajax({
			url: 'index.php?load=accounting/account/getaccounts&token=<?php echo $token; ?>&type=' + $(this).val(),
			dataType: 'json',
			beforeSend: function () {
				$('select[name=\'type\']').after(' <i class="fa fa-spinner fa-spin"></i>');
			},
			complete: function () {
				$('.fa-spinner').remove();
			},
			success: function (json) {
				html = '<option value=""><?php echo $text_none; ?></option>';

				if (json['account']) {
					for (i = 0; i < json['account'].length; i++) {
						html += '<option value="' + json['account'][i]['account_id'] + '"';

						if (json['account'][i]['account_id'] == '<?php echo $parent_id; ?>') {
							html += ' selected="selected"';
						}

						html += '>' + json['account'][i]['name'] + '</option>';
					}
				} else {
					html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
				}

				$('select[name=\'parent_id\']').html(html);
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	$('select[name=\'type\']').trigger('change');
});
//--></script>
<?php echo $footer; ?>