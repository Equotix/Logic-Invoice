<?php echo $header; ?>
<div class="header">
  <div class="container">
    <h1><?php echo $text_invoice_payment; ?></h1>
    <p><?php echo $text_invoice_info; ?></p>
  </div>
</div>
<div id="content" class="container">
  <ol class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ol>
  <div class="row">
    <div class="col-lg-12">
      <h3><?php echo $text_item; ?></h3>
	  <div class="table-responsive">
        <table class="table table-striped table-bordered">
          <tr>
            <th class="text-left" style="width:10%;"><?php echo $column_number; ?></th>
            <th class="text-left" style="width:30%;"><?php echo $column_description; ?></th>
            <th class="text-left" style="width:10%;"><?php echo $column_quantity; ?></th>
            <th class="text-right" style="width:20%;"><?php echo $column_price; ?></th>
            <th class="text-right" style="width:10%;"><?php echo $column_discount; ?></th>
            <th class="text-right" style="width:20%;"><?php echo $column_total; ?></th>
          </tr>
          <?php foreach ($items as $item) { ?>
          <tr>
            <td class="text-left"><?php echo $item['number']; ?></td>
            <td class="text-left">
              <b><?php echo $item['title']; ?></b><br />
              <?php echo $item['description']; ?>
            </td>
            <td class="text-left"><?php echo $item['quantity']; ?></td>
            <td class="text-right"><?php echo $item['price']; ?></td>
            <td class="text-right"><?php echo $item['discount']; ?></td>
            <td class="text-right"><?php echo $item['total']; ?></td>
          </tr>
          <?php } ?>
          <?php foreach ($totals as $total) { ?>
          <tr>
            <td class="text-right" colspan="5"><b><?php echo $total['title']; ?></b></td>
            <td class="text-right"><?php echo $total['text']; ?></td>
          </tr>
          <?php } ?>
        </table>
	  </div>
    </div>
    <div class="col-lg-12">
      <h3><?php echo $text_payment; ?></h3>
      <table class="table table-striped table-bordered">
        <tr>
          <th class="text-left"></th>
          <th class="text-left"><?php echo $column_description; ?></th>
        </tr>
        <tr>
          <td class="text-left"><?php echo $payment_name; ?></td>
          <td class="text-left"><?php echo $payment_description; ?></td>
        </tr>
      </table>
    </div>
    <div class="col-lg-6">
      <h3><?php echo $text_payment_method; ?></h3>
      <table class="table table-striped table-bordered">
        <?php if ($payments) { ?>
        <?php foreach ($payments as $payment) { ?>
        <tr>
          <td class="text-left">
            <div class="radio">
              <label><input type="radio" name="payment_method" value="<?php echo $payment['code']; ?>" /> <?php echo $payment['name']; ?></label>
            </div>
          </td>
        </tr>
        <?php } ?>
        <?php } else { ?>
        <tr>
          <td class="text-left"><div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $text_no_payment; ?></div></td>
        </tr>
        <?php } ?>
      </table>
    </div>
    <div id="payment" class="col-lg-6"></div>
  </div>
</div>
<script type="text/javascript"><!--
$(document).ready(function () {
	$('input[name=\'payment_method\']').change(function () {
		payment_code = $(this).val();

		$.ajax({
			url: 'index.php?load=payment/' + payment_code + '/' + payment_code + '&invoice_id=<?php echo $invoice_id; ?>',
			type: 'get',
			dataType: 'html',
			beforeSend: function () {
				$('#payment').html(' <i class="fa fa-spinner fa-spin"></i>');
			},
			complete: function () {
				$('.fa-spinner').remove();
			},
			success: function (html) {
				$('#payment').html(html);
			}
		});
	});

	$('input[name=\'payment_method\']:checked').trigger('change');
	
	var selected = false;
	
	$('input[name=\'payment_method\']').each(function() {
		if ($(this).is(':checked')) {
			selected = true;
		}
	});
	
	if (selected == false) {
		$('input[name=\'payment_method\']').first().prop('checked', true).trigger('change');
	}
});
//--></script>
<?php echo $footer; ?>