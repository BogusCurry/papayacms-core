<?php
require_once(dirname(__FILE__).'/../../../../../bootstrap.php');

class PapayaFileSystemChangeNotifierTest extends PapayaTestCase {

  /**
   * @covers PapayaFileSystemChangeNotifier::__construct
   * @covers PapayaFileSystemChangeNotifier::setTarget
   * @covers PapayaFileSystemChangeNotifier::action
   */
  public function testConstructorWithScript() {
    $notifier = new PapayaFileSystemChangeNotifier('/sample/script.php');
    $this->assertInstanceOf(
      'PapayaFileSystemActionScript', $notifier->action()
    );
  }

  /**
   * @covers PapayaFileSystemChangeNotifier::__construct
   * @covers PapayaFileSystemChangeNotifier::setTarget
   * @covers PapayaFileSystemChangeNotifier::action
   */
  public function testConstructorWithUrl() {
    $notifier = new PapayaFileSystemChangeNotifier('http://example.tld/sample/script.php');
    $this->assertInstanceOf(
      'PapayaFileSystemActionUrl', $notifier->action()
    );
  }

  /**
   * @covers PapayaFileSystemChangeNotifier::__construct
   * @covers PapayaFileSystemChangeNotifier::setTarget
   * @covers PapayaFileSystemChangeNotifier::action
   */
  public function testConstructorWithEmptyString() {
    $notifier = new PapayaFileSystemChangeNotifier('');
    $this->assertNull(
      $notifier->action()
    );
  }

  /**
   * @covers PapayaFileSystemChangeNotifier::action
   */
  public function testActionGetAfterSet() {
    $notifier = new PapayaFileSystemChangeNotifier('');
    $notifier->action($action = $this->getMock('PapayaFileSystemAction'));
    $this->assertSame($action, $notifier->action());
  }

  public function testNotify() {
    $action = $this->getMock('PapayaFileSystemAction');
    $action
      ->expects($this->once())
      ->method('execute')
      ->with(array('action' => 'A', 'file' => '/sample/file.png', 'path' => '/sample/'));
    $notifier = new PapayaFileSystemChangeNotifier('');
    $notifier->action($action);
    $notifier->notify(PapayaFileSystemChangeNotifier::ACTION_ADD, '/sample/file.png', '/sample/');
  }
}