<script>
	$(document).ready(function(){
		// equal height on some elements
		$(".user_galleries .title").equalHeights();
		$(".user_galleries .cover").equalHeights();
	});
</script>

<?php echo __('Galleries of the user')?>: <?php echo count($arr_galleries)?>

<div class="user_galleries">

	<?php foreach($arr_galleries as $gallery):?>
		<div class="gallery">
			
			<div class="title">
				<?php echo $gallery;?>
			</div>
			<div class="info">
				<div class="cover">
					<?php echo $gallery->getCoverImageTag()?>
				</div>
				<div class="count">
					<?php echo count($gallery->getPhotos())?> foto
				</div>
			</div>
			
			<div class="actions">
				<ul>
					<li>
						<?php echo link_to(__('manage photos'), '@user_manage_photos?gallery_id='.$gallery->getId(), array('method' => 'post'))?>
					</li>
					<li>
						<?php echo link_to(__('manage tags'), '@user_manage_tags?gallery_id='.$gallery->getId(), array('method' => 'post'))?>
					</li>
					<br>
					<li>
						<?php echo link_to(__('edit'), '@user_edit_gallery?id='.$gallery->getId().'&title='.$gallery->getTitleSlug(), array('method' => 'post'))?>
					</li>
					<li>
						<?php echo link_to('delete', '@user_delete_gallery?id='.$gallery->getId().'&title='.$gallery->getTitleSlug(), array('method' => 'delete', 'confirm' => 'Are you sure?'))?>
					</li>
					<br>
					<?php if (count($gallery->getPhotos()) > 0):?>
						<li>
							<a href="<?php echo url_for('gallery_show', $gallery, array('method' => 'post'))?>"><?php echo __('Show gallery')?></a>
						</li>
					<?php endif;?>
				</ul>		
			</div>
		</div>
		
	<?php endforeach;?>
</div>

<br><br>

<?php echo link_to(__('Create a new gallery'), '@user_new_gallery')?>