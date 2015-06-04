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
	<article itemscope itemtype="http://schema.org/BlogPosting" class="col-lg-9 col-md-8 col-sm-7 col-xs-12">
	  <div class="row bottom-20">
	    <div class="col-xs-12">
	      <h2 itemprop="name"><?php echo $title; ?></h2>
	      <span itemprop="datePublished" class="date"><?php echo $date_added; ?></span>
		</div>
	  </div>
	  <div class="row bottom-20">
	    <div class="col-xs-12">
		  <img src="<?php echo $image; ?>" alt="<?php echo $title; ?>" class="img-responsive center-block" itemprop="image" />
		</div>
	  </div>
	  <div class="row bottom-20">
	    <div class="col-xs-12">
	      <p><?php echo $description; ?></p>
	    </div>
	  </div>
	  <div class="text-right"><?php echo $text_by; ?> <?php echo $user; ?></div>
	  <?php if ($tag) { ?>
	  <div><?php echo $tag; ?></div>
	  <?php } ?>
	</article>
  </div>
</div>
<?php echo $footer; ?>