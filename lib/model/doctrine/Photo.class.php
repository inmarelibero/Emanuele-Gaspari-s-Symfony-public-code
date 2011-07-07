<?php

/**
 * Photo
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    
 * @subpackage model
 * @author     Emanuele Gaspari
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Photo extends BasePhoto
{
	/**
	 * overrides of the setUp() method
	 */
	public function setUp()
	{ 
		parent::setUp();
		$this->actAs('Timestampable');
	}
	
	/**
	 * before deleting a photo, it deletes the image and the generted thumbnail
	 * 
	 * @param unknown_type $conn
	 */
	public function delete(Doctrine_Connection $conn = null)
	{
		// deletes the image
		$filename_photo = sfConfig::get('sf_upload_dir').'/'.sfConfig::get('app_photos_folder').'/'.$this->getFilename();
		if (file_exists($filename_photo))
			unlink($filename_photo);
		
		// deletes the thumbnail
		$filename_thumbnail = sfConfig::get('sf_upload_dir').'/'.sfConfig::get('app_thumbnails_folder').'/'.sfConfig::get('app_thumbnails_prefix').$this->getFilename();
		if (file_exists($filename_thumbnail))
			unlink($filename_thumbnail);
		
		// calls parent to delete object
		parent::delete($conn);
	}
	
	/**
	 * prints the image tag of the thumbnail
	 * 
	 * @param unknown_type $array_options array to pass directly to image_tag function
	 */
	public function getThumbnailImageTag($array_options = Array())
	{
		$thumbnails_dir = sfConfig::get('app_thumbnails_folder');
		$thumbnails_prefix = sfConfig::get('app_thumbnails_prefix');
		echo image_tag('../uploads/'.$thumbnails_dir.'/'.$thumbnails_prefix.$this->getFilename(), $array_options);
	}
}