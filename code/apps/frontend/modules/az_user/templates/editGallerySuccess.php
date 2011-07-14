<script type="text/javascript">
	$(document).ready(function(){
		// converts all textarea into tinyMCE editors
		tinyMCE.init({
	        mode : "textareas",
	        theme : "simple"
		});
	
		// equal height to some elements
		photosToEqualHeights();
	
		// hides the add photo link if user can not upload more photos
		if (!canUploadMorePhotos())
		{
			hideAddPhotosLink();
		}
	
		// form valifation
		$("#form_edit_gallery").validate({
			rules: {
				'gallery[title]': "required"
			},
			errorPlacement: function(error, element) {
				error.appendTo( element.parent().next('.field_error') );
			},
		});
	});

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

	/**
	 *	adds a tag to the gallery
	 */
	function add_tag()
	{
		var tag_name = $('#tag_name').val();
	
		if (tag_name != '')
		{
			$.ajax({
				type: 'POST',
				url: '<?php echo url_for('gallery/addTag')?>',
				data:
					{
					'gallery_id': '<?php echo $gallery->getId()?>',
					'tag_name': tag_name
					},
				success: function(msg){
					$('#tag_name').val('');

					var tag_tag = $('<div class="tag"></div>');

					var tag_name = $('<div class="name">'+tag_name+'</div>');
					tag_tag.append(tag_name);

					var tag_remove = $('<div class="remove"></div>');
					tag_tag.append(tag_remove);

					var tag_image = $('<?php echo image_tag('delete.png', array('onclick'=>'remove_tag(this)'))?>');
					tag_remove.append(tag_image);

					$('.tags_list').append(tag_tag);
				}
			});
		}
	}

	/**
	 *	removes a tag from the gallery
	 */
	function remove_tag(t)
	{
		var tag_name = (jQuery.trim($(t).parent().prev().html()));
		
		$.ajax({
			type: 'POST',
			url: '<?php echo url_for('gallery/removeTag')?>',
			data:
				{
				'gallery_id': '<?php echo $gallery->getId()?>',
				'tag_name': tag_name
				},
			success: function(msg){
				$(t).closest('.tag').remove();
			}
		});
	}
</script>


<div class="back">
	<?php echo link_to(__('Back to my galleries'), 'user/galleries', array('method'=>'post'))?>
</div>

<br><br><br>

<div class="title">
	<?php echo __('Edit gallery')?> "<?php echo $gallery?>"
</div>


<form action="<?php echo url_for('user/updateGallery')?>" method="post" class="form" id="form_edit_gallery">

	<div>
		<div class="label">
			<?php echo __('Title')?>
		</div>
		<div class="field">
			<?php echo $form['title']?>
		</div>
		<div class="field_error"></div>
		
		<div class="label">
			<?php echo __('Description')?>
		</div>
		<div class="field">
			<?php echo $form['description']?>
		</div>
		<div class="field_error"></div>
		
		<div class="label">
			<?php echo __('Category')?>
		</div>
		<div class="field">
			<?php echo $form['category_id']?>
		</div>
		<div class="field_error"></div>
		
		<div class="label">
			<?php echo __('Tags')?>
		</div>
		<div class="field">
			<div class="manage_tags">

				<?php $tags = $gallery->getTags();?>
				<div class="tags_list">
					<?php foreach($tags as $tag):?>
						<div class="tag">
							<div class="name">
								<?php echo $tag?>
							</div>
							<div class="remove">
								<?php echo image_tag('delete.png', array('onclick'=>'remove_tag(this)'))?>
							</div>
						</div>
					<?php endforeach;?>
				</div>
				
				<div class="add_tag">
					<?php echo __('Add tag')?>: <input name="tag_name" id="tag_name"> <button onclick="add_tag()" type="button"><?php echo __('Add')?></button>
				</div>
			</div>
		</div>
		
  <?php echo $form->renderHiddenFields() ?>
  </div>
</form>

<div class="form_edit_gallery_photos form">

		<div class="label">
			<?php echo __('Photos')?>
		</div>
		<div class="field">
			<div class="photos_list manage_gallery">
				<?php foreach($gallery->getPhotos() as $key => $photo):?>
					<div class="photo_box" id="photo_<?php echo $photo->getId()?>">
						<div class="image">
							<?php echo image_tag($photo->getThumbnailImageTag())?>
						</div>
						<div class="photo_actions">
							<a onclick="if (confirm('Are you sure?')) {delete_photo(<?php echo $photo->getId()?>, '<?php echo url_for('photo/ajaxDelete', true)?>')}"><?php echo __('delete')?></a>				
						</div>
					</div>
				<?php endforeach;?>
			</div>
			
			<div class="upload_photo">
				<form action="<?php echo url_for('user/ajaxSavePhotosToGallery')?>" method="post" enctype="multipart/form-data" class="form" id="form_upload_photo">
					<?php echo $form_upload_photo['filename']?>
					<?php echo $form_upload_photo['gallery_id']?>
					<?php echo $form_upload_photo->renderHiddenFields()?>
			    <button><?php echo __('Upload')?></button>
			    <div><?php echo __('Add a photo')?></div>
				</form>
				<table id="files"></table>
			</div>
		</div>
		
		<div class="label"></div>
		<div class="field">
			<button type="button" onclick="$('#form_edit_gallery').submit()"><?php echo __('Save')?></button>
		</div>
		<div class="field_error"></div>
	</div>
  
</div>

<br><br><br>

<div class="back">
	<?php echo link_to(__('Back to my galleries'), 'user/galleries', array('method'=>'post'))?>
</div>