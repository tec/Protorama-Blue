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
		  	$job = RenderJobTable::getInstance()->getNextJob();
		  	
			if($job) {				
				echo "Process job.\n";
			    		 
			    // to make sure the images is not rendered again even in the case an error occurs 
			    $job->setProcessStartedAt(date('Y-m-d H:i:s'));
			    $job->save();

				// generate image
			    try {
					echo "Render URL to ".$job->getType().": ".$job->getUrl().", parameters: ".$job->getParams()."\n";					
					echo "Using command: ".	$job->getCommand() ."\n";
				   	$job->execCommand();	 
			    } catch (Exception $e) {
		    		echo $e->getMessage(), "\n";
				}
	    	
			    // save to DB
			    clearstatcache();	    	
			    if(file_exists(getcwd().'/web/uploads/'.$job->getHash().'.'.$job->getParam('format'))) {
			    	$job->setPath('uploads/'.$job->getHash().'.'.$job->getParam('format'));
			    } else {
			    	$job->setPath('images/could-not-render.png');  
			    }  
			    $job->setProcessFinishedAt(date('Y-m-d H:i:s'));
			    $job->save();
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
