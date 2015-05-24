<?php echo $header; ?>
<div class="header">
  <div class="container">
    <h1><?php echo $text_view_recurring; ?></h1>
    <p><?php echo $text_recurring_info; ?></p>
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
      <h3><?php echo $text_details; ?></h3>
      <table class="table table-striped table-bordered">
        <tr>
          <th><?php echo $column_recurring_id; ?></th>
          <th><?php echo $column_status; ?></th>
          <th><?php echo $column_cycle; ?></th>
          <th><?php echo $column_recurring_date; ?></th>
          <th><?php echo $column_date_added; ?></th>
        </tr>
        <tr>
          <td><?php echo $recurring_id; ?></td>
          <td><?php echo $status; ?></td>
          <td><?php echo $cycle; ?></td>
          <td><?php echo $date_due; ?></td>
          <td><?php echo $date_added; ?></td>
        </tr>
      </table>
    </div>
    <div class="col-lg-12">
      <h3><?php echo $text_item; ?></h3>
      <table class="table table-striped table-bordered">
        <tr>
          <th class="text-left" style="width:10%;"><?php echo $column_number; ?></th>
          <th class="text-left" style="width:30%;"><?php echo $column_description; ?></th>
          <th class="text-left" style="width:10%;"><?php echo $column_quantity; ?></th>
          <th class="text-right" style="width:20%;"><?php echo $column_price; ?></th>
          <th class="text-right" style="width:10%;"><?php echo $column_discount; ?></th>
          <th class="text-right" style="width:20%;"><?php echo $column_total; ?></th>
        </tr>
        <?php foreach ($items as $item) { ?>
        <tr>
          <td class="text-left"><?php echo $item['number']; ?></td>
          <td class="text-left">
            <b><?php echo $item['title']; ?></b><br />
            <?php echo $item['description']; ?>
          </td>
          <td class="text-left"><?php echo $item['quantity']; ?></td>
          <td class="text-right"><?php echo $item['price']; ?></td>
          <td class="text-right"><?php echo $item['discount']; ?></td>
          <td class="text-right"><?php echo $item['total']; ?></td>
        </tr>
        <?php } ?>
        <?php foreach ($totals as $total) { ?>
        <tr>
          <td class="text-right" colspan="5"><b><?php echo $total['title']; ?></b></td>
          <td class="text-right"><?php echo $total['value']; ?></td>
        </tr>
        <?php } ?>
      </table>
    </div>
    <div class="col-lg-12">
      <h3><?php echo $text_payment; ?></h3>
      <table class="table table-striped table-bordered">
        <tr>
          <th class="text-left"></th>
          <th class="text-left"><?php echo $column_description; ?></th>
        </tr>
        <tr>
          <td class="text-left"><?php echo $payment_name; ?></td>
          <td class="text-left"><?php echo $payment_description; ?></td>
        </tr>
      </table>
    </div>
    <?php if ($recurring_status) { ?>
    <div class="col-lg-12">
      <h3><?php echo $text_cancel; ?></h3>
      <table class="table table-striped table-bordered">
        <tr>
          <td class="text-right"><a href="<?php echo $cancel; ?>" onclick="return confirm('<?php echo $text_confirm; ?>') ? true : false;" class="btn btn-primary"><?php echo $button_cancel_payment; ?></a></td>
        </tr>
      </table>
    </div>
    <?php } ?>
  </div>
</div>
<?php echo $footer; ?>