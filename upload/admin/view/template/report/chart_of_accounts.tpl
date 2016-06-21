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
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-hover">
        <tr>
          <th class="text-left"><?php echo $column_account_id; ?></th>
          <th class="text-left"><?php echo $column_name; ?></th>
          <th class="text-left"><?php echo $column_description; ?></th>
          <th class="text-left"><?php echo $column_type; ?></th>
          <th class="text-right"><?php echo $column_ytd; ?></th>
        </tr>
        <?php if ($accounts) { ?>
        <?php $header = ''; ?>
        <?php foreach ($accounts as $account) { ?>
        <?php if (in_array($account['type'], $asset) && $header != 'asset') { ?>
        <tr>
          <th class="text-left" colspan="5"><?php echo $text_assets; ?></th>
        </tr>
        <?php $header = 'asset'; ?>
        <?php } ?>
        <?php if (in_array($account['type'], $equity) && $header != 'equity') { ?>
        <tr>
          <th class="text-left" colspan="5"><?php echo $text_equity; ?></th>
        </tr>
        <?php $header = 'equity'; ?>
        <?php } ?>
        <?php if (in_array($account['type'], $expense) && $header != 'expense') { ?>
        <tr>
          <th class="text-left" colspan="5"><?php echo $text_expenses; ?></th>
        </tr>
        <?php $header = 'expense'; ?>
        <?php } ?>
        <?php if (in_array($account['type'], $liability) && $header != 'liability') { ?>
        <tr>
          <th class="text-left" colspan="5"><?php echo $text_liabilities; ?></th>
        </tr>
        <?php $header = 'liability'; ?>
        <?php } ?>
        <?php if (in_array($account['type'], $revenue) && $header != 'revenue') { ?>
        <tr>
          <th class="text-left" colspan="5"><?php echo $text_revenue; ?></th>
        </tr>
        <?php $header = 'revenue'; ?>
        <?php } ?>
        <?php if ($account['children']) { ?>
        <tr>
          <td class="text-left indent-1" colspan="5"><b><?php echo $account['name']; ?></td>
        </tr>
        <?php foreach ($account['children'] as $child) { ?>
        <?php if ($child['grandchildren']) { ?>
        <tr>
          <td class="text-left indent-2" colspan="5"><b><?php echo $child['name']; ?></td>
        </tr>
        <?php foreach ($child['grandchildren'] as $grandchild) { ?>
        <tr>
          <td class="text-left indent-2">;<?php echo $grandchild['account_id']; ?></td>
          <td class="text-left"><?php echo $grandchild['name']; ?></td>
          <td class="text-left"><?php echo $grandchild['description']; ?></td>
          <td class="text-left"><?php echo $grandchild['formatted_type']; ?></td>
          <td class="text-right"><?php echo $grandchild['ytd']; ?></td>
        </tr>
        <?php } ?>
        <?php } else { ?>
        <tr>
          <td class="text-left indent-1"><?php echo $child['account_id']; ?></td>
          <td class="text-left"><?php echo $child['name']; ?></td>
          <td class="text-left"><?php echo $child['description']; ?></td>
          <td class="text-left"><?php echo $child['formatted_type']; ?></td>
          <td class="text-right"><?php echo $child['ytd']; ?></td>
        </tr>
        <?php } ?>
        <?php } ?>
        <?php } else { ?>
        <tr>
          <td class="text-left"><?php echo $account['account_id']; ?></td>
          <td class="text-left"><?php echo $account['name']; ?></td>
          <td class="text-left"><?php echo $account['description']; ?></td>
          <td class="text-left"><?php echo $account['formatted_type']; ?></td>
          <td class="text-right"><?php echo $account['ytd']; ?></td>
        </tr>
        <?php } ?>
        <?php } ?>
        <?php } else { ?>
        <tr>
          <td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
        </tr>
        <?php } ?>
      </table>
    </div>
  </div>
</div>
<?php if ($print_version) { ?>
<script type="text/javascript"><!--
$('#column-left').hide();
$('header').hide();
$('.breadcrumb').hide();
$('.panel-heading .pull-right').hide();
window.print();
//--></script>
<?php } ?>
<?php echo $footer; ?>