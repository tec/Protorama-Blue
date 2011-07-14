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
		new sfCommandOption('lock', null, sfCommandOption::PARAMETER_REQUIRED, 'Unique lock string', '1'),
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
			

		$fp = fopen("/tmp/protorama-blue.".$options['lock'].".lock", "a+");

		if (flock($fp, LOCK_EX | LOCK_NB)) { // do an exclusive lock
			ftruncate($fp, 0); // truncate file
			 
			$cwd = getcwd();
			chdir($cwd);
				
			for($i = 0; $i < $arguments['chunksize']; $i++) {
				$job = RenderJobTable::getInstance()->getNextJob();

				if($job) {
					$this->logSection("render", "Process job ".$job->getHash().": ".$job->getParams());
					$job->render();
				} else {
					$this->logSection("render", "Wait for 1 second");
					sleep(1);
				}
			}
			flock($fp, LOCK_UN); // release the lock
		}
		fclose($fp);
	}
}
