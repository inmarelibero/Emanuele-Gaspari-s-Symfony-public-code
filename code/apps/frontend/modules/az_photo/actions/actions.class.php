<?php

/**
 * photo actions.
 *
 * @package    
 * @subpackage photo
 * @author     Emanuele Gaspari
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class photoActions extends sfActions
{
 /**
  * deletes a photo via ajax
  *
  * @param sfRequest $request A request object
  */
  public function executeAjaxDelete(sfWebRequest $request)
  {
    $this->forward404Unless($photo = Doctrine_Core::getTable('Photo')->find(array($request->getParameter('photo_id'))), sprintf('Object photo does not exist (%s).', $request->getParameter('photo_id')));
  	$photo->delete();
  	return sfView::NONE;
  }
  
  /**
   * retrieves a photo via ajax
   * @param sfWebRequest $request
   */
	public function executeGetAjaxPhoto(sfWebRequest $request)
  {
    $this->forward404Unless($this->photo = Doctrine_Core::getTable('Photo')->find(array($request->getParameter('photo_id'))), sprintf('Object photo does not exist (%s).', $request->getParameter('photo_id')));
  }
}