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
      <button type="submit" form="form-journal" title="<?php echo $button_save; ?>" data-toggle="tooltip" class="btn btn-success">
        <i class="fa fa-save"></i></button>
      <a href="<?php echo $cancel; ?>" title="<?php echo $button_cancel; ?>" data-toggle="tooltip" class="btn btn-danger"><i class="fa fa-times"></i></a>
    </div>
    <h1 class="panel-title"><i class="fa fa-pencil-square fa-lg"></i> <?php echo $heading_title; ?></h1>
  </div>
  <div class="panel-body">
    <form method="post" action="<?php echo $action; ?>" id="form-journal" class="form-horizontal">
      <div class="form-group">
        <label class="required col-sm-2 control-label" for="input-description"><?php echo $entry_description; ?></label>

        <div class="col-sm-10">
          <textarea name="description" id="input-description" class="form-control" rows="5" placeholder="<?php echo $entry_description; ?>" required autofocus><?php echo $description; ?></textarea>
          <?php if ($error_description) { ?>
          <div class="text-danger"><?php echo $error_description; ?></div>
          <?php } ?>
        </div>
      </div>
      <div class="form-group">
        <label class="required col-sm-2 control-label" for="input-currency-code"><?php echo $entry_currency; ?></label>

        <div class="col-sm-10">
          <select name="currency_code" id="input-currency-code" class="form-control"><?php foreach ($currencies as $currency) { ?>
            <option value="<?php echo $currency['code']; ?>"<?php echo $currency_code == $currency['code'] ? ' selected="selected"' : ''; ?>><?php echo $currency['title']; ?></option>
            <?php } ?></select>
        </div>
      </div>
      <div class="form-group">
        <label class="required col-sm-2 control-label" for="input-currency-value"><?php echo $entry_currency_value; ?></label>

        <div class="col-sm-10">
          <input type="text" name="currency_value" value="<?php echo $currency_value; ?>" id="input-currency-value" class="form-control" placeholder="<?php echo $entry_currency_value; ?>" required />
          <?php if ($error_currency_value) { ?>
          <div class="text-danger"><?php echo $error_currency_value; ?></div>
          <?php } ?>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-invoice-id"><?php echo $entry_invoice_id; ?></label>

        <div class="col-sm-10">
          <input type="text" name="invoice_id" value="<?php echo $invoice_id; ?>" id="input-invoice-id" class="form-control" placeholder="<?php echo $entry_invoice_id; ?>" />
        </div>
      </div>
      <div class="form-group">
        <label class="required col-sm-2 control-label" for="input-date"><?php echo $entry_date; ?></label>

        <div class="col-sm-10">
          <div class="input-group">
            <input type="text" name="date" value="<?php echo $date; ?>" id="input-date" class="form-control date" placeholder="<?php echo $entry_date; ?>" required />
            <span class="input-group-btn">
              <button class="btn btn-default" type="button" onclick="$(this).parent().siblings('input').focus();"><i class="fa fa-calendar"></i></button>
            </span></div>
          <?php if ($error_date) { ?>
          <div class="text-danger"><?php echo $error_date; ?></div>
          <?php } ?>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
          <tr>
            <th class="text-left"><?php echo $column_account; ?></th>
            <th class="text-right"><?php echo $column_debit; ?></th>
            <th class="text-right"><?php echo $column_credit; ?></th>
            <th class="text-right"></th>
          </tr>
          <?php $transaction_row = 0; ?>
          <?php foreach ($transaction_accounts as $transaction_account) { ?>
          <tr>
            <td class="text-left"><select name="transaction_accounts[<?php echo $transaction_row; ?>][account_id]" class="form-control">
                <optgroup>
                  <?php $header = ''; ?>
                  <?php foreach ($accounts as $account) { ?>
                  <?php if (in_array($account['type'], $asset) && $header != 'asset') { ?>
                </optgroup><optgroup label="<?php echo $text_assets; ?>">
                  <?php $header = 'asset'; ?>
                  <?php } ?>
                  <?php if (in_array($account['type'], $equity) && $header != 'equity') { ?>
                </optgroup><optgroup label="<?php echo $text_equity; ?>">
                  <?php $header = 'equity'; ?>
                  <?php } ?>
                  <?php if (in_array($account['type'], $expense) && $header != 'expense') { ?>
                </optgroup><optgroup label="<?php echo $text_expenses; ?>">
                  <?php $header = 'expense'; ?>
                  <?php } ?>
                  <?php if (in_array($account['type'], $liability) && $header != 'liability') { ?>
                </optgroup><optgroup label="<?php echo $text_liabilities; ?>">
                  <?php $header = 'liability'; ?>
                  <?php } ?>
                  <?php if (in_array($account['type'], $revenue) && $header != 'revenue') { ?>
                </optgroup><optgroup label="<?php echo $text_revenue; ?>">
                  <?php $header = 'revenue'; ?>
                  <?php } ?>
                  <option value="<?php echo $account['account_id']; ?>"<?php echo $account['account_id'] == $transaction_account['account_id'] ? ' selected="selected"' : ''; ?><?php echo $account['children'] ? ' disabled' : ''; ?>><?php echo $account['name']; ?></option>
                  <?php foreach ($account['children'] as $child) { ?>
                  <option value="<?php echo $child['account_id']; ?>"<?php echo $child['account_id'] == $transaction_account['account_id'] ? ' selected="selected"' : ''; ?><?php echo $child['grandchildren'] ? ' disabled' : ''; ?>><?php echo $child['name']; ?></option>
                  <?php foreach ($child['grandchildren'] as $grandchild) { ?>
                  <option value="<?php echo $grandchild['account_id']; ?>"<?php echo $grandchild['account_id'] == $transaction_account['account_id'] ? ' selected="selected"' : ''; ?>><?php echo $grandchild['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                  <?php } ?>
                </optgroup>
              </select></td>
            <td class="text-right">
              <div class="col-sm-5">
                <input type="text" name="transaction_accounts[<?php echo $transaction_row; ?>][converted_debit]" value="<?php echo $transaction_account['converted_debit']; ?>" class="form-control converted" placeholder="-" /> (<span class="transaction-currency"><?php echo $currency_code; ?></span>)
              </div>
              <div class="col-sm-2 text-center">
                =
              </div>
              <div class="col-sm-5">
                <input type="text" name="transaction_accounts[<?php echo $transaction_row; ?>][debit]" value="<?php echo $transaction_account['debit']; ?>" class="form-control debit" placeholder="-" /> (<?php echo $default_currency_code; ?>)
              </div>
            </td>
            <td class="text-right">
              <div class="col-sm-5">
                <input type="text" name="transaction_accounts[<?php echo $transaction_row; ?>][converted_credit]" value="<?php echo $transaction_account['converted_credit']; ?>" class="form-control converted" placeholder="-" /> (<span class="transaction-currency"><?php echo $currency_code; ?></span>)
              </div>
              <div class="col-sm-2 text-center">
                =
              </div>
              <div class="col-sm-5">
                <input type="text" name="transaction_accounts[<?php echo $transaction_row; ?>][credit]" value="<?php echo $transaction_account['credit']; ?>" class="form-control credit" placeholder="-" /> (<?php echo $default_currency_code; ?>)
              </div>
            </td>
            <td class="text-right"><button type="button" title="<?php echo $button_remove; ?>" data-toggle="tooltip" class="btn btn-danger btn-xs" onclick="$(this).parents('tr').remove();"><i class="fa fa-minus-circle"></i></button></td>
          </tr>
          <?php $transaction_row++; ?>
          <?php } ?>
          <tr id="transaction-add">
            <td colspan="4" class="text-right"><button type="button" title="<?php echo $button_add; ?>" data-toggle="tooltip" class="btn btn-success btn-xs" onclick="addTransaction();"><i class="fa fa-plus-circle"></i></button></td>
          </tr>
          <tr>
            <th class="text-left"><?php echo $text_total; ?></th>
            <td class="text-right"><span id="total-debit"></span> (<?php echo $default_currency_code; ?>)</td>
            <td class="text-right"><span id="total-credit"></span> (<?php echo $default_currency_code; ?>)</td>
            <td class="text-right"></td>
          </tr>
        </table>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript"><!--
var currency_code = '<?php echo $currency_code; ?>';
var currency_value = '<?php echo $currency_value; ?>';
var transaction_row = <?php echo $transaction_row; ?> ;

function addTransaction() {
	var html = '';

	html += '<tr>';
	html += '  <td class="text-left"><select name="transaction_accounts[' + transaction_row + '][account_id]" class="form-control">';
	html += '<optgroup>';
	<?php $header = ''; ?>
	<?php foreach ($accounts as $account) { ?>
	<?php if (in_array($account['type'], $asset) && $header != 'asset') { ?>
	html += '</optgroup><optgroup label="<?php echo $text_assets; ?>">';
	<?php $header = 'asset'; ?>
	<?php } ?>
	<?php if (in_array($account['type'], $equity) && $header != 'equity') { ?>
	html += '</optgroup><optgroup label="<?php echo $text_equity; ?>">';
	<?php $header = 'equity'; ?>
	<?php } ?>
	<?php if (in_array($account['type'], $expense) && $header != 'expense') { ?>
	html += '</optgroup><optgroup label="<?php echo $text_expenses; ?>">';
	<?php $header = 'expense'; ?>
	<?php } ?>
	<?php if (in_array($account['type'], $liability) && $header != 'liability') { ?>
	html += '</optgroup><optgroup label="<?php echo $text_liabilities; ?>">';
	<?php $header = 'liability'; ?>
	<?php } ?>
	<?php if (in_array($account['type'], $revenue) && $header != 'revenue') { ?>
	html += '</optgroup><optgroup label="<?php echo $text_revenue; ?>">';
	<?php $header = 'revenue'; ?>
	<?php } ?>
	html += '<option value="<?php echo $account['account_id']; ?>"<?php echo $account['children'] ? ' disabled' : ''; ?>><?php echo $account['name']; ?></option>';
	<?php foreach ($account['children'] as $child) { ?>
	html += '<option value="<?php echo $child['account_id']; ?>"<?php echo $child['grandchildren'] ? ' disabled' : ''; ?>><?php echo $child['name']; ?></option>';
	<?php foreach ($child['grandchildren'] as $grandchild) { ?>
	html += '<option value="<?php echo $grandchild['account_id']; ?>"><?php echo $grandchild['name']; ?></option>';
	<?php } ?>
	<?php } ?>
	<?php } ?>
	html += '</optgroup>';
	html += '</select></td>';
	html += '  <td class="text-right">';
	html += '    <div class="col-sm-5">';
	html += '      <input type="text" name="transaction_accounts[' + transaction_row + '][converted_debit]" value="" class="form-control converted" placeholder="-" /> (<span class="transaction-currency">' + currency_code + '</span>)';
	html += '    </div>';
	html += '    <div class="col-sm-2 text-center">';
	html += '      =';
	html += '    </div>';
	html += '    <div class="col-sm-5">';
	html += '      <input type="text" name="transaction_accounts[' + transaction_row + '][debit]" value="" class="form-control debit" placeholder="-" /> (<?php echo $default_currency_code; ?>)';
	html += '    </div></td>';
	html += '  <td class="text-right">';
	html += '    <div class="col-sm-5">';
	html += '      <input type="text" name="transaction_accounts[' + transaction_row + '][converted_credit]" value="" class="form-control converted" placeholder="-" /> (<span class="transaction-currency">' + currency_code + '</span>)';
	html += '    </div>';
	html += '    <div class="col-sm-2 text-center">';
	html += '      =';
	html += '    </div>';
	html += '    <div class="col-sm-5">';
	html += '      <input type="text" name="transaction_accounts[' + transaction_row + '][credit]" value="" class="form-control credit" placeholder="-" /> (<?php echo $default_currency_code; ?>)';
	html += '    </div></td>';
	html += '  <td class="text-right"><button type="button" title="<?php echo $button_remove; ?>" data-toggle="tooltip" class="btn btn-danger btn-xs" onclick="$(this).parents(\'tr\').remove();"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';

	$('#transaction-add').before(html);

	$('[data-toggle=\'tooltip\']').tooltip();

	transaction_row++;
}

$(document).ready(function () {
	$('.date').datetimepicker({
		format: 'YYYY-MM-DD'
	});

	$(document).on('keyup', '.table input', function () {
		var converted_value = 0;

		value = $(this).val() ? $(this).val() : 0;

		value = value.toString();

		if (value.match(/^\(.+\)$/)) {
			value = '-' + value;
		}

		value = value.replace(/[^\d.-]/g, '');

		if ($(this).hasClass('converted')) {
			converted_value = value / currency_value;
		} else {
			converted_value = value * currency_value;
		}

		$(this).parent().siblings().children('input').val(converted_value.toFixed(4));

		var debit = 0;
		var credit = 0;

		$('.table input').each(function () {
			value = $(this).val() ? $(this).val() : 0;

			value = value.toString();

			if (value.match(/^\(.+\)$/)) {
				value = '-' + value;
			}

			value = value.replace(/[^\d.-]/g, '');

			if (value == '') {
				value = 0;
			}

			if ($(this).hasClass('debit')) {
				debit = parseFloat(debit) + parseFloat(value);
			} else if ($(this).hasClass('credit')) {
				credit = parseFloat(credit) + parseFloat(value);
			}
		});

		$('#total-debit').html(debit.toFixed(4));
		$('#total-credit').html(credit.toFixed(4));
	});

	$('.table input:first').trigger('keyup');

	$('select[name=\'currency_code\']').change(function () {
		$.ajax({
			url: 'index.php?load=accounting/currency/currency&token=<?php echo $token; ?>&code=' + $(this).val(),
			dataType: 'json',
			beforeSend: function () {
				$('select[name=\'currency_code\']').after(' <i class="fa fa-spinner fa-spin"></i>');
			},
			complete: function () {
				$('.fa-spinner').remove();
			},
			success: function (currency) {
				$('input[name=\'currency_value\']').val(currency['value']);

				currency_code = currency['code'];

				currency_value = currency['value'];

				$('.transaction-currency').html(currency['code']);

				$('.table input.converted').each(function () {
					$(this).trigger('keyup');
				});
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	$('input[name=\'currency_value\']').on('keyup', function () {
		currency_value = $(this).val();

		$('.table input.converted').each(function () {
			$(this).trigger('keyup');
		});
	});
});
//--></script>
<?php echo $footer; ?>