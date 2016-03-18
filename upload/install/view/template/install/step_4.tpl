<div class="container">
  <h2><?php echo $heading_complete; ?></h2>
  <p><?php echo $text_thank_you; ?></p>
  <div class="text-center">
    <button id="button-remove" class="btn btn-success btn-lg"><?php echo $button_remove; ?></button>
  </div>
</div>
<script type="text/javascript"><!--
$('#button-remove').on('click', function () {
	$.ajax({
		url: 'index.php?load=install/step_4/remove',
		cache: false,
		beforeSend: function () {
			$('#button-remove').button('loading');
		},
		success: function (html) {
			location = '<?php echo $admin; ?>';
		}
	});
});
//--></script>