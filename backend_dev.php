<?php

// this check prevents access to debug front controllers that are deployed by accident to production servers.
// feel free to remove this, extend it or make something more sophisticated.
if (!in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1')))
{
  die('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}

/**
 * change require_once in order to reflect new folder organization of symfony project
 *
 * the new structure would be:
 * 	| - cache
 * 	| - code
 * 			| - apps
 * 			| - config
 * 			| - data
 * 			| - lib
 * 			| - plugins
 * 			\ - test
 * 	| - css
 * 	| - images
 * 	| - js
 * 	| - log
 * 	| - uploads
 * 	| - index.php
 * 	| - frontend_dev.php
 * 	| - backend.php
 * 	\ - backend_dev.php
 */
require_once(dirname(__FILE__).'/code/config/ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('backend', 'dev', true);
sfContext::createInstance($configuration)->dispatch();
