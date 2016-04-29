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
      <button type="submit" form="form-invoice" title="<?php echo $button_save; ?>" data-toggle="tooltip" id="button-save" class="btn btn-success disabled total"><i class="fa fa-save"></i></button>
      <a href="<?php echo $cancel; ?>" title="<?php echo $button_cancel; ?>" data-toggle="tooltip" class="btn btn-danger"><i class="fa fa-times"></i></a>
    </div>
    <h1 class="panel-title"><i class="fa fa-pencil-square"></i> <?php echo $heading_title; ?></h1>
  </div>
  <div class="panel-body">
    <form method="post" action="<?php echo $action; ?>" class="form-horizontal" id="form-invoice">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
        <li class="disabled"><a href="#tab-payment-detail" data-toggle="tab"><?php echo $tab_payment_detail; ?></a></li>
        <li class="disabled"><a href="#tab-payment-method" data-toggle="tab"><?php echo $tab_payment_method; ?></a></li>
        <li class="disabled"><a href="#tab-item" data-toggle="tab"><?php echo $tab_item; ?></a></li>
        <li class="disabled"><a href="#tab-total" data-toggle="tab"><?php echo $tab_total; ?></a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab-general">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-customer"><?php echo $entry_customer; ?></label>
            <div class="col-sm-10">
              <input type="text" name="customer" value="<?php echo $customer; ?>" id="input-customer" class="form-control" placeholder="<?php echo $entry_customer; ?>" autofocus />
              <input type="hidden" name="customer_id" value="<?php echo $customer_id; ?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="required col-sm-2 control-label" for="input-firstname"><?php echo $entry_firstname; ?></label>
            <div class="col-sm-10"><input type="text" name="firstname" value="<?php echo $firstname; ?>" id="input-firstname" class="form-control" placeholder="<?php echo $entry_firstname; ?>" required />
            </div>
          </div>
          <div class="form-group">
            <label class="required col-sm-2 control-label" for="input-lastname"><?php echo $entry_lastname; ?></label>
            <div class="col-sm-10">
              <input type="text" name="lastname" value="<?php echo $lastname; ?>" id="input-lastname" class="form-control" placeholder="<?php echo $entry_lastname; ?>" required />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-company"><?php echo $entry_company; ?></label>
            <div class="col-sm-10">
              <input type="text" name="company" value="<?php echo $company; ?>" id="input-company" class="form-control" placeholder="<?php echo $entry_company; ?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-website"><?php echo $entry_website; ?></label>
            <div class="col-sm-10">
              <input type="text" name="website" value="<?php echo $website; ?>" id="input-website" class="form-control" placeholder="<?php echo $entry_website; ?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="required col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
            <div class="col-sm-10">
              <input type="text" name="email" value="<?php echo $email; ?>" id="input-email" class="form-control" placeholder="<?php echo $entry_email; ?>" required />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-comment"><?php echo $entry_comment; ?></label>
            <div class="col-sm-10">
              <textarea name="comment" id="input-comment" class="form-control" rows="5" placeholder="<?php echo $entry_comment; ?>"><?php echo $comment; ?></textarea>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="status_id" id="input-status" class="form-control">
                <?php foreach ($statuses as $status) { ?>
                <option value="<?php echo $status['status_id']; ?>"<?php echo $status['status_id'] == $status_id ? ' selected="selected"' : ''; ?>><?php echo $status['name']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="required col-sm-2 control-label" for="input-date-due"><?php echo $entry_date_due; ?></label>
            <div class="col-sm-10">
              <div class="input-group">
                <input type="text" name="date_due" value="<?php echo $date_due; ?>" id="input-date-due" class="form-control date" placeholder="<?php echo $entry_date_due; ?>" required />
                <span class="input-group-btn">
                  <button class="btn btn-default" type="button" onclick="$(this).parent().siblings('input').focus();"><i class="fa fa-calendar"></i></button>
                </span>
              </div>
            </div>
          </div>
          <div class="pull-right"><button type="button" id="button-forward-step-1" class="btn btn-primary"><i class="fa fa-arrow-circle-o-right"></i></button></div>
        </div>
        <div class="tab-pane" id="tab-payment-detail">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-payment-firstname"><?php echo $entry_payment_firstname; ?></label>
            <div class="col-sm-10">
              <input type="text" name="payment_firstname" value="<?php echo $payment_firstname; ?>" id="input-payment-firstname" class="form-control" placeholder="<?php echo $entry_payment_firstname; ?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-payment-lastname"><?php echo $entry_payment_lastname; ?></label>
            <div class="col-sm-10">
              <input type="text" name="payment_lastname" value="<?php echo $payment_lastname; ?>" id="input-payment-lastname" class="form-control" placeholder="<?php echo $entry_payment_lastname; ?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-payment-company"><?php echo $entry_payment_company; ?></label>
            <div class="col-sm-10">
              <input type="text" name="payment_company" value="<?php echo $payment_company; ?>" id="input-payment-company" class="form-control" placeholder="<?php echo $entry_payment_company; ?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-payment-address-1"><?php echo $entry_payment_address_1; ?></label>
            <div class="col-sm-10">
              <input type="text" name="payment_address_1" value="<?php echo $payment_address_1; ?>" id="input-payment-address-1" class="form-control" placeholder="<?php echo $entry_payment_address_1; ?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-payment-address-2"><?php echo $entry_payment_address_2; ?></label>
            <div class="col-sm-10">
              <input type="text" name="payment_address_2" value="<?php echo $payment_address_2; ?>" id="input-payment-address-2" class="form-control" placeholder="<?php echo $entry_payment_address_2; ?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-payment-city"><?php echo $entry_payment_city; ?></label>
            <div class="col-sm-10">
              <input type="text" name="payment_city" value="<?php echo $payment_city; ?>" id="input-payment-city" class="form-control" placeholder="<?php echo $entry_payment_city; ?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-payment-postcode"><?php echo $entry_payment_postcode; ?></label>
            <div class="col-sm-10">
              <input type="text" name="payment_postcode" value="<?php echo $payment_postcode; ?>" id="input-payment-postcode" class="form-control" placeholder="<?php echo $entry_payment_postcode; ?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-payment-country"><?php echo $entry_payment_country; ?></label>
            <div class="col-sm-10">
              <input type="text" name="payment_country" value="<?php echo $payment_country; ?>" id="input-payment-country" class="form-control" placeholder="<?php echo $entry_payment_country; ?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-payment-zone"><?php echo $entry_payment_zone; ?></label>
            <div class="col-sm-10">
              <input type="text" name="payment_zone" value="<?php echo $payment_zone; ?>" id="input-payment-zone" class="form-control" placeholder="<?php echo $entry_payment_zone; ?>" />
            </div>
          </div>
          <div class="pull-left"><button type="button" id="button-back-step-2" class="btn btn-primary"><i class="fa fa-arrow-circle-o-left"></i></button></div>
          <div class="pull-right"><button type="button" id="button-forward-step-2" class="btn btn-primary"><i class="fa fa-arrow-circle-o-right"></i></button></div>
        </div>
        <div class="tab-pane" id="tab-payment-method">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-payment-code"><?php echo $entry_payment_code; ?></label>
            <div class="col-sm-10">
              <select name="payment_code" id="input-payment-code" class="form-control">
                <option value=""><?php echo $text_select; ?></option>
                <?php foreach ($payments as $payment) { ?>
                <option value="<?php echo $payment['code']; ?>"<?php echo $payment['code'] == $payment_code ? ' selected="selected"' : ''; ?>><?php echo $payment['name']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="required col-sm-2 control-label" for="input-payment-name"><?php echo $entry_payment_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="payment_name" value="<?php echo $payment_name; ?>" id="input-payment-name" class="form-control" placeholder="<?php echo $entry_payment_name; ?>" required />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-payment-description"><?php echo $entry_payment_description; ?></label>
            <div class="col-sm-10">
              <textarea name="payment_description" id="input-payment-description" class="form-control" rows="5" placeholder="<?php echo $entry_payment_description; ?>"><?php echo $payment_description; ?></textarea>
            </div>
          </div>
          <div class="form-group">
            <label class="required col-sm-2 control-label" for="input-currency-code"><?php echo $entry_currency_code; ?></label>
            <div class="col-sm-10">
              <select name="currency_code" id="input-currency-code" class="form-control">
                <option value=""><?php echo $text_select; ?></option>
                <?php foreach ($currencies as $currency) { ?>
                <option value="<?php echo $currency['code']; ?>" data-value="<?php echo $currency['value']; ?>"<?php echo $currency['code'] == $currency_code ? ' selected="selected"' : ''; ?>><?php echo $currency['title']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="required col-sm-2 control-label" for="input-currency-value"><?php echo $entry_currency_value; ?></label>
            <div class="col-sm-10">
              <input type="text" name="currency_value" value="<?php echo $currency_value; ?>" id="input-currency-value" class="form-control" placeholder="<?php echo $entry_currency_value; ?>" required />
            </div>
          </div>
          <div class="pull-left"><button type="button" id="button-back-step-3" class="btn btn-primary"><i class="fa fa-arrow-circle-o-left"></i></button></div>
          <div class="pull-right"><button type="button" id="button-forward-step-3" class="btn btn-primary"><i class="fa fa-arrow-circle-o-right"></i></button></div>
        </div>
        <div class="tab-pane" id="tab-item">
          <div class="table-responsive">
            <table id="items" class="table table-striped table-bordered table-hover">
              <tr>
                <th class="text-left" style="width:20%;"><?php echo $column_title; ?></th>
                <th class="text-left" style="width:20%;"><?php echo $column_description; ?></th>
                <th class="text-left" style="width:5%;"><?php echo $column_quantity; ?></th>
                <th class="text-right" style="width:15%;"><span data-toggle="tooltip" title="<?php echo $tooltip_price; ?>"><?php echo $column_price; ?> <i class="fa fa-question-circle"></i></span></th>
                <th class="text-right" style="width:20%;"><?php echo $column_tax_class; ?></th>
                <th class="text-right" style="width:15%;"><?php echo $column_discount; ?></th>
                <th class="text-right" style="width:1%;"></th>
              </tr>
              <?php $item_row = 0; ?>
              <?php foreach ($items as $item) { ?>
              <tr>
                <td class="text-left"><input type="text" name="items[<?php echo $item_row; ?>][title]" value="<?php echo $item['title']; ?>" class="form-control" /></td>
                <td class="text-left"><textarea name="items[<?php echo $item_row; ?>][description]" class="form-control" rows="5"><?php echo $item['description']; ?></textarea></td>
                <td class="text-left"><input type="text" name="items[<?php echo $item_row; ?>][quantity]" value="<?php echo $item['quantity']; ?>" class="form-control" /></td>
                <td class="text-right">
                  <div><input type="text" name="items[<?php echo $item_row; ?>][converted_price]" value="<?php echo $item['converted_price']; ?>" class="form-control converted" placeholder="0.00" /> (<span class="invoice-currency"><?php echo $currency_code; ?></span>)</div>
                  <div class="text-center">=</div>
                  <div><input type="text" name="items[<?php echo $item_row; ?>][price]" value="<?php echo $item['price']; ?>" class="form-control actual" placeholder="0.00" /> (<?php echo $default_currency_code; ?>)</div>
                </td>
                <td class="text-right">
                  <select name="items[<?php echo $item_row; ?>][tax_class_id]" class="form-control">
                    <option value="0"><?php echo $text_none; ?></option>
                    <?php foreach ($tax_classes as $tax_class) { ?>
                    <option value="<?php echo $tax_class['tax_class_id']; ?>"<?php echo $item['tax_class_id'] == $tax_class['tax_class_id'] ? ' selected="selected"' : ''; ?>><?php echo $tax_class['name']; ?></option>
                    <?php } ?>
                  </select>
                  <input type="hidden" name="items[<?php echo $item_row; ?>][tax]" value="<?php echo $item['tax']; ?>" />
                </td>
                <td class="text-right">
                  <div><input type="text" name="items[<?php echo $item_row; ?>][converted_discount]" value="<?php echo $item['converted_discount']; ?>" class="form-control converted" placeholder="0.00" /> (<span class="invoice-currency"><?php echo $currency_code; ?></span>)</div>
                  <div class="text-center">=</div>
                  <div><input type="text" name="items[<?php echo $item_row; ?>][discount]" value="<?php echo $item['discount']; ?>" class="form-control actual" placeholder="0.00" /> (<?php echo $default_currency_code; ?>)</div>
                </td>
                <td class="text-right"><button title="<?php echo $button_remove; ?>" data-toggle="tooltip" class="btn btn-danger btn-xs" onclick="$(this).parents('tr').remove();
                        $('.tooltip').remove();"><i class="fa fa-minus-circle"></i></button></td>
              </tr>
              <?php $item_row++; ?>
              <?php } ?>
              <tr>
                <td class="text-right" colspan="7"><button type="button" title="<?php echo $button_add; ?>" data-toggle="tooltip" class="btn btn-success btn-xs" onclick="addRow();"><i class="fa fa-plus-circle"></i></button></td>
              </tr>
            </table>
          </div>
          <div class="pull-left"><button type="button" id="button-back-step-4" class="btn btn-primary"><i class="fa fa-arrow-circle-o-left"></i></button></div>
          <div class="pull-right"><button type="button" id="button-forward-step-4" class="btn btn-primary"><?php echo $button_calculate; ?> <i class="fa fa-arrow-circle-o-right"></i></button></div>
        </div>
        <div class="tab-pane" id="tab-total">
          <div class="table-responsive">
            <table id="totals" class="table table-striped table-bordered table-hover">
            </table>
          </div>
          <div class="pull-left"><button type="button" id="button-back-step-5" class="btn btn-primary"><i class="fa fa-arrow-circle-o-left"></i></button></div>
        </div>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript"><!--
var currency_value = '<?php echo $currency_value; ?>';
var currency_code = '<?php echo $currency_code; ?>';

$(document).ready(function () {
	$('.date').datetimepicker({
		format: 'YYYY-MM-DD'
	});

	$('#input-customer').autocomplete({
		'source': function (request, response) {
			$.ajax({
				url: 'index.php?load=billing/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
				dataType: 'json',
				success: function (json) {
					response($.map(json, function (item) {
						return {
							label: item['name'] + ' (' + item['email'] + ')',
							value: item['customer_id'],
							name: item['name'],
							firstname: item['firstname'],
							lastname: item['lastname'],
							company: item['company'],
							website: item['website'],
							email: item['email']
						}
					}));
				}
			});
		},
		'select': function (item) {
			$('#input-customer').val(item['name']);
			$('#input-firstname').val(item['firstname']);
			$('#input-lastname').val(item['lastname']);
			$('#input-company').val(item['company']);
			$('#input-website').val(item['website']);
			$('#input-email').val(item['email']);
			$('#input-payment-firstname').val(item['firstname']);
			$('#input-payment-lastname').val(item['lastname']);
			$('#input-payment-company').val(item['company']);
			$('input[name=\'customer_id\']').val(item['value']);
		}
	});

	$('#input-currency-code').change(function () {
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

				currency_value = currency['value'];
				currency_code = currency['code'];

				$('.invoice-currency').html(currency['code']);

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

		$('#items input.converted').each(function () {
			$(this).trigger('keyup');
		});
	});

	$(document).on('keyup', '#items input', function () {
		if ($(this).hasClass('converted') || $(this).hasClass('actual')) {
			var converted_value = 0;

			value = $(this).val() ? $(this).val() : 0;

			value = value.toString();

			if (value.match(/^\(.+\)$/)) {
				value = '-' + value;
			}

			value = value.replace(/[^\d.-]/g, '');

			if (value == '') {
				value = 0;
			}

			if ($(this).hasClass('converted')) {
				converted_value = value / currency_value;
			} else if ($(this).hasClass('actual')) {
				converted_value = value * currency_value;
			}

			$(this).parent().siblings().children('input').val(converted_value.toFixed(4));

			$('a[href=\'#tab-total\']').parent().addClass('disabled');
		}
	});

	$('#button-forward-step-1').click(function () {
		$.ajax({
			url: 'index.php?load=billing/invoice/validate_step_1&token=<?php echo $token; ?>',
			data: $('#tab-general input, #tab-general select'),
			type: 'post',
			dataType: 'json',
			beforeSend: function () {
				$('.text-danger, .alert').remove();

				$('#button-forward-step-1').button('loading');
			},
			complete: function () {
				$('#button-forward-step-1').button('reset');
			},
			success: function (json) {
				if (json['success']) {
					$('a[href=\'#tab-payment-detail\']').parent().removeClass('disabled');
					$('a[href=\'#tab-payment-detail\']').trigger('click');

					$('a[href=\'#tab-general\']').parent().addClass('disabled');

					$('html, body').animate({scrollTop: 0}, 800);
				} else {
					if (json['warning']) {
						$('.breadcrumb').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}

					if (json['firstname']) {
						$('#tab-general input[name=\'firstname\']').after('<span class="text-danger">' + json['firstname'] + '</span>');
					}

					if (json['lastname']) {
						$('#tab-general input[name=\'lastname\']').after('<span class="text-danger">' + json['lastname'] + '</span>');
					}

					if (json['email']) {
						$('#tab-general input[name=\'email\']').after('<span class="text-danger">' + json['email'] + '</span>');
					}

					if (json['date_due']) {
						$('#tab-general input[name=\'date_due\']').after('<span class="text-danger">' + json['date_due'] + '</span>');
					}
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	$('#button-forward-step-2').click(function () {
		$('a[href=\'#tab-payment-method\']').parent().removeClass('disabled');
		$('a[href=\'#tab-payment-method\']').trigger('click');

		$('a[href=\'#tab-payment-detail\']').parent().addClass('disabled');

		$('html, body').animate({scrollTop : 0}, 800);
	});

	$('#button-forward-step-3').click(function () {
		$.ajax({
			url: 'index.php?load=billing/invoice/validate_step_3&token=<?php echo $token; ?>',
			data: $('#tab-payment-method input, #tab-payment-method select'),
			type: 'post',
			dataType: 'json',
			beforeSend: function () {
				$('.text-danger, .alert').remove();

				$('#button-forward-step-3').button('loading');
			},
			complete: function () {
				$('#button-forward-step-3').button('reset');
			},
			success: function (json) {
				if (json['success']) {
					$('a[href=\'#tab-item\']').parent().removeClass('disabled');
					$('a[href=\'#tab-item\']').trigger('click');

					$('a[href=\'#tab-payment-method\']').parent().addClass('disabled');

					$('html, body').animate({scrollTop : 0}, 800);
				} else {
					if (json['warning']) {
						$('.breadcrumb').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
					}

					if (json['payment_name']) {
						$('#tab-payment-method input[name=\'payment_name\']').after('<span class="text-danger">' + json['payment_name'] + '</span>');
					}

					if (json['currency_code']) {
						$('#tab-payment-method select[name=\'currency_code\']').after('<span class="text-danger">' + json['currency_code'] + '</span>');
					}

					if (json['currency_value']) {
						$('#tab-payment-method input[name=\'currency_value\']').after('<span class="text-danger">' + json['currency_value'] + '</span>');
					}
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	$('#button-forward-step-4').click(function () {
		$.ajax({
			url: 'index.php?load=billing/invoice/validate_step_4&token=<?php echo $token; ?>',
			type: 'post',
			data: $('#form-invoice input, #form-invoice textarea, #form-invoice select'),
			dataType: 'json',
			beforeSend: function () {
				$('#button-forward-step-4').button('loading');
			},
			complete: function () {
				$('#button-forward-step-4').button('reset');
			},
			success: function (json) {
				if (json['totals']) {
					html = '<tr>';
					html += '  <th class="text-left" style="width:10%;"><?php echo $column_number; ?>';
					html += '  <th class="text-left" style="width:30%;"><?php echo $column_description; ?>';
					html += '  <th class="text-left" style="width:10%;"><?php echo $column_quantity; ?>';
					html += '  <th class="text-right" style="width:20%;"><?php echo $column_price; ?></th>';
					html += '  <th class="text-right" style="width:10%;"><?php echo $column_discount; ?></th>';
					html += '  <th class="text-right" style="width:20%;"><?php echo $column_total; ?></th>';
					html += '</tr>';

					for (i = 0; i < json['items'].length; i++) {
						html += '<tr>';
						html += '  <td class="text-left">' + json['items'][i]['number'] + '</td>';
						html += '  <td class="text-left"><b>' + json['items'][i]['title'] + '</b><br />' + json['items'][i]['description'] + '</td>';
						html += '  <td class="text-left">' + json['items'][i]['quantity'] + '</td>';
						html += '  <td class="text-right">' + json['items'][i]['price'] + '</td>';
						html += '  <td class="text-right">' + json['items'][i]['discount'] + '</td>';
						html += '  <td class="text-right">' + json['items'][i]['total'] + '</td>';
						html += '</tr>';

						$('input[name=\'items[' + json['items'][i]['key'] + '][tax]\']').val(json['items'][i]['tax']);
					}

					for (i = 0; i < json['totals'].length; i++) {
						html += '<tr>';
						html += '  <td class="text-right" colspan="5"><b>' + json['totals'][i]['title'] + '</b></td>';
						html += '  <td class="text-right">' + json['totals'][i]['text'] + '</td>';
						html += '</tr>';
						html += '<input type="hidden" name="totals[' + i + '][code]" value="' + json['totals'][i]['code'] + '" />';
						html += '<input type="hidden" name="totals[' + i + '][title]" value="' + json['totals'][i]['title'] + '" />';
						html += '<input type="hidden" name="totals[' + i + '][value]" value="' + json['totals'][i]['value'] + '" />';
						html += '<input type="hidden" name="totals[' + i + '][sort_order]" value="' + json['totals'][i]['sort_order'] + '" />';

						total = json['totals'][i]['value'];
					}

					html += '<input type="hidden" name="total" value="' + total + '" />';

					$('#totals').html(html);

					$('a[href=\'#tab-total\']').parent().removeClass('disabled');
					$('a[href=\'#tab-total\']').trigger('click');

					$('a[href=\'#tab-item\']').parent().addClass('disabled');

					$('html, body').animate({scrollTop : 0}, 800);

					$('#button-save').removeClass('disabled');
				} else {
					alert('<?php echo $error_item; ?>');
				}
			}
		});
	});

	$('#button-back-step-2').click(function () {
		$('a[href=\'#tab-general\']').parent().removeClass('disabled');
		$('a[href=\'#tab-general\']').trigger('click');

		$('a[href=\'#tab-payment-detail\']').parent().addClass('disabled');

		$('html, body').animate({scrollTop : 0}, 800);
	});

	$('#button-back-step-3').click(function () {
		$('a[href=\'#tab-payment-detail\']').parent().removeClass('disabled');
		$('a[href=\'#tab-payment-detail\']').trigger('click');

		$('a[href=\'#tab-payment-method\']').parent().addClass('disabled');

		$('html, body').animate({scrollTop : 0}, 800);
	});

	$('#button-back-step-4').click(function () {
		$('a[href=\'#tab-payment-method\']').parent().removeClass('disabled');
		$('a[href=\'#tab-payment-method\']').trigger('click');

		$('a[href=\'#tab-item\']').parent().addClass('disabled');

		$('html, body').animate({scrollTop : 0}, 800);
	});

	$('#button-back-step-5').click(function () {
		$('a[href=\'#tab-item\']').parent().removeClass('disabled');
		$('a[href=\'#tab-item\']').trigger('click');

		$('a[href=\'#tab-total\']').parent().addClass('disabled');
		$('#button-save').addClass('disabled');

		$('html, body').animate({scrollTop : 0}, 800);
	});
});

var item_row = <?php echo $item_row; ?>;

function addRow() {
	html = '';
	html += '<tr>';
	html += '  <td><input type="text" name="items[' + item_row + '][title]" value="" class="form-control" /></td>';
	html += '  <td><textarea name="items[' + item_row + '][description]" class="form-control" rows="5"></textarea></td>';
	html += '  <td><input type="text" name="items[' + item_row + '][quantity]" value="1" class="form-control" /></td>';
	html += '  <td class="text-right">';
	html += '    <div><input type="text" name="items[' + item_row + '][converted_price]" value="" class="form-control converted" placeholder="0.00" /> (<span class="invoice-currency">' + currency_code + '</span>)</div>';
	html += '    <div class="text-center">=</div>';
	html += '    <div><input type="text" name="items[' + item_row + '][price]" value="" class="form-control actual" placeholder="0.00" /> (<?php echo $default_currency_code; ?>)</div>';
	html += '  </td>';
	html += '  <td class="text-right"><select name="items[' + item_row + '][tax_class_id]" class="form-control">';
	html += '    <option value="0"><?php echo $text_none; ?></option>';
	<?php foreach ($tax_classes as $tax_class) { ?>
	html += '    <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['name']; ?></option>';
	<?php } ?>
	html += '  </select><input type="hidden" name="items[' + item_row + '][tax]" /></td>';
	html += '  <td class="text-right">';
	html += '    <div><input type="text" name="items[' + item_row + '][converted_discount]" value="" class="form-control converted" placeholder="0.00" /> (<span class="invoice-currency">' + currency_code + '</span>)</div>';
	html += '    <div class="text-center">=</div>';
	html += '    <div><input type="text" name="items[' + item_row + '][discount]" value="" class="form-control actual" placeholder="0.00" /> (<?php echo $default_currency_code; ?>)</div>';
	html += '  </td>';
	html += '  <td class="text-right"><button title="<?php echo $button_remove; ?>" data-toggle="tooltip" class="btn btn-danger btn-xs" onclick="$(this).parents(\'tr\').remove(); $(\'.tooltip\').remove();"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';

	$('#items tr:last').before(html);

	$('[data-toggle=\'tooltip\']').tooltip();
	
	itemAutocomplete(item_row);

	item_row++;
}

<?php $item_row = 0; ?>
<?php foreach ($items as $item) { ?>
	itemAutocomplete('<?php echo $item_row; ?>');
	<?php $item_row++; ?>
<?php } ?>

function itemAutocomplete(item_row) {
	$('input[name=\'items[' + item_row + '][title]\']').autocomplete({
		'source': function (request, response) {
			$.ajax({
				url: 'index.php?load=accounting/inventory/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
				dataType: 'json',
				success: function (json) {
					response($.map(json, function (item) {
						return {
							label: item['name'] + ' (' + item['sku'] + ')',
							value: item['name'],
							sell: item['sell']
						}
					}));
				}
			});
		},
		'select': function (item) {
			$('input[name=\'items[' + item_row + '][title]\']').val(item['label']);
			$('input[name=\'items[' + item_row + '][price]\']').val(item['sell']).trigger('keyup');
		}
	});
}
//--></script>
<?php echo $footer; ?>