<?php

/**
 * Image form base class.
 *
 * @method Image getObject() Returns the current form's model object
 *
 * @package    Protorama Blue
 * @subpackage form
 * @author     Tino Truppel
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 29553 2010-05-20 14:33:00Z Kris.Wallsmith $
 */
abstract class BaseImageForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'url'          => new sfWidgetFormInputText(),
      'params'       => new sfWidgetFormInputText(),
      'hash'         => new sfWidgetFormInputText(),
      'path'         => new sfWidgetFormInputText(),
      'accessed_at'  => new sfWidgetFormDateTime(),
      'processed_at' => new sfWidgetFormDateTime(),
      'created_at'   => new sfWidgetFormDateTime(),
      'updated_at'   => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorChoice(array('choices' => array($this->getObject()->get('id')), 'empty_value' => $this->getObject()->get('id'), 'required' => false)),
      'url'          => new sfValidatorString(array('max_length' => 255)),
      'params'       => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'hash'         => new sfValidatorString(array('max_length' => 255)),
      'path'         => new sfValidatorString(array('max_length' => 255)),
      'accessed_at'  => new sfValidatorDateTime(),
      'processed_at' => new sfValidatorDateTime(array('required' => false)),
      'created_at'   => new sfValidatorDateTime(),
      'updated_at'   => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Image', 'column' => array('hash')))
    );

    $this->widgetSchema->setNameFormat('image[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Image';
  }

}
