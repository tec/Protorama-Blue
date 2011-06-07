<?php

class pngRenderer extends abstractRenderer {
	function renderJob(Image $image, $params = array()) {
		
		//if(time() - strtotime($this->image->getAccessedAt()) > 30)
	  		//$this->image->setAccessedAt(date('Y-m-d H:i:s'));	
		
	}
	
	function createJob($urls = array(), $params = array()) {
		$hash = $this->getHash($urls, array('width' => $params['width']));
		$image = Doctrine::getTable('Image')->findOneBy("hash", $hash);  		
	  	if(!$image) {  	
		    $image = new Image();
		    $image->setUrl(json_encode(array($urls[0])));
		    $image->setParams(json_encode($params));
			$image->setHash($hash);
			$image->setPath('images/not-yet-rendered.png');			   
	  	} else if(!file_exists(getcwd().'/'.$image->getPath())) {
	  		$image->setPath('images/not-yet-rendered.png');
	  	}		
	  	$image->setAccessedAt(date('Y-m-d H:i:s'));	
		$image->save();		
		return $image;
	}
}

?>