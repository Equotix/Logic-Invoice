<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $language_code; ?>">
  <head>
    <meta charset="UTF-8" />
    <title><?php echo $title; ?></title>
    <base href="<?php echo $base; ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script type="text/javascript" src="vendor/jquery/jquery-2.1.4.min.js"></script>
    <script type="text/javascript" src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="vendor/font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="application/view/stylesheet/stylesheet.css" />
  </head>
  <body>
    <div class="container">
      <div class="row well">
        <div class="col-xs-6">
          <h3 class="status"><?php echo $status; ?></h3>
          <?php echo $text_updated; ?> <?php echo $date_modified; ?>
        </div>
        <!-- Download quotation as PDF -->
        <div class="col-xs-6 text-right">
          <form action="<?php echo $application; ?>dompdf.php" method="post" id="pdf_submit">
            <input type="hidden" id="pdf_input" name="pdf">
            <a class="btn btn-primary btn-lg" id="pdf_button">Download as PDF</a>
          </form>
        </div>
        <!-- Download quotation as PDF -->
      </div>
      <div class="row well">
        <div class="col-xs-6">
          <h3><?php echo $quotation_prefix; ?><?php echo $quotation_id; ?></h3>
          <?php echo $date_issued; ?><br />
          <?php if ($payment_firstname || $payment_lastname) { ?>
          <?php echo $payment_firstname; ?> <?php echo $payment_lastname; ?><br />
          <?php } else { ?>
          <?php echo $firstname; ?> <?php echo $lastname; ?><br />
          <?php } ?>
          <?php echo $email; ?><br />
          <?php if ($website) { ?>
          <?php echo $website; ?><br />
          <?php } ?>
          <?php if ($payment_company) { ?>
          <?php echo $payment_company; ?><br />
          <?php } elseif ($company) { ?>
          <?php echo $company; ?><br />
          <?php } ?>
          <?php if ($payment_address_1) { ?>
          <?php echo $payment_address_1; ?><br />
          <?php } ?>
          <?php if ($payment_address_2) { ?>
          <?php echo $payment_address_2; ?><br />
          <?php } ?>
          <?php if ($payment_city) { ?>
          <?php echo $payment_city; ?><br />
          <?php } ?>
          <?php if ($payment_postcode) { ?>
          <?php echo $payment_postcode; ?><br />
          <?php } ?>
          <?php if ($payment_country) { ?>
          <?php echo $payment_country; ?><br />
          <?php } ?>
          <?php if ($payment_zone) { ?>
          <?php echo $payment_zone; ?><br />
          <?php } ?>
        </div>
        <div class="col-xs-6 text-right">
          <h3><?php echo $system_company; ?></h3>
          <?php echo $system_address; ?><br />
          <?php echo $system_email; ?><br />
          <?php if ($system_telephone) { ?>
          <?php echo $system_telephone; ?><br />
          <?php } ?>
          <?php if ($system_fax) { ?>
          <?php echo $system_fax; ?><br />
          <?php } ?>
        </div>
      </div>
      <div class="row">
        <div class="col-xs-12">
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
              <td class="text-right"><?php echo $total['text']; ?></td>
            </tr>
            <?php } ?>
          </table>
        </div>
      </div>      
    </div>
  </body>
  <script type="text/javascript">
  $("#pdf_button").click(function (e) {
    e.preventDefault();

    //Set Original HTML into var
    var originalHTML = $("html").html();

    //Change CSS for PDF
    $("head").append("<style> @page { margin: 0.5in; } html { margin: 0.5in; } </style>");    
    //$(".container").css("margin", "20px");
    $(".text-right").removeClass("text-right");
    $(".row:nth-child(3)").css("margin","0");
    $(".row .col-xs-12").css("padding","0");
    $(".row:nth-child(4)").css("margin","0");
    $(".row .col-xs-12").css("padding","0");
    $("#pdf_button").hide();

    //Set input for pdf
    var html = "<html>" + $("html").html() + "</html>";
    $("#pdf_input").val(html);

    //Send HTML to PDF processor
    $("#pdf_submit").submit();

    //Reset Changes for PDF
    $("html").html(originalHTML);
  });
  </script>
</html>