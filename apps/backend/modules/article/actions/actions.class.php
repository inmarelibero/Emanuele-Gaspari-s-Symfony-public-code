<?php

require_once dirname(__FILE__).'/../lib/articleGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/articleGeneratorHelper.class.php';

/**
 * article actions.
 *
 * @package    
 * @subpackage article
 * @author     Emanuele Gaspari
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class articleActions extends autoArticleActions
{
	/**
	 * executeIndex(sfWebRequest $request)
	 * index action
	 * 
	 * @param sfWebRequest $request
	 */
  public function executeIndex(sfWebRequest $request)
  {
  	$this->arr_offers		= ArticleTable::getOffers();
  	$this->arr_promotions	= ArticleTable::getPromotions();
  }
  
  /**
   * executeUpdate(sfWebRequest $request)
   * updates an article object
   * 
   * @param sfWebRequest $request
   */
	public function executeUpdate(sfWebRequest $request)
  {
    $this->article = $this->getRoute()->getObject();
    $this->forward404Unless($this->article);
    $this->form = $this->configuration->getForm($this->article);

    
    $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));
    if (!$this->form->isValid())
    {
    	$this->setTemplate('edit');
    }
    else
    {
    	$this->processForm($request, $this->form);
    }
  }
  
  /**
   * executeEdit(sfWebRequest $request)
   * custom edit action
   * if an article has already setted a cropped image, go to the edit info form
   * otherwise first create the cropped image
   * 
   * @param sfWebRequest $request
   */
  public function executeEdit(sfWebRequest $request)
  {
  	$this->article = $this->getRoute()->getObject();
  	$this->forward404Unless($this->article);
  	
    if (!$this->article->hasCrop())
    {
    	$this->redirect('article/crop?id='.$this->article->getId());
    }
    else
    {
    	parent::executeEdit($request);
    }
  }
  
  /**
   * executeNew(sfWebRequest $request)
   * sets a default form field switching between two tipes of different articles
   * 
   * @param sfWebRequest $request
   */
  public function executeNew(sfWebRequest $request)
  {
    parent::executeNew($request);
    
    $type = $request->getParameter('type');
    switch ($type)
    {
    	case 'offer':
    		$this->form->setDefault('offer', '1');
    	break;
    	case 'promotion':
    		$this->form->setDefault('promotion', '1');
    	break;
    }
  }
  
  /**
   * executeCrop()
   * action to create a cropped image of an article
   */
	public function executeCrop()
  {
  	$this->article = $this->getRoute()->getObject();
		$this->forward404Unless($this->article);
  }
  
  /**
   * executeSaveCrop(sfWebRequest $request)
   * saves the cropped image
   * 
   * @param sfWebRequest $request
   */
	public function executeSaveCrop(sfWebRequest $request)
  {
  	$this->forward404Unless($article = Doctrine_Core::getTable('Article')->find($request->getParameter('id')));

		// executes the crop and saves the image
		
  		// crop size
	  	$targ_w = $request->getParameter('w');
	  	$targ_h = $request->getParameter('h');
	  	
	  	// crop coordinates
	  	$x			 = $request->getParameter('x');
	  	$y			 = $request->getParameter('y');
	  	
			$jpeg_quality = 100;

			// destionation file
			$src = sfConfig::get('sf_upload_dir').'/'.$article->getFotoFilename();
			$img_r = imagecreatefromjpeg($src);
			$dst_r = ImageCreateTrueColor( $targ_w, $targ_h );
			
			imagecopyresampled($dst_r,$img_r,0,0,$x,$y,
			$targ_w,$targ_h,$targ_w,$targ_h);
	
			$new_filename = sfConfig::get('sf_upload_dir').'/crop_'.$article->getFotoFilename();
		
		// display a message
		if (imagejpeg($dst_r,$new_filename,$jpeg_quality))
		{
			$this->getUser()->setFlash('notice', 'Cropper image correctly saved');
		}
		else
		{
			$this->getUser()->setFlash('notice', 'Error while saving cropped image');
		}

		$this->redirect('article/index');
  	
  }
  
  /**
   * executeSaveOrderAndType(sfWebRequest $request)
   * interface to display a maximum number of articles, ordering them through jQueryUI and saving via ajax
   * 
   * @param sfWebRequest $request
   */
  public function executeSaveOrderAndType(sfWebRequest $request)
  {
  	// saves the offers
  	$offers = $request->getParameter('offers');
  	foreach($offers as $k => $offer)
  	{
  		$item = Doctrine_Core::getTable('Article')->find($offer['value']);
  		if ($item)
  		{
	  		$item->setOffer(true);
	  		$item->setPromotion(false);
	  		$item->setOrder($k);
	  		$item->save();
  		}
  	}
  	
  	// saves the promotions
  	$promotions = $request->getParameter('promotions');
  	foreach($promotions as $k => $promotion)
  	{
  		$item = Doctrine_Core::getTable('Article')->find($promotion['value']);
  		if ($item)
  		{
  			$item->setOffer(false);
	  		$item->setPromotion(true);
	  		$item->setOrder($k);
	  		$item->save();
  		}
  	}
  	
  	exit;
  }
  
}








