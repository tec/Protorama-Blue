<?php

/**
 * InstantRender actions.
 *
 * @package    Protorama Blue
 * @subpackage Protorama Blue
 * @author     Tino Truppel
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class InstantRenderActions extends sfActions
{
  public function executeRender(sfWebRequest $request)
  { 	
  	// extract parameters
  	$uri = preg_replace('/-[a-z]+:[a-z0-9]+,/', '', $request->getUri());
  	$path = preg_replace('/-[a-z]+:[a-z0-9]+,/', '', $request->getPathInfo());
  	preg_match_all('/-[a-z]+:[a-z0-9]+,/', $request->getUri(), $matches);
  	$params = array();
  	foreach ($matches[0] as $match) {
  		$param = trim($match, '-,');
  		$param = explode(':', $param, 2);
  		$params[$param[0]] = $param[1];  			
  	}
  	
  	// extract url
  	$url = substr($uri, stripos($uri, $request->getHost()) + strlen($request->getHost()));
  	$urlFristPart = substr($path, 1);
  	if(strpos($urlFristPart, "/") !== FALSE) $urlFristPart = substr($urlFristPart, 0, strpos($urlFristPart, "/"));
  	$url = substr($url, strpos($url, $urlFristPart));
  	$params['url'] = $url;

  	// create job
  	$this->job = RenderJobTable::getInstance()->createNewJob($params);
  	
	// waits max 60 seconds until job is rendered
	$wait = $this->job->getProcessFinishedAt() != NULL ? 0 : 60; 
	while ($wait > 0) {
		$this->job = RenderJobTable::getInstance()->find($this->job->getId());  
		if($this->job->getProcessFinishedAt() > $this->job->getProcessStartedAt() && file_exists(getcwd().'/'.$this->job->getPath())) {
			$wait = 0;
		} else {
			$wait--;
			session_write_close();
			sleep(1);
			clearstatcache();
		}
	}

	if($this->job->getParam('format') == 'pdf' && stripos($this->job->getPath(), 'pdf') !== FALSE) {
		$this->getResponse()->setContentType('application/pdf');
		header('Content-Disposition: attachment; filename="'.$this->job->getParam('url').'.pdf"');		
	} else {
		$this->getResponse()->setContentType('image/png');
	}
  }
}
