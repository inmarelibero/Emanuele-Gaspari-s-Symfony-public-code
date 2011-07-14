<?php

/**
 * default actions.
 *
 * @package    
 * @subpackage default
 * @author     Emanuele Gaspari
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class defaultActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
	public function executeIndex(sfWebRequest $request)
	{
		sfProjectConfiguration::getActive()->loadHelpers('Languages');
		if (!$request->getParameter('sf_culture')) {
    		if ($this->getUser()->isFirstRequest()) {

    			$culture = $request->getPreferredCulture( array_keys(getAvailableLanguages()) );
					$this->getUser()->setCulture($culture);
					$this->getUser()->isFirstRequest(false);
    		} else {
      			$culture = $this->getUser()->getCulture();
    		}
    		$this->redirect('localized_homepage');
  		}
  		
	}
	
	public function executeChangeLanguage(sfWebRequest $request) {
		
		sfProjectConfiguration::getActive()->loadHelpers('Languages');
    	$arr_cultures = array_keys(getAvailableLanguages());
    	
		$form = new sfFormLanguage(
      		$this->getUser(),
      		array('languages' => $arr_cultures)
    	);
 
    	$form->process($request);
		
    	return $this->redirect('localized_homepage');
    	
  }
	
	public function executeError404() {
		$this->redirect('@localized_homepage');
	}

}
