<?php

abstract class abstractRenderer {
	
	// global configs like image path
	private $config = array();
	
	function __construct($config = array()) {
		$this->config = $config;
	}
	
	// call for cron job
	abstract function renderJob(Image $image, $params = array());
	
	// call for frontend and other render jobs
	abstract function createJob($urls = array(), $params = array());
	
	// default hash
	protected function getHash($urls = array(), $params = array()) {
		return sha1(json_encode($urls).json_encode($params));
	}
}

?>