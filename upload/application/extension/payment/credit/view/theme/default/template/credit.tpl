<h3><?php echo $heading_title; ?></h3>
<p>
  <?php echo $credit; ?>
</p>
<?php if (isset($action)) { ?>
<table class="table table-striped table-bordered">
  <tr>
    <td class="text-right">
      <a href="<?php echo $action; ?>" class="btn btn-primary"><?php echo $button_make_payment; ?></a>
    </td>
  </tr>
</table>
<?php } else { ?>
  <div class="alert alert-warning"><?php echo $warning; ?></div>
<?php } ?>