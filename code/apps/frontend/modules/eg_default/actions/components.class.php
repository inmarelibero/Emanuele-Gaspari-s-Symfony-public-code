<?php

class defaultComponents extends sfComponents
{
	public function executeLanguage(sfWebRequest $request) {  	
		$arr_cultures = array_keys(getAvailableLanguages());
		
		$this->arr_forms_cultures = array();
		foreach($arr_cultures as $culture) {
			$form_name = 'form_'.$culture;
			
			$new_form = $form_name = new sfFormLanguage(
	      		$this->getUser(),
	      		array('languages' => array($culture))
	    	);
	    	$this->arr_forms_cultures[$culture] = $new_form;
	    	
		}
		
  	}
	public function executeHeaderMenu(sfWebRequest $request) {  	
		
  	}
}


?>