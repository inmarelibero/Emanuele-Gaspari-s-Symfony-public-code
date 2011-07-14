<?php

/**
 * user actions.
 *
 * @package    
 * @subpackage user
 * @author     Emanuele Gaspari
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class userActions extends sfActions
{
  /**
   * returns the faceook session
   */
	private function getFacebook()
	{
		return new Facebook(array(
			  'appId' => sfConfig::get('app_facebook_api_id'),
			  'secret' => sfConfig::get('app_facebook_api_secret'),
			  'cookie' => true,
		));
	}
	
  /**
   * decodes a encrypted request sent by facebook
   * 
   * @param unknown_type $signed_request
   * @param unknown_type $secret
   */
  private function parse_signed_request($signed_request, $secret)
  {
	  list($encoded_sig, $payload) = explode('.', $signed_request, 2); 
	
	  // decode the data
	  $sig = $this->base64_url_decode($encoded_sig);
	  $data = json_decode($this->base64_url_decode($payload), true);
	
	  if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
	    error_log('Unknown algorithm. Expected HMAC-SHA256');
	    return null;
	  }
	
	  // check sig
	  $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
	  if ($sig !== $expected_sig) {
	    error_log('Bad Signed JSON signature!');
	    return null;
	  }
	
	  return $data;
	}
	
	/**
	 * decodes an url
	 * used in parse_signed_request() function
	 * @param unknown_type $input
	 */
	private function base64_url_decode($input) {
	    return base64_decode(strtr($input, '-_', '+/'));
	}

	/**
	 * signs in a user through facebook session
	 * 
	 * @param unknown_type $request
	 */
  public function executeFacebookLogin(sfWebRequest $request)
  {
  	$this->getUser()->signInByFacebookSession();
  	$this->redirect('@homepage');
  }
  
  /**
   * shows a form to associate a user to his facebook account
   * 
   * @param sfWebRequest $request
   */
  public function executeFacebookAssociation(sfWebRequest $request) {}
  
  /**
   * associates a user to his facebook account
   * 
   * @param sfWebRequest $request
   */
  public function executeFacebookAssociationCreate(sfWebRequest $request)
  {
  	try
  	{
			$facebook	= $this->getFacebook();
			$session	= $facebook->getSession();
			
			$me = null;
			if ($session) {
				$me = $facebook->api('/me');
				
				if($me)
				{
					$sf_guard_user = $this->getUser()->getGuardUser();
					if ($sf_guard_user)
					{
						$sf_guard_user->Profile->setFacebookUid($me['id']);
						$sf_guard_user->Profile->save();
					}
				}
			}
			$this->redirect('user/editProfile');
  	}
  	catch (Exception $e)
  	{
			$this->redirect('user/editProfile');
  	}
  }
	
  /***************************************************************************************************
   * user profile
   **************************************************************************************************/
  /**
   * shows the form to register a user
   */
	public function executeFormRegister() {}
	
	/**
	 * verify the registration link sent by email
	 * 
	 * @param sfWebRequest $request
	 */
  public function executeVerifyRegistration(sfWebRequest $request)
  {
  	// retrieves the token sent by email
  	$token_verify_registration = $request->getParameter('t');
  	
  	// serch the database for a user not yet activated with this token
  	$this->forward404Unless($sf_guard_user = SfGuardUserTable::getByTokenVerifyRegistration($token_verify_registration));
  	
  	$sf_guard_user->setIsActive(true);
  	
  	// erase the activation token
  	$sf_guard_user->Profile->setTokenVerifyRegistration(null);
  	
  	$sf_guard_user->Profile->save();
  	
  	$this->user = $sf_guard_user;
  }
  
  /**
   * resets a password when requested by a user
   * 
   * @param sfWebRequest $request
   */
  public function executeResetPassword(sfWebRequest $request)
  {
  	// retrieves the token sent by email
  	$token_verify_reset_password = $request->getParameter('t');
  	
  	// serch the database for a user with this token
  	$this->forward404Unless($sf_guard_user = SfGuardUserTable::getByTokenVerifyResetPassword($token_verify_reset_password));
  	
  	
		// load helpers
		// ! deprecated use of sfContext::getInstance()
		sfContext::getInstance()->getConfiguration()->loadHelpers(array('Url', 'Tag', 'File'));
    	
  	$new_password = generateRandomString(20);
  	
  	$sf_guard_user->setPassword($new_password);
  	$sf_guard_user->save();
  	
		$body =		'Your new password is: '.$new_password;
		$body .=	'<br><br>';
		$body .=	'You can now log in on the <a href="http://'.$_SERVER['HTTP_HOST'].url_for('@homepage').'">homepage</a>.';
		$body .=	'<br><br>';
		
		// compose a message
		$message = Swift_Message::newInstance()
		  ->setFrom(sfConfig::get('app_provider_email'))
		  ->setTo($sf_guard_user->getEmailAddress())
		  ->setSubject('Reset Password')
		  ->setBody($body);
		
		$type = $message->getHeaders()->get('Content-Type');
		$type->setValue('text/html');
		$type->setParameter('charset', 'utf-8');
		
		// sned the email
		$this->mail = $this->getMailer()->send($message);
  	
  	// erase the reset password token
  	$sf_guard_user->Profile->setTokenVerifyResetPassword(null);
  	$sf_guard_user->Profile->save();
  	
  	$this->user = $sf_guard_user;
	}
  
	/**
	 * shows a form to edit a user profile
	 * 
	 * @param unknown_type $request
	 */
  public function executeEditProfile(sfWebRequest $request)
  {
  	$this->form = new UtenteForm($this->getUser()->getGuardUser());
  }
  
  /**
   * updates the profile
   * 
   * @param sfWebRequest $request
   */
  public function executeUpdateProfile(sfWebRequest $request) {
  	
  	$form_params = $request->getParameter('sf_guard_user');
  	
  	$this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
  	$this->forward404Unless($sf_guard_user = Doctrine_Core::getTable('SfGuardUser')->find(array($form_params['id'])), sprintf('Object user does not exist (%s).', $form_params['id']));
  	
		$this->form = new UtenteForm($sf_guard_user);		
		$this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
		
    if ($this->form->isValid())
    {
 			// load helper
			// ! deprecated use of sfContext::getInstance()
    	sfContext::getInstance()->getConfiguration()->loadHelpers('File');

    	$sf_guard_user = $this->form->save();
    	$sf_guard_user->setIsActive(true);
    	$sf_guard_user->save();
    	
    	if ($sf_guard_user->Profile)
    	{
    		$sf_guard_user->Profile->setDescription($form_params['user_profile']['description']);
    		$sf_guard_user->Profile->save();
    	}
    	
    	
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
							case 'foto':
								$sf_guard_user->Profile->setPhoto($filename);
							break;
						}
						$sf_guard_user->Profile->save();
					}
    		}
    	}
    	$this->redirect('user/editProfile');
    }
    $this->setTemplate('editProfile');
  }
  
  /***************************************************************************************************
   * galleries
   **************************************************************************************************/
	/**
	 * shows a list of galleries
	 * 
	 * a user can crete/edit/delete them using apposite buttons
	 * 
	 * @param unknown_type $request
	 */
  public function executeGalleries(sfWebRequest $request)
  {
  	$this->arr_galleries = $this->getUser()->getGuardUser()->getGalleries();
  }
  
  /**
   * shows a form to create a photo gallery
   * 
   * @param sfWebRequest $request
   */
	public function executeNewGallery(sfWebRequest $request)
  {
  	$gallery = new Gallery();
  	$gallery->setSfGuardUser($this->getUser()->getGuardUser());
  	
  	$this->form = new GalleryForm($gallery);
  	$this->gallery = $gallery;
  }
  
  /**
   * creates a photo gallery
   * 
   * @param sfWebRequest $request
   */
  public function executeCreateGallery(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    
    $this->form = new GalleryForm();
    $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
    
    if ($this->form->isValid())
    {
      $gallery = $this->form->save();
      
 			// load helper
			// ! deprecated use of sfContext::getInstance()
			sfContext::getInstance()->getConfiguration()->loadHelpers('File');
			
			// save the photos
			$form_files_uploaded = $request->getFiles();
			if (key_exists('gallery', $form_files_uploaded))
			{
				foreach ($form_files_uploaded['gallery']['photos'] as $i => $uploadedFile)
				{
					$uploadedFile = $uploadedFile['filename'];
					if ($uploadedFile["name"] != '')
					{
						$uploadDir = sfConfig::get('sf_upload_dir').'/'.sfConfig::get('app_photos_folder');
						
						$path_parts			= pathinfo($uploadedFile["name"]);
						$file_extension	= strtolower($path_parts['extension']);
						
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
							
						// save the photo in the database
						if ($move_file)
						{
							$photo = new Photo();
							$photo->setGalleryId($gallery->getId());
							$photo->setFilename($filename);
							$photo->save();
						}
					}
				}
			}
			
			// save the tags
			$arr_tags = $this->form['tags']->getValue();

			if (count($arr_tags) > 0)
			{
				$arr = Array();
				foreach ($arr_tags as $tag)
				{
			    $tag_name = $tag['name'];
			  	if (is_string($tag_name) && $tag_name != '')
			  	{
						array_push($arr, $tag_name);
			  	}
				}
				$gallery->setTags($arr);
				$gallery->save();
			}

			
			$this->redirect('user/galleries');
		}
    $this->setTemplate('newGallery');
  }
  
  /**
   * shows a form to edit a gallery
   * 
   * @param sfWebRequest $request
   */
  public function executeEditGallery(sfWebRequest $request)
  {
    $request->checkCSRFProtection();
    
  	$this->forward404Unless($gallery = Doctrine_Core::getTable('Gallery')->find(array($request->getParameter('id'))), sprintf('Object gallery does not exist (%s).', $request->getParameter('id')));
    $this->form = new GalleryForm($gallery);
    $this->gallery = $gallery;
    
    
    $photo = new Photo();
  	$photo->setGallery($this->gallery);
  	$this->form_upload_photo = new PhotoForm($photo);
  }
	
  /**
   * updates a gallery
   * 
   * @param sfWebRequest $request
   */
  public function executeUpdateGallery(sfWebRequest $request)
  {
  	$form_params = $request->getParameter('gallery');
  	
  	$this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
  	$this->forward404Unless($gallery = Doctrine_Core::getTable('Gallery')->find(array($form_params['id'])), sprintf('Object gallery does not exist (%s).', $form_params['id']));		
		
  	$this->form = new GalleryForm($gallery);
  	$this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
  	
    if ($this->form->isValid())
    {
    	$arr_tags = $gallery->getTags();
      $gallery = $this->form->save();
      $gallery->setTags($arr_tags);
      $gallery->save();
    }
    $this->redirect('user/galleries');
  }
  
  /**
   * deletes a gallery
   * 
   * @param sfWebRequest $request
   */
  public function executeDeleteGallery(sfWebRequest $request)
  {
  	$request->checkCSRFProtection();

    $this->forward404Unless($gallery = Doctrine_Core::getTable('Gallery')->find(array($request->getParameter('id'))), sprintf('Object gallery does not exist (%s).', $request->getParameter('id')));
    
    $gallery->delete();

    $this->redirect('user/galleries');
  }
  
  /**
   * shows the interface to manage the tags of a gallery
   * 
   * @param sfWebRequest $request
   */
	public function executeManageTags(sfWebRequest $request)
  {
  	$this->forward404Unless($this->gallery = Doctrine_Core::getTable('Gallery')->find(array($request->getParameter('gallery_id'))), sprintf('Object gallery does not exist (%s).', $request->getParameter('gallery_id'))); 
  }
  
  
  /***************************************************************************************************
   * photos
   **************************************************************************************************/
	/**
	 * saves the uploaded photos in a gallery
	 * 
	 * @param sfWebRequest $request
	 */
  public function executeSavePhotosToGallery(sfWebRequest $request)
  {
		$this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
		
		$this->form = new PhotoForm();
		$this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));

		// load helper
		// ! deprecated use of sfContext::getInstance()
		sfContext::getInstance()->getConfiguration()->loadHelpers('File');
		
		// save the photos
		$form_files_uploaded = $request->getFiles();					
		$arr_photos_from_form = $request->getParameter('photos');			
		foreach ($form_files_uploaded['photos'] as $i => $uploadedFile)
		{
			// each uploaded file
			$uploadedFile = $uploadedFile['filename'];
			
			if ($uploadedFile["name"] != '')
			{
				$uploadDir = sfConfig::get('sf_upload_dir').'/'.sfConfig::get('app_photos_folder');
				
				$path_parts			= pathinfo($uploadedFile["name"]);
				$file_extension	= strtolower($path_parts['extension']);
				
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
					
				// save the photo in the database
				if ($move_file)
				{
					$photo_from_form = $arr_photos_from_form[$i];
					$photo = new Photo();
					$photo->setGalleryId($photo_from_form['gallery_id']);
					$photo->setFilename($filename);
					$photo->save();
				}
			}
		}
		
		$this->forward('user', 'galleries');
	}
	
	/**
	 * saves the uploaded photos in a gallery (via ajax)
	 * 
	 * @param sfWebRequest $request
	 */
  public function executeAjaxSavePhotosToGallery(sfWebRequest $request)
  {
		$this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
		
		$this->form = new PhotoForm();
		$this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));

		// load helper
		// ! deprecated use of sfContext::getInstance()
		sfContext::getInstance()->getConfiguration()->loadHelpers('File');
		
		// saves the photos
		$form_files_uploaded = $request->getFiles();
		foreach ($form_files_uploaded['photo'] as $i => $uploadedFile)
		{
			if ($uploadedFile["name"] != '')
			{
				$uploadDir = sfConfig::get('sf_upload_dir').'/'.sfConfig::get('app_photos_folder');
				
				$path_parts			= pathinfo($uploadedFile["name"]);
				$file_extension	= strtolower($path_parts['extension']);
				
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

				// save the photo in the database
				if ($move_file)
				{
					$photo_from_form = $uploadedFile;
					$photo = new Photo();
					$photo->setGalleryId($this->form['gallery_id']->getValue());
					$photo->setFilename($filename);
					$photo->save();
				}
			}
		}
		
		// json
		$arr_json = array('name'=>$uploadedFile["name"], 'id'=>$photo->getId());

		$this->getResponse()->setContent(json_encode($arr_json));
  	return sfView::NONE;
	}
	
	/**
	 * shows the interface to manage the photos of a gallery
	 * @param sfWebRequest $request
	 */
	public function executeManagePhotos(sfWebRequest $request)
  {
  	$this->forward404Unless($this->gallery = Doctrine_Core::getTable('Gallery')->find(array($request->getParameter('gallery_id'))), sprintf('Object gallery does not exist (%s).', $request->getParameter('gallery_id')));
  	
  	$photo = new Photo();
  	$photo->setGallery($this->gallery);
  	$this->form = new PhotoForm($photo);
  }
}