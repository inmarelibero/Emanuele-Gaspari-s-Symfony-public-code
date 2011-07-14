<script type="text/javascript">
	/**
	 * adds a tag to a gallery (via ajax)
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
		
					// after a successful ajax call, add an html tag representing the saved gallery tag
					var tag_html = $('<div class="tag"></div>');
	
					var tag_name = $('<div class="name">'+tag_name+'</div>');
					tag_html.append(tag_name);
					
					var tag_name = $('<div class="remove"><?php echo image_tag('delete.png', array('onclick'=>'remove_tag(this)'))?></div>');
					tag_html.append(tag_name);
	
					$('.tags_list').append(tag_html);
				}
			});
		}
	}
	
	/**
	 * removes a tag from a gallery (via ajax)
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
				// remove the tag from the list	
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
	<?php echo __('Manage tags the the gallery')?> "<?php echo $gallery?>"
</div>

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

<br><br><br>

<div class="back">
	<?php echo link_to(__('Back to my galleries'), 'user/galleries', array('method'=>'post'))?>
</div>