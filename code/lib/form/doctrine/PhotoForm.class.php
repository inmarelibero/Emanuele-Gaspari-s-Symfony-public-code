<?php

/**
 * Photo form.
 *
 * @package    
 * @subpackage form
 * @author     Emanuele Gaspari
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PhotoForm extends BasePhotoForm
{
  public function configure()
  {
	  parent::configure();
	  
	  unset($this['created_at'], $this['updated_at']);
	  
		// set gallery_id as hidden field
  	$this->setWidget('gallery_id', new sfWidgetFormInputHidden());

		// change the widget filname to make it an uploadable file
	  $this->setWidget('filename', new sfWidgetFormInputFile());

		// set the validator for filename widget
		// and specify the class which will handle the file upload
	  $this->setValidator('filename', new sfValidatorFile(
	  	array(
			'max_size' => 2000000,
			'mime_types' => 'web_images',
			'required' => false,
			'validated_file_class' => 'sfValidatedFileCustom'
		)));
  }
}