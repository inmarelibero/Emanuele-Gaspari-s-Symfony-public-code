<?php

/**
 * sfGuardUserTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class sfGuardUserTable extends PluginsfGuardUserTable
{
    /**
     * Returns an instance of this class.
     *
     * @return object sfGuardUserTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('sfGuardUser');
    }
    
    /**
     * finds a user in the database searching by facebook uid
     * 
     * @param unknown_type $fbid
     */
		public static function findByFacebookUid($fbid = null)
		{
			return Doctrine_Core::getTable('SfGuardUser')
							->createQuery('u')
							->innerjoin('u.Profile p WITH p.facebook_uid = ?', $fbid)
							->fetchOne();
		}

		/**
     * finds a user in the database searching by email (exact matching)
     * 
     * @param unknown_type $fbid
     */
		public static function findByEmail($email_address = null)
		{
			return Doctrine_Core::getTable('SfGuardUser')
							->createQuery('u')
							->where('u.email_address = ?', $email_address)
							->fetchOne();
		}
		
		/**
     * finds a user in the database searching by username (exact matching)
     * 
     * @param unknown_type $fbid
     */
		public static function findByUsername($username = null)
		{
			return Doctrine_Core::getTable('SfGuardUser')
							->createQuery('u')
							->where('u.Username = ?', $username)
							->fetchOne();
		}

		/**
		 * checks the availability of an email address
		 * 
		 * an address is available if not present in the database
		 * 
		 * @param unknown_type $email_address
		 */
		public static function isEmailAddressAvailable($email_address = null)
		{
			return Doctrine_Core::getTable('SfGuardUser')
							->createQuery('u')
							->where('u.email_address = ?', $email_address)
							->count() <= 0;
		}
		
		/**
		 * finds a user in the database searching by the token used to verify the user (exact matching)
		 * @param unknown_type $token
		 */
		public static function getByTokenVerifyRegistration($token = null)
		{
			return Doctrine_Core::getTable('SfGuardUser')
							->createQuery('u')
							->innerJoin('u.Profile p WITH p.token_verify_registration = ?', $token)
							->fetchOne();
		}
		
		/**
		 * finds a user in the database searching by the token used to reset the password (exact matching)
		 * @param unknown_type $token
		 */
		public static function getByTokenVerifyResetPassword($token = null)
		{
			return Doctrine_Core::getTable('SfGuardUser')
							->createQuery('u')
							->innerJoin('u.Profile p WITH p.token_verify_reset_password = ?', $token)
							->fetchOne();
		}
}