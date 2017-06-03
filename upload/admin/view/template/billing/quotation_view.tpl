<?php echo $header; ?>
<ol class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ol>
<div class="panel panel-default">
  <div class="panel-heading">
    <div class="pull-right">
      <a href="<?php echo $quotation; ?>" title="<?php echo $button_quotation; ?>" data-toggle="tooltip" class="btn btn-primary" target="_blank"><i class="fa fa-clipboard"></i></a>
      <a href="<?php echo $cancel; ?>" title="<?php echo $button_cancel; ?>" data-toggle="tooltip" class="btn btn-danger"><i class="fa fa-times"></i></a>
    </div>
    <h1 class="panel-title"><i class="fa fa-search"></i> <?php echo $heading_title; ?></h1>
  </div>
  <div class="panel-body">
    <ul class="nav nav-tabs">
      <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
      <li><a href="#tab-payment-method" data-toggle="tab"><?php echo $tab_payment_method; ?></a></li>
      <li><a href="#tab-item" data-toggle="tab"><?php echo $tab_item; ?></a></li>
      <li><a href="#tab-history" data-toggle="tab"><?php echo $tab_history; ?></a></li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane active" id="tab-general">
        <table class="table table-striped table-bordered table-hover">
          <tr>
            <td><b><?php echo $entry_quotation_id; ?></b></td>
            <td><?php echo $quotation_id; ?></td>
          </tr>
          <tr>
            <td><b><?php echo $entry_customer; ?></b></td>
            <td><a href="<?php echo $customer; ?>"><?php echo $firstname; ?> <?php echo $lastname; ?></a></td>
          </tr>
          <tr>
            <td><b><?php echo $entry_firstname; ?></b></td>
            <td><?php echo $firstname; ?></td>
          </tr>
          <tr>
            <td><b><?php echo $entry_lastname; ?></b></td>
            <td><?php echo $lastname; ?></td>
          </tr>
          <?php if ($company) { ?>
          <tr>
            <td><b><?php echo $entry_company; ?></b></td>
            <td><?php echo $company; ?></td>
          </tr>
          <?php } ?>
          <?php if ($website) { ?>
          <tr>
            <td><b><?php echo $entry_website; ?></b></td>
            <td><?php echo $website; ?></td>
          </tr>
          <?php } ?>
          <tr>
            <td><b><?php echo $entry_email; ?></b></td>
            <td><?php echo $email; ?></td>
          </tr>
          <?php if ($comment) { ?>
          <tr>
            <td><b><?php echo $entry_comment; ?></b></td>
            <td><?php echo $comment; ?></td>
          </tr>
          <?php } ?>
          <tr>
            <td><b><?php echo $entry_status; ?></b></td>
            <td><?php echo $status; ?></td>
          </tr>
          <tr>
            <td><b><?php echo $entry_date_due; ?></b></td>
            <td><?php echo $date_due; ?></td>
          </tr>
        </table>
      </div>      
      <div class="tab-pane" id="tab-payment-method">
        <table class="table table-striped table-bordered table-hover">          
          <tr>
            <td><b><?php echo $entry_currency_code; ?></b></td>
            <td><?php echo $currency_code; ?></td>
          </tr>
          <tr>
            <td><b><?php echo $entry_currency_value; ?></b></td>
            <td><?php echo $currency_value; ?></td>
          </tr>
        </table>
      </div>
      <div class="tab-pane" id="tab-item">
        <table class="table table-striped table-bordered table-hover">
          <tr>
            <th class="text-left"><?php echo $column_number; ?></th>
            <th class="text-left"><?php echo $column_title; ?></th>
            <th class="text-left"><?php echo $column_description; ?></th>
            <th class="text-left"><?php echo $column_quantity; ?></th>
            <th class="text-right"><span data-toggle="tooltip" title="<?php echo $tooltip_price; ?>"><?php echo $column_price; ?> <i class="fa fa-question-circle"></i></span></th>
            <th class="text-right"><?php echo $column_discount; ?></th>
            <th class="text-right"><?php echo $column_total; ?></th>
          </tr>
          <?php foreach ($items as $item) { ?>
          <tr>
            <td class="text-left"><?php echo $item['number']; ?></td>
            <td class="text-left"><?php echo $item['title']; ?></td>
            <td class="text-left"><?php echo $item['description']; ?></td>
            <td class="text-left"><?php echo $item['quantity']; ?></td>
            <td class="text-right"><?php echo $item['price']; ?></td>
            <td class="text-right"><?php echo $item['discount']; ?></td>
            <td class="text-right"><?php echo $item['total']; ?></td>
          </tr>
          <?php } ?>
          <?php foreach ($totals as $total) { ?>
          <tr>
            <td class="text-right" colspan="6"><b><?php echo $total['title']; ?></b></td>
            <td class="text-right"><?php echo $total['text']; ?></td>
          </tr>
          <?php } ?>
        </table>
      </div>
      <div class="tab-pane" id="tab-history"></div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function history(page) {
	$.ajax({
		url: 'index.php?load=billing/quotation/history&token=<?php echo $token; ?>&quotation_id=<?php echo $quotation_id; ?>&page=' + page,
		dataType: 'json',
		beforeSend: function () {
			$('#tab-history').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-4x"></i></div>');
		},
		complete: function () {
			$('.fa-spinner').parent().remove();
		},
		success: function (json) {
			var html = '<div class="table-responsive">';
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
			html += '<div class="form-horizontal">';
			html += '  <div class="form-group">';
			html += '    <label class="required col-sm-2 control-label" for="input-status">' + json['entry_status'] + '</label>';
			html += '    <div class="col-sm-10">';
			html += '      <select name="status_id" class="form-control">';

			for (i = 0; i < json['statuses'].length; i++) {
				if (json['status_id'] == json['statuses'][i]['status_id']) {
					html += '<option value="' + json['statuses'][i]['status_id'] + '" selected="selected">' + json['statuses'][i]['name'] + '</option>';
				} else {
					html += '<option value="' + json['statuses'][i]['status_id'] + '">' + json['statuses'][i]['name'] + '</option>';
				}
			}

			html += '      </select>';
			html += '    </div>';
			html += '  </div>';
			html += '  <div class="form-group">';
			html += '    <label class="col-sm-2 control-label" for="input-comment">' + json['entry_comment'] + '</label>';
			html += '    <div class="col-sm-10">';
			html += '      <input type="text" name="comment" value="" id="input-comment" class="form-control" placeholder="' + json['entry_comment'] + '" />';
			html += '    </div>';
			html += '  </div>';
			html += '  <div class="text-right">';
			html += '    <button type="button" id="button-history" class="btn btn-success" onclick="addHistory();">' + json['button_add'] + '</button>';
			html += '  </div>';
			html += '</div>';

			$('#tab-history').html(html);
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}

function addHistory() {
	$.ajax({
		url: 'index.php?load=billing/quotation/history&token=<?php echo $token; ?>&quotation_id=<?php echo $quotation_id; ?>',
		dataType: 'json',
		data: $('#tab-history input, #tab-history select'),
		type: 'post',
		beforeSend: function () {
			$('.alert').remove();

			$('#button-history').button('loading');
		},
		complete: function () {
			$('#button-history').button('reset');
		},
		success: function (json) {
			history(1);

			$('.breadcrumb').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}

$(document).ready(function () {
	history(1);
});

$(document).on('click', '#tab-history .pagination a', function () {
	history($(this).attr('href'));

	return false;
});
//--></script>
<?php echo $footer; ?>