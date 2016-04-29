<?php echo $header; ?>
<ol class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ol>
<div class="panel panel-default">
  <div class="panel-heading">
    <div class="pull-right">
      <a href="<?php echo $print; ?>" title="<?php echo $button_print; ?>" data-toggle="tooltip" class="btn btn-primary" target="_blank"><i class="fa fa-print"></i></a>
    </div>
    <h1 class="panel-title"><i class="fa fa-line-chart fa-lg"></i> <?php echo $heading_title; ?></h1>
  </div>
  <div class="panel-body">
    <div class="well well-lg">
      <div class="row">
        <div class="col-sm-6">
          <div class="form-group">
            <label class="control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
            <div class="input-group">
              <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" placeholder="<?php echo $entry_date_start; ?>" id="input-date-start" class="form-control date" />
              <span class="input-group-btn">
                <button type="button" class="btn btn-default" onclick="$(this).parent().siblings('input').focus();"><i class="fa fa-calendar"></i></button>
              </span>
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label class="control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
            <div class="input-group">
              <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" id="input-date-end" class="form-control date" />
              <span class="input-group-btn">
                <button type="button" class="btn btn-default" onclick="$(this).parent().siblings('input').focus();"><i class="fa fa-calendar"></i></button>
              </span>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <button type="button" title="<?php echo $button_filter; ?>" data-toggle="tooltip" class="btn btn-primary pull-right" onclick="filter();"><i class="fa fa-search"></i></button>
        </div>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-hover">
        <tr>
          <th class="text-left"><?php echo $text_revenue; ?></th>
          <th class="text-right"><?php echo $date_start; ?> - <?php echo $date_end; ?></th>
        </tr>
        <?php if ($revenue_accounts) { ?>
        <?php foreach ($revenue_accounts as $revenue_account) { ?>
        <tr>
          <td class="text-left indent-1"><?php echo $revenue_account['name']; ?></td>
          <td class="text-right"><?php echo $revenue_account['total']; ?></td>
        </tr>
        <?php } ?>
        <?php } ?>
        <tr>
          <th class="text-left indent-1"><?php echo $text_total_revenue; ?></td>
          <th class="text-right"><?php echo $revenue_total; ?></td>
        </tr>
        <tr>
          <th class="text-left" colspan="2"><?php echo $text_expense; ?></th>
        </tr>
        <?php if ($expense_accounts) { ?>
        <?php foreach ($expense_accounts as $expense_account) { ?>
        <tr>
          <td class="text-left indent-1"><?php echo $expense_account['name']; ?></td>
          <td class="text-right"><?php echo $expense_account['total']; ?></td>
        </tr>
        <?php } ?>
        <?php } ?>
        <tr>
          <th class="text-left indent-1"><?php echo $text_total_expense; ?></td>
          <th class="text-right"><?php echo $expense_total; ?></td>
        </tr>
        <tr>
          <th class="text-left"><?php echo $text_net_profit; ?></td>
          <th class="text-right"><?php echo $net_profit; ?></td>
        </tr>
      </table>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?load=report/sci&token=<?php echo $token; ?>';

	var filter_date_start = $('input[name=\'filter_date_start\']').val();

	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();

	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}

	location = url;
}

$('.date').datetimepicker({
	format: 'YYYY-MM-DD'
});
//--></script>
<?php if ($print_version) { ?>
<script type="text/javascript"><!--
$('#column-left').hide();
$('header').hide();
$('.breadcrumb').hide();
$('.panel-heading .pull-right').hide();
$('.well').hide();
window.print();
//--></script>
<?php } ?>
<?php echo $footer; ?>