<?php

/**
 * PdfRenderJob form base class.
 *
 * @method PdfRenderJob getObject() Returns the current form's model object
 *
 * @package    Protorama Blue
 * @subpackage form
 * @author     Tino Truppel
 * @version    SVN: $Id: sfDoctrineFormGeneratedInheritanceTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BasePdfRenderJobForm extends RenderJobForm
{
  protected function setupInheritance()
  {
    parent::setupInheritance();

    $this->widgetSchema->setNameFormat('pdf_render_job[%s]');
  }

  public function getModelName()
  {
    return 'PdfRenderJob';
  }

}
