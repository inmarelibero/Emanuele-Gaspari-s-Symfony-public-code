<?php

/**
 * FileHelper.php
 * 
 * functions designed to work with files and paths
 */


/**
 * generates a random string (numbers and letters) with size given by parameter
 * 
 * @param unknown_type $length
 */
function generateRandomString($length = 20) {
	$alphabet = str_split('abcdefghijklmnopqrstuvwxyz0123456789');

	$str = '';
	while(strlen($str) < $length)
	{
		$str .= $alphabet[array_rand($alphabet)];
	}
	
	return $str;
}

?>