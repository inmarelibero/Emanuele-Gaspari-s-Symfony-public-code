<script>
	// equal height of some elements
	$(document).ready(function() {
		$(".gallery_box").equalHeights();
	});
</script>

<div class="galleries_list">
	
	<?php foreach($arr_galleries as $gallery):?>
	
		<div class="gallery_box">
			<div class="title">
				<?php echo $gallery->getTitle()?>
			</div>
			
			<div class="cover" onclick="location.href = '<?php echo url_for('gallery_show', $gallery)?>'">
				<?php echo $gallery->getCoverImageTag()?>
			</div>
			
				<?php if ($gallery->getSfGuardUser()->getFirstNameLastName() != ''):?>
				<div class="info">
					<?php echo __('user')?>: <?php echo $gallery->getSfGuardUser()->getFirstNameLastName()?>
				</div>
			<?php endif;?>
			
			<div class="tags">
				<?php echo __('Tags')?>: 
				<?php $tags = $gallery->getTags(); ?>
					<?php $i = 0;?>
					<?php foreach ($tags as $tag):?>
						<?php echo ($i < count($tags)-1) ? $tag.',' : $tag ?>
						<?php $i++?>
					<?php endforeach;?>
			</div>
		</div>
		
	<?php endforeach;?>
	
</div>