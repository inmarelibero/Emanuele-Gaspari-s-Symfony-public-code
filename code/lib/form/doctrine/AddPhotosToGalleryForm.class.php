<?php

/**
 * AddPhotosToGallery form.
 *
 * @package    
 * @subpackage form
 * @author     Emanuele Gaspari
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class AddPhotosToGalleryForm extends BaseFormDoctrine
{
  public function configure()
  {
		// set gallery_id as hidden field
  	$this->setWidget('gallery_id', new sfWidgetFormInputHidden());
  	
  	// if the form is not new, sets the default value of the field gallery_id
  	if ($this->getObject())
  		$this->setDefault('gallery_id', $this->getObject()->getId());

  	// create as many subforms as the value in app_max_num_uploaded_photos
  	$subForm = new sfForm();
	  for ($i=0; $i < sfConfig::get('app_max_num_uploaded_photos'); $i++)
	  {
	  	$photo = new Photo();
	  	$photo->setGalleryId($this->getObject()->getId());
	  	$photoForm = new PhotoForm($photo);
	    $subForm->embedForm($i, $photoForm);
	  }
    $this->embedForm('photos', $subForm);
  }
}