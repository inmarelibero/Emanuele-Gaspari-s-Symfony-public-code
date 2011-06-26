<?php

class myUser extends sfGuardSecurityUser
{
	
	/**
	 * overwrite of signIn() method in /plugins/sfDoctrineGuardPlugin/lib/user/sfGuardSecurityUser.class.php
	 * 
	 * signs out the user once logged if it is not a super admin
	 * 
	 * @param unknown_type $user
	 * @param unknown_type $remember
	 * @param unknown_type $con
	 */
	public function signIn($user, $remember = false, $con = null) {
		parent::signIn($user, $remember, $con);
		
		$this->signOutIfIsNotAdmin();
	}
	
	public function signOutIfIsNotAdmin()
	{
		if(!$this->hasCredential('admin') || !$this->isSuperAdmin())
		{
			$this->signOut();
		}
	}
}




