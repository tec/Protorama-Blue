<?php

/**
 * api actions.
 *
 * @package    Protorama Blue
 * @subpackage api
 * @author     Tino Truppel
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class apiActions extends apiAbstractActions
{
  public function executePost(sfWebRequest $request)
  {
  	$job = RenderJobTable::getInstance()->createNewJob(array_merge($request->getGetParameters(), $request->getPostParameters()));
  	$this->_returnJob($job);
  }
  
  public function executeGet(sfWebRequest $request)
  {
  	$job = $this->module = $this->getRoute()->getObject();  
  	$this->_returnJob($job);
  }
  
  private function _returnJob(RenderJob $job) 
  {  		
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
    $this->returnJson($result);
  }
}
