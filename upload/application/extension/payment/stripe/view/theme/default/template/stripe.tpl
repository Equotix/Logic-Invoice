<h3><?php echo $heading_title; ?></h3>
<?php if ($testmode) { ?>
<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $text_test; ?></div>
<?php } ?>
<table class="table table-striped table-bordered">
  <tr>
    <td class="text-right">
      <form action="<?php echo $action; ?>" method="post">
        <script
          src="https://checkout.stripe.com/checkout.js" class="stripe-button"
          data-key="<?php echo $api_key; ?>"
          data-name="<?php echo $heading_title; ?>"
          data-description="<?php echo $description; ?>"
          data-amount="<?php echo $amount; ?>"
          data-locale="<?php echo $currency_code; ?>">
        </script>
        <input type="hidden" name="custom" value="<?php echo $custom; ?>" />
      </form>
    </td>
  </tr>
</table>