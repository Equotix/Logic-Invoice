<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $language_code; ?>">
  <head>
    <meta charset="UTF-8" />
    <title><?php echo $heading_upgrade; ?></title>
    <base href="<?php echo $base; ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="<?php echo $application; ?>vendor/jquery/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="<?php echo $application; ?>vendor/bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo $application; ?>vendor/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $application; ?>vendor/font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="view/stylesheet/stylesheet.css" />
  </head>
  <body>
    <div id="license">
      <div class="container">
        <h1><?php echo $heading_upgrade; ?></h1>
        <div id="license-text">
          <?php echo $upgrade_1; ?><br />
          <?php echo $upgrade_2; ?><br />
          <?php echo $upgrade_3; ?><br />
          <?php echo $upgrade_4; ?><br />
          <?php echo $upgrade_5; ?><br />
        </div>
        <div class="text-center"><br /><br />
          <button id="button-upgrade" class="btn btn-success btn-lg"><?php echo $button_upgrade; ?></button>
        </div>
      </div>
    </div>
    <div id="complete">
	  <div class="container">
		<h2><?php echo $heading_upgrade_complete; ?></h2>
		<p><?php echo $text_thank_you_support; ?></p>
		<div class="text-center">
		  <button id="button-remove" class="btn btn-success btn-lg"><?php echo $button_remove; ?></button>
		</div>
	  </div>
    </div>
	<script type="text/javascript"><!--
	$('#button-upgrade').on('click', function () {
		if (confirm('<?php echo $text_confirm_upgrade; ?>')) {
			upgrade('index.php?load=upgrade/upgrade/upgrade');
		}
	});
	
	function upgrade(url) {
		$.ajax({
			url: url,
			dataType: 'json',
			cache: false,
			beforeSend: function () {
				$('#button-upgrade').prop('disabled', true);
				$('#button-upgrade').after('<i class="fa fa-spinner fa-spin"></i>');
			},
			success: function (json) {
				$('.fa-spinner').remove();
				
				if (json['error']) {
					alert(json['error']);
				} else if (json['url']) {
					$('#button-upgrade').html(json['success']);
					
					upgrade(json['url']);
				} else {
					$('#complete').show();

					$('html, body').animate({scrollTop: $('#complete').offset().top}, 'slow');
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
	
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
  </body>
</html>