<?php
require_once(dirname(__FILE__).'/../../../../bootstrap.php');

class PapayaContentPageVersionsTest extends PapayaTestCase {
/**
  * @covers PapayaContentPageVersions::load
  */
  public function testLoad() {
    $databaseResult = $this->getMock('PapayaDatabaseResult');
    $databaseResult
      ->expects($this->any())
      ->method('fetchRow')
      ->with($this->equalTo(PapayaDatabaseResult::FETCH_ASSOC))
      ->will(
        $this->onConsecutiveCalls(
          array(
            'version_id' => '21',
            'version_time' => '123',
            'version_author_id' => '1',
            'version_message' => 'Version log message',
            'topic_change_level' => '0',
            'topic_id' => '42'
          ),
          FALSE
        )
      );
    $databaseAccess = $this->getMock(
      'PapayaDatabaseAccess', array('getTableName', 'queryFmt'), array(new stdClass)
    );
    $databaseAccess
      ->expects($this->any())
      ->method('getTableName')
      ->with($this->isType('string'))
      ->will($this->returnArgument(0));
    $databaseAccess
      ->expects($this->once())
      ->method('queryFmt')
      ->with($this->isType('string'), array('topic_versions', 42), 10, 0)
      ->will($this->returnValue($databaseResult));
    $list = new PapayaContentPageVersions();
    $list->setDatabaseAccess($databaseAccess);
    $this->assertTrue($list->load(42, 10, 0));
    $this->assertAttributeEquals(
      array(
        '21' => array(
          'id' => '21',
          'created' => '123',
          'owner' => '1',
          'message' => 'Version log message',
          'level' => '0',
          'page_id' => '42',
        )
      ),
      '_records',
      $list
    );
  }

  /**
  * @covers PapayaContentPageVersions::getVersion
  */
  public function testGetVersion() {
    $databaseAccess = $this->getMock(
      'PapayaDatabaseAccess', array('getTableName', 'queryFmt'), array(new stdClass)
    );
    $databaseAccess
      ->expects($this->any())
      ->method('getTableName')
      ->withAnyParameters()
      ->will($this->returnArgument(0));
    $databaseAccess
      ->expects($this->once())
      ->method('queryFmt')
      ->with($this->isType('string'), array('topic_versions', 21))
      ->will($this->returnValue(FALSE));
    $list = new PapayaContentPageVersions();
    $list->setDatabaseAccess($databaseAccess);
    $version = $list->getVersion(21);
    $this->assertInstanceOf(
      'PapayaContentPageVersion', $version
    );
  }
}