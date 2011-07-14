<?php

/**
 * RenderJob form base class.
 *
 * @method RenderJob getObject() Returns the current form's model object
 *
 * @package    Protorama Blue
 * @subpackage form
 * @author     Tino Truppel
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseRenderJobForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'params'              => new sfWidgetFormInputText(),
      'hash'                => new sfWidgetFormInputText(),
      'path'                => new sfWidgetFormInputText(),
      'status'              => new sfWidgetFormChoice(array('choices' => array('queued' => 'queued', 'processing' => 'processing', 'processed' => 'processed', 'failed' => 'failed'))),
      'error_message'       => new sfWidgetFormInputText(),
      'accessed_at'         => new sfWidgetFormDateTime(),
      'process_started_at'  => new sfWidgetFormDateTime(),
      'process_finished_at' => new sfWidgetFormDateTime(),
      'type'                => new sfWidgetFormInputText(),
      'created_at'          => new sfWidgetFormDateTime(),
      'updated_at'          => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'params'              => new sfValidatorPass(array('required' => false)),
      'hash'                => new sfValidatorString(array('max_length' => 255)),
      'path'                => new sfValidatorString(array('max_length' => 255)),
      'status'              => new sfValidatorChoice(array('choices' => array(0 => 'queued', 1 => 'processing', 2 => 'processed', 3 => 'failed'))),
      'error_message'       => new sfValidatorPass(array('required' => false)),
      'accessed_at'         => new sfValidatorDateTime(),
      'process_started_at'  => new sfValidatorDateTime(array('required' => false)),
      'process_finished_at' => new sfValidatorDateTime(array('required' => false)),
      'type'                => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'          => new sfValidatorDateTime(),
      'updated_at'          => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'RenderJob', 'column' => array('hash')))
    );

    $this->widgetSchema->setNameFormat('render_job[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'RenderJob';
  }

}
