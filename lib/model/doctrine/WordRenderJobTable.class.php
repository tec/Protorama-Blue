<?php

/**
 * WordRenderJobTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class WordRenderJobTable extends RenderJobTable
{
    /**
     * Returns an instance of this class.
     *
     * @return object WordRenderJobTable
     */
    public static function getInstance()
    {
        return Doctrine_Core::getTable('WordRenderJob');
    }
}