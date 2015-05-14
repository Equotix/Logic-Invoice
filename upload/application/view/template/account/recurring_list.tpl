<?php echo $header; ?>
<div class="header">
  <div class="container">
    <h1><?php echo $heading_title; ?></h1>
    <p><?php echo $text_info; ?></p>
  </div>
</div>
<div id="content" class="container">
  <ol class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ol>
  <div class="row">
    <div class="col-lg-12">
      <h2><?php echo $heading_title; ?></h2>
      <?php if ($success) { ?>
      <div class="alert alert-success"><?php echo $success; ?></div>
      <?php } ?>
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
          <tr>
            <th><?php echo $column_recurring_id; ?></th>
            <th><?php echo $column_total; ?></th>
            <th><?php echo $column_status; ?></th>
            <th><?php echo $column_cycle; ?></th>
            <th><?php echo $column_recurring_date; ?></th>
            <th><?php echo $column_date_added; ?></th>
            <th></th>
          </tr>
          <?php if ($recurrings) { ?>
          <?php foreach ($recurrings as $recurring) { ?>
          <tr>
            <td><?php echo $recurring['recurring_id']; ?></td>
            <td><?php echo $recurring['total']; ?></td>
            <td><?php echo $recurring['status']; ?></td>
            <td><?php echo $recurring['cycle']; ?></td>
            <td><?php echo $recurring['date_due']; ?></td>
            <td><?php echo $recurring['date_added']; ?></td>
            <td class="text-right"><a href="<?php echo $recurring['view']; ?>" title="<?php echo $button_view; ?>" data-toggle="tooltip" class="btn btn-primary btn-xs"><i class="fa fa-search"></i></a></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </table>
      </div>
      <?php echo $pagination; ?>
    </div>
  </div>
</div>
<?php echo $footer; ?>