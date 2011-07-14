<?php

abstract class apiAbstractActions extends sfActions {
	public function returnJson($data){
		$this->data = $data;
		$this->getResponse()->setHttpHeader('Content-type','application/json');
      	$this->setTemplate('json', 'api');
    	sfConfig::set('sf_web_debug', false);
	}
}