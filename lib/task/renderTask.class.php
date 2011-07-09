<?php

class renderTask extends sfBaseTask
{
  protected function configure()
  {
    // add your own arguments here
    $this->addArguments(array(
       new sfCommandArgument('chunksize', sfCommandArgument::REQUIRED, 'Chunksize'),
    ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = '';
    $this->name             = 'render';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [render|INFO] task renders the images.
Call it with:

  [php symfony render|INFO] chunksize
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();
    
    $fp = fopen("/tmp/protorama-blue.lock", "a+");

	if (flock($fp, LOCK_EX | LOCK_NB)) { // do an exclusive lock
	    ftruncate($fp, 0); // truncate file
	    
	    $cwd = getcwd();  
	    chdir($cwd); 
	    
		for($i = 0; $i < $arguments['chunksize']; $i++) {
		  	$q = Doctrine_Query::create()
		    	->from('Image i')
		    	->where('i.processed_at IS NULL OR i.accessed_at > i.processed_at')
		    	->limit(1)
		    	->orderBy('i.accessed_at DESC');
		    $this->images = $q->execute();

			if(count($this->images) == 0) {
				echo "Wait for 2 seconds.\n";
				sleep(2);
			} else {
				echo "Fetched ".count($this->images)." image.\n";

			    foreach ($this->images as $image) {
			    	$modified = date('D, j M Y G:i:s T', strtotime($image->getProcessedAt()));
			    		 
			    	// to make sure the images is not rendered again even in the case an error occurs 
			    	$image->setProcessedAt(date('Y-m-d H:i:s'));
			    	$image->save();
			    	
					// get parameters
					$params = json_decode($image->getParams(), true);
			    	
					if ($params['format']!='jpg') {
				    	// generate PDF
				    	try {
							echo "Render URL to PDF: ".$image->getUrl().", parameters: ".$image->getParams()."\n";
							$command = "";
					    	//if($options['env'] == 'prod')	$command .= 'timeout 30 '; // timeout
					    	if($options['env'] == 'prod') 	$command .= getcwd().'/tools/wkhtmltopdf-amd64 '; // wkhtmltopdf binary amd64
					    	// TODO
					    	else 				$command .= getcwd().'/tools/wkhtmltopdf-amd64 ';  // wkhtmltopdf binary amd64
					    	//else 				$command .= getcwd().'/tools/wkhtmltopdf-i386 '; // wkhtmltopdf binary i386
					    	$command .= '"'.$image->getUrl().'" '; // url
							//$command .= '--custom-header "If-Modified-Since" "'.$modified.'" ';
					    	$command .= getcwd().'/web/uploads/'.$image->getHash().'.pdf; '; // pdf path
							echo "Using command: ".	$command ."\n";
					    	shell_exec($command);	 
				    	} catch (Exception $e) {
			    			echo $e->getMessage(), "\n";
						}
				    	
				    	// generate PNG
				    	if(filesize(getcwd().'/web/uploads/'.$image->getHash().'.pdf') > 2500 && $params['format']!='pdf') {    
				    		try {
								echo "Render PDF to PNG: ".$image->getUrl()."\n";
						    	$im = new Imagick();
								$im->setResolution(300, 300);
								$im->readImage(getcwd().'/web/uploads/'.$image->getHash().'.pdf[0]');
								$im->trimImage(0);
								$im->scaleImage($params['width'],0); 						
					       		$im->setImageFormat('png');
						 		$im->writeImage(getcwd().'/web/uploads/'.$image->getHash().'.png');
				    		} catch (Exception $e) {
			    				echo $e->getMessage(), "\n";
							}
				    	}
					} else {
						// generate JPG
				    	try {
							echo "Render URL to JPG: ".$image->getUrl().", parameters: ".$image->getParams()."\n";
							$command = "";
					    	//if($options['env'] == 'prod')	$command .= 'timeout 30 '; // timeout
					    	if($options['env'] == 'prod') 	$command .= getcwd().'/tools/wkhtmltoimage-amd64 '; // wkhtmltopdf binary amd64
					    	// TODO
					    	else 				$command .= getcwd().'/tools/wkhtmltoimage-amd64 ';  // wkhtmltopdf binary amd64
					    	//else 				$command .= getcwd().'/tools/wkhtmltopdf-i386 '; // wkhtmltopdf binary i386
					    	$command .= '--zoom '.round($params['width']/1024, 2).' --width '.$params['width'].' ';
					    	$command .= '"'.$image->getUrl().'" '; // url
							//$command .= '--custom-header "If-Modified-Since" "'.$modified.'" ';
					    	$command .= getcwd().'/web/uploads/'.$image->getHash().'.jpg; '; // pdf path
							echo "Using command: ".	$command ."\n";
					    	shell_exec($command);	 
				    	} catch (Exception $e) {
			    			echo $e->getMessage(), "\n";
						}
					}
		    	
			    	// save to DB
			    	clearstatcache();	    	
			    	if(file_exists(getcwd().'/web/uploads/'.$image->getHash().'.'.$params['format'])) 
			    		$image->setPath('uploads/'.$image->getHash().'.'.$params['format']);
			    	else
			    		$image->setPath('images/could-not-render.png');    
			    	$image->save();
				}
			}
	    }
	    flock($fp, LOCK_UN); // release the lock
	}
	fclose($fp);
  }
}
