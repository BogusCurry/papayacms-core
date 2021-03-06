<?php
require_once(dirname(__FILE__).'/../../../../bootstrap.php');

class PapayaApplicationProfileResponseTest extends PapayaTestCase {

  /**
  * @covers PapayaApplicationProfileResponse::createObject
  */
  public function testCreateObject() {
    $profile = new PapayaApplicationProfileResponse();
    $response = $profile->createObject($papaya = $this->mockPapaya()->application());
    $this->assertInstanceOf(
      'PapayaResponse', $response
    );
    $this->assertSame($papaya, $response->papaya());
  }
}