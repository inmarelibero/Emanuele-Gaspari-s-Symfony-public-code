<?php

class myUser extends sfBasicSecurityUser
{
	/**
	 * handles firts requests
	 * @param unknown_type $boolean
	 */
	public function isFirstRequest($boolean = null)
	{
	  if (is_null($boolean))
	  {
	    return $this->getAttribute('first_request', true);
	  }
	 
	  $this->setAttribute('first_request', $boolean);
	}	
}