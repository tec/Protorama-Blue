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
  	$job = JobTable::getInstance()->createNewJob($params);
  	
	// waits max ca. 60 seconds until job is rendered
	$wait = 60; 
	while ($wait > 0 && $job->getStatus() != 'processed' && $job->getStatus() != 'failed') {
		$wait--;
		session_write_close();
		sleep(1);
		clearstatcache();
		$job = JobTable::getInstance()->find($job->getId());
	}
	$this->getResponse()->setContentType('image/png');
	if ($job->getStatus() == 'processed') {
		$this->file = $job->getLastResult()->getAbsolutePath();
	} else if ($job->getStatus() == 'failed') {
		sfContext::getInstance()->getConfiguration()->loadHelpers(array('Asset'));
		$this->file = getcwd().'/images/could-not-render.png';
	} else {
		sfContext::getInstance()->getConfiguration()->loadHelpers(array('Asset'));
		$this->file = getcwd().'/images/not-yet-rendered.png';
	}
  }
}
