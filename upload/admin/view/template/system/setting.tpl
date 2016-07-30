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
      <button type="submit" form="form-setting" title="<?php echo $button_save; ?>" data-toggle="tooltip" class="btn btn-success"><i class="fa fa-save"></i></button>
      <a href="<?php echo $cancel; ?>" title="<?php echo $button_cancel; ?>" data-toggle="tooltip" class="btn btn-danger"><i class="fa fa-times"></i></a>
    </div>
    <h1 class="panel-title"><i class="fa fa-pencil-square fa-lg"></i> <?php echo $heading_title; ?></h1>
  </div>
  <div class="panel-body">
    <form method="post" action="<?php echo $action; ?>" id="form-setting" class="form-horizontal">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
        <li><a href="#tab-website" data-toggle="tab"><?php echo $tab_website; ?></a></li>
        <li><a href="#tab-accounting" data-toggle="tab"><?php echo $tab_accounting; ?></a></li>
        <li><a href="#tab-billing" data-toggle="tab"><?php echo $tab_billing; ?></a></li>
        <li><a href="#tab-recurring" data-toggle="tab"><?php echo $tab_recurring; ?></a></li>
        <li><a href="#tab-mail" data-toggle="tab"><?php echo $tab_mail; ?></a></li>
        <li><a href="#tab-server" data-toggle="tab"><?php echo $tab_server; ?></a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab-general">
          <div class="form-group">
            <label class="required col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="config_name" value="<?php echo $config_name; ?>" id="input-name" class="form-control" placeholder="<?php echo $entry_name; ?>" required autofocus />
              <?php if ($error_name) { ?>
              <div class="text-danger"><?php echo $error_name; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="required col-sm-2 control-label" for="input-registered-name"><?php echo $entry_registered_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="config_registered_name" value="<?php echo $config_registered_name; ?>" id="input-registered-name" class="form-control" placeholder="<?php echo $entry_registered_name; ?>" required />
              <?php if ($error_registered_name) { ?>
              <div class="text-danger"><?php echo $error_registered_name; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-address"><?php echo $entry_address; ?></label>
            <div class="col-sm-10">
              <textarea name="config_address" id="input-address" class="form-control" placeholder="<?php echo $entry_address; ?>" rows="5"><?php echo $config_address; ?></textarea>
            </div>
          </div>
          <div class="form-group">
            <label class="required col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
            <div class="col-sm-10">
              <input type="text" name="config_email" value="<?php echo $config_email; ?>" id="input-email" class="form-control" placeholder="<?php echo $entry_email; ?>" required />
              <?php if ($error_email) { ?>
              <div class="text-danger"><?php echo $error_email; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-telephone"><?php echo $entry_telephone; ?></label>
            <div class="col-sm-10">
              <input type="text" name="config_telephone" value="<?php echo $config_telephone; ?>" id="input-telephone" class="form-control" placeholder="<?php echo $entry_telephone; ?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-fax"><?php echo $entry_fax; ?></label>
            <div class="col-sm-10">
              <input type="text" name="config_fax" value="<?php echo $config_fax; ?>" id="input-fax" class="form-control" placeholder="<?php echo $entry_fax; ?>" />
            </div>
          </div>
        </div>
        <div class="tab-pane" id="tab-website">
		  <div class="form-group">
            <label class="required col-sm-2 control-label" for="input-theme"><?php echo $entry_theme; ?></label>
            <div class="col-sm-10"><select name="config_theme" class="form-control">
              <?php foreach ($themes as $theme) { ?>
			  <option value="<?php echo $theme; ?>"<?php echo $theme == $config_theme ? ' selected="selected"' : ''; ?>><?php echo $theme; ?></option>
			  <?php } ?></select>
            </div>
          </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="input-logo"><?php echo $entry_logo; ?></label>
			<div class="col-sm-10">
			  <a href="" id="thumb-logo" data-toggle="image" class="img-thumbnail"><img src="<?php echo $config_logo_thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
			  <input type="hidden" name="config_logo" value="<?php echo $config_logo; ?>" id="input-logo" />
			</div>
		  </div>
          <div class="form-group">
			<label class="col-sm-2 control-label" for="input-icon"><?php echo $entry_icon; ?></label>
			<div class="col-sm-10">
			  <a href="" id="thumb-icon" data-toggle="image" class="img-thumbnail"><img src="<?php echo $config_icon_thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
			  <input type="hidden" name="config_icon" value="<?php echo $config_icon; ?>" id="input-icon" />
			</div>
		  </div>
          <div class="form-group">
            <label class="required col-sm-2 control-label" for="input-limit-admin"><?php echo $entry_limit_admin; ?></label>
            <div class="col-sm-10">
              <input type="text" name="config_limit_admin" value="<?php echo $config_limit_admin; ?>" id="input-limit-admin" class="form-control" placeholder="<?php echo $entry_limit_admin; ?>" required />
              <?php if ($error_limit_admin) { ?>
              <div class="text-danger"><?php echo $error_limit_admin; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="required col-sm-2 control-label" for="input-limit-application"><?php echo $entry_limit_application; ?></label>
            <div class="col-sm-10">
              <input type="text" name="config_limit_application" value="<?php echo $config_limit_application; ?>" id="input-limit-application" class="form-control" placeholder="<?php echo $entry_limit_application; ?>" required />
              <?php if ($error_limit_application) { ?>
              <div class="text-danger"><?php echo $error_limit_application; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-admin-language"><?php echo $entry_admin_language; ?></label>
            <div class="col-sm-10"><select name="config_admin_language" id="input-admin-language" class="form-control">
                <?php foreach ($languages as $language) { ?>
                <option value="<?php echo $language['code']; ?>"<?php echo $language['code'] == $config_admin_language ? ' selected="selected"' : ''; ?>><?php echo $language['name']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-language"><?php echo $entry_language; ?></label>
            <div class="col-sm-10"><select name="config_language" id="input-language" class="form-control">
                <?php foreach ($languages as $language) { ?>
                <option value="<?php echo $language['code']; ?>"<?php echo $language['code'] == $config_language ? ' selected="selected"' : ''; ?>><?php echo $language['name']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-forgotten-admin"><?php echo $entry_forgotten_admin; ?></label>
            <div class="col-sm-10"><select name="config_forgotten_admin" id="input-forgotten-admin" class="form-control">
                <option value="1"<?php echo $config_forgotten_admin ? ' selected="selected"' : ''; ?>><?php echo $text_yes; ?></option>
                <option value="0"<?php echo $config_forgotten_admin ? '' : ' selected="selected"'; ?>><?php echo $text_no; ?></option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-forgotten-application"><?php echo $entry_forgotten_application; ?></label>
            <div class="col-sm-10"><select name="config_forgotten_application" id="input-forgotten-application" class="form-control">
                <option value="1"<?php echo $config_forgotten_application ? ' selected="selected"' : ''; ?>><?php echo $text_yes; ?></option>
                <option value="0"<?php echo $config_forgotten_application ? '' : ' selected="selected"'; ?>><?php echo $text_no; ?></option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-registration"><?php echo $entry_registration; ?></label>
            <div class="col-sm-10"><select name="config_registration" id="input-registration" class="form-control">
                <option value="1"<?php echo $config_registration ? ' selected="selected"' : ''; ?>><?php echo $text_yes; ?></option>
                <option value="0"<?php echo $config_registration ? '' : ' selected="selected"'; ?>><?php echo $text_no; ?></option>
              </select>
            </div>
          </div>
          <ul id="home" class="nav nav-tabs">
            <?php foreach ($languages as $language) { ?>
            <li><a href="#language-<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" /> <?php echo $language['name']; ?></a></li>
            <?php } ?>
          </ul>
          <div class="tab-content">
            <?php foreach ($languages as $language) { ?>
            <div class="tab-pane" id="language-<?php echo $language['language_id']; ?>">
			  <div class="form-group">
				<label class="required col-sm-2 control-label" for="input-meta-title-<?php echo $language['language_id']; ?>"><?php echo $entry_meta_title; ?></label>
				<div class="col-sm-10">
				  <input type="text" name="config_meta_title[<?php echo $language['language_id']; ?>]" value="<?php echo !empty($config_meta_title[$language['language_id']]) ? $config_meta_title[$language['language_id']] : ''; ?>" id="input-meta-title-<?php echo $language['language_id']; ?>" class="form-control" placeholder="<?php echo $entry_meta_title; ?>" required />
				  <?php if (!empty($error_meta_title[$language['language_id']])) { ?>
				  <div class="text-danger"><?php echo $error_meta_title[$language['language_id']]; ?></div>
				  <?php } ?>
				</div>
			  </div>
			  <div class="form-group">
				<label class="col-sm-2 control-label" for="input-meta-description-<?php echo $language['language_id']; ?>"><?php echo $entry_meta_description; ?></label>
				<div class="col-sm-10">
				  <textarea name="config_meta_description[<?php echo $language['language_id']; ?>]" id="input-meta-description-<?php echo $language['language_id']; ?>" class="form-control" rows="5" placeholder="<?php echo $entry_meta_description; ?>"><?php echo !empty($config_meta_description[$language['language_id']]) ? $config_meta_description[$language['language_id']] : ''; ?></textarea>
				</div>
			  </div>
              <div class="form-group">
                <label class="required col-sm-2 control-label" for="input-home-<?php echo $language['language_id']; ?>"><?php echo $entry_home; ?></label>
                <div class="col-sm-10">
                  <textarea name="config_home[<?php echo $language['language_id']; ?>]" id="input-home-<?php echo $language['language_id']; ?>"><?php echo !empty($config_home[$language['language_id']]) ? html_entity_decode($config_home[$language['language_id']], ENT_QUOTES) : ''; ?></textarea>
                </div>
              </div>
            </div>
            <?php } ?>
          </div>
        </div>
        <div class="tab-pane" id="tab-accounting">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-currency"><?php echo $entry_currency; ?></label>
            <div class="col-sm-10"><select name="config_currency" id="input-currency" class="form-control">
                <?php foreach ($currencies as $currency) { ?>
                <option value="<?php echo $currency['code']; ?>"<?php echo $currency['code'] == $config_currency ? ' selected="selected"' : ''; ?>><?php echo $currency['title']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-financial-year"><?php echo $entry_financial_year; ?></label>
            <div class="col-sm-10">
              <input type="text" name="config_financial_year" value="<?php echo $config_financial_year; ?>" id="input-financial-year" class="form-control" placeholder="<?php echo $entry_financial_year; ?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-auto-update-currency"><?php echo $entry_auto_update_currency; ?></label>
            <div class="col-sm-10"><select name="config_auto_update_currency" id="input-auto-update-currency" class="form-control">
                <option value="1"<?php echo $config_auto_update_currency ? ' selected="selected"' : ''; ?>><?php echo $text_yes; ?></option>
                <option value="0"<?php echo $config_auto_update_currency ? '' : ' selected="selected"'; ?>><?php echo $text_no; ?></option>
              </select>
            </div>
          </div>
        </div>
        <div class="tab-pane" id="tab-billing">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-invoice-prefix"><?php echo $entry_invoice_prefix; ?></label>
            <div class="col-sm-10">
              <input type="text" name="config_invoice_prefix" value="<?php echo $config_invoice_prefix; ?>" id="input-invoice-prefix" class="form-control" placeholder="<?php echo $entry_invoice_prefix; ?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-invoice-void-days"><?php echo $entry_invoice_void_days; ?></label>
            <div class="col-sm-10">
              <input type="text" name="config_invoice_void_days" value="<?php echo $config_invoice_void_days; ?>" id="input-invoice-void-days" class="form-control" placeholder="<?php echo $entry_invoice_void_days; ?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-draft-status"><?php echo $entry_draft_status; ?></label>
            <div class="col-sm-10">
              <div class="well well-sm" style="overflow:auto;height:250px;">
                <?php foreach ($statuses as $status) { ?>
                <div class="checkbox">
                  <label>
                    <?php if (in_array($status['status_id'], $config_draft_status)) { ?>
                    <input type="checkbox" name="config_draft_status[]" value="<?php echo $status['status_id']; ?>" checked="checked" />
                    <?php echo $status['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="config_draft_status[]" value="<?php echo $status['status_id']; ?>" />
                    <?php echo $status['name']; ?>
                    <?php } ?>
                  </label>
                </div>
                <?php } ?>
              </div>
              <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-overdue-status"><?php echo $entry_overdue_status; ?></label>
            <div class="col-sm-10">
              <div class="well well-sm" style="overflow:auto;height:250px;">
                <?php foreach ($statuses as $status) { ?>
                <div class="checkbox">
                  <label>
                    <?php if (in_array($status['status_id'], $config_overdue_status)) { ?>
                    <input type="checkbox" name="config_overdue_status[]" value="<?php echo $status['status_id']; ?>" checked="checked" />
                    <?php echo $status['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="config_overdue_status[]" value="<?php echo $status['status_id']; ?>" />
                    <?php echo $status['name']; ?>
                    <?php } ?>
                  </label>
                </div>
                <?php } ?>
              </div>
              <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-paid-status"><?php echo $entry_paid_status; ?></label>
            <div class="col-sm-10">
              <div class="well well-sm" style="overflow:auto;height:250px;">
                <?php foreach ($statuses as $status) { ?>
                <div class="checkbox">
                  <label>
                    <?php if (in_array($status['status_id'], $config_paid_status)) { ?>
                    <input type="checkbox" name="config_paid_status[]" value="<?php echo $status['status_id']; ?>" checked="checked" />
                    <?php echo $status['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="config_paid_status[]" value="<?php echo $status['status_id']; ?>" />
                    <?php echo $status['name']; ?>
                    <?php } ?>
                  </label>
                </div>
                <?php } ?>
              </div>
              <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-pending-status"><?php echo $entry_pending_status; ?></label>
            <div class="col-sm-10">
              <div class="well well-sm" style="overflow:auto;height:250px;">
                <?php foreach ($statuses as $status) { ?>
                <div class="checkbox">
                  <label>
                    <?php if (in_array($status['status_id'], $config_pending_status)) { ?>
                    <input type="checkbox" name="config_pending_status[]" value="<?php echo $status['status_id']; ?>" checked="checked" />
                    <?php echo $status['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="config_pending_status[]" value="<?php echo $status['status_id']; ?>" />
                    <?php echo $status['name']; ?>
                    <?php } ?>
                  </label>
                </div>
                <?php } ?>
              </div>
              <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-void-status"><?php echo $entry_void_status; ?></label>
            <div class="col-sm-10">
              <div class="well well-sm" style="overflow:auto;height:250px;">
                <?php foreach ($statuses as $status) { ?>
                <div class="checkbox">
                  <label>
                    <?php if (in_array($status['status_id'], $config_void_status)) { ?>
                    <input type="checkbox" name="config_void_status[]" value="<?php echo $status['status_id']; ?>" checked="checked" />
                    <?php echo $status['name']; ?>
                    <?php } else { ?>
                    <input type="checkbox" name="config_void_status[]" value="<?php echo $status['status_id']; ?>" />
                    <?php echo $status['name']; ?>
                    <?php } ?>
                  </label>
                </div>
                <?php } ?>
              </div>
              <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-default-overdue-status"><?php echo $entry_default_overdue_status; ?></label>
            <div class="col-sm-10">
              <select name="config_default_overdue_status" id="input-default-overdue-status" class="form-control">
                <?php foreach ($statuses as $status) { ?>
                <option value="<?php echo $status['status_id']; ?>"<?php echo $status['status_id'] == $config_default_overdue_status ? ' selected="selected"' : ''; ?>><?php echo $status['name']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-default-void-status"><?php echo $entry_default_void_status; ?></label>
            <div class="col-sm-10">
              <select name="config_default_void_status" id="input-default-void-status" class="form-control">
                <?php foreach ($statuses as $status) { ?>
                <option value="<?php echo $status['status_id']; ?>"<?php echo $status['status_id'] == $config_default_void_status ? ' selected="selected"' : ''; ?>><?php echo $status['name']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>
        <div class="tab-pane" id="tab-recurring">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-recurring-invoice-days"><?php echo $entry_recurring_invoice_days; ?></label>
            <div class="col-sm-10">
              <input type="text" name="config_recurring_invoice_days" value="<?php echo $config_recurring_invoice_days; ?>" id="input-recurring-invoice-days" class="form-control" placeholder="<?php echo $entry_recurring_invoice_days; ?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-recurring-disable-days"><?php echo $entry_recurring_disable_days; ?></label>
            <div class="col-sm-10">
              <input type="text" name="config_recurring_disable_days" value="<?php echo $config_recurring_disable_days; ?>" id="input-recurring-disable-days" class="form-control" placeholder="<?php echo $entry_recurring_disable_days; ?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-recurring-default-status"><?php echo $entry_recurring_default_status; ?></label>
            <div class="col-sm-10">
              <select name="config_recurring_default_status" id="input-recurring-default-status" class="form-control">
                <?php foreach ($statuses as $status) { ?>
                <option value="<?php echo $status['status_id']; ?>"<?php echo $status['status_id'] == $config_recurring_default_status ? ' selected="selected"' : ''; ?>><?php echo $status['name']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>
        <div class="tab-pane" id="tab-mail">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-mail-protocol"><?php echo $entry_mail_protocol; ?></label>
            <div class="col-sm-10"><select name="config_mail[protocol]" id="input-mail-protocol" class="form-control">
                <option value="mail"<?php echo $config_mail['protocol'] == 'mail' ? ' selected="selected"' : ''; ?>><?php echo $text_mail; ?></option>
                <option value="smtp"<?php echo $config_mail['protocol'] == 'smtp' ? ' selected="selected"' : ''; ?>><?php echo $text_smtp; ?></option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-mail-parameter"><?php echo $entry_mail_parameter; ?></label>
            <div class="col-sm-10">
              <input type="text" name="config_mail[parameter]" value="<?php echo $config_mail['parameter']; ?>" id="input-mail-parameter" class="form-control" placeholder="<?php echo $entry_mail_parameter; ?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-smtp-host"><?php echo $entry_smtp_hostname; ?></label>
            <div class="col-sm-10">
              <input type="text" name="config_mail[smtp_hostname]" value="<?php echo $config_mail['smtp_hostname']; ?>" id="input-smtp-hostname" class="form-control" placeholder="<?php echo $entry_smtp_hostname; ?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-smtp-username"><?php echo $entry_smtp_username; ?></label>
            <div class="col-sm-10">
              <input type="text" name="config_mail[smtp_username]" value="<?php echo $config_mail['smtp_username']; ?>" id="input-smtp-username" class="form-control" placeholder="<?php echo $entry_smtp_username; ?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-smtp-password"><?php echo $entry_smtp_password; ?></label>
            <div class="col-sm-10">
              <input type="text" name="config_mail[smtp_password]" value="<?php echo $config_mail['smtp_password']; ?>" id="input-smtp-password" class="form-control" placeholder="<?php echo $entry_smtp_password; ?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-smtp-port"><?php echo $entry_smtp_port; ?></label>
            <div class="col-sm-10">
              <input type="text" name="config_mail[smtp_port]" value="<?php echo $config_mail['smtp_port']; ?>" id="input-smtp-port" class="form-control" placeholder="<?php echo $entry_smtp_port; ?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-smtp-timeout"><?php echo $entry_smtp_timeout; ?></label>
            <div class="col-sm-10">
              <input type="text" name="config_mail[smtp_timeout]" value="<?php echo $config_mail['smtp_timeout']; ?>" id="input-smtp-timeout" class="form-control" placeholder="<?php echo $entry_smtp_timeout; ?>" />
            </div>
          </div>
        </div>
        <div class="tab-pane" id="tab-server">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-secure"><?php echo $entry_secure; ?></label>
            <div class="col-sm-10"><select name="config_secure" id="input-secure" class="form-control">
                <option value="1"<?php echo $config_secure ? ' selected="selected"' : ''; ?>><?php echo $text_yes; ?></option>
                <option value="0"<?php echo $config_secure ? '' : ' selected="selected"'; ?>><?php echo $text_no; ?></option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-seo-url"><?php echo $entry_seo_url; ?></label>
            <div class="col-sm-10"><select name="config_seo_url" id="input-seo-url" class="form-control">
                <option value="1"<?php echo $config_seo_url ? ' selected="selected"' : ''; ?>><?php echo $text_yes; ?></option>
                <option value="0"<?php echo $config_seo_url ? '' : ' selected="selected"'; ?>><?php echo $text_no; ?></option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-maintenance"><?php echo $entry_maintenance; ?></label>
            <div class="col-sm-10"><select name="config_maintenance" id="input-maintenance" class="form-control">
                <option value="1"<?php echo $config_maintenance ? ' selected="selected"' : ''; ?>><?php echo $text_yes; ?></option>
                <option value="0"<?php echo $config_maintenance ? '' : ' selected="selected"'; ?>><?php echo $text_no; ?></option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-compression"><?php echo $entry_compression; ?></label>
            <div class="col-sm-10">
              <input type="text" name="config_compression" value="<?php echo $config_compression; ?>" id="input-compression" class="form-control" placeholder="<?php echo $entry_compression; ?>" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-cache"><?php echo $entry_cache; ?></label>
            <div class="col-sm-10"><select name="config_cache" id="input-cache" class="form-control">
                <option value="file"<?php echo $config_cache == 'file' ? ' selected="selected"' : ''; ?>><?php echo $text_file; ?></option>
                <option value="apc"<?php echo $config_cache == 'apc' ? ' selected="selected"' : ''; ?>><?php echo $text_apc; ?></option>
                <option value="memcache"<?php echo $config_cache == 'memcache' ? ' selected="selected"' : ''; ?>><?php echo $text_memcache; ?></option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-error-display"><?php echo $entry_error_display; ?></label>
            <div class="col-sm-10"><select name="config_error_display" id="input-error-display" class="form-control">
                <option value="1"<?php echo $config_error_display ? ' selected="selected"' : ''; ?>><?php echo $text_yes; ?></option>
                <option value="0"<?php echo $config_error_display ? '' : ' selected="selected"'; ?>><?php echo $text_no; ?></option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-error-log"><?php echo $entry_error_log; ?></label>
            <div class="col-sm-10"><select name="config_error_log" id="input-error-log" class="form-control">
                <option value="1"<?php echo $config_error_log ? ' selected="selected"' : ''; ?>><?php echo $text_yes; ?></option>
                <option value="0"<?php echo $config_error_log ? '' : ' selected="selected"'; ?>><?php echo $text_no; ?></option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="required col-sm-2 control-label" for="input-error-filename"><?php echo $entry_error_filename; ?></label>
            <div class="col-sm-10">
              <input type="text" name="config_error_filename" value="<?php echo $config_error_filename; ?>" id="input-error-filename" class="form-control" placeholder="<?php echo $entry_error_filename; ?>" required />
              <?php if ($error_error_log_filename) { ?>
              <div class="text-danger"><?php echo $error_error_log_filename; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-cron-user"><?php echo $entry_cron_user; ?></label>
            <div class="col-sm-10"><select name="config_cron_user_id" id="input-cron-user" class="form-control"><?php foreach ($users as $user) { ?>
                <option value="<?php echo $user['user_id']; ?>"<?php echo $config_cron_user_id == $user['user_id'] ? ' selected="selected"' : ''; ?>><?php echo $user['username']; ?></option>
                <?php } ?></select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-google-analytics"><?php echo $entry_google_analytics; ?></label>
            <div class="col-sm-10">
              <textarea name="config_google_analytics" id="input-google-analytics" class="form-control" placeholder="<?php echo $entry_google_analytics; ?>" rows="3"><?php echo $config_google_analytics; ?></textarea/>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript"><!--
$(document).ready(function() {
<?php foreach ($languages as $language) { ?>
$('#input-home-<?php echo $language['language_id']; ?>').summernote({
	height: 300
});
<?php } ?>

$('#home li :first').trigger('click');
});
//--></script>
<?php echo $footer; ?>