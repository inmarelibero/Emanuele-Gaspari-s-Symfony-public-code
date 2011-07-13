<?php

/**
 * default actions.
 *
 * @package    
 * @subpackage default
 * @author     Emanuele Gaspari
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class defaultActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
  	$this->arr_galleries = Doctrine_Core::getTable('Gallery')->createQuery('g')
  													->orderBy('updated_at')
  													->innerJoin('g.Photoes p')
  													->execute();
  }
  
  /**
   * handles the user registration
   * @param sfWebRequest $request
   */
	public function executeRegisterFormSave(sfWebRequest $request)
	{
		$this->form = new UserForm();
		$this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
		
		$email_address 		= $this->form['email_address']->getValue();
		
		// check email availability
    $email_available	= sfGuardUserTable::isEmailAddressAvailable($email_address);
    if ($email_available)
    {
	    if ($this->form->isValid())
	    {
				// load helpers
				// ! deprecated use of sfContext::getInstance()
	    	sfContext::getInstance()->getConfiguration()->loadHelpers(array('File', 'Url', 'Tag'));
	    	
	    	$sf_guard_user = $this->form->save();
	    	
	    	// generate a random token to verify registration
	    	$token_verify_registration = generateRandomString(20);
	    	
	    	$sf_guard_user->Profile->setTokenVerifyRegistration($token_verify_registration);
	    	$sf_guard_user->Profile->save();
	    	
	    	// handle avatar and photo
	    	$form_files_uploaded = $request->getFiles($this->form->getName());
	    	foreach ( $form_files_uploaded['user_profile'] as $field_name => $uploadedFile)
	    	{
	    		if ($uploadedFile["name"] != '')
	    		{
		    		$path_parts			= pathinfo($uploadedFile["name"]);
						$file_extension	= strtolower($path_parts['extension']);
						
						$uploadDir = sfConfig::get('sf_upload_dir').'/'.sfConfig::get('app_users_folder');
						
						$filename = generateRandomString(20).'.'.$file_extension;
						while (file_exists($uploadDir.'/'.$filename))
						{
							$filename = generateRandomString(20).'.'.$file_extension;
						}
						$move_file = move_uploaded_file($uploadedFile["tmp_name"], $uploadDir."/".$filename);
						
						// generate the thumbnail
							$thumbnails_dir = sfConfig::get('sf_upload_dir').'/'.sfConfig::get('app_thumbnails_folder');
							$thumbnail = new sfThumbnail(sfConfig::get('app_thumbnails_width'), sfConfig::get('app_thumbnails_height'));
							$thumbnail->loadFile($uploadDir . "/" . $filename);
							$thumbnail->save($thumbnails_dir."/".sfConfig::get('app_thumbnails_prefix').$filename, 'image/jpeg');
							
	    			if ($move_file)
						{
							switch ($field_name)
							{
								case 'avatar':
									$sf_guard_user->Profile->setAvatar($filename);
								break;
								case 'photo':
									$sf_guard_user->Profile->setPhoto($filename);
								break;
							}
							$sf_guard_user->Profile->save();
						}
	    		}
	    	}
	    	
	    	// send confirmation email to the registered user
				if ($email_address !== '')
				{
					
					$body =		'Thank you for your registration';
					$body .=	'<br><br>';
					$body .=	'Your user infos are:';
					$body .=	'<br>';
					$body .=	'username: '.$sf_guard_user->getUsername();
					$body .=	'<br>';
					$body .=	'password: '.$this->form['password']->getValue();
					$body .=	'<br><br>';
					$body .=	'Click on <a href="http://'.$_SERVER['HTTP_HOST'].url_for('user/verifyRegistration?t='.$token_verify_registration).'">this link</a> to activate your account.';
					$body .=	'<br><br>';
					$body .=	'Then you can log in on the <a href="http://'.$_SERVER['HTTP_HOST'].url_for('@homepage').'">homepage</a>.';
					$body .=	'<br><br>';
					
					// compose the message
					$message = Swift_Message::newInstance()
					  ->setFrom(sfConfig::get('app_provider_email'))
					  ->setTo($sf_guard_user->getEmailAddress())
					  ->setSubject('Registration')
					  ->setBody($body);
					
					$type = $message->getHeaders()->get('Content-Type');
					$type->setValue('text/html');
					$type->setParameter('charset', 'utf-8');
					
					// send the mail
					$this->mail = $this->getMailer()->send($message);
					
					$this->token_verify_registration = $token_verify_registration;
				}
	    }
	    else
	    {
	    	$html_errors = '';

				if ($this->form['email_address']->getValue() == '')	$html_errors .= 'Email is necessary<br>';
				if ($this->form['password']->getValue() == '')	$html_errors .= 'Password is necessary<br>';
				if ($this->form['password']->getValue() != $this->form['password_confirmation']->getValue())	$html_errors .= 'Passwords must coincide<br>';
				if ($this->form['termini']->getValue() == '')	$html_errors .= 'You must accept the terms to continue<br>';
				
				$this->getUser()->setFlash('error', $html_errors);
				$this->redirect('user/formRegister');
	    }
    }
    else
    {
			$this->getUser()->setFlash('error', 'Email address already in use.');
    	$this->forward('user', 'formRegister');
		}
	}
  
	/**
	 * displays the form to retrieve forgotten password
	 */
	public function executeForgotPasswordForm() {}
	
	/**
	 * handles the forgotten password submit request
	 * 
	 * @param sfWebRequest $request
	 */
	public function executeForgotPassword(sfWebRequest $request) {
		
		// load helpers
		// ! deprecated use of sfContext::getInstance()
		sfContext::getInstance()->getConfiguration()->loadHelpers(array('File', 'Url', 'Tag'));
		
		$email_address = $request->getParameter('email_address', '');
		if ($email_address !== '')
		{
			$sf_guard_user = sfGuardUserTable::findByEmail($email_address);

			if ($sf_guard_user)	// if exists a user with that email
			{
				$token_verify_reset_password = generateRandomString(20);
				$sf_guard_user->Profile->setTokenVerifyResetPassword($token_verify_reset_password);
				$sf_guard_user->Profile->save();

				$body =		'User '.$sf_guard_user->getUsername().' requested a password change.';
				$body .=	'<br><br>';
				$body .=	'To reset password click on <a href="http://'.$_SERVER['HTTP_HOST'].url_for('user/resetPassword?t='.$token_verify_reset_password).'">this link</a>.';
				$body .=	'<br><br>';
				
				// compose the mesage
				$message = Swift_Message::newInstance()
					->setFrom(sfConfig::get('app_provider_email'))
					->setTo($sf_guard_user->getEmailAddress())
					->setSubject('Reset Password')
					->setBody($body);
					
				$type = $message->getHeaders()->get('Content-Type');
				$type->setValue('text/html');
				$type->setParameter('charset', 'utf-8');
				
				// send the email
				$this->mail = $this->getMailer()->send($message);
			}
			else {
				$this->error = 'No user found associated to this email address';
			}
		}
		else {
			$this->error = 'Specify a valid email address';
		}
	}
	
	/**
	 * raw handle of a 404 error: forward to home page without displaying any error
	 */
	public function executeError404()
	{
		$this->redirect('@homepage');
	}
}