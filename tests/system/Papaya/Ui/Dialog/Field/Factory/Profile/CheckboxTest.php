<?php
require_once(dirname(__FILE__).'/../../../../../../../bootstrap.php');

class PapayaUiDialogFieldFactoryProfileCheckboxTest extends PapayaTestCase {

  /**
   * @covers PapayaUiDialogFieldFactoryProfileCheckbox
   */
  public function testGetField() {
    $options = new PapayaUiDialogFieldFactoryOptions(
      array(
        'name' => 'chebkoxfield',
        'caption' => 'Label',
        'default' => TRUE
      )
    );
    $profile = new PapayaUiDialogFieldFactoryProfileCheckbox();
    $profile->options($options);
    $this->assertInstanceOf('PapayaUiDialogFieldInputCheckbox', $field = $profile->getField());
  }
}