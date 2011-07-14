<?php

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

$configuration = ProjectConfiguration::getApplicationConfiguration('backend', 'prod', false);
sfContext::createInstance($configuration)->dispatch();