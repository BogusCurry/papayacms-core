<?php
require_once(dirname(__FILE__).'/../../../../../bootstrap.php');

class PapayaCacheIdentifierDefinitionParametersTest extends PapayaTestCase {

  /**
   * @covers PapayaCacheIdentifierDefinitionParameters
   * @dataProvider provideParameterData
   */
  public function testGetStatus($expected, $group, $names, $data) {
    $definition = new PapayaCacheIdentifierDefinitionParameters($names, $group);
    $definition->parameterGroup($group);
    $definition->papaya(
      $this->mockPapaya()->application(
        array(
          'request' => $this->mockPapaya()->request($data)
        )
      )
    );
    $this->assertEquals($expected, $definition->getStatus());
  }

  /**
   * @covers PapayaCacheIdentifierDefinitionParameters
   */
  public function testGetSourcesWithDefaultMethodGet() {
    $definition = new PapayaCacheIdentifierDefinitionParameters(array('foo'));
    $this->assertEquals(
      PapayaCacheIdentifierDefinition::SOURCE_URL,
      $definition->getSources()
    );
  }

  /**
   * @covers PapayaCacheIdentifierDefinitionParameters
   */
  public function testGetSourcesWithMethodPost() {
    $definition = new PapayaCacheIdentifierDefinitionParameters(
      array('foo'), NULL, PapayaRequestParametersInterface::METHOD_POST
    );
    $this->assertEquals(
      PapayaCacheIdentifierDefinition::SOURCE_REQUEST,
      $definition->getSources()
    );
  }

  public static function provideParameterData() {
    return array(
      array(
        TRUE,
        NULL,
        array('foobar'),
        array('foo' => 'bar')
      ),
      array(
        array('PapayaCacheIdentifierDefinitionParameters' => array('foo' => 'bar')),
        NULL,
        array('foo'),
        array('foo' => 'bar')
      ),
      array(
        array('PapayaCacheIdentifierDefinitionParameters' => array('foo' => '')),
        NULL,
        array('foo'),
        array('foo' => '')
      ),
      array(
        array('PapayaCacheIdentifierDefinitionParameters' => array('bar' => '42')),
        NULL,
        array('foo', 'bar'),
        array('bar' => '42')
      ),
      array(
        array('PapayaCacheIdentifierDefinitionParameters' => array('foo' => '21', 'bar' => '42')),
        NULL,
        array('foo', 'bar'),
        array('foo' => '21', 'bar' => '42')
      ),
      array(
        array('PapayaCacheIdentifierDefinitionParameters' => array('bar' => '42')),
        'foo',
        array('bar'),
        array('foo' => array('bar' => '42'))
      ),
      array(
        array('PapayaCacheIdentifierDefinitionParameters' => array('foo[bar]' => '42')),
        NULL,
        array('foo/bar'),
        array('foo' => array('bar' => '42'))
      ),
      array(
        array('PapayaCacheIdentifierDefinitionParameters' => array('bar' => '42')),
        NULL,
        'bar',
        array('bar' => '42')
      ),
    );
  }
}