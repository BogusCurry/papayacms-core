<?php
require_once(dirname(__FILE__).'/../../../../../../../../../bootstrap.php');

class PapayaUiDialogFieldFactoryProfileInputMediaFileTest extends PapayaTestCase {

  /**
   * @covers PapayaUiDialogFieldFactoryProfileInputMediaFile::getField
   */
  public function testGetField() {
    $options = new PapayaUiDialogFieldFactoryOptions(
      array(
        'name' => 'inputfield',
        'caption' => 'Input'
      )
    );
    $profile = new PapayaUiDialogFieldFactoryProfileInputMediaFile();
    $profile->options($options);
    $this->assertInstanceOf('PapayaUiDialogFieldInputMediaFile', $field = $profile->getField());
  }
}