<?php

/**
 * sfGuardUserProfile form.
 *
 * @package    
 * @subpackage form
 * @author     Emanuele Gaspari
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sfGuardUserProfileForm extends BasesfGuardUserProfileForm
{
  public function configure()
  {
  	parent::configure();
  	
  	// set facebook_uid as hidden field
  	$this->widgetSchema['facebook_uid'] = new sfWidgetFormInputHidden();
  	
  	// set avatar widget
		if ($this->getObject() && $this->getObject()->getAvatar() != '')
		{
			$this->setWidget('avatar', new sfWidgetFormInputFileEditable(array(
				'edit_mode' => !$this->getObject()->isNew() && $this->getObject()->getAvatar() != '',
				'with_delete'	=> $this->getObject()->getAvatar() != '',
				'is_image' => true,
				'file_src' => '/uploads/'.sfConfig::get('app_thumbnails_folder').'/'.sfConfig::get('app_thumbnails_prefix').$this->getObject()->getAvatar()
			)));
  	}
		else
		{
			$this->setWidget('avatar', new sfWidgetFormInputFileEditable(array(
				'edit_mode' => true,
				'with_delete'	=> $this->getObject()->getAvatar() != '',
				'is_image' => true,
				'file_src' => '/images/icon_person.gif'
			)));
		}
	  // set avatar validator
		$this->setValidator('avatar', new sfValidatorFile(array(
			'max_size' => 2000000,
			'mime_types' => 'web_images', //you can set your own of course
			'required' => false,
		)));
		$this->validatorSchema['avatar_delete'] = new sfValidatorPass();
			
		// photo
		if ($this->getObject() && $this->getObject()->getPhoto() != '')
		{
			$this->setWidget('photo', new sfWidgetFormInputFileEditable(array(
				'edit_mode' => !$this->getObject()->isNew() && $this->getObject()->getPhoto() != '',
				'with_delete'	=> $this->getObject()->getPhoto() != '',
				'is_image' => true,
				'file_src' => '/uploads/'.sfConfig::get('app_thumbnails_folder').'/'.sfConfig::get('app_thumbnails_prefix').$this->getObject()->getPhoto()
			)));
		}
		else
		{
			$this->setWidget('photo', new sfWidgetFormInputFileEditable(array(
				'edit_mode' => true,
				'with_delete'	=> $this->getObject()->getPhoto() != '',
				'is_image' => true,
				'file_src' => '/images/icon_person.gif'
			)));
		}
		// set photo validator
		$this->setValidator('photo', new sfValidatorFile(array(
			'max_size' => 2000000,
			'mime_types' => 'web_images', //you can set your own of course
			'required' => false,
		)));
		$this->validatorSchema['photo_delete'] = new sfValidatorPass();
  }
}