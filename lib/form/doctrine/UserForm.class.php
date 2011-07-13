<?php

/**
 * UtenteForm form.
 *
 * @package    
 * @subpackage form
 * @author     Emanuele Gaspari
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class UtenteForm extends sfGuardUserForm
{
  public function configure()
  {
  	parent::configure();
  	
  	// embeds profile form
  	$profileForm = new sfGuardUserProfileForm($this->object->Profile);
    unset($profileForm['id'], $profileForm['user_id'], $profileForm['created_at'], $profileForm['updated_at']);
    $this->embedForm('user_profile', $profileForm);
    $this->widgetSchema['user_profile']->setLabel(false);
	  
    unset(
	  	$this['created_at'],
			$this['updated_at'],
			$this['salt'],
			$this['algorithm']
   	);
		
   	// if a user is already registered, he cannot change the email
   	if (!$this->getObject()->isNew())
   		$this->widgetSchema['email_address'] = new sfWidgetFormInputHidden();
   	
  	// requires username field
  	$this->validatorSchema['username']->setOption('required', false);
  	
  	// sets password field
  	$this->widgetSchema['password'] = new sfWidgetFormInputPassword();
  	$this->validatorSchema['password_confirmation'] = clone $this->validatorSchema['password'];
  	$this->widgetSchema['password_confirmation'] = new sfWidgetFormInputPassword();
  	
  	// if a user is already registered, password and password confirmation are not required
  	if (!$this->getObject()->isNew())
  	{
  		$this->validatorSchema['password']->setOption('required', false);
  		$this->validatorSchema['password_confirmation']->setOption('required', false);
  	}
  	else
  	{
    	$this->validatorSchema['password']->setOption('required', true);
  	}
    
    
    $this->widgetSchema->moveField('password_confirmation', 'after', 'password');
    $this->mergePostValidator(new sfValidatorSchemaCompare('password', sfValidatorSchemaCompare::EQUAL, 'password_confirmation', array(), array('invalid' => 'The two passwords must be the same.')));
    
    // terms & conditions checkbox
    $this->widgetSchema['terms'] = new sfWidgetFormInputCheckbox();
    $this->setValidator('terms', new sfValidatorChoice(array(
    	'choices' => array('on', 'true'),
    	'required' => false
    )));
  }
 
  /**
   * overwrite of the updateObject() method to set the username equal to the email
   * @see sfFormObject::save()
   */
  public function updateObject($values = null)
  {
  	$values = $this->values;
  	$values['username'] = $this['email_address']->getValue();
  	
  	parent::updateObject($values);
  }
}