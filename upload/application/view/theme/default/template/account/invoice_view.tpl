<?php echo $header; ?>
<div class="header">
  <div class="container">
    <h1><?php echo $text_invoice; ?> - <?php echo $date_issued; ?></h1>
    <p><?php echo $status; ?> - <?php echo $date_due; ?></p>
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
    <div id="history" class="col-lg-12"></div>
    <?php if ($payment_url) { ?>
    <div class="col-lg-12 text-right">
      <a href="<?php echo $payment_url; ?>" class="btn btn-primary btn-lg"><?php echo $button_make_payment; ?></a>
    </div>
    <?php } ?>
  </div>
</div>
<script type="text/javascript"><!--
function history(page) {
	$.ajax({
		url: 'index.php?load=account/invoice/history&invoice_id=<?php echo $invoice_id; ?>&page=' + page,
		dataType: 'json',
		beforeSend: function () {
			$('#history').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-4x"></i></div>');
		},
		complete: function () {
			$('.fa-spinner').parent().remove();
		},
		success: function (json) {
			var html = '<h3>' + json['text_history'] + '</h3>';

			html += '<div class="table-responsive">';
			html += '  <table class="table table-striped table-bordered table-hover">';
			html += '    <tr>';
			html += '      <th class="text-left">' + json['column_status'] + '</th>';
			html += '      <th class="text-left">' + json['column_comment'] + '</th>';
			html += '      <th class="text-right">' + json['column_date_added'] + '</th>';
			html += '    </tr>';

			if (json['histories'].length > 0) {
				for (i = 0; i < json['histories'].length; i++) {
					html += '    <tr>';
					html += '      <td class="text-left">' + json['histories'][i]['status'] + '</td>';
					html += '      <td class="text-left">' + json['histories'][i]['comment'] + '</td>';
					html += '      <td class="text-right">' + json['histories'][i]['date_added'] + '</td>';
					html += '    </tr>';
				}
			} else {
				html += '    <tr>';
				html += '      <td class="text-center" colspan="3">' + json['text_no_histories'] + '</td>';
				html += '    </tr>';
			}

			html += '  </table>';
			html += '</div>';
			html += '<div class="pagination">' + json['pagination'] + '</div><br /><br />';

			$('#history').html(html);
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}

$(document).ready(function () {
	history(1);
});

$(document).on('click', '#history .pagination a', function () {
	history($(this).attr('href'));

	return false;
});
//--></script>
<?php echo $footer; ?>