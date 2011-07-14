<?php

require_once dirname(__FILE__).'/../lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    $this->enablePlugins('sfDoctrinePlugin');
    $this->enablePlugins('sfDoctrineGuardPlugin');
    $this->enablePlugins('sfDoctrineActAsTaggablePlugin');
    $this->enablePlugins('sfThumbnailPlugin');
    //$this->enablePlugins('sfCaptchaGDPlugin');
    //$this->enablePlugins('sfFacebookConnectPlugin');
    
    
    /**
     * change some dirs in order to reflect new folder organization of symfony project
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
    $this->setWebDir(dirname(__FILE__).'/../..');
    $this->setCacheDir(dirname(__FILE__).'/../../cache');
    $this->setLogDir(dirname(__FILE__).'/../../log');
  }

  static protected $mailer  = null;
 
  /**
   * Returns the project mailer
   * 
   * piece of code taken from: http://snippets.symfony-project.org/snippet/377
   */
  static public function getMailer()
  {
    if (null !== self::$mailer)
    {
      return self::$mailer;
    }
 
    // If sfContext has instance, returns the classic mailer resource
    if (sfContext::hasInstance() && sfContext::getInstance()->getMailer())
    {
      self::$mailer = sfContext::getInstance()->getMailer();
    }
    else
    {
      // Else, initialization
      if (!self::hasActive())
      {
        throw new sfException('No sfApplicationConfiguration loaded');
      }
      require_once sfConfig::get('sf_symfony_lib_dir').'/vendor/swiftmailer/classes/Swift.php';
      Swift::registerAutoload();
      sfMailer::initialize();
      $applicationConfiguration = self::getActive();
 
      $config = sfFactoryConfigHandler::getConfiguration($applicationConfiguration->getConfigPaths('config/factories.yml'));
 
      self::$mailer = new $config['mailer']['class']($applicationConfiguration->getEventDispatcher(), $config['mailer']['param']);
    }
 
    return self::$mailer;
  }
}