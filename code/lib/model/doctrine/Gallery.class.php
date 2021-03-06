<?php

/**
 * Gallery
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Gallery extends BaseGallery
{
	/**
	 * overrides of the setUp() method
	 */
	public function setUp()
	{ 
		parent::setUp();
		$this->actAs('Timestampable');
		$this->actAs('Taggable');
	}
	
	/**
	 * before deleting a gallery, it deletes all the related photos
	 * 
	 * @param unknown_type $conn
	 */
	public function delete(Doctrine_Connection $conn = null)
	{
		// deletes all photos
		foreach ($this->getPhotoes() as $photo)
		{
			$photo->delete();
		}
		
		// calls parent to delete object
		parent::delete($conn);
	}
	
	/**
	 * __toString() function
	 */
	public function __toString()
	{
		return $this->getTitle();
	}
	
	/**
	 * returns the slugged title
	 */
	public function getTitleSlug()
	{
		sfContext::getInstance()->getConfiguration()->loadHelpers('Slugify');
		return slugify($this->getTitle());
	}
	
	/**
	 * prints the image tag of the cover of the gallery (the first image)
	 */
	public function getCoverImageTag()
	{
		$arr_photos = $this->getPhotoes();
		if (count($arr_photos) > 0)
		{
			$photo = $arr_photos[0];
			echo $photo->getThumbnailImageTag();
			return;
		}
		echo $this->getBlankCover();
		return; 
	}
	
	/**
	 * prints a placeholder image tag
	 */
	public function getBlankCover()
	{
		echo image_tag('noimage_100.jpg');
		return;
	}
}