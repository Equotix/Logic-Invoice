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
      <button type="submit" form="form-contact-form" title="<?php echo $button_save; ?>" data-toggle="tooltip" class="btn btn-success"><i class="fa fa-save"></i></button>
      <a href="<?php echo $cancel; ?>" title="<?php echo $button_cancel; ?>" data-toggle="tooltip" class="btn btn-danger"><i class="fa fa-times"></i></a>
    </div>
    <h1 class="panel-title"><i class="fa fa-pencil-square fa-lg"></i> <?php echo $heading_title; ?></h1>
  </div>
  <div class="panel-body">
    <form method="post" action="<?php echo $action; ?>" id="form-contact-form" class="form-horizontal">
	  <div class="form-group">
        <label class="required col-sm-2 control-label" for="input-receiving_email" data-toggle="tooltip" title="<?php echo $help_receiving_email; ?>"><?php echo $entry_receiving_email; ?> <i class="fa fa-question-circle"></i></label>
        <div class="col-sm-10"><textarea name="contact_form_receiving_email" id="input-receiving-email" class="form-control" placeholder="<?php echo $entry_receiving_email; ?>" rows="3"><?php echo $contact_form_receiving_email; ?></textarea></div>
        <?php if ($error_receiving_email) { ?>
		<div class="text-danger"><?php echo $error_receiving_email; ?></div>
		<?php } ?>
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
            <label class="required col-sm-2 control-label" for="input-description-<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
            <div class="col-sm-10">
              <textarea name="contact_form_description[<?php echo $language['language_id']; ?>]" id="input-description-<?php echo $language['language_id']; ?>"><?php echo !empty($contact_form_description[$language['language_id']]) ? html_entity_decode($contact_form_description[$language['language_id']], ENT_QUOTES) : ''; ?></textarea>
            </div>
          </div>
        </div>
        <?php } ?>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
        <div class="col-sm-10"><select name="contact_form_status" id="input-status" class="form-control">
            <option value="1"<?php echo $contact_form_status ? ' selected="selected"' : ''; ?>><?php echo $text_enabled; ?></option>
            <option value="0"<?php echo $contact_form_status ? '' : ' selected="selected"'; ?>><?php echo $text_disabled; ?></option>
          </select>
        </div>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript"><!--
$(document).ready(function() {
	<?php foreach ($languages as $language) { ?>
	$('#input-description-<?php echo $language['language_id']; ?>').summernote({
		height: 300
	});
	<?php } ?>
	
	$('.nav-tabs li :first').trigger('click');
});
//--></script>
<?php echo $footer; ?>