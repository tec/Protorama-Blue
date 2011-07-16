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
			$job =	new RenderJob();
			$job->setStatus('failed')->setErrorMessage('Could not parse PARAMS');
		} else {	
			$job = RenderJobTable::getInstance()->createNewJob($params);			
		}
		$this->returnJob($job, $request->getParameter('format'));
	}

	public function executeGet(sfWebRequest $request)
	{
		$job = $this->getRoute()->getObject();
		$this->returnJob($job, $request->getParameter('format'));
	}

	public function returnJob(RenderJob $job, $format){
		$result = array(
	    	'hash' => $job->getHash(),
	    	'params' => json_decode($job->getParams(), true),
	    	'accessedAt' => $job->getAccessedAt(),
			'processStartedAt' => $job->getProcessStartedAt(),
			'processFinishedAt' => $job->getProcessFinishedAt(),
			'type' => $job->getType(),
			'createdAt' => $job->getCreatedAt(),
	    	'result' => $job->getResult(),
	    	'status' => $job->getStatus(),
		);
		if ($result['status'] == 'failed') {
			$result['errorMessage'] = $job->getErrorMessage();
		}

		switch ($format) {
			case 'json':
				$this->result = $result;
				$this->getResponse()->setHttpHeader('Content-type','application/json');
				$this->setTemplate('json', 'api');
				sfConfig::set('sf_web_debug', false);
				break;
			default:
				$this->redirect($job->getResult());
				break;
		}
	}
}
