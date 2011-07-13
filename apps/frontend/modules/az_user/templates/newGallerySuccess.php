<script type="text/javascript">
	$(document).ready(function(){

		// textarea converted to tinyMCE editors
		tinyMCE.init({
	        mode : "textareas",
	        theme : "simple"
		});

		// form validation
		$("#form_new_gallery").validate({
			rules: {
				'gallery[title]': "required",
				'gallery[photos][0][filename]': 'required'
			},
			errorPlacement: function(error, element) {
				error.appendTo( element.parent().next('.field_error') );
			},
		});
	});

	/****************************************************************************
	* photos functions
	****************************************************************************/
	/**
	 *  shows the link to add a photo
	 */
	function show_add_photo_link()
	{
		$('#add_photo_link').show();
	}
	
	/**
	 *  hides the link to add a photo
	 */
	function hide_add_photo_link()
	{
		$('#add_photo_link').hide();
	}
	
	/**
	 *  removes the box to ann a new photo
	 */
	function remove_add_photo_box(e)
	{
		e.closest('.add_photo_box').remove();
		var num_fields = $('#add_photos').children('.add_photo_box').length;
		if (num_fields < 4)
		{
			show_add_photo_link();
		}
	}

	/**
	 *  adds a form to add a photo
	 */
	function add_photo_form()
	{
		var num_fields = $('#add_photos').children('.add_photo_box').length;
		
		if (num_fields < 4)
		{
			var tag_add = $('<div class="add_photo_box"></div>');

			var tag_label = $('<div class="label"><?php echo __('Add photo')?></div>');
			tag_add.append(tag_label);

			var tag_field = $('<div class="field"></div>');
			tag_add.append(tag_field);

			var input_filename = $('<input type="file" name="gallery[photos]['+num_fields+'][filename]" id="gallery_photos_'+num_fields+'_filename">');
			tag_field.append(input_filename);
			var input_id = $('<input type="hidden" name="gallery[photos]['+num_fields+'][id]" id="gallery_photos_'+num_fields+'_id">');
			tag_field.append(input_id);
			var tag_image = $('<?php echo image_tag('delete.png', array('onclick' => 'remove_add_photo_box($(this))'))?>');
			tag_field.append(tag_image);
			
			$('#add_photos').append(tag_add);
		}
		
		if (num_fields >= 3)
		{
			hide_add_photo_link();
		}
	}

	/****************************************************************************
	* tags functions
	****************************************************************************/
	/**
	 *	adds a tag to a gallery 
	 */
	function add_tag()
	{
		var value = $('#add_tag').val();

		if (value != '')
		{
			var num_tags = $('#add_tags').children('.add_tag_box').length;

			var tag_add_tag = $('<div class="add_tag_box"></div>');

			var tag_label = $('<div class="label">'+value+'</div>');
			tag_add_tag.append(tag_label);

			var tag_field = $('<div class="field"></div>');
			tag_add_tag.append(tag_field);

			var input = $('<input type="hidden" name="gallery[tags]['+num_tags+'][name]" id="gallery_tags_'+num_tags+'_name" value="'+value+'">');
			tag_field.append(input);

			var tag_image = $('<?php echo image_tag('delete.png', array('onclick' => 'remove_add_tag_box($(this))'))?>');
			tag_field.append(tag_image);
			
			$('#add_tags').append(tag_add_tag);
			$('#add_tag').val('');
		}
	}

	/**
	 *	removes the add tag box
	 */
	function remove_add_tag_box(e)
	{
		e.closest('.add_tag_box').remove();
	}
</script>


<div class="back">
	<?php echo link_to(__('Back to my galleries'), 'user/galleries', array('method'=>'post'))?>
</div>

<br><br><br>

<form action="<?php echo url_for('user/createGallery');?>" method="post" class="form" id="form_new_gallery" enctype="multipart/form-data" class="form">
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
		
		<!-- tags -->
		<div class="label">
			<?php echo __('Tags')?>
		</div>
		<div class="field">
			<div id="add_tags"></div>
			<div>
				<input name="add_tag" id="add_tag">
				<a onclick="add_tag()"><?php echo __('Add tag')?></a>
			</div>
		</div>
		
		<!-- photos -->
		<div id="add_photos">
			<div class="add_photo_box">
				<div class="label">
					<?php echo __('Cover photo')?>
				</div>
				<div class="field">
					<?php echo $form['photos'][0]['filename']?>
				</div>
				<div class="field_error"></div>
			</div>
		</div>
		
		<div class="label"></div>
		<div class="field">
			<a onclick="add_photo_form()" id="add_photo_link"><?php echo __('Add photo')?></a>
		</div>
		
		<div class="label"></div>
		<div class="field">
			<button type="submit"><?php echo __('Save')?></button>
		</div>		
	</div>

	<?php echo $form->renderHiddenFields()?>
</form>


<br><br><br>
<div class="back">
	<?php echo link_to(__('Back to my galleries'), 'user/galleries', array('method'=>'post'))?>
</div>