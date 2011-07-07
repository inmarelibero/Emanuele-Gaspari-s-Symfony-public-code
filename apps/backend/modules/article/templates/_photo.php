<?php $filename = sfConfig::get('sf_upload_dir').'/'.$article->getFotoFilename()?>

<?php if (file_exists($filename)):?>
	<?php echo image_tag('../uploads/'.$article->getPhotoFilename(), array('class'=>'thumb'))?>
<?php endif;?>