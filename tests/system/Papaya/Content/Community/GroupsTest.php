<?php
require_once(dirname(__FILE__).'/../../../../bootstrap.php');

class PapayaContentCommunityGroupsTest extends PapayaTestCase {

  /**
  * @covers PapayaContentCommunityGroups::loadByPermission
  */
  public function testLoadByPermission() {
    $databaseResult = $this->getMock('PapayaDatabaseResult');
    $databaseResult
      ->expects($this->atLeastOnce())
      ->method('fetchRow')
      ->with(PapayaDatabaseResult::FETCH_ASSOC)
      ->will(
        $this->onConsecutiveCalls(
          array(
            'surfergroup_id' => 42,
            'surfergroup_title' => 'surfer group'
          ),
          FALSE
        )
      );
    $databaseAccess = $this
      ->getMockBuilder('PapayaDatabaseAccess')
      ->disableOriginalConstructor()
      ->setMethods(array('queryFmt'))
      ->getMock();
    $databaseAccess
      ->expects($this->once())
      ->method('queryFmt')
      ->with(
        $this->isType('string'),
        array(
          PapayaContentTables::COMMUNITY_GROUPS,
          PapayaContentTables::COMMUNITY_GROUP_PERMISSIONS,
          23
        )
      )
      ->will($this->returnValue($databaseResult));

    $groups = new PapayaContentCommunityGroups();
    $groups->setDatabaseAccess($databaseAccess);
    $this->assertTrue($groups->loadByPermission(23));
    $this->assertEquals(
      array(
        42 => array(
         'id' => 42,
         'title' => 'surfer group'
        )
      ),
      iterator_to_array($groups)
    );
  }
}