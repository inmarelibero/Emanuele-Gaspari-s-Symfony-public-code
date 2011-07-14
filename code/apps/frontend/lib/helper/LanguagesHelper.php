<?php

/**
 * LanguagesHelper.php
 * 
 * functions designed to work with languages, i18n and localization
 */

/**
 * retrieve the entire array of available languages
 * 
 * @return array
 */
function getAvailableLanguages() {
	return sfConfig::get('app_available_interface_languages');
}

/**
 * returns the array with language parameters for a certain language code
 * 
 * @param $language_code
 * @return array like array	('en' =>  array	(	'code'	 			=> it,
 *							     										   		'long_name' 		=> Italiano,
 *							        											'flag_filename' 	=> it.png
 *							        	 									)
 *													)
 */
function getLanguageToArray($language_code = 'en')
{
	$arr_languages = getAvailableLanguages();
	
	if( array_key_exists($language_code, $arr_languages) )
	{
		return $arr_languages[$language_code];
	}
	
	return false;
}

/**
 * returns the filename of the flag image for a certain language code
 * 
 * @param unknown_type $language_code
 */
function getFlagFilename($language_code = 'en')
{
	
	$arr_available_interface_languages = getAvailableLanguages();
	
	if( array_key_exists($language_code, $arr_available_interface_languages) && array_key_exists('flag_filename', $arr_available_interface_languages[$language_code]) )
	{
		
		$filename = sfConfig::get('app_folder_flag_interface_languages').$arr_available_interface_languages[$language_code]['flag_filename'];
		return $filename;
	}
	
	return false;
}

?>