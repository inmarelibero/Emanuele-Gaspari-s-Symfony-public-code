<!-- prints a flag image tag for each culture available -->
<?php foreach($arr_forms_cultures as $culture => $form_object): ?>
<div>
	<a href="<?php echo localized_current_url($culture); ?>">
		<?php echo image_tag(getFlagFilename($culture)); ?>
	</a>
</div>
<?php endforeach;?>