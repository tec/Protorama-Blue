<?php

require_once sfConfig::get('sf_lib_dir').'/vendor/PHPWord/PHPWord.php';

/**
 * WordJob
 *
 * This class has been auto-generated by the Doctrine ORM Framework
 *
 * @package    Protorama Blue
 * @subpackage model
 * @author     Tino Truppel
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class WordJob extends BaseWordJob
{
	public function doRender() {
		$continue = true;
		foreach ($this->getParam('pages') as $page) {
			$subJob = JobTable::getInstance()->createNewJob($page);
			if ($subJob->getStatus() == 'failed') {
				$this->setStatus('failed')->setErrorMessage('Sub job failed with: "'.$subJob->getErrorMessage().'"');
				return $this;
			} else if ($subJob->getStatus() != 'processed') {
				$continue = false;
			}
		}

		if ($continue) {
			$PHPWord = new PHPWord();
			foreach ($this->getParam('pages') as $page) {
				$subJob = JobTable::getInstance()->createNewJob($page);
				$section = $PHPWord->createSection();
				$section->addText($page['title']);
				$section->addTextBreak(2);
				$section->addImage(getcwd().'/web/'.$subJob->getSavePath());
				$section->addText($page['caption']);
			}
			$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
			$objWriter->save(getcwd().'/web/'.$this->getSavePath());
			$this->setStatus('processed');					
		} else {
			$this->setStatus('waiting');
		}
		return $this;
	}
}
