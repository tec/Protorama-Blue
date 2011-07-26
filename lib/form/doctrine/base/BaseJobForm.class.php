<?php

/**
 * Job form base class.
 *
 * @method Job getObject() Returns the current form's model object
 *
 * @package    Protorama Blue
 * @subpackage form
 * @author     Tino Truppel
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseJobForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'params'              => new sfWidgetFormInputText(),
      'hash'                => new sfWidgetFormInputText(),
      'status'              => new sfWidgetFormChoice(array('choices' => array('new' => 'new', 'queued' => 'queued', 'processing' => 'processing', 'processed' => 'processed', 'failed' => 'failed', 'waiting' => 'waiting'))),
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
      'status'              => new sfValidatorChoice(array('choices' => array(0 => 'new', 1 => 'queued', 2 => 'processing', 3 => 'processed', 4 => 'failed', 5 => 'waiting'))),
      'error_message'       => new sfValidatorPass(array('required' => false)),
      'accessed_at'         => new sfValidatorDateTime(),
      'process_started_at'  => new sfValidatorDateTime(array('required' => false)),
      'process_finished_at' => new sfValidatorDateTime(array('required' => false)),
      'type'                => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'          => new sfValidatorDateTime(),
      'updated_at'          => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Job', 'column' => array('hash')))
    );

    $this->widgetSchema->setNameFormat('job[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Job';
  }

}
