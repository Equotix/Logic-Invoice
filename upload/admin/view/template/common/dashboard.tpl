<?php echo $header; ?>
<div class="container-fluid">
  <div class="dashboard-heading">
    <h1><?php echo $heading_title; ?></h1>
  </div>
  <div class="row dashboard-stats">
    <div class="col-xs-12 col-md-3 primary">
      <div class="tile">
        <div class="heading"><?php echo $text_total_invoices; ?></div>
        <div class="body">
          <i class="fa fa-envelope"></i>
          <div class="pull-right"><?php echo $total_invoices; ?></div>
        </div>
        <div class="footer">
          <a href="<?php echo $invoice; ?>"><?php echo $text_view_more; ?></a>
          <div class="pull-right"><a href="<?php echo $invoice; ?>" title="<?php echo $text_view_more; ?>" data-toggle="tooltip"><i class="fa fa-external-link"></i></a></div>
        </div>
      </div>
    </div>
    <div class="col-xs-12 col-md-3 alternate">
      <div class="tile">
        <div class="heading"><?php echo $text_total_journal_entries; ?></div>
        <div class="body">
          <i class="fa fa-book"></i>
          <div class="pull-right"><?php echo $total_journal_entries; ?></div>
        </div>
        <div class="footer">
          <a href="<?php echo $journal; ?>"><?php echo $text_view_more; ?></a>
          <div class="pull-right"><a href="<?php echo $journal; ?>" title="<?php echo $text_view_more; ?>" data-toggle="tooltip"><i class="fa fa-external-link"></i></a></div>
        </div>
      </div>
    </div>
    <div class="col-xs-12 col-md-3 primary">
      <div class="tile">
        <div class="heading"><?php echo $text_total_recurring; ?></div>
        <div class="body">
          <i class="fa fa-refresh"></i>
          <div class="pull-right"><?php echo $total_recurring; ?></div>
        </div>
        <div class="footer">
          <a href="<?php echo $recurring; ?>"><?php echo $text_view_more; ?></a>
          <div class="pull-right"><a href="<?php echo $recurring; ?>" title="<?php echo $text_view_more; ?>" data-toggle="tooltip"><i class="fa fa-external-link"></i></a></div>
        </div>
      </div>
    </div>
    <div class="col-xs-12 col-md-3 alternate">
      <div class="tile">
        <div class="heading"><?php echo $text_total_customers; ?></div>
        <div class="body">
          <i class="fa fa-users"></i>
          <div class="pull-right"><?php echo $total_customers; ?></div>
        </div>
        <div class="footer">
          <a href="<?php echo $customer; ?>"><?php echo $text_view_more; ?></a>
          <div class="pull-right"><a href="<?php echo $customer; ?>" title="<?php echo $text_view_more; ?>" data-toggle="tooltip"><i class="fa fa-external-link"></i></a></div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-12 col-md-6">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><i class="fa fa-envelope"></i> <?php echo $text_10_latest_invoices; ?></h3>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
              <tr>
                <th class="text-left"><?php echo $column_invoice_id; ?></th>
                <th class="text-left"><?php echo $column_name; ?></th>
                <th class="text-left"><?php echo $column_total; ?></th>
                <th class="text-left"><?php echo $column_status; ?></th>
                <th class="text-left"><?php echo $column_date_due; ?></th>
                <th class="text-right"><?php echo $column_action; ?></th>
              </tr>
              <?php if ($invoices) { ?>
              <?php foreach ($invoices as $invoice) { ?>
              <tr>
                <td class="text-left"><?php echo $invoice['invoice_id']; ?></td>
                <td class="text-left"><?php echo $invoice['name']; ?></td>
                <td class="text-left"><?php echo $invoice['total']; ?></td>
                <td class="text-left"><?php echo $invoice['status']; ?></td>
                <td class="text-left"><?php echo $invoice['date_due']; ?></td>
                <td class="text-right">
                  <a href="<?php echo $invoice['invoice']; ?>" target="_blank" title="<?php echo $button_invoice; ?>" data-toggle="tooltip" class="btn btn-primary btn-xs"><i class="fa fa-clipboard"></i></a>
                  <a href="<?php echo $invoice['view']; ?>" title="<?php echo $button_view; ?>" data-toggle="tooltip" class="btn btn-primary btn-xs"><i class="fa fa-search"></i></a>
                </td>
              </tr>
              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="6"><?php echo $text_no_invoice; ?></td>
              </tr>
              <?php } ?>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xs-12 col-md-6">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><i class="fa fa-book"></i> <?php echo $text_10_latest_journal_entries; ?></h3>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
              <tr>
                <th class="text-left"><?php echo $column_description; ?></th>
                <th class="text-left"><?php echo $column_linked_invoice; ?></th>
                <th class="text-left"><?php echo $column_date; ?></th>
                <th class="text-left"><?php echo $column_date_added; ?></th>
                <th class="text-right"><?php echo $column_action; ?></th>
              </tr>
              <?php if ($transactions) { ?>
              <?php foreach ($transactions as $transaction) { ?>
              <tr>
                <td class="text-left"><?php echo $transaction['description']; ?></td>
                <td class="text-left"><?php echo $transaction['invoice_id']; ?></td>
                <td class="text-left"><?php echo $transaction['date']; ?></td>
                <td class="text-left"><?php echo $transaction['date_added']; ?></td>
                <td class="text-right"><a href="<?php echo $transaction['edit']; ?>" title="<?php echo $button_edit; ?>" data-toggle="tooltip" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a></td>
              </tr>
              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="5"><?php echo $text_no_journal_entries; ?></td>
              </tr>
              <?php } ?>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title"><i class="fa fa-exclamation-triangle"></i> <?php echo $text_10_latest_activities; ?></h3>
        </div>
        <div class="panel-body">
          <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
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
                <td class="text-center" colspan="2"><?php echo $text_no_activity; ?></td>
              </tr>
              <?php } ?>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>