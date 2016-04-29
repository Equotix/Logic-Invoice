<?php echo $header; ?>
<ol class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ol>
<div class="panel panel-default">
  <div class="panel-heading">
    <h1 class="panel-title"><i class="fa fa-line-chart fa-lg"></i> <?php echo $heading_title; ?></h1>
  </div>
  <div class="panel-body">
    <div class="well well-lg">
      <div class="row">
        <div class="col-sm-6">
          <div class="form-group">
            <label class="control-label" for="input-date-issued-start"><?php echo $entry_date_issued_start; ?></label>
            <div class="input-group">
              <input type="text" name="filter_date_issued_start" value="<?php echo $filter_date_issued_start; ?>" placeholder="<?php echo $entry_date_issued_start; ?>" id="input-date-issued-start" class="form-control date" />
              <span class="input-group-btn">
                <button type="button" class="btn btn-default" onclick="$(this).parent().siblings('input').focus();"><i class="fa fa-calendar"></i></button>
              </span>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label" for="input-date-issued-end"><?php echo $entry_date_issued_end; ?></label>
            <div class="input-group">
              <input type="text" name="filter_date_issued_end" value="<?php echo $filter_date_issued_end; ?>" placeholder="<?php echo $entry_date_issued_end; ?>" id="input-date-issued-end" class="form-control date" />
              <span class="input-group-btn">
                <button type="button" class="btn btn-default" onclick="$(this).parent().siblings('input').focus();"><i class="fa fa-calendar"></i></button>
              </span>
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
            <select name="filter_status_id" id="input-status" class="form-control">
              <option value=""><?php echo $text_all_statuses; ?></option>
              <?php foreach ($statuses as $status) { ?>
              <option value="<?php echo $status['status_id']; ?>"<?php echo $status['status_id'] == $filter_status_id ? ' selected="selected"' : ''; ?>><?php echo $status['name']; ?></option>
              <?php } ?>
            </select>
          </div>
          <div class="form-group">
            <label class="control-label" for="input-group"><?php echo $entry_group_by; ?></label>
            <select name="filter_group" id="input-group" class="form-control">
              <?php foreach ($groups as $group) { ?>
              <option value="<?php echo $group['value']; ?>"<?php echo $group['value'] == $filter_group ? ' selected="selected"' : ''; ?>><?php echo $group['text']; ?></option>
              <?php } ?>
            </select>
          </div>
          <button type="button" title="<?php echo $button_filter; ?>" data-toggle="tooltip" class="btn btn-primary pull-right" onclick="filter();"><i class="fa fa-search"></i></button>
        </div>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-hover">
        <tr>
          <th class="text-left"><?php echo $column_date_start; ?></th>
          <th class="text-left"><?php echo $column_date_end; ?></th>
          <th class="text-left"><?php echo $column_total_invoices; ?></th>
          <th class="text-left"><?php echo $column_total_items; ?></th>
          <th class="text-right"><?php echo $column_total_tax; ?></th>
          <th class="text-right"><?php echo $column_total; ?></th>
        </tr>
        <?php if ($invoices) { ?>
        <?php foreach ($invoices as $invoice) { ?>
        <tr>
          <td class="text-left"><?php echo $invoice['date_start']; ?></td>
          <td class="text-left"><?php echo $invoice['date_end']; ?></td>
          <td class="text-left"><?php echo $invoice['invoices']; ?></td>
          <td class="text-left"><?php echo $invoice['items']; ?></td>
          <td class="text-right"><?php echo $invoice['tax']; ?></td>
          <td class="text-right"><?php echo $invoice['total']; ?></td>
        </tr>
        <?php } ?>
        <?php } else { ?>
        <tr>
          <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
        </tr>
        <?php } ?>
      </table>
    </div>
    <?php echo $pagination; ?>
  </div>
</div>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?load=report/invoice&token=<?php echo $token; ?>';

	var filter_date_issued_start = $('input[name=\'filter_date_issued_start\']').val();

	if (filter_date_issued_start) {
		url += '&filter_date_issued_start=' + encodeURIComponent(filter_date_issued_start);
	}

	var filter_date_issued_end = $('input[name=\'filter_date_issued_end\']').val();

	if (filter_date_issued_end) {
		url += '&filter_date_issued_end=' + encodeURIComponent(filter_date_issued_end);
	}

	var filter_group = $('select[name=\'filter_group\']').val();

	if (filter_group) {
		url += '&filter_group=' + encodeURIComponent(filter_group);
	}

	var filter_status_id = $('select[name=\'filter_status_id\']').val();

	if (filter_status_id != '') {
		url += '&filter_status_id=' + encodeURIComponent(filter_status_id);
	}

	location = url;
}

$('.date').datetimepicker({
	format: 'YYYY-MM-DD'
});
//--></script>
<?php echo $footer; ?>