<?php

/**
 * sfGuardUser form.
 *
 * @package    
 * @subpackage form
 * @author     Emanuele Gaspari
 * @version    SVN: $Id: sfDoctrinePluginFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sfGuardUserForm extends PluginsfGuardUserForm
{
	/**
	 * overrides the doSave() method
	 * 
	 * deletes the avatar, the photo and relative thumbnails files before saving the new ones or just because
	 * a user does not want them anymore
	 * 
	 * @param unknown_type $con
	 */
  public function doSave($con = null)
	{
		// retrieves the form array
		$user_profile_form = $this->getValue('user_profile');
		
		// delete the avatar
		if (key_exists('avatar_delete', $user_profile_form) && $user_profile_form['avatar_delete'] == 'on')
	  {
	  	$avatar = sfConfig::get('sf_upload_dir').'/'.sfConfig::get('app_users_folder').'/'.$this->getObject()->Profile->getAvatar();
	    if (file_exists($avatar))
	      unlink($avatar);

	    $avatar_thumb = sfConfig::get('sf_upload_dir').'/'.sfConfig::get('app_thumbnails_folder').'/'.sfConfig::get('app_thumbnails_prefix').$this->getObject()->Profile->getAvatar();
	    if (file_exists($avatar_thumb))
	      unlink($avatar_thumb);
	  }

	  // delete the photo
		if (key_exists('photo_delete', $user_profile_form) && $user_profile_form['foto_delete'] == 'on')
	  {
	  	$photo = sfConfig::get('sf_upload_dir').'/'.sfConfig::get('app_users_folder').'/'.$this->getObject()->Profile->getPhoto();
	    if (file_exists($photo))
	      unlink($photo);

	    $photo_thumb = sfConfig::get('sf_upload_dir').'/'.sfConfig::get('app_thumbnails_folder').'/'.sfConfig::get('app_thumbnails_prefix').$this->getObject()->Profile->getPhoto();
	    if (file_exists($photo_thumb))
	      unlink($photo_thumb);
	  }

		parent::doSave($con);
	}
}