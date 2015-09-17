<h3><?php echo $heading_title; ?></h3>
<?php if ($sandbox) { ?>
<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $text_sandbox; ?></div>
<?php } ?>
<form action="<?php echo $action; ?>" method="post">
  <input type="hidden" name="cmd" value="_cart" />
  <input type="hidden" name="upload" value="1" />
  <input type="hidden" name="business" value="<?php echo $business; ?>" />
  <?php $i = 1; ?>
  <?php foreach ($items as $item) { ?>
  <input type="hidden" name="item_name_<?php echo $i; ?>" value="<?php echo $item['title']; ?>" />
  <input type="hidden" name="amount_<?php echo $i; ?>" value="<?php echo $item['price']; ?>" />
  <input type="hidden" name="quantity_<?php echo $i; ?>" value="1" />
  <?php $i++; ?>
  <?php } ?>
  <?php if ($discount_amount_cart) { ?>
  <input type="hidden" name="discount_amount_cart" value="<?php echo $discount_amount_cart; ?>" />
  <?php } ?>
  <input type="hidden" name="currency_code" value="<?php echo $currency_code; ?>" />
  <input type="hidden" name="first_name" value="<?php echo $first_name; ?>" />
  <input type="hidden" name="last_name" value="<?php echo $last_name; ?>" />
  <input type="hidden" name="email" value="<?php echo $email; ?>" />
  <input type="hidden" name="invoice" value="<?php echo $invoice; ?>" />
  <input type="hidden" name="lc" value="<?php echo $lc; ?>" />
  <input type="hidden" name="rm" value="2" />
  <input type="hidden" name="no_note" value="1" />
  <input type="hidden" name="charset" value="utf-8" />
  <input type="hidden" name="return" value="<?php echo $return; ?>" />
  <input type="hidden" name="notify_url" value="<?php echo $notify_url; ?>" />
  <input type="hidden" name="cancel_return" value="<?php echo $cancel_return; ?>" />
  <input type="hidden" name="paymentaction" value="<?php echo $paymentaction; ?>" />
  <input type="hidden" name="custom" value="<?php echo $custom; ?>" />
  <table class="table table-striped table-bordered">
    <tr>
      <td class="text-right">
        <input type="submit" value="<?php echo $button_make_payment; ?>" class="btn btn-primary" />
      </td>
    </tr>
  </table>
</form>