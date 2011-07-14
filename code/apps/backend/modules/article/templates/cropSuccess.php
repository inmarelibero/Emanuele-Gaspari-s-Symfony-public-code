<script language="Javascript">
//<![CDATA[
           
	$(document).ready(function() {

		// initializes the cropbox
		jQuery('#cropbox img').Jcrop({
			onChange: 		setCoords,
			onSelect: 		setCoords,
			bgColor:     	'black',
			bgOpacity:   	.4,
			setSelect:   	[ 100, 100, 380, 310 ],
			aspectRatio: 	280 / 210,
			minSize:			[280, 210]
		});

		// divs to same height
		$("#cropbox_wrapper, #cropbox_actions").equalHeights(200).css('overflow', 'hidden');
	});

	/**
	 *	sets size and coordinates when moving the crop
	 *
	 *	@param c dictionary of coordinates and size
	 */
	function setCoords(c)
	{
		jQuery('#x').val(c.x);
		jQuery('#y').val(c.y);
		jQuery('#x2').val(c.x2);
		jQuery('#y2').val(c.y2);
		jQuery('#w').val(c.w);
		jQuery('#h').val(c.h);
	};
	
//]]>
</script>




<h1><?php echo__('Edit crop image')?></h1>

<form name="form_crop" method="post" action="<?php echo url_for('article/saveCrop')?>">

	<div id="cropbox_wrapper">
		<?php $filename = sfConfig::get('sf_upload_dir').'/'.$article->getPhotoFilename()?>
		
		<?php if (file_exists($filename)):?>
			<div id="cropbox">
				<?php echo image_tag('../uploads/'.$article->getFotoFilename())?>
			</div>
		<?php endif?>
		
		<input type="hidden" name="id" value="<?php echo $article->getId()?>">
		
		<input type="hidden" size="4" id="x" name="x" />
		<input type="hidden" size="4" id="y" name="y" />
		<input type="hidden" size="4" id="x2" name="x2" />
		<input type="hidden" size="4" id="y2" name="y2" />
		<input type="hidden" size="4" id="w" name="w" />
		<input type="hidden" size="4" id="h" name="h" />
	
	</div>
	
	<div id="cropbox_actions">
		<div>
			<?php echo button_to('', 'article/index', array('class'=>'button_cancel'))?>
			<button type="submit" class="button_submit"></button>
		</div>
		
		<?php if (file_exists(sfConfig::get('sf_upload_dir').'/'.$artile->getPhotoFilenameCrop())):?>
			<div class="cropbox_croppedimage">
				<?php echo__('Current cropped image')?>:
				<?php echo image_tag('../uploads/'.$article->getFotoFilenameCrop())?>
			</div>
		<?php endif;?>
		
	</div>
	
</form>