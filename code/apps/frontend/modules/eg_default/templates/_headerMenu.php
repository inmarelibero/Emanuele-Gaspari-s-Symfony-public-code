<?php echo link_to('my<span class="colored">skills</span>', 'default/myskills'); ?>
<?php echo link_to(__('about<span class="colored">me</span>'), 'default/aboutme'); ?>
<?php echo link_to(__('contact<span class="colored">me</span>'), 'default/contact'); ?>

<div class="language">
	<?php include_component('eg_default', 'language') ?>
</div>