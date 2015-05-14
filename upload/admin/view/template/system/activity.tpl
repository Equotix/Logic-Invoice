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
    <div class="pull-right">
      <a href="<?php echo $clear; ?>" title="<?php echo $button_clear; ?>" data-toggle="tooltip" class="btn btn-danger"><i class="fa fa-times"></i></a>
    </div>
    <h1 class="panel-title"><i class="fa fa-list fa-lg"></i> <?php echo $heading_title; ?></h1>
  </div>
  <div class="panel-body">
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-hover">
        <tr>
          <th class="text-left"><?php echo $column_date_added; ?></th>
          <th class="text-left"><?php echo $column_message; ?></th>
        </tr>
        <?php if ($activities) { ?>
        <?php foreach ($activities as $activity) { ?>
        <tr>
          <td class="text-left"><?php echo $activity['date_added']; ?></td>
          <td class="text-left"><?php echo $activity['message']; ?></td>
        </tr>
        <?php } ?>
        <?php } else { ?>
        <tr>
          <td class="text-center" colspan="2"><?php echo $text_no_results; ?></td>
        </tr>
        <?php } ?>
      </table>
    </div>
    <?php echo $pagination; ?>
  </div>
</div>
<?php echo $footer; ?>