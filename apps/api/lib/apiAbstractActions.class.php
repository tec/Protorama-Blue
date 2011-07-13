<?php

abstract class apiAbstractActions extends sfActions {
	public function returnJson($data){
		$this->data = $data;
		if (sfConfig::get('sf_environment') == 'dev' && !$this->getRequest()->isXmlHttpRequest()){
			$this->setTemplate('jsonDebug','api');
		} else {
			$this->getResponse()->setHttpHeader('Content-type','application/json');
			$this->setTemplate('json', 'api');
		}		
    	sfConfig::set('sf_web_debug', false);
	}
}