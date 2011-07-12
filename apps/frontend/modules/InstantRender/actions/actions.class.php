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
  	
  	// set default params, TODO: change params to default if param not valid
  	if(!isset($params['wait']))		$params['wait'] = 60;
  	if(!isset($params['width'])) 	$params['width'] = 800;
  	if(!isset($params['format']))	$params['format'] = 'jpg';

  	// extract url
  	$url = substr($uri, stripos($uri, $request->getHost()) + strlen($request->getHost()));
  	$urlFristPart = substr($path, 1);
  	if(strpos($urlFristPart, "/") !== FALSE) $urlFristPart = substr($urlFristPart, 0, strpos($urlFristPart, "/"));
  	$url = substr($url, strpos($url, $urlFristPart));
  	
  	// save to DB
  	$this->image = Doctrine::getTable('ImageRenderJob')->findOneBy("hash", sha1($url.json_encode($params)));  		
  	if(!$this->image) {  	
	    $this->image = new ImageRenderJob();
	    $this->image->setUrl($url);
	    $this->image->setParams(json_encode($params));
		$this->image->setHash(sha1($url.json_encode($params)));
		$this->image->setPath('images/not-yet-rendered.png');			   
  	} else if(!file_exists(getcwd().'/'.$this->image->getPath())) {
  		$this->image->setPath('images/not-yet-rendered.png');
  	}  	
	if(time() - strtotime($this->image->getAccessedAt()) > 30)
  		$this->image->setAccessedAt(date('Y-m-d H:i:s'));	
	$this->image->save();

	// if wait set and image not yet processed then wait
	$wait = $this->image->getProcessFinishedAt() != NULL ? 0 : $params['wait']; 
	while ($wait > 0) {
		$this->image = Doctrine::getTable('ImageRenderJob')->findOneBy("hash", sha1($url.json_encode($params)));  
		if(	$this->image &&
			$this->image->getProcessFinishedAt() != NULL && 
			$this->image->getPath() != 'images/not-yet-rendered.png' &&
			file_exists(getcwd().'/'.$this->image->getPath())) 
			$wait = 0;
		else {
			$wait -= 5;
			session_write_close();
			sleep(5);
			clearstatcache();
		}
	}

	if($params['format'] == 'pdf' && stripos($this->image->getPath(), 'pdf') !== FALSE) {
		$this->getResponse()->setContentType('application/pdf');
		header('Content-Disposition: attachment; filename="'.$url.'.pdf"');		
	} else
		$this->getResponse()->setContentType('image/png');
	
	//$factory = new rendererFactory();
	//print_r($factory->getRenderer($params['format'])->createJob(array($url), $params)->getPath());
  }
}
