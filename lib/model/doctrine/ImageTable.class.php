<?php

class ImageTable extends Doctrine_Table
{   
    public static function getInstance() {
        return Doctrine_Core::getTable('Image');
    }
    
	public function getImageToProcess() {
		return  $this->createQuery('i')
			 	->where('i.processed_at IS NULL OR i.accessed_at > i.processed_at')
		    	->limit(1)
		    	->orderBy('i.accessed_at DESC')
		    	->execute()
		    	->getFirst();
	}
}
