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
      <button type="submit" form="form-customer" title="<?php echo $button_save; ?>" data-toggle="tooltip" class="btn btn-success"><i class="fa fa-save"></i></button>
      <a href="<?php echo $cancel; ?>" title="<?php echo $button_cancel; ?>" data-toggle="tooltip" class="btn btn-danger"><i class="fa fa-times"></i></a>
    </div>
    <h1 class="panel-title"><i class="fa fa-pencil-square fa-lg"></i> <?php echo $heading_title; ?></h1>
  </div>
  <div class="panel-body">
    <form method="post" action="<?php echo $action; ?>" id="form-customer" class="form-horizontal">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
        <?php if ($customer_id) { ?>
        <li><a href="#tab-credit" data-toggle="tab"><?php echo $tab_credit; ?></a></li>
        <li><a href="#tab-ip" data-toggle="tab"><?php echo $tab_ip; ?></a></li>
        <?php } ?>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab-general">
          <div class="form-group">
            <label class="required col-sm-2 control-label" for="input-firstname"><?php echo $entry_firstname; ?></label>
            <div class="col-sm-10">
              <input type="text" name="firstname" value="<?php echo $firstname; ?>" id="input-firstname" class="form-control" placeholder="<?php echo $entry_firstname; ?>" required autofocus />
              <?php if ($error_firstname) { ?>
              <div class="text-danger"><?php echo $error_firstname; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="required col-sm-2 control-label" for="input-lastname"><?php echo $entry_lastname; ?></label>
            <div class="col-sm-10">
              <input type="text" name="lastname" value="<?php echo $lastname; ?>" id="input-lastname" class="form-control" placeholder="<?php echo $entry_lastname; ?>" required />
              <?php if ($error_lastname) { ?>
              <div class="text-danger"><?php echo $error_lastname; ?></div>
              <?php } ?>
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
              <?php if ($error_email) { ?>
              <div class="text-danger"><?php echo $error_email; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-password"><?php echo $entry_password; ?></label>
            <div class="col-sm-10">
              <input type="password" name="password" value="<?php echo $password; ?>" id="input-password" class="form-control" placeholder="<?php echo $entry_password; ?>" />
              <?php if ($error_password) { ?>
              <div class="text-danger"><?php echo $error_password; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-confirm"><?php echo $entry_confirm; ?></label>
            <div class="col-sm-10">
              <input type="password" name="confirm" value="<?php echo $confirm; ?>" id="input-confirm" class="form-control" placeholder="<?php echo $entry_confirm; ?>" />
              <?php if ($error_confirm) { ?>
              <div class="text-danger"><?php echo $error_confirm; ?></div>
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
        </div>
        <?php if ($customer_id) { ?>
        <div class="tab-pane" id="tab-credit"></div>
        <div class="tab-pane" id="tab-ip"></div>
        <?php } ?>
      </div>
    </form>
  </div>
</div>
<?php if ($customer_id) { ?>
<script type="text/javascript"><!--
function credit(page) {
	$.ajax({
		url: 'index.php?load=billing/customer/credit&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>&page=' + page,
		dataType: 'json',
		beforeSend: function () {
			$('#tab-credit').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-4x"></i></div>');
		},
		complete: function () {
			$('.fa-spinner').parent().remove();
		},
		success: function (json) {
			var html = '<p>' + json['credit'] + '</p>';

			html += '<div class="table-responsive">';
			html += '  <table class="table table-striped table-bordered table-hover">';
			html += '    <tr>';
			html += '      <th class="text-left">' + json['column_credit'] + '</th>';
			html += '      <th class="text-left">' + json['column_description'] + '</th>';
			html += '      <th class="text-right">' + json['column_date_added'] + '</th>';
			html += '    </tr>';

			if (json['credits'].length > 0) {
				for (i = 0; i < json['credits'].length; i++) {
					html += '    <tr>';
					html += '      <td class="text-left">' + json['credits'][i]['amount'] + '</td>';
					html += '      <td class="text-left">' + json['credits'][i]['description'] + '</td>';
					html += '      <td class="text-right">' + json['credits'][i]['date_added'] + '</td>';
					html += '    </tr>';
				}
			} else {
				html += '    <tr>';
				html += '      <td class="text-center" colspan="3">' + json['text_no_credits'] + '</td>';
				html += '    </tr>';
			}

			html += '  </table>';
			html += '</div>';

			html += '<div class="pagination">' + json['pagination'] + '</div><br /><br />';
			html += '<div class="form-group">';
			html += '  <label class="required col-sm-2 control-label" for="input-amount">' + json['entry_amount'] + '</label>';
			html += '  <div class="col-sm-10">';
			html += '    <input type="text" name="amount" value="" id="input-amount" class="form-control" placeholder="' + json['entry_amount'] + '" />';
			html += '  </div>';
			html += '</div>';
			html += '<div class="form-group">';
			html += '  <label class="col-sm-2 control-label" for="input-description">' + json['entry_description'] + '</label>';
			html += '  <div class="col-sm-10">';
			html += '    <input type="text" name="description" value="" id="input-description" class="form-control" placeholder="' + json['entry_description'] + '" />';
			html += '  </div>';
			html += '</div>';
			html += '<div class="text-right">';
			html += '  <button type="button" id="button-credit" class="btn btn-success" onclick="addCredit();">' + json['button_add'] + '</button>';
			html += '</div>';

			$('#tab-credit').html(html);
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}

function addCredit() {
	$.ajax({
		url: 'index.php?load=billing/customer/credit&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>',
		dataType: 'json',
		data: $('#tab-credit input'),
		type: 'post',
		beforeSend: function () {
			$('.alert').remove();

			$('#button-credit').button('loading');
		},
		complete: function () {
			$('#button-credit').button('reset');
		},
		success: function (json) {
			credit(1);

			$('.breadcrumb').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}

function ip(page) {
	$.ajax({
		url: 'index.php?load=billing/customer/ip&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>&page=' + page,
		dataType: 'json',
		beforeSend: function () {
			$('#tab-ip').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-4x"></i></div>');
		},
		complete: function () {
			$('.fa-spinner').parent().remove();
		},
		success: function (json) {
			var html = '';

			html += '<div class="table-responsive">';
			html += '  <table class="table table-striped table-bordered table-hover">';
			html += '    <tr>';
			html += '      <th class="text-left">' + json['column_ip'] + '</th>';
			html += '      <th class="text-right">' + json['column_date_added'] + '</th>';
			html += '    </tr>';

			if (json['ips'].length > 0) {
				for (i = 0; i < json['ips'].length; i++) {
					html += '    <tr>';
					html += '      <td class="text-left">' + json['ips'][i]['ip'] + '</td>';
					html += '      <td class="text-right">' + json['ips'][i]['date_added'] + '</td>';
					html += '    </tr>';
				}
			} else {
				html += '    <tr>';
				html += '      <td class="text-center" colspan="2">' + json['text_no_ips'] + '</td>';
				html += '    </tr>';
			}

			html += '  </table>';
			html += '</div>';

			html += '<div class="pagination">' + json['pagination'] + '</div>';

			$('#tab-ip').html(html);
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}

$(document).ready(function () {
	credit(1);
	ip(1);
});

$(document).on('click', '#tab-credit .pagination a', function () {
	credit($(this).attr('href'));

	return false;
});

$(document).on('click', '#tab-ip .pagination a', function () {
	ip($(this).attr('href'));

	return false;
});
//--></script>
<?php } ?>
<?php echo $footer; ?>