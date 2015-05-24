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
      <p><?php echo $credit; ?></p>
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
          <tr>
            <th><?php echo $column_amount; ?></th>
            <th><?php echo $column_description; ?></th>
            <th><?php echo $column_date_added; ?></th>
          </tr>
          <?php if ($credits) { ?>
          <?php foreach ($credits as $credit) { ?>
          <tr>
            <td><?php echo $credit['amount']; ?></td>
            <td><?php echo $credit['description']; ?></td>
            <td><?php echo $credit['date_added']; ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="text-center" colspan="3"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </table>
      </div>
      <?php echo $pagination; ?>
    </div>
  </div>
</div>
<?php echo $footer; ?>