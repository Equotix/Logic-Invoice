<div class="container">
  <h2><?php echo $heading_requirements; ?></h2>
  <table class="table table-bordered table-hover table-striped">
    <tr>
      <th></th>
      <th><?php echo $column_recommended; ?></th>
      <th><?php echo $column_detected; ?></th>
      <th><?php echo $column_status; ?></th>
    </tr>
    <?php foreach ($requirements as $requirement => $value) { ?>
    <tr>
      <td><?php echo ${'text_' . $requirement}; ?></td>
      <td><?php echo $value[0]; ?></td>
      <td><?php echo $value[1]; ?></td>
      <td><?php if ($value[2]) { ?>
        <span class="text-success"><i class="fa fa-check-circle"></i></span>
        <?php } else { ?>
        <span class="text-danger"><i class="fa fa-exclamation-circle"></i></span>
        <?php } ?></td>
    </tr>
    <?php } ?>
  </table>
  <div class="row">
    <div class="col-sm-6">
      <button id="button-retry" class="btn btn-primary"><?php echo $button_retry; ?></button>
    </div>
    <div class="col-sm-6 text-right">
      <button id="button-requirement" class="btn btn-success"><?php echo $button_continue; ?></button>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#button-retry').on('click', function () {
	$.ajax({
		url: 'index.php?load=install/step_2',
		dataType: 'html',
		cache: false,
		beforeSend: function () {
			$('#requirement').html('');
		},
		success: function (html) {
			$('#requirement').html(html);

			$('#requirement').fadeIn('medium');

			$('html, body').animate({scrollTop: $('#requirement').offset().top}, 'slow');
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#button-requirement').on('click', function () {
	$.ajax({
		url: 'index.php?load=install/step_3',
		dataType: 'html',
		cache: false,
		beforeSend: function () {
			$('#button-requirement').prop('disabled', true);
			$('#button-retry').prop('disabled', true);
		},
		success: function (html) {
			$('#configure').html(html);

			$('#configure').show();

			$('html, body').animate({scrollTop: $('#configure').offset().top}, 'slow');
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
//--></script>