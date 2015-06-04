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
          <th class="text-right"><?php echo $column_action; ?></th>
        </tr>
        <?php if ($extensions) { ?>
        <?php foreach ($extensions as $extension) { ?>
        <tr>
          <td class="text-left"><?php echo $extension['name']; ?></td>
          <td class="text-left"><a href="<?php echo $extension['url']; ?>" target="_blank"><?php echo $extension['author']; ?></a></td>
		  <td class="text-left"><a href="mailto:<?php echo $extension['email']; ?>"><?php echo $extension['version']; ?></a></td>
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
          <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
        </tr>
        <?php } ?>
      </table>
    </div>
  </div>
</div>
<?php echo $footer; ?>