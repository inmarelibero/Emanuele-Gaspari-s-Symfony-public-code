<div class="photo_box" id="photo_<?php echo $photo->getId()?>">
	<div class="image">
		<?php echo image_tag($photo->getThumbnailImageTag())?>
	</div>
	<div class="photo_actions">
		<a onclick="if (confirm('Are you sure?')) {delete_photo(<?php echo $photo->getId()?>, '<?php echo url_for('photo/ajaxDelete', true)?>')}"><?php echo __('delete')?></a>				
	</div>
</div>