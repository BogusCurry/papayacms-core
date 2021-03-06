<?php
require_once(dirname(__FILE__).'/../../../../bootstrap.php');

class PapayaContentPageDependencyTest extends PapayaTestCase {

  /**
  * @covers PapayaContentPageDependency::_createKey
  */
  public function testCreateKey() {
    $dependency = new PapayaContentPageDependency();
    $key = $dependency->key();
    $this->assertInstanceOf('PapayaDatabaseRecordKeyFields', $key);
    $this->assertEquals(array('id'), $key->getProperties());
  }

  /**
  * @covers PapayaContentPageDependency::save
  */
  public function testSaveWithoutPageIdExpectingException() {
    $dependency = new PapayaContentPageDependency();
    try {
      $dependency->save();
    } catch (UnexpectedValueException $e) {
      $this->assertEquals(
        'UnexpectedValueException: No target page defined.',
        $e->getMessage()
      );
    }
  }

  /**
  * @covers PapayaContentPageDependency::save
  */
  public function testSaveWithoutOriginPageIdExpectingException() {
    $dependency = new PapayaContentPageDependency();
    $dependency->id = 1;
    try {
      $dependency->save();
    } catch (UnexpectedValueException $e) {
      $this->assertEquals(
        'UnexpectedValueException: No origin page defined.',
        $e->getMessage()
      );
    }
  }

  /**
  * @covers PapayaContentPageDependency::save
  */
  public function testSaveIdEqualsOriginExpectingException() {
    $dependency = new PapayaContentPageDependency();
    $dependency->id = 1;
    $dependency->originId = 1;
    try {
      $dependency->save();
    } catch (UnexpectedValueException $e) {
      $this->assertEquals(
        'UnexpectedValueException: Target equals origin.',
        $e->getMessage()
      );
    }
  }

  /**
  * @covers PapayaContentPageDependency::save
  */
  public function testSaveOriginHasDependencyExpectingException() {
    $dependency = new PapayaContentPageDependency_TestProxy();
    $dependency->isDependency = TRUE;
    $dependency->id = 1;
    $dependency->originId = 2;
    try {
      $dependency->save();
    } catch (UnexpectedValueException $e) {
      $this->assertEquals(
        'UnexpectedValueException: Origin page is a dependency. Chaining is not possible.',
        $e->getMessage()
      );
    }
  }

  /**
  * @covers PapayaContentPageDependency::save
  */
  public function testSaveInsertsRecordExpectingTrue() {
    $databaseAccess = $this
      ->getMockBuilder('PapayaDatabaseAccess')
      ->setMethods(array('getTableName', 'getSqlCondition', 'queryFmt', 'insertRecord'))
      ->disableOriginalConstructor()
      ->getMock();
    $databaseAccess
      ->expects($this->any())
      ->method('getTableName')
      ->with($this->isType('string'))
      ->will($this->returnArgument(0));
    $databaseAccess
      ->expects($this->any())
      ->method('getSqlCondition')
      ->with(array('topic_id' => 21))
      ->will($this->returnValue('>>CONDITION<<'));
    $databaseAccess
      ->expects($this->any())
      ->method('queryFmt')
      ->with('SELECT COUNT(*) FROM %s WHERE >>CONDITION<<', array('topic_dependencies'))
      ->will($this->returnValue(NULL));
    $databaseAccess
      ->expects($this->once())
      ->method('insertRecord')
      ->with(
        'topic_dependencies',
        NULL,
        array(
          'topic_id' => 21,
          'topic_origin_id' => 42,
          'topic_synchronization' => 35,
          'topic_note' => 'sample note'
        )
      )
      ->will($this->returnValue(TRUE));
    $dependency = new PapayaContentPageDependency_TestProxy();
    $dependency->setDatabaseAccess($databaseAccess);
    $dependency->isDependency = FALSE;
    $dependency->id = 21;
    $dependency->originId = 42;
    $dependency->synchronization =
      PapayaContentPageDependency::SYNC_PROPERTIES |
      PapayaContentPageDependency::SYNC_CONTENT |
      PapayaContentPageDependency::SYNC_PUBLICATION;
    $dependency->note = 'sample note';
    $this->assertEquals(array('id' => 21), $dependency->save()->getFilter());
  }

  /**
  * @covers PapayaContentPageDependency::isDependency
  */
  public function testIsDependencyExpectingTrue() {
    $databaseResult = $this->getMock('PapayaDatabaseResult');
    $databaseResult
      ->expects($this->once())
      ->method('fetchField')
      ->will($this->returnValue(1));
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
      ->with($this->isType('string'), array('topic_dependencies', 42))
      ->will($this->returnValue($databaseResult));
    $dependency = new PapayaContentPageDependency();
    $dependency->setDatabaseAccess($databaseAccess);
    $this->assertTrue($dependency->isDependency(42));
  }

  /**
  * @covers PapayaContentPageDependency::isDependency
  */
  public function testIsDependencyExpectingFalse() {
    $databaseResult = $this->getMock('PapayaDatabaseResult');
    $databaseResult
      ->expects($this->once())
      ->method('fetchField')
      ->will($this->returnValue(0));
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
      ->with($this->isType('string'), array('topic_dependencies', 42))
      ->will($this->returnValue($databaseResult));
    $dependency = new PapayaContentPageDependency();
    $dependency->setDatabaseAccess($databaseAccess);
    $this->assertFalse($dependency->isDependency(42));
  }

  /**
  * @covers PapayaContentPageDependency::isDependency
  */
  public function testIsDependencyWithDatabaseErrorExpectingFalse() {
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
      ->with($this->isType('string'), array('topic_dependencies', 42))
      ->will($this->returnValue(FALSE));
    $dependency = new PapayaContentPageDependency();
    $dependency->setDatabaseAccess($databaseAccess);
    $this->assertFalse($dependency->isDependency(42));
  }

  /**
  * @covers PapayaContentPageDependency::isOrigin
  */
  public function testIsOriginExpectingTrue() {
    $databaseResult = $this->getMock('PapayaDatabaseResult');
    $databaseResult
      ->expects($this->once())
      ->method('fetchField')
      ->will($this->returnValue(1));
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
      ->with($this->isType('string'), array('topic_dependencies', 42))
      ->will($this->returnValue($databaseResult));
    $dependency = new PapayaContentPageDependency();
    $dependency->setDatabaseAccess($databaseAccess);
    $this->assertTrue($dependency->isOrigin(42));
  }

  /**
  * @covers PapayaContentPageDependency::isOrigin
  */
  public function testIsOriginExpectingFalse() {
    $databaseResult = $this->getMock('PapayaDatabaseResult');
    $databaseResult
      ->expects($this->once())
      ->method('fetchField')
      ->will($this->returnValue(0));
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
      ->with($this->isType('string'), array('topic_dependencies', 42))
      ->will($this->returnValue($databaseResult));
    $dependency = new PapayaContentPageDependency();
    $dependency->setDatabaseAccess($databaseAccess);
    $this->assertFalse($dependency->isOrigin(42));
  }

  /**
  * @covers PapayaContentPageDependency::isOrigin
  */
  public function testIsOriginWithDatabaseErrorExpectingFalse() {
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
      ->with($this->isType('string'), array('topic_dependencies', 42))
      ->will($this->returnValue(FALSE));
    $dependency = new PapayaContentPageDependency();
    $dependency->setDatabaseAccess($databaseAccess);
    $this->assertFalse($dependency->isOrigin(42));
  }
}

class PapayaContentPageDependency_TestProxy extends PapayaContentPageDependency {

  public $isDependency = FALSE;

  public function isDependency($pageId) {
    return $this->isDependency;
  }
}