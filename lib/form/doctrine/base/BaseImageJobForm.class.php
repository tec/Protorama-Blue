<?php

/**
 * ImageJob form base class.
 *
 * @method ImageJob getObject() Returns the current form's model object
 *
 * @package    Protorama Blue
 * @subpackage form
 * @author     Tino Truppel
 * @version    SVN: $Id: sfDoctrineFormGeneratedInheritanceTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseImageJobForm extends JobForm
{
  protected function setupInheritance()
  {
    parent::setupInheritance();

    $this->widgetSchema->setNameFormat('image_job[%s]');
  }

  public function getModelName()
  {
    return 'ImageJob';
  }

}
