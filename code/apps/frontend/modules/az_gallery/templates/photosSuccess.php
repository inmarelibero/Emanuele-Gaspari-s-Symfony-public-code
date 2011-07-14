<script>
	// equal size to some elements
	$(document).ready(function(){
		photosToEqualHeights();
	});
</script>

<script type="text/javascript">
	$(function() {
		// initializes the slideshow (lightbox)
		$('.photos_list a').lightBox({
			overlayBgColor: '#000',
			overlayOpacity: 0.6,
			imageLoading: '/images/lightbox-ico-loading.gif',
			imageBtnClose: '/images/lightbox-btn-close.gif',
			imageBtnPrev: '/images/lightbox-btn-prev.gif',
			imageBtnNext: '/images/lightbox-btn-next.gif',
			containerResizeSpeed: 350,
		});
	});
</script>


<div class="gallery_title">
	<div>
		<?php echo __('Title')?>:
	</div>
	<div>
		<?php echo $gallery->getTitle()?>
	</div>
</div>

<?php if ($gallery->getDescription() != ''):?>
	<div class="gallery_description">
		<div>
			<?php echo __('Description')?>:
		</div>
		<div>
			<?php echo html_entity_decode($gallery->getDescription())?>
		</div>
	</div>
<?php endif;?>

<?php if ($gallery->getCategory()):?>
	<div class="gallery_category">
		<div>
			<?php echo __('Category')?>:
		</div>
		<div>
			<?php echo $gallery->getCategory()->getName()?>
		</div>
	</div>
<?php endif;?>

<?php $tags = $gallery->getTags(); ?>
<?php if (count($tags) > 0):?>
	<div class="gallery_tags">
		<div>
			<?php echo __('Tags')?>:
		</div>
		<div>
			<?php $i = 0;?>
			<?php foreach ($tags as $tag):?>
				<?php echo ($i < count($tags)-1) ? $tag.',' : $tag ?>
				<?php $i++?>
			<?php endforeach;?>
		</div>
	</div>
<?php endif;?>

<div class="photos_list">
	<?php foreach($gallery->getPhotos() as $key => $photo):?>
		<div class="photo_box">
			<div class="image">
				<a href="/uploads/<?php echo sfConfig::get('app_photos_folder').'/'.$photo->getFilename()?>">
					<?php echo image_tag($photo->getThumbnailImageTag())?>
				</a>
			</div>
		</div>
	<?php endforeach;?>
</div>