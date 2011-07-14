<?php

/**
 * gallery actions.
 *
 * @package    
 * @subpackage gallery
 * @author     Emanuele Gaspari
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class galleryActions extends sfActions
{
	/**
	 * displays all photos of a gallery
	 * @param sfWebRequest $request
	 */
  public function executePhotos(sfWebRequest $request)
  {
		$this->forward404Unless($this->gallery = $this->getRoute()->getObject());
  }
  
  /**
   * adds a tag to a gallery (ajax call)
   * 
   * @param sfWebRequest $request
   */
  public function executeAddTag(sfWebRequest $request)
  {
  	$this->forward404Unless($gallery = Doctrine_Core::getTable('Gallery')->find($request->getParameter('gallery_id')));
  	
  	$tag_name = $request->getParameter('tag_name');
  	if ($tag_name != '')
  	{
			$gallery->addTag($tag_name);
			$gallery->save();
  	}
  	return sfView::NONE;
  }
  
  /**
   * removes a tag of a gallery (ajax call)
   * 
   * @param sfWebRequest $request
   */
  public function executeRemoveTag(sfWebRequest $request)
  {
  	$this->forward404Unless($gallery = Doctrine_Core::getTable('Gallery')->find($request->getParameter('gallery_id')));
  	
  	$tag_name = $request->getParameter('tag_name');
  	if ($tag_name != '')
  	{
			$gallery->removeTag($tag_name);
			$gallery->save();
  	}
  	return sfView::NONE;
  }
}