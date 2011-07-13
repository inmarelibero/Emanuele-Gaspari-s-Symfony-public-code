<div class="back">
	<?php echo link_to(__('Back to my galleries'), 'user/galleries', array('method'=>'post'))?>
</div>

<br><br><br>

<div class="title">
	<?php echo __('Add photos to the gallery')?> "<?php echo $gallery?>"
</div>

<div id="add_photos">
	<form action="<?php echo url_for('user/savePhotosToGallery')?>" method="post" enctype="multipart/form-data" class="form">
		
		<?php foreach ($form_add_photos_to_gallery['photos'] as $i => $photo):?>
			<div class="upload_photo">
				<div class="title">
					<?php echo __('Add photo')?> #<?php echo $i?>
				</div>
			
				<div class="label">
					<?php echo __('Filename')?>
				</div>
				<div class="field">
					<?php echo $photo['filename']?>
				</div>
			</div>
		<?php endforeach;?>
		
		<br><br>
		
		<button type="submit"><?php echo __('Upload')?></button>
		
		<?php echo $form_add_photos_to_gallery->renderHiddenFields()?>
	</form>
</div>

<br><br><br>

<div class="back">
	<?php echo link_to(__('Back to my galleries'), 'user/galleries', array('method'=>'post'))?>
</div>