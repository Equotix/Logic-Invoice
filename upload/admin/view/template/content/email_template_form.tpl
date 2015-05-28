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
      <button type="submit" form="form-email-template" title="<?php echo $button_save; ?>" data-toggle="tooltip" class="btn btn-success"><i class="fa fa-save"></i></button>
      <a href="<?php echo $cancel; ?>" title="<?php echo $button_cancel; ?>" data-toggle="tooltip" class="btn btn-danger"><i class="fa fa-times"></i></a>
    </div>
    <h1 class="panel-title"><i class="fa fa-pencil-square fa-lg"></i> <?php echo $heading_title; ?></h1>
  </div>
  <div class="panel-body">
    <form method="post" action="<?php echo $action; ?>" id="form-email-template" class="form-horizontal">
      <div class="form-group">
        <label class="required col-sm-2 control-label" for="input-type"><?php echo $entry_type; ?></label>
        <div class="col-sm-10">
          <select name="type" id="input-type" class="form-control"><?php foreach ($email_template_types as $email_template_type) { ?>
            <option value="<?php echo $email_template_type['type']; ?>"<?php echo $email_template_type['type'] == $type ? ' selected="selected"' : ''; ?>><?php echo $email_template_type['name']; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <ul class="nav nav-tabs">
        <?php foreach ($languages as $language) { ?>
        <li><a href="#language-<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" /> <?php echo $language['name']; ?></a></li>
        <?php } ?>
      </ul>
      <div class="tab-content">
        <?php foreach ($languages as $language) { ?>
        <div class="tab-pane" id="language-<?php echo $language['language_id']; ?>">
          <div class="form-group">
            <label class="required col-sm-2 control-label" for="input-subject-<?php echo $language['language_id']; ?>"><?php echo $entry_subject; ?></label>
            <div class="col-sm-10">
              <div class="input-group">
                <span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>" /></span>
                <input type="text" name="description[<?php echo $language['language_id']; ?>][subject]" value="<?php echo !empty($description[$language['language_id']]['subject']) ? $description[$language['language_id']]['subject'] : ''; ?>" id="input-subject-<?php echo $language['language_id']; ?>" class="form-control" placeholder="<?php echo $entry_subject; ?>" required />
              </div>
              <?php if (!empty($error_subject[$language['language_id']])) { ?>
              <div class="text-danger"><?php echo $error_subject[$language['language_id']]; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="required col-sm-2 control-label" for="input-html-<?php echo $language['language_id']; ?>"><?php echo $entry_html; ?></label>
            <div class="col-sm-10">
              <textarea name="description[<?php echo $language['language_id']; ?>][html]" id="input-html-<?php echo $language['language_id']; ?>"><?php echo !empty($description[$language['language_id']]['html']) ? html_entity_decode($description[$language['language_id']]['html'], ENT_QUOTES) : ''; ?></textarea>
              <div class="variables"></div>
              <?php if (!empty($error_html[$language['language_id']])) { ?>
              <div class="text-danger"><?php echo $error_html[$language['language_id']]; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="required col-sm-2 control-label" for="input-text-<?php echo $language['language_id']; ?>"><?php echo $entry_text; ?></label>
            <div class="col-sm-10">
              <textarea name="description[<?php echo $language['language_id']; ?>][text]" id="input-text-<?php echo $language['language_id']; ?>" class="form-control" rows="15"><?php echo !empty($description[$language['language_id']]['text']) ? html_entity_decode($description[$language['language_id']]['text'], ENT_QUOTES) : ''; ?></textarea>
              <div class="variables"></div>
              <?php if (!empty($error_text[$language['language_id']])) { ?>
              <div class="text-danger"><?php echo $error_text[$language['language_id']]; ?></div>
              <?php } ?>
            </div>
          </div>
        </div>
        <?php } ?>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-priority"><?php echo $entry_priority; ?></label>
        <div class="col-sm-10">
          <input type="text" name="priority" value="<?php echo $priority; ?>" id="input-priority" class="form-control" placeholder="<?php echo $entry_priority; ?>" />
        </div>
      </div>
      <div class="form-group">
        <label class="required col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
        <div class="col-sm-10">
          <select name="status" id="input-status" class="form-control">
            <option value="1"<?php echo $status ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
            <option value="0"<?php echo $status ? '' : ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
        <div class="col-sm-10">
          <textarea name="email" id="input-email" class="form-control" placeholder="<?php echo $entry_email; ?>" rows="2"><?php echo $email; ?></textarea>
        </div>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript"><!--
$('select[name=\'type\']').on('change', function() {
	<?php foreach ($email_template_types as $email_template_type) { ?>
	if ($(this).val() == '<?php echo $email_template_type['type']; ?>') {
		$('.variables').html('<?php echo $email_template_type['variables']; ?>');
	}
	<?php } ?>
});

$('select[name=\'type\']').trigger('change');

<?php foreach ($languages as $language) { ?>
$('#input-html-<?php echo $language['language_id']; ?>').summernote({
	height: 300
});
<?php } ?>

$('.nav-tabs li :first').trigger('click');
//--></script> 
<?php echo $footer; ?>