<?php echo $header; ?>
<ol class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ol>
<?php if ($error_warning) { ?>
<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
  <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<?php } ?>
<?php if ($success) { ?>
<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
  <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<?php } ?>
<div class="panel panel-default">
  <div class="panel-heading">
    <div class="pull-right">
      <a href="<?php echo $insert; ?>" title="<?php echo $button_add; ?>" data-toggle="tooltip" class="btn btn-success"><i class="fa fa-plus"></i></a>
      <button type="button" class="btn btn-danger" title="<?php echo $button_delete; ?>" data-toggle="tooltip" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-account').submit() : false;">
        <i class="fa fa-trash"></i></button>
    </div>
    <h1 class="panel-title"><i class="fa fa-list fa-lg"></i> <?php echo $heading_title; ?></h1>
  </div>
  <div class="panel-body">
    <form method="post" action="<?php echo $delete; ?>" id="form-account">
      <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
          <tr>
            <th class="text-center" width="1">
              <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" />
            </th>
            <th class="text-left"><?php if ($sort == 'account_id') { ?>
              <a href="<?php echo $sort_account_id; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_account_id; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_account_id; ?>"><?php echo $column_account_id; ?></a>
              <?php } ?></th>
            <th class="text-left"><?php if ($sort == 'name') { ?>
              <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
              <?php } ?></th>
            <th class="text-left"><?php if ($sort == 'description') { ?>
              <a href="<?php echo $sort_description; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_description; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_description; ?>"><?php echo $column_description; ?></a>
              <?php } ?></th>
            <th class="text-left"><?php if ($sort == 'type') { ?>
              <a href="<?php echo $sort_type; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_type; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_type; ?>"><?php echo $column_type; ?></a>
              <?php } ?></th>
            <th class="text-left"><?php if ($sort == 'parent_id') { ?>
              <a href="<?php echo $sort_parent; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_parent; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_parent; ?>"><?php echo $column_parent; ?></a>
              <?php } ?></th>
            <th class="text-right"><?php if ($sort == 'status') { ?>
              <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
              <?php } ?></th>
            <th class="text-right"><?php echo $column_action; ?></th>
          </tr>
          <?php if ($accounts) { ?>
          <?php $header = ''; ?>
          <?php foreach ($accounts as $account) { ?>
          <?php if (in_array($account['type'], $asset) && $header != 'asset') { ?>
          <tr>
            <th class="text-left" colspan="8"><?php echo $text_assets; ?></th>
          </tr>
          <?php $header = 'asset'; ?>
          <?php } ?>
          <?php if (in_array($account['type'], $equity) && $header != 'equity') { ?>
          <tr>
            <th class="text-left" colspan="8"><?php echo $text_equity; ?></th>
          </tr>
          <?php $header = 'equity'; ?>
          <?php } ?>
          <?php if (in_array($account['type'], $expense) && $header != 'expense') { ?>
          <tr>
            <th class="text-left" colspan="8"><?php echo $text_expenses; ?></th>
          </tr>
          <?php $header = 'expense'; ?>
          <?php } ?>
          <?php if (in_array($account['type'], $liability) && $header != 'liability') { ?>
          <tr>
            <th class="text-left" colspan="8"><?php echo $text_liabilities; ?></th>
          </tr>
          <?php $header = 'liability'; ?>
          <?php } ?>
          <?php if (in_array($account['type'], $revenue) && $header != 'revenue') { ?>
          <tr>
            <th class="text-left" colspan="8"><?php echo $text_revenue; ?></th>
          </tr>
          <?php $header = 'revenue'; ?>
          <?php } ?>
          <tr>
            <td class="text-center"><?php if (in_array($account['account_id'], $selected)) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $account['account_id']; ?>" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $account['account_id']; ?>" />
              <?php } ?></td>
            <td class="text-left"><?php echo $account['account_id']; ?></td>
            <td class="text-left"><?php echo $account['name']; ?></td>
            <td class="text-left"><?php echo $account['description']; ?></td>
            <td class="text-left"><?php echo $account['formatted_type']; ?></td>
            <td class="text-left"><?php echo $account['parent']; ?></td>
            <td class="text-right"><?php echo $account['status']; ?></td>
            <td class="text-right">
              <a href="<?php echo $account['edit']; ?>" title="<?php echo $button_edit; ?>" data-toggle="tooltip" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>
            </td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </table>
      </div>
    </form>
    <?php echo $pagination; ?>
  </div>
</div>
<?php echo $footer; ?>