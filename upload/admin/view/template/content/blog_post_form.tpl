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
      <ul class="nav nav-tabs">
        <?php foreach ($languages as $language) { ?>
        <li><a href="#language-<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" /> <?php echo $language['name']; ?></a></li>
        <?php } ?>
      </ul>
      <div class="tab-content">
        <?php foreach ($languages as $language) { ?>
        <div class="tab-pane" id="language-<?php echo $language['language_id']; ?>">
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="input-image-<?php echo $language['language_id']; ?>"><?php echo $entry_image; ?></label>
			<div class="col-sm-10">
			  <a href="" id="thumb-image-<?php echo $language['language_id']; ?>" data-toggle="image" class="img-thumbnail"><img src="<?php echo !empty($description[$language['language_id']]['thumb']) ? $description[$language['language_id']]['thumb'] : $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
			  <input type="hidden" name="description[<?php echo $language['language_id']; ?>][image]" value="<?php echo !empty($description[$language['language_id']]['image']) ? $description[$language['language_id']]['image'] : ''; ?>" id="input-image-<?php echo $language['language_id']; ?>" />
			</div>
		  </div>
          <div class="form-group">
            <label class="required col-sm-2 control-label" for="input-name-<?php echo $language['language_id']; ?>"><?php echo $entry_title; ?></label>
            <div class="col-sm-10">
              <div class="input-group">
                <span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>" /></span>
                <input type="text" name="description[<?php echo $language['language_id']; ?>][title]" value="<?php echo !empty($description[$language['language_id']]['title']) ? $description[$language['language_id']]['title'] : ''; ?>" id="input-title-<?php echo $language['language_id']; ?>" class="form-control" placeholder="<?php echo $entry_title; ?>" required />
              </div>
              <?php if (!empty($error_title[$language['language_id']])) { ?>
              <div class="text-danger"><?php echo $error_title[$language['language_id']]; ?></div>
              <?php } ?>
            </div>
          </div>
		  <div class="form-group">
            <label class="required col-sm-2 control-label" for="input-meta-title-<?php echo $language['language_id']; ?>"><?php echo $entry_meta_title; ?></label>
            <div class="col-sm-10">
              <div class="input-group">
                <span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>" /></span>
                <input type="text" name="description[<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo !empty($description[$language['language_id']]['meta_title']) ? $description[$language['language_id']]['meta_title'] : ''; ?>" id="input-meta-title-<?php echo $language['language_id']; ?>" class="form-control" placeholder="<?php echo $entry_meta_title; ?>" required />
              </div>
              <?php if (!empty($error_meta_title[$language['language_id']])) { ?>
              <div class="text-danger"><?php echo $error_meta_title[$language['language_id']]; ?></div>
              <?php } ?>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-meta-description-<?php echo $language['language_id']; ?>"><?php echo $entry_meta_description; ?></label>
            <div class="col-sm-10">
              <div class="input-group">
                <span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>" /></span>
                <textarea name="description[<?php echo $language['language_id']; ?>][meta_description]" id="input-name-<?php echo $language['language_id']; ?>" class="form-control" placeholder="<?php echo $entry_meta_description; ?>" rows="5"><?php echo !empty($description[$language['language_id']]['meta_description']) ? $description[$language['language_id']]['meta_description'] : ''; ?></textarea>
              </div>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-meta-keyword-<?php echo $language['language_id']; ?>"><?php echo $entry_meta_keyword; ?></label>
            <div class="col-sm-10">
              <div class="input-group">
                <span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>" /></span>
                <textarea name="description[<?php echo $language['language_id']; ?>][meta_keyword]" id="input-name-<?php echo $language['language_id']; ?>" class="form-control" placeholder="<?php echo $entry_meta_keyword; ?>" rows="5"><?php echo !empty($description[$language['language_id']]['meta_keyword']) ? $description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
              </div>
            </div>
          </div>
		  <div class="form-group">
            <label class="required col-sm-2 control-label" for="input-short-description-<?php echo $language['language_id']; ?>"><?php echo $entry_short_description; ?></label>
            <div class="col-sm-10">
              <textarea name="description[<?php echo $language['language_id']; ?>][short_description]" id="input-short-description-<?php echo $language['language_id']; ?>"><?php echo !empty($description[$language['language_id']]['short_description']) ? html_entity_decode($description[$language['language_id']]['short_description'], ENT_QUOTES) : ''; ?></textarea>
              <?php if (!empty($error_short_description[$language['language_id']])) { ?>
              <div class="text-danger"><?php echo $error_short_description[$language['language_id']]; ?></div>
              <?php } ?>
            </div>
          </div>
		  <div class="form-group">
            <label class="required col-sm-2 control-label" for="input-description-<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
            <div class="col-sm-10">
              <textarea name="description[<?php echo $language['language_id']; ?>][description]" id="input-description-<?php echo $language['language_id']; ?>"><?php echo !empty($description[$language['language_id']]['description']) ? html_entity_decode($description[$language['language_id']]['description'], ENT_QUOTES) : ''; ?></textarea>
              <?php if (!empty($error_description[$language['language_id']])) { ?>
              <div class="text-danger"><?php echo $error_description[$language['language_id']]; ?></div>
              <?php } ?>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-tag-<?php echo $language['language_id']; ?>"><?php echo $entry_tag; ?></label>
            <div class="col-sm-10">
              <div class="input-group">
                <span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>" /></span>
                <input type="text" name="description[<?php echo $language['language_id']; ?>][tag]" value="<?php echo !empty($description[$language['language_id']]['tag']) ? $description[$language['language_id']]['tag'] : ''; ?>" id="input-tag-<?php echo $language['language_id']; ?>" class="form-control" placeholder="<?php echo $entry_tag; ?>" />
              </div>
            </div>
          </div>
		  <div class="form-group">
			<label class="col-sm-2 control-label" for="input-url-alias"><?php echo $entry_url_alias; ?></label>
			<div class="col-sm-10">
			  <div class="input-group">
				<span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>" /></span>
				<input type="text" name="url_alias[<?php echo $language['language_id']; ?>]" value="<?php echo !empty($url_alias[$language['language_id']]) ? $url_alias[$language['language_id']] : ''; ?>" id="input-url-alias-<?php echo $language['language_id']; ?>" class="form-control" placeholder="<?php echo $entry_url_alias; ?>" />
			  </div>
			  <?php if (!empty($error_url_alias[$language['language_id']])) { ?>
              <div class="text-danger"><?php echo $error_url_alias[$language['language_id']]; ?></div>
              <?php } ?>
			</div>
		  </div>
        </div>
        <?php } ?>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
        <div class="col-sm-10">
          <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" id="input-sort-order" class="form-control" placeholder="<?php echo $entry_sort_order; ?>" />
        </div>
      </div>
	  <div class="form-group">
		<label class="col-sm-2 control-label" for="input-category"><?php echo $entry_category; ?></label>
		<div class="col-sm-10">
		  <div class="well well-sm" style="overflow:auto;height:150px;">
			<?php foreach ($blog_categories as $category) { ?>
			<div class="checkbox">
			  <label>
				<?php if (in_array($category['blog_category_id'], $blog_category)) { ?>
				<input type="checkbox" name="blog_category[]" value="<?php echo $category['blog_category_id']; ?>" checked="checked" />
				<?php echo $category['name']; ?>
				<?php } else { ?>
				<input type="checkbox" name="blog_category[]" value="<?php echo $category['blog_category_id']; ?>" />
				<?php echo $category['name']; ?>
				<?php } ?>
			  </label>
			</div>
			<?php } ?>
		  </div>
		  <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a>
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
    </form>
  </div>
</div>
<script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
$('#input-description-<?php echo $language['language_id']; ?>').summernote({
	height: 300
});

$('#input-short-description-<?php echo $language['language_id']; ?>').summernote({
	height: 200
});
<?php } ?>

$('.nav-tabs li :first').trigger('click');
//--></script> 
<?php echo $footer; ?>