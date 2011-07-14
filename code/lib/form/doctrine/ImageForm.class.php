<?php

/**
 * Image form.
 *
 * @package    
 * @subpackage form
 * @author     Emanuele Gaspari
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ImmagineForm extends BaseImmagineForm
{
  public function configure()
  {
		parent::setup();
		unset($this['created_at'], $this['updated_at']);
  	
		// set widget for filename
		$this->widgetSchema['filename'] = new sfWidgetFormInputFileEditable(array(
			'label'     => 'File',
			'file_src'  => '/uploads/'.$this->getObject()->getFilename(),
			'is_image'  => true,
			'edit_mode' => !$this->isNew() && $this->getObject()->getFilename() != '',
			'template'  => '<div>%file%<br />%input%<br />%delete% %delete_label%</div>',
		));
		
		// set validator for filename
		$this->setValidator('filename', new sfValidatorFile(array(
			'max_size' => 500000,
			'mime_types' => 'web_images',
			'path' => sfConfig::get('sf_upload_dir'),
			'required' => false
		)));
	  
		$this->validatorSchema['filename_delete'] = new sfValidatorPass();
  		
  }
}