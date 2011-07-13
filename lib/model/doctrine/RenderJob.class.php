<?php

/**
 * RenderJob
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    Protorama Blue
 * @subpackage model
 * @author     Tino Truppel
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class RenderJob extends BaseRenderJob
{
	public function getCommand() {
		throw new Exception('Method "getCommand" only implemented in child classes.');
	}
	
	public function execCommand() {
		shell_exec($this->getCommand());	 
	}
	
	public function getParam($name) {
		$parmas = json_decode($this->getParams(), true);
		return $parmas[$name];
	}
	
	public function getResult() {		
    	// in the case this method is called within an action
    	sfContext::getInstance()->getConfiguration()->loadHelpers(array('Asset'));
    	return image_path('/'.$this->getPath(), true);
	}
	
	public function getStatus() {
		if (is_null($this->getProcessStartedAt())) {
			return 'NEW';
		} else if ($this->getProcessStartedAt() < $this->getAccessedAt()) {
			return 'QUEUED';
		} else if ($this->getProcessStartedAt() > $this->getProcessFinishedAt()) {
			return 'PROCESSING';
		} else if ($this->getProcessStartedAt() <= $this->getProcessFinishedAt()) {
			return 'PROCESSED';
		} else {
			return 'UNKOWN';
		}
	}
}
