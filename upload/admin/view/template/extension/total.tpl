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
<?php if ($success) { ?>
<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
  <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<?php } ?>
<div class="panel panel-default">
  <div class="panel-heading">
    <h1 class="panel-title"><i class="fa fa-list fa-lg"></i> <?php echo $heading_title; ?></h1>
  </div>
  <div class="panel-body">
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-hover">
        <tr>
          <th class="text-left"><?php echo $column_name; ?></th>
          <th class="text-left"><?php echo $column_author; ?></th>
          <th class="text-left"><?php echo $column_version; ?></th>
          <th class="text-left"><?php echo $column_status; ?></th>
          <th class="text-left"><?php echo $column_sort_order; ?></th>
          <th class="text-right"><?php echo $column_action; ?></th>
        </tr>
        <?php if ($extensions) { ?>
        <?php foreach ($extensions as $extension) { ?>
        <tr>
          <td class="text-left"><?php echo $extension['name']; ?></td>
		  <td class="text-left"><a href="<?php echo $extension['url']; ?>" target="_blank"><?php echo $extension['author']; ?></a></td>
		  <td class="text-left"><a href="mailto:<?php echo $extension['email']; ?>"><?php echo $extension['version']; ?></a></td>
          <td class="text-left"><select name="<?php echo $extension['code']; ?>_status" class="form-control input-sm status"<?php echo $extension['installed'] ? '' : ' disabled'; ?>>
                                        <option value="1"<?php echo $extension['status'] ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
              <option value="0"<?php echo $extension['status'] ? '' : ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
            </select></td>
          <td class="text-left"><input type="text" name="<?php echo $extension['code']; ?>_sort_order" value="<?php echo $extension['sort_order']; ?>" class="form-control input-sm sort-order" <?php echo $extension['installed'] ? '' : ' disabled'; ?> /></td>
          <td class="text-right"><?php if ($extension['installed']) { ?>
            <a href="<?php echo $extension['edit']; ?>" title="<?php echo $button_edit; ?>" data-toggle="tooltip" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
            <a href="<?php echo $extension['uninstall']; ?>" title="<?php echo $button_uninstall; ?>" data-toggle="tooltip" class="btn btn-danger btn-xs" onclick="return confirm('<?php echo $text_confirm; ?>') ? true : false;"><i class="fa fa-minus-circle"></i></a>
            <?php } else { ?>
            <button type="button" title="<?php echo $button_edit; ?>" data-toggle="tooltip" class="btn btn-primary btn-xs" disabled="disabled"><i class="fa fa-pencil"></i></button>
            <a href="<?php echo $extension['install']; ?>" title="<?php echo $button_install; ?>" data-toggle="tooltip" class="btn btn-success btn-xs"><i class="fa fa-plus-circle"></i></a>
            <?php } ?></td>
        </tr>
        <?php } ?>
        <?php } else { ?>
        <tr>
          <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
        </tr>
        <?php } ?>
      </table>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$(document).ready(function () {
	$('.status').change(function () {
		var field = $(this);

		$.ajax({
			url: 'index.php?load=extension/total/update&token=<?php echo $token; ?>',
			type: 'post',
			data: {extension: field.attr('name').replace(/_status/, ''), key: field.attr('name'), value: field.val()},
			dataType: 'json',
			beforeSend: function () {
				field.after('<i class="fa fa-spinner"></i>');
			},
			complete: function () {
				$('.fa-spinner').remove();
			},
			success: function (json) {
				$('.alert-danger').remove();
				$('.alert-success').remove();

				if (json['warning']) {
					field.css('border', '1px solid #a94442');

					$('.breadcrumb').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>' + json['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				} else {
					field.css('border', '1px solid #3c763d');

					$('.breadcrumb').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i>' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});

	$('.sort-order').keyup(function () {
		var field = $(this);

		$.ajax({
			url: 'index.php?load=extension/total/update&token=<?php echo $token; ?>',
			type: 'post',
			data: {extension: field.attr('name').replace(/_sort_order/, ''), key: field.attr('name'), value: field.val()},
			dataType: 'json',
			beforeSend: function () {
				field.after('<i class="fa fa-spinner"></i>');
			},
			complete: function () {
				$('.fa-spinner').remove();
			},
			success: function (json) {
				$('.alert-danger').remove();
				$('.alert-success').remove();

				if (json['warning']) {
					field.css('border', '1px solid #a94442');

					$('.breadcrumb').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>' + json['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				} else {
					field.css('border', '1px solid #3c763d');

					$('.breadcrumb').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i>' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	});
});
//--></script>
<?php echo $footer; ?>