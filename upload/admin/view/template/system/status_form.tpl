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
<div class="panel panel-default">
  <div class="panel-heading">
    <div class="pull-right">
      <button type="submit" form="form-status" title="<?php echo $button_save; ?>" data-toggle="tooltip" class="btn btn-success"><i class="fa fa-save"></i></button>
      <a href="<?php echo $cancel; ?>" title="<?php echo $button_cancel; ?>" data-toggle="tooltip" class="btn btn-danger"><i class="fa fa-times"></i></a>
    </div>
    <h1 class="panel-title"><i class="fa fa-pencil-square fa-lg"></i> <?php echo $heading_title; ?></h1>
  </div>
  <div class="panel-body">
    <form method="post" action="<?php echo $action; ?>" id="form-status" class="form-horizontal">
      <?php foreach ($languages as $language) { ?>
      <div class="form-group">
        <label class="required col-sm-2 control-label" for="input-name-<?php echo $language['language_id']; ?>"><?php echo $entry_name; ?></label>
        <div class="col-sm-10">
          <div class="input-group">
            <span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>" /></span>
            <input type="text" name="name[<?php echo $language['language_id']; ?>]" value="<?php echo !empty($name[$language['language_id']]) ? $name[$language['language_id']] : ''; ?>" id="input-name-<?php echo $language['language_id']; ?>" class="form-control" placeholder="<?php echo $entry_name; ?>" required />
          </div>
          <?php if (!empty($error_name[$language['language_id']])) { ?>
          <div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
          <?php } ?>
        </div>
      </div>
      <?php } ?>
    </form>
  </div>
</div>
<?php echo $footer; ?>