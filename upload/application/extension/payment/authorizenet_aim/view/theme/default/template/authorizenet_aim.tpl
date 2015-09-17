<h3><?php echo $heading_title; ?></h3>
<form id="payment" class="form-horizontal">
  <fieldset>
    <div class="form-group required">
      <label class="col-sm-4 control-label" for="input-cc-owner"><?php echo $entry_cc_owner; ?></label>
      <div class="col-sm-8">
        <input type="text" name="cc_owner" value="" placeholder="<?php echo $entry_cc_owner; ?>" id="input-cc-owner" class="form-control" />
      </div>
    </div>
    <div class="form-group required">
      <label class="col-sm-4 control-label" for="input-cc-number"><?php echo $entry_cc_number; ?></label>
      <div class="col-sm-8">
        <input type="text" name="cc_number" value="" placeholder="<?php echo $entry_cc_number; ?>" id="input-cc-number" class="form-control" />
      </div>
    </div>
    <div class="form-group required">
      <label class="col-sm-4 control-label" for="input-cc-expire-date"><?php echo $entry_cc_expire_date; ?></label>
      <div class="col-sm-4">
        <select name="cc_expire_date_month" id="input-cc-expire-date" class="form-control">
          <?php foreach ($months as $month) { ?>
          <option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
          <?php } ?>
        </select>
       </div>
       <div class="col-sm-4">
        <select name="cc_expire_date_year" class="form-control">
          <?php foreach ($year_expire as $year) { ?>
          <option value="<?php echo $year['value']; ?>"><?php echo $year['text']; ?></option>
          <?php } ?>
        </select>
      </div>
    </div>
    <div class="form-group required">
      <label class="col-sm-4 control-label" for="input-cc-cvv2"><?php echo $entry_cc_cvv2; ?></label>
      <div class="col-sm-8">
        <input type="text" name="cc_cvv2" value="" placeholder="<?php echo $entry_cc_cvv2; ?>" id="input-cc-cvv2" class="form-control" />
      </div>
    </div>
  </fieldset>
</form>
<table class="table table-striped table-bordered">
  <tr>
    <td class="text-right">
	  <button type="button" id="button-payment" class="btn btn-primary"><?php echo $button_make_payment; ?></button>
    </td>
  </tr>
</table>
<script type="text/javascript"><!--
$('#button-payment').on('click', function() {
	$.ajax({
		url: 'index.php?load=payment/authorizenet_aim/authorizenet_aim/confirm&invoice_id=<?php echo $invoice_id; ?>',
		type: 'post',
		data: $('#payment :input'),
		dataType: 'json',
		cache: false,
		beforeSend: function() {
			$('#button-payment').button('loading');
		},
		complete: function() {
			$('#button-payment').button('reset');
		},
		success: function(json) {
			if (json['error']) {
				alert(json['error']);
			}

			if (json['redirect']) {
				location = json['redirect'];
			}
		}
	});
});
//--></script>