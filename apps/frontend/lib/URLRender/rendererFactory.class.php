<?php

class rendererFactory
{
	private $renderers = array();
	private $config = array(
		'not_yet_render_path' 	=> 'images/not-yet-rendered.png',
		'could_not_render_path'	=> 'images/could-not-render.png',
	);
	
	function __construct() {
		$this->renderers['pdf'] = new pdfRenderer($this->config);
		$this->renderers['png'] = new pngRenderer($this->config);
	}
	
	function getRenderer($renderer) {
		if(isset($this->renderers[$renderer])) 
			return $this->renderers[$renderer];
		else
			NULL;
	}
	
	
}

?>