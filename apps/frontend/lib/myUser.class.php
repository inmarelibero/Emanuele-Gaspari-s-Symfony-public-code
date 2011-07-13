<?php

require_once sfConfig::get('sf_app_lib_dir').'/facebook.php';

class myUser extends sfGuardSecurityUser
{
	/**
	 * retrieves facebook session
	 */
	public function getFacebook()
	{
		return new Facebook(array(
			  'appId'		=> sfConfig::get('app_facebook_api_id'),
			  'secret'	=> sfConfig::get('app_facebook_api_secret'),
			  'cookie'	=> true,
		));
	}
	
	/**
	 * tries to sign in the symfony platform through facebook session
	 */
	public function signinByFacebookSession()
	{
		try
		{
			$facebook	= $this->getFacebook();
			$session = $facebook->getSession();
			
			$me = null;
			if ($session)
			{
				$me = $facebook->api('/me');
				if($me)
				{
					// facebook uid
					$fbid = $session['uid'];
					
					if (!$this->isAuthenticated())
					{
						//retrieve the user by facebook id
						$sf_guard_user = sfGuardUserTable::findByFacebookUid($fbid);
						
						// if the user logging through facebook does not exist in database, crete one
						if (!$sf_guard_user)
						{
							// verify if email already exists
							$sf_guard_user = sfGuardUserTable::findByEmail($me['email']);
							if (!$sf_guard_user)
							{
								// load helpers
								// ! deprecated use of sfContext::getInstance()
								sfContext::getInstance()->getConfiguration()->loadHelpers(array('Url', 'Tag', 'File'));
								
								// generate random password
								$random_password = generateRandomString(10);
								
								// create a new user
								$sf_guard_user = new sfGuardUser();
											  	
								$sf_guard_user->setFirstName($me['first_name']);
								$sf_guard_user->setLastName($me['last_name']);
								
								// set the username
								$username = $me['email'];
								$verify_username = sfGuardUserTable::findByUsername($username);
								while ($verify_username != null)
								{
									$username = $me['email'].'_'.generaStringaRandom(8);
									$verify_username  = sfGuardUserTable::findByUsername($username);
								}
						  	$sf_guard_user->setUsername($username);
						  	
						  	// set password
						  	$sf_guard_user->setPassword($random_password);
						  	$sf_guard_user->setEmailAddress($me['email']);
					  		
						  	// save the user
					  		$sf_guard_user->save();
					  		
					  		// send registration email
					  		$email_address = $me['email'];
					  		if ($email_address != '')
								{
									$body =		'Thank you for your registration';
									$body .=	'<br><br>';
									$body .=	'Your user infos are:';
									$body .=	'<br>';
									$body .=	'username: '.$sf_guard_user->getUsername();
									$body .=	'<br>';
									$body .=	'password: '.$random_password;
									$body .=	'<br><br>';
									$body .=	'You can login <a href="http://'.$_SERVER['HTTP_HOST'].url_for('@homepage').'">here</a>.';
									$body .=	'<br><br>';
									
									// compose message
									$message = Swift_Message::newInstance()
									  ->setFrom(sfConfig::get('app_provider_email'))
									  ->setTo($sf_guard_user->getEmailAddress())
									  ->setSubject('Registrazione')
									  ->setBody($body);
									
									$type = $message->getHeaders()->get('Content-Type');
									$type->setValue('text/html');
									$type->setParameter('charset', 'utf-8');
									
									// send the mail
									ProjectConfiguration::getMailer()->send($message);
								}
							}
							
					  	$sf_guard_user->Profile->setFacebookUid($fbid);
					  	$sf_guard_user->Profile->save();
					  	$user = $sf_guard_user;
						}
					} //! if (!$this->getUser()->isAuthenticated())
					// if user is authenticaed, simply associate the user to the facebook uid
					else
					{
						$sf_guard_user = $this->getUser()->getGuardUser();
						if ($sf_guard_user && !$sf_guard_user->Profile->isNew())
						{
							$sf_guard_user->Profile->setFacebookUid($me['id']);
							$sf_guard_user->Profile->save();
						}
					}
					
					// sign in the user
					$this->signIn($sf_guard_user);
				}
			}
		}
		// if some error occurs, sign out the user
		catch (Exception $e)
		{
			$this->signOut();
		}
	}
	
	/**
	 * returns the user avatar from user profile
	 */
	public function getAvatar()
	{
		if ($this->getGuardUser()->Profile && $this->getGuardUser()->Profile->getAvatar() != '')
		{
			return $this->getGuardUser()->Profile->getAvatar();
		}
		return false;
	}
}