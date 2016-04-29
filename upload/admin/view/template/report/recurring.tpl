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
            <label class="control-label" for="input-date-added-start"><?php echo $entry_date_added_start; ?></label>
            <div class="input-group">
              <input type="text" name="filter_date_added_start" value="<?php echo $filter_date_added_start; ?>" placeholder="<?php echo $entry_date_added_start; ?>" id="input-date-added-start" class="form-control date" />
              <span class="input-group-btn">
                <button type="button" class="btn btn-default" onclick="$(this).parent().siblings('input').focus();"><i class="fa fa-calendar"></i></button>
              </span>
            </div>
          </div>
          <div class="form-group">
            <label class="control-label" for="input-date-added-end"><?php echo $entry_date_added_end; ?></label>
            <div class="input-group">
              <input type="text" name="filter_date_added_end" value="<?php echo $filter_date_added_end; ?>" placeholder="<?php echo $entry_date_added_end; ?>" id="input-date-added-end" class="form-control date" />
              <span class="input-group-btn">
                <button type="button" class="btn btn-default" onclick="$(this).parent().siblings('input').focus();"><i class="fa fa-calendar"></i></button>
              </span>
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="form-group">
            <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
            <select name="filter_status" id="input-status" class="form-control">
              <option value=""><?php echo $text_all_statuses; ?></option>
              <option value="1"<?php echo $filter_status ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
              <option value="0"<?php echo (!$filter_status && !is_null($filter_status)) ? ' selected="selected"' : ''; ?>><?php echo $text_disabled; ?></option>
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
          <th class="text-left"><?php echo $column_cycle; ?></th>
          <th class="text-left"><?php echo $column_total_recurrings; ?></th>
          <th class="text-left"><?php echo $column_total_items; ?></th>
          <th class="text-right"><?php echo $column_total_tax; ?></th>
          <th class="text-right"><?php echo $column_total; ?></th>
        </tr>
        <?php if ($recurrings) { ?>
        <?php foreach ($recurrings as $recurring) { ?>
        <tr>
          <td class="text-left"><?php echo $recurring['date_start']; ?></td>
          <td class="text-left"><?php echo $recurring['date_end']; ?></td>
          <td class="text-left"><?php echo $recurring['cycle']; ?></td>
          <td class="text-left"><?php echo $recurring['recurrings']; ?></td>
          <td class="text-left"><?php echo $recurring['items']; ?></td>
          <td class="text-right"><?php echo $recurring['tax']; ?></td>
          <td class="text-right"><?php echo $recurring['total']; ?></td>
        </tr>
        <?php } ?>
        <?php } else { ?>
        <tr>
          <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
        </tr>
        <?php } ?>
      </table>
    </div>
    <?php echo $pagination; ?>
  </div>
</div>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?load=report/recurring&token=<?php echo $token; ?>';

	var filter_date_added_start = $('input[name=\'filter_date_added_start\']').val();

	if (filter_date_added_start) {
		url += '&filter_date_added_start=' + encodeURIComponent(filter_date_added_start);
	}

	var filter_date_added_end = $('input[name=\'filter_date_added_end\']').val();

	if (filter_date_added_end) {
		url += '&filter_date_added_end=' + encodeURIComponent(filter_date_added_end);
	}

	var filter_group = $('select[name=\'filter_group\']').val();

	if (filter_group) {
		url += '&filter_group=' + encodeURIComponent(filter_group);
	}

	var filter_status = $('select[name=\'filter_status\']').val();

	if (filter_status != '') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

	location = url;
}

$('.date').datetimepicker({
	format: 'YYYY-MM-DD'
});
//--></script>
<?php echo $footer; ?>