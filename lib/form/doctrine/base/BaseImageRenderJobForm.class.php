<?php

/**
 * ImageRenderJob form base class.
 *
 * @method ImageRenderJob getObject() Returns the current form's model object
 *
 * @package    Protorama Blue
 * @subpackage form
 * @author     Tino Truppel
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseImageRenderJobForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                  => new sfWidgetFormInputHidden(),
      'url'                 => new sfWidgetFormInputText(),
      'params'              => new sfWidgetFormInputText(),
      'hash'                => new sfWidgetFormInputText(),
      'path'                => new sfWidgetFormInputText(),
      'accessed_at'         => new sfWidgetFormDateTime(),
      'process_started_at'  => new sfWidgetFormDateTime(),
      'process_finished_at' => new sfWidgetFormDateTime(),
      'type'                => new sfWidgetFormInputText(),
      'created_at'          => new sfWidgetFormDateTime(),
      'updated_at'          => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'                  => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'url'                 => new sfValidatorString(array('max_length' => 255)),
      'params'              => new sfValidatorPass(array('required' => false)),
      'hash'                => new sfValidatorString(array('max_length' => 255)),
      'path'                => new sfValidatorString(array('max_length' => 255)),
      'accessed_at'         => new sfValidatorDateTime(),
      'process_started_at'  => new sfValidatorDateTime(array('required' => false)),
      'process_finished_at' => new sfValidatorDateTime(array('required' => false)),
      'type'                => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'          => new sfValidatorDateTime(),
      'updated_at'          => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'ImageRenderJob', 'column' => array('hash')))
    );

    $this->widgetSchema->setNameFormat('image_render_job[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ImageRenderJob';
  }

}
