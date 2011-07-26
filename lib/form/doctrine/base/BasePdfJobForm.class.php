<?php

/**
 * PdfJob form base class.
 *
 * @method PdfJob getObject() Returns the current form's model object
 *
 * @package    Protorama Blue
 * @subpackage form
 * @author     Tino Truppel
 * @version    SVN: $Id: sfDoctrineFormGeneratedInheritanceTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePdfJobForm extends JobForm
{
  protected function setupInheritance()
  {
    parent::setupInheritance();

    $this->widgetSchema->setNameFormat('pdf_job[%s]');
  }

  public function getModelName()
  {
    return 'PdfJob';
  }

}
