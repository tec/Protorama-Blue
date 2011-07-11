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
		  	$image = ImageTable::getInstance()->getImageToProcess();
		  	
			if($image) {				
				echo "Process image.\n";
			    		 
			    // to make sure the images is not rendered again even in the case an error occurs 
			    $image->setProcessedAt(date('Y-m-d H:i:s'));
			    $image->save();

				// generate image
			    try {
					echo "Render URL to image: ".$image->getUrl().", parameters: ".$image->getParams()."\n";					
					echo "Using command: ".	$image->getCommand() ."\n";
				   	shell_exec($image->getCommand());	 
			    } catch (Exception $e) {
		    		echo $e->getMessage(), "\n";
				}
	    	
			    // save to DB
			    clearstatcache();	    	
			    if(file_exists(getcwd().'/web/uploads/'.$image->getHash().'.'.$image->decodeParams()->format)) {
			    	$image->setPath('uploads/'.$image->getHash().'.'.$image->decodeParams()->format);
			    } else {
			    	$image->setPath('images/could-not-render.png');  
			    }  
			    $image->save();
			} else {
				echo "Wait for 1 second.\n";
				sleep(1);
			}
	    }
	    flock($fp, LOCK_UN); // release the lock
	}
	fclose($fp);
  }
}
