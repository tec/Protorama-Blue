<?php

/**
 * api actions.
 *
 * @package    Protorama Blue
 * @subpackage api
 * @author     Tino Truppel
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class apiActions extends sfActions
{
	public function executePost(sfWebRequest $request)
	{
		$params = urldecode($request->getParameter('params'));		
		$params = json_decode($params, true);
		if (is_null($params)) {
			$job =	new Job();
			$job->setStatus('failed')->setErrorMessage('Could not parse PARAMS');
		} else {	
			$job = JobTable::getInstance()->createNewJob($params);			
		}
		return $this->returnJob($job, $request);
	}

	public function executeGet(sfWebRequest $request)
	{
		$job = $this->getRoute()->getObject();
		return $this->returnJob($job, $request);
	}

	public function returnJob(Job $job, sfWebRequest $request){
		$array = array(
	    	'hash' => $job->getHash(),
	    	'params' => json_decode($job->getParams(), true),
	    	'accessedAt' => $job->getAccessedAt(),
			'processStartedAt' => $job->getProcessStartedAt(),
			'processFinishedAt' => $job->getProcessFinishedAt(),
			'type' => $job->getType(),
			'createdAt' => $job->getCreatedAt(),
	    	'results' => array(),
	    	'status' => $job->getStatus(),
		);
		if ($array['status'] == 'failed') {
			$array['errorMessage'] = $job->getErrorMessage();
		}
		foreach ($job->getResults() as $result) {
			$array['results'][$result->getCreatedAt()] = $result->getUrl();
		}

		switch ($request->getParameter('format')) {
			case 'json':
				$json = json_encode($array);
				// provide JSONP if needed
				if($request->hasParameter('callback')) {
    				$json = $request->getParameter('callback').'('.$json.')';
				}    
				$this->getResponse()->setHttpHeader('Content-type','application/json');
				sfConfig::set('sf_web_debug', false);
				return $this->renderText($json);
				break;
			default:
				$this->redirect($job->getLastResult()->getUrl());
				break;
		}
	}
}
