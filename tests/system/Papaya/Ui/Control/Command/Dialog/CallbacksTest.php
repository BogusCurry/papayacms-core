<?php
require_once(dirname(__FILE__).'/../../../../../../bootstrap.php');

class PapayaUiControlCommandDialogCallbacksTest extends PapayaTestCase {

  /**
  * @covers PapayaUiControlCommandDialogCallbacks::__construct
  */
  public function testConstructor() {
    $callbacks = new PapayaUiControlCommandDialogCallbacks();
    $this->assertNull($callbacks->onCreateDialog->defaultReturn);
    $this->assertNull($callbacks->onExecuteFailed->defaultReturn);
    $this->assertNull($callbacks->onExecuteSuccessful->defaultReturn);
  }
}