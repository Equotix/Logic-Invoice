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
        <div class="col-sm-12">
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
    <div class="col-sm-6">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
          <tr>
            <th class="text-left"><?php echo $text_assets; ?></th>
            <th class="text-right"><?php echo $date_end; ?></th>
          </tr>
          <?php if ($current_asset_accounts) { ?>
          <tr>
            <th class="indent-1" colspan="2"><?php echo $text_current_assets; ?></th>
          </tr>
          <?php foreach ($current_asset_accounts as $current_asset_account) { ?>
          <tr>
            <td class="text-left indent-2"><?php echo $current_asset_account['name']; ?></td>
            <td class="text-right"><?php echo $current_asset_account['total']; ?></td>
          </tr>
          <?php } ?>
          <tr>
            <th class="text-left indent-2"><?php echo $text_total_current_assets; ?></td>
            <th class="text-right"><?php echo $current_asset_total; ?></td>
          </tr>
          <?php } ?>
          <?php if ($non_current_asset_accounts) { ?>
          <tr>
            <th class="indent-1" colspan="2"><?php echo $text_non_current_assets; ?></th>
          </tr>
          <?php foreach ($non_current_asset_accounts as $non_current_asset_account) { ?>
          <tr>
            <td class="text-left indent-2"><?php echo $non_current_asset_account['name']; ?></td>
            <td class="text-right"><?php echo $non_current_asset_account['total']; ?></td>
          </tr>
          <?php } ?>
          <tr>
            <th class="text-left indent-2"><?php echo $text_total_non_current_assets; ?></td>
            <th class="text-right"><?php echo $non_current_asset_total; ?></td>
          </tr>
          <?php } ?>
          <tr>
            <th class="text-left indent-1"><?php echo $text_total_assets; ?></td>
            <th class="text-right"><?php echo $asset_total; ?></td>
          </tr>
        </table>
      </div>
    </div>
    <div class="col-sm-6">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
          <tr>
            <th class="text-left"><?php echo $text_liabilities; ?></th>
            <th class="text-right"><?php echo $date_end; ?></th>
          </tr>
          <?php if ($current_liability_accounts) { ?>
          <tr>
            <th class="indent-1" colspan="2"><?php echo $text_current_liabilities; ?></th>
          </tr>
          <?php foreach ($current_liability_accounts as $current_liability_account) { ?>
          <tr>
            <td class="text-left indent-2"><?php echo $current_liability_account['name']; ?></td>
            <td class="text-right"><?php echo $current_liability_account['total']; ?></td>
          </tr>
          <?php } ?>
          <tr>
            <th class="text-left indent-2"><?php echo $text_total_current_liabilities; ?></td>
            <th class="text-right"><?php echo $current_liability_total; ?></td>
          </tr>
          <?php } ?>
          <?php if ($non_current_liability_accounts) { ?>
          <tr>
            <th class="indent-1" colspan="2"><?php echo $text_non_current_liabilities; ?></th>
          </tr>
          <?php foreach ($non_current_liability_accounts as $non_current_liability_account) { ?>
          <tr>
            <td class="text-left indent-2"><?php echo $non_current_liability_account['name']; ?></td>
            <td class="text-right"><?php echo $non_current_liability_account['total']; ?></td>
          </tr>
          <?php } ?>
          <tr>
            <th class="text-left indent-2"><?php echo $text_total_non_current_liabilities; ?></td>
            <th class="text-right"><?php echo $non_current_liability_total; ?></td>
          </tr>
          <?php } ?>
          <tr>
            <th class="text-left indent-1"><?php echo $text_total_liabilities; ?></td>
            <th class="text-right"><?php echo $liability_total; ?></td>
          </tr>
          <tr>
            <th class="text-left" colspan="2"><?php echo $text_equity; ?></th>
          </tr>
          <?php if ($equity_accounts) { ?>
          <?php foreach ($equity_accounts as $equity_account) { ?>
          <tr>
            <td class="text-left indent-1"><?php echo $equity_account['name']; ?></td>
            <td class="text-right"><?php echo $equity_account['total']; ?></td>
          </tr>
          <?php } ?>
          <?php } ?>
          <tr>
            <th class="text-left indent-1"><?php echo $text_total_equity; ?></td>
            <th class="text-right"><?php echo $equity_total; ?></td>
          </tr>
          <tr>
            <th class="text-left"><?php echo $text_total_liability_equity; ?></th>
            <th class="text-right"><?php echo $liability_equity_total; ?></th>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?load=report/sfp&token=<?php echo $token; ?>';

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