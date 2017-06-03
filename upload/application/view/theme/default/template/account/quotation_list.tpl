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
            <th><?php echo $column_quotation_id; ?></th>
            <th><?php echo $column_date_issued; ?></th>
            <th><?php echo $column_date_due; ?></th>
            <th><?php echo $column_total; ?></th>
            <th><?php echo $column_status; ?></th>
            <th></th>
          </tr>
          <?php if ($quotations) { ?>
          <?php foreach ($quotations as $quotation) { ?>
          <tr>
            <td><?php echo $quotation['quotation_id']; ?></td>
            <td><?php echo $quotation['date_issued']; ?></td>
            <td><?php echo $quotation['date_due']; ?></td>
            <td><?php echo $quotation['total']; ?></td>
            <td><?php echo $quotation['status']; ?></td>
            <td class="text-right"><a href="<?php echo $quotation['quotation']; ?>" title="<?php echo $button_quotation; ?>" data-toggle="tooltip" class="btn btn-primary btn-xs" target="_blank"><i class="fa fa-clipboard"></i></a> <a href="<?php echo $quotation['view']; ?>" title="<?php echo $button_view; ?>" data-toggle="tooltip" class="btn btn-primary btn-xs"><i class="fa fa-search"></i></a></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </table>
      </div>
      <?php echo $pagination; ?>
    </div>
  </div>
</div>
<?php echo $footer; ?>