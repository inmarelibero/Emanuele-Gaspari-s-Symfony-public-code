<script>
	$(document).ready(function(){
		// equa height to some elements
		photosToEqualHeights();

		// hide the add photo link if user cannot add more photos
		if (!canUploadMorePhotos())
		{
			hideAddPhotosLink();
		}

		// performs a cool ajax upload
		$('#form_upload_photo').fileUploadUI({
			uploadTable: $('#files'),
			downloadTable: $('#files'),

			beforeSend: function (event, files, index, xhr, handler, callBack) {
				callBack();
				if (!canUploadMorePhotos())
				{
      		hideAddPhotosLink();
      	}
			},

			onLoad: function (event, files, index, xhr, handler) {
				handler.removeNode(handler.uploadRow, function () {
					handler.initDownloadRow(event, files, index, xhr, handler, function () {});
				});
			            
				var json = handler.parseResponse(xhr);
					
				var photo_id = json.id;
				getAjaxPhoto(photo_id, '<?php echo url_for('photo/getAjaxPhoto')?>');
			},

			initUpload: function (event, files, index, xhr, handler, callBack) {
				if (canUploadMorePhotos())
				{
					handler.initUploadRow(event, files, index, xhr, handler, function () {
						handler.beforeSend(event, files, index, xhr, handler, function () {
						handler.initUploadProgress(xhr, handler);
							callBack();
						});
					});
				}
		 	},
		 	
	 		onAbort: function (event, files, index, xhr, handler) {
				handler.removeNode(handler.uploadRow);
	 		 	showAddPhotosLink();
	 		},

	 	  buildUploadRow: function (files, index) {
				return $('<tr><td>' + files[index].name + '<\/td>' +
                    '<td class="file_upload_progress"><div><\/div><\/td>' +
                    '<td class="file_upload_cancel">' +
                    '<button class="ui-state-default ui-corner-all" title="Cancel">' +
                    '<span class="ui-icon ui-icon-cancel">Cancel<\/span>' +
                    '<\/button><\/td><\/tr>');
    	},
    	
    	buildDownloadRow: function (file) {
				return '';
			}
		});
	});
</script>

<div class="back">
	<?php echo link_to(__('Back to my galleries'), 'user/galleries', array('method'=>'post'))?>
</div>

<br><br><br>

<div class="title">
	<?php echo __('Manage photos for the gallery')?> "<?php echo $gallery?>"
</div>

<div class="photos_list manage_gallery">
	<?php foreach($gallery->getPhotos() as $key => $photo):?>
		<div class="photo_box" id="photo_<?php echo $photo->getId()?>">
			<div class="image">
				<?php echo image_tag($photo->getThumbnailImageTag())?>
			</div>
			<div class="photo_actions">
				<a onclick="if (confirm('Are you sure?')) {delete_photo(<?php echo $photo->getId()?>, '<?php echo url_for('photo/ajaxDelete')?>')}"><?php echo __('delete')?></a>				
			</div>
		</div>
	<?php endforeach;?>
</div>

<div class="upload_photo">
	<form action="<?php echo url_for('user/ajaxSavePhotosToGallery')?>" method="post" enctype="multipart/form-data" class="form" id="form_upload_photo">
		<?php echo $form['filename']?>
		<?php echo $form['gallery_id']?>
		<?php echo $form->renderHiddenFields()?>
    <button><?php echo __('Upload')?></button>
    <div><?php echo __('Add a photo')?></div>
	</form>
	<table id="files"></table>
</div>

<br><br><br>

<div class="back">
	<?php echo link_to(__('Back to my galleries'), 'user/galleries', array('method'=>'post'))?>
</div>