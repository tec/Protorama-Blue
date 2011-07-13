<?php

/**
 * PdfRenderJob filter form base class.
 *
 * @package    Protorama Blue
 * @subpackage filter
 * @author     Tino Truppel
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedInheritanceTemplate.php 29570 2010-05-21 14:49:47Z Kris.Wallsmith $
 */
abstract class BasePdfRenderJobFormFilter extends RenderJobFormFilter
{
  protected function setupInheritance()
  {
    parent::setupInheritance();

    $this->widgetSchema->setNameFormat('pdf_render_job_filters[%s]');
  }

  public function getModelName()
  {
    return 'PdfRenderJob';
  }
}
