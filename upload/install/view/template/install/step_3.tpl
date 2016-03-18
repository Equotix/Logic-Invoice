<div class="container">
  <h2><?php echo $heading_configurations; ?></h2>
  <div id="form-configuration" class="form-horizontal">
    <h4><?php echo $text_database; ?></h4>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-database"><?php echo $entry_database; ?></label>
      <div class="col-sm-10">
        <select name="database" class="form-control"><?php foreach ($databases as $database) { ?>
          <option value="<?php echo $database; ?>"><?php echo ${'text_' . $database}; ?></option>
          <?php } ?></select>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-database-host"><?php echo $entry_database_hostname; ?></label>
      <div class="col-sm-10">
        <input type="text" name="database_hostname" value="localhost" id="input-database-hostname" class="form-control" placeholder="<?php echo $entry_database_hostname; ?>" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-database-name"><?php echo $entry_database_name; ?></label>
      <div class="col-sm-10">
        <input type="text" name="database_name" value="" id="input-database-name" class="form-control" placeholder="<?php echo $entry_database_name; ?>" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-database-username"><?php echo $entry_database_username; ?></label>
      <div class="col-sm-10">
        <input type="text" name="database_username" value="" id="input-database-username" class="form-control" placeholder="<?php echo $entry_database_username; ?>" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-database-password"><?php echo $entry_database_password; ?></label>
      <div class="col-sm-10">
        <input type="password" name="database_password" value="" id="input-database-password" class="form-control" placeholder="<?php echo $entry_database_password; ?>" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-database-prefix"><?php echo $entry_database_prefix; ?></label>
      <div class="col-sm-10">
        <input type="text" name="database_prefix" value="<?php echo $prefix; ?>" id="input-database-prefix" class="form-control" placeholder="<?php echo $entry_database_prefix; ?>" />
      </div>
    </div>
    <h4><?php echo $text_administration; ?></h4>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-admin-username"><?php echo $entry_admin_username; ?></label>
      <div class="col-sm-10">
        <input type="text" name="admin_username" value="admin" id="input-admin-username" class="form-control" placeholder="<?php echo $entry_admin_username; ?>" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-admin-password"><?php echo $entry_admin_password; ?></label>
      <div class="col-sm-10">
        <input type="password" name="admin_password" value="" id="input-admin-password" class="form-control" placeholder="<?php echo $entry_admin_password; ?>" />
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-admin-email"><?php echo $entry_admin_email; ?></label>
      <div class="col-sm-10">
        <input type="text" name="admin_email" value="" id="input-admin-email" class="form-control" placeholder="<?php echo $entry_admin_email; ?>" />
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-12 text-right">
      <button id="button-configure" class="btn btn-success"><?php echo $button_continue; ?></button>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#button-configure').on('click', function () {
	$.ajax({
		url: 'index.php?load=install/step_3/validate',
		data: $('#form-configuration input, #form-configuration select'),
		type: 'post',
		dataType: 'json',
		cache: false,
		beforeSend: function () {
			$('#button-configure').button('loading');
		},
		success: function (json) {
			if (json['error']) {
				alert(json['error']);

				$('#button-configure').button('reset');
			} else {
				install();
			}
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

function install() {
	$.ajax({
		url: 'index.php?load=install/step_4',
		data: $('#form-configuration input, #form-configuration select'),
		type: 'post',
		dataType: 'html',
		cache: false,
		success: function (html) {
			$('#complete').html(html);

			$('#complete').show();

			$('html, body').animate({scrollTop: $('#complete').offset().top}, 'slow');
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}
//--></script>