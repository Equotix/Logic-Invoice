<h3><?php echo $heading_title; ?></h3>
<p>
  <?php echo $text_payable; ?><br /><br />
  <strong><?php echo $payable; ?></strong><br />
  <?php echo $details; ?>
</p>
<table class="table table-striped table-bordered">
  <tr>
    <td class="text-right">
      <a href="<?php echo $action; ?>" class="btn btn-primary"><?php echo $button_make_payment; ?></a>
    </td>
  </tr>
</table>