<?php

/**
 * Image
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @package    webtopng
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 6820 2009-11-30 17:27:49Z jwage $
 */
class Image extends BaseImage
{
	public function getCommand() {		
		$command = "";
		//if($options['env'] == 'prod')	$command .= 'timeout 30 '; // timeout
		$command .= getcwd().'/tools/wkhtmltoimage-amd64 ';  // wkhtmltopdf binary amd64
		$command .= '--zoom '.round($this->decodeParams()->width/1024, 2).' --width '.$this->decodeParams()->width.' ';
		$command .= '--format '.$this->decodeParams()->format.' ';
		$command .= '"'.$this->getUrl().'" '; // url
		//$command .= '--custom-header "If-Modified-Since" "'.$modified.'" ';
		$command .= getcwd().'/web/uploads/'.$this->getHash().'.'.$this->decodeParams()->format.'; '; // image path
		return $command;
	}
	
	public function decodeParams() {
		return json_decode($this->getParams());
	}
}
