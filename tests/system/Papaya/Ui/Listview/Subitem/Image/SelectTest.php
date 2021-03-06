<?php
require_once(dirname(__FILE__).'/../../../../../../bootstrap.php');

class PapayaUiListviewSubitemImageSelectTest extends PapayaTestCase {

  /**
  * @covers PapayaUiListviewSubitemImageSelect::__construct
  * @covers PapayaUiListviewSubitemImageSelect::setIcons
  */
  public function testConstructor() {
    $icons = $this->getMock('PapayaUiIconList');
    $subitem = new PapayaUiListviewSubitemImageSelect($icons, 'foo');
    $this->assertSame($icons, $subitem->icons);
    $this->assertEquals('foo', $subitem->selection);
  }

  /**
  * @covers PapayaUiListviewSubitemImageSelect::appendTo
  */
  public function testAppendToWithIcon() {
    $icon = $this
      ->getMockBuilder('PapayaUiIcon')
      ->disableOriginalConstructor()
      ->getMock();
    $icon
      ->expects($this->once())
      ->method('appendTo')
      ->with($this->isInstanceOf('PapayaXmlElement'));
    $icons = $this->getMock('PapayaUiIconList', array('offsetExists', 'offsetGet'));
    $icons
      ->expects($this->once())
      ->method('offsetExists')
      ->with('foo')
      ->will($this->returnValue(TRUE));
    $icons
      ->expects($this->once())
      ->method('offsetGet')
      ->with('foo')
      ->will($this->returnValue($icon));

    $dom = new PapayaXmlDocument();
    $subitem = new PapayaUiListviewSubitemImageSelect($icons, 'foo');
    $subitem->icons = $icons;
    $subitem->appendTo($dom->appendElement('sample'));
    $this->assertEquals(
      '<sample><subitem align="left"/></sample>',
      $dom->saveXml($dom->documentElement)
    );
  }

  /**
  * @covers PapayaUiListviewSubitemImageSelect::appendTo
  */
  public function testAppendToWithoutIcon() {
    $icons = $this->getMock('PapayaUiIconList', array('offsetExists', 'offsetGet'));
    $icons
      ->expects($this->once())
      ->method('offsetExists')
      ->with('foo')
      ->will($this->returnValue(FALSE));

    $dom = new PapayaXmlDocument();
    $subitem = new PapayaUiListviewSubitemImageSelect($icons, 'foo');
    $subitem->icons = $icons;
    $subitem->appendTo($dom->appendElement('sample'));
    $this->assertEquals(
      '<sample><subitem align="left"/></sample>',
      $dom->saveXml($dom->documentElement)
    );
  }

}