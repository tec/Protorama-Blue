<?php

/**
 * PdfJob
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @package    Protorama Blue
 * @subpackage model
 * @author     Tino Truppel
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class PdfJob extends BasePdfJob
{
	private $defaultParams = array('width' => '1024');

	private function getCommand() {
		$params = array_merge($this->defaultParams, json_decode($this->getParams(), true));

		$command = "";
		$command .= 'timeout 120 '; // timeout
		$command .= getcwd().'/tools/wkhtmltopdf-amd64 ';  // wkhtmltopdf binary amd64
		$command .= '--load-error-handling ignore ';
		$command .= '"'.$params['url'].'" '; // url
		//$command .= '--custom-header "If-Modified-Since" "'.$modified.'" ';
		$command .= getcwd().'/web/'.$this->getSavePath().'; '; // pdf path
		return $command;
	}

	protected function doRender() {
		shell_exec($this->getCommand());
		$this->setStatus('processed');
	}

	public function validateParams() {
		$params = array_merge($this->defaultParams, json_decode($this->getParams(), true));
		if (!is_numeric($params['width'])) {
			$this->setStatus('failed');
			$this->setErrorMessage('The value of the parameter WIDTH is not valid');
			return false;
		}
		if (!isset($params['url'])) {
			$this->setStatus('failed');
			$this->setErrorMessage('The parameter URL is required');
			return false;
		}
		// TODO: doesn't validate on server for cux-du-hier.de
		/*if (!filter_var($params['url'], FILTER_VALIDATE_URL) && !filter_var('http://'.$params['url'], FILTER_VALIDATE_URL) && !filter_var('https://'.$params['url'], FILTER_VALIDATE_URL)) {
		$this->setStatus('failed');
		$this->setErrorMessage('The value of the parameter URL is not valid');
		return false;
		}*/
		return true;
	}
}