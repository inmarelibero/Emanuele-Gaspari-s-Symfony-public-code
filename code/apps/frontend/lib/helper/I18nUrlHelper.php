<?php

/**
 * I18nUrlHelper.php
 * 
 * functions designed to work with i18n urls
 */

/**
 * Generate a localized version for the URL you are visiting.
 */
function localized_current_url($sf_culture = null)
{
  if (!$sf_culture)
  {
    throw new sfException(sprintf('Invalid parameter $sf_culture "%s".', $sf_culture));
  }
 
  $routing    = sfContext::getInstance()->getRouting();
  $request    = sfContext::getInstance()->getRequest();
  $controller = sfContext::getInstance()->getController();
 
  // depending on your routing configuration, you can set $route_name = $routing->getCurrentRouteName()
  $route_name = '';
 
  $parameters = $controller->convertUrlStringToParameters($routing->getCurrentInternalUri());
  $parameters[1]['sf_culture'] = $sf_culture;
 
  return $routing->generate($route_name, array_merge($request->getGetParameters(), $parameters[1]));
}

?>