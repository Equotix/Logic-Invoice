<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $language_code; ?>">
  <head>
    <meta charset="UTF-8" />
    <title><?php echo $heading_install; ?></title>
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
        <h1><?php echo $heading_install; ?></h1>
        <div id="license-text">
          <?php echo $license; ?>
        </div>
        <div class="text-center"><br /><br />
          <button id="button-license" class="btn btn-success btn-lg"><?php echo $button_agree; ?></button>
        </div>
      </div>
    </div>
    <div id="requirement">
    </div>
    <div id="configure">
    </div>
    <div id="complete">
    </div>
	<script type="text/javascript"><!--
	$('#button-license').on('click', function () {
		$.ajax({
			url: 'index.php?load=install/step_2',
			dataType: 'html',
			cache: false,
			beforeSend: function () {
				$('#button-license').prop('disabled', true);
			},
			success: function (html) {
				$('#requirement').html(html);

				$('#requirement').show();

				$('html, body').animate({scrollTop: $('#requirement').offset().top}, 'slow');
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});
	//--></script>
  </body>
</html>