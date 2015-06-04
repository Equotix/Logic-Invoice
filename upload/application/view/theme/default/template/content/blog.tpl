<?php echo $header; ?>
<div class="header">
  <div class="container">
    <h1><?php echo $heading_title; ?></h1>
  </div>
</div>
<div id="content" class="container">
  <ol class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ol>
  <div class="row">
    <div class="col-lg-3 col-md-4 col-sm-5 col-xs-12">
	  <div class="list-group">
        <?php foreach ($blog_categories as $blog_category) { ?>
	    <a href="<?php echo $blog_category['href']; ?>" class="list-group-item<?php echo $blog_category['blog_category_id'] == $blog_category_id ? ' active' : ''; ?>"><?php echo $blog_category['name']; ?></a>
		<?php if ($blog_category['children']) { ?>
		<?php foreach ($blog_category['children'] as $child) { ?>
	    <a href="<?php echo $child['href']; ?>" class="list-group-item<?php echo $child['blog_category_id'] == $blog_category_id ? ' active' : ''; ?>">&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $child['name']; ?></a>
		<?php if ($child['grandchildren']) { ?>
		<?php foreach ($child['grandchildren'] as $grandchild) { ?>
	    <a href="<?php echo $grandchild['href']; ?>" class="list-group-item<?php echo $grandchild['blog_category_id'] == $blog_category_id ? ' active' : ''; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $grandchild['name']; ?></a>
		<?php } ?>
		<?php } ?>
		<?php } ?>
		<?php } ?>
	    <?php } ?>
	  </div>
    </div>
	<div class="col-lg-9 col-md-8 col-sm-7 col-xs-12">
	  <?php if ($blog_posts) { ?>
	  <?php foreach ($blog_posts as $blog_post) { ?>
	  <article class="row bottom-20">
	    <div class="col-xs-12">
		  <div class="row bottom-20">
		    <div class="col-xs-12">
			  <h2><?php echo $blog_post['title']; ?></h2>
			  <span class="date"><?php echo $blog_post['date_added']; ?></span>
			</div>
		  </div>
		  <div class="row bottom-20">
		    <div class="col-xs-12">
		      <a href="<?php echo $blog_post['href']; ?>"><img src="<?php echo $blog_post['image']; ?>" alt="<?php echo $blog_post['title']; ?>" class="img-responsive center-block" /></a>
		    </div>
		  </div>
		  <div class="row">
 		    <div class="col-xs-12">
			  <p><?php echo $blog_post['short_description']; ?></p>
			  <div class="text-right">
			    <a href="<?php echo $blog_post['href']; ?>" class="btn btn-info"><?php echo $text_read_more; ?></a>
			  </div>
			</div>
		  </div>
		</div>
	  </article>
	  <?php } ?>
	  <?php } else { ?>
	    <p class="text-center"><?php echo $text_no_results; ?></p>
	  <?php } ?>
	  <?php echo $pagination; ?>
	</div>
  </div>
</div>
<?php echo $footer; ?>