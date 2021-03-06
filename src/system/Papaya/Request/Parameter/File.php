<?php
/**
* Encapsulate an uploaded file.
*
* @copyright 2009 by papaya Software GmbH - All rights reserved.
* @link http://www.papaya-cms.com/
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, version 2
*
* You can redistribute and/or modify this script under the terms of the GNU General Public
* License (GPL) version 2, provided that the copyright and license notes, including these
* lines, remain unmodified. papaya is distributed in the hope that it will be useful, but
* WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
* FOR A PARTICULAR PURPOSE.
*
* @package Papaya-Library
* @subpackage Request
* @version $Id: File.php 39721 2014-04-07 13:13:23Z weinert $
*/

/**
* Encapsulate an uploaded file.
*
* @package Papaya-Library
* @subpackage Request
*/
class PapayaRequestParameterFile implements ArrayAccess, IteratorAggregate {

  private $_values = array(
    'temporary' => NULL,
    'name' => '',
    'type' => 'application/octet-stream',
    'size' => 0,
    'error' => 0
  );

  /**
   * @var PapayaRequestParametersName
   */
  private $_name = '';

  private $_loaded = FALSE;

  private $_fileSystem = NULL;

  /**
   * Create file object, provide name and group
   * @param string|PapayaRequestParametersName $name
   * @param string $group
   */
  public function __construct($name, $group = NULL) {
    if ($name instanceof PapayaRequestParametersName) {
      $this->_name = $name;
    } else {
      $this->_name = new PapayaRequestParametersName($name);
    }
    if (!empty($group)) {
      $this->_name->prepend($group);
    }
  }

  /**
   * @return PapayaRequestParametersName
   */
  public function getName() {
    return $this->_name;
  }

  /**
   * Return file path to the uploaded file
   * @return string
   */
  public function __toString() {
    $this->lazyFetch();
    return (string)$this['temporary'];
  }

  /**
   * Return TRUE if here is an temporary uploaded file
   *
   * @return boolean
   */
  public function isValid() {
    $this->lazyFetch();
    return !empty($this['temporary']);
  }

  /**
   * Get the file parameter data as an Iterator
   *
   * @see IteratorAggregate::getIterator()
   * @return Iterator
   */
  public function getIterator() {
    $this->lazyFetch();
    return new ArrayIterator($this->_values);
  }

  /**
   * @see ArrayAccess::offsetExists()
   */
  public function offsetExists($offset) {
    if ($offset == 'temporary') {
      $this->lazyFetch();
      return isset($this->_values['temporary']);
    }
    return array_key_exists($offset, $this->_values);
  }

  /**
   * @see ArrayAccess::offsetGet()
   */
  public function offsetGet($offset) {
    $this->lazyFetch();
    return $this->_values[$offset];
  }

  /**
   * Block changes trough array syntax
   * @see ArrayAccess::offsetSet()
   */
  public function offsetSet($offset, $value) {
    $this->lazyFetch();
    throw new LogicException('Values are loaded from $_FILES.');
  }

  /**
   * Block changes trough array syntax
   * @see ArrayAccess::offsetSet()
   */
  public function offsetUnset($offset) {
    $this->lazyFetch();
    throw new LogicException('Values are loaded from $_FILES.');
  }

  /**
   * Fetch the file data from $_FILES
   */
  private function lazyFetch() {
    if (!$this->_loaded) {
      if (count($this->getName())) {
        $temporaryFile = $this->fetchValue('tmp_name');
        if (!empty($temporaryFile) &&
            $this->fileSystem()->getFile($temporaryFile)->isUploadedFile()) {
          $this->_values['temporary'] = $temporaryFile;
          $this->_values['name'] = $this->fetchValue('name', $this->_values['name']);
          $this->_values['type'] = $this->fetchValue('type', $this->_values['type']);
          $this->_values['size'] = $this->fetchValue('size', $this->_values['size']);
        }
        $this->_values['error'] = $this->fetchValue('error', 0);
      }
    }
  }

  /**
   * Fetch a specific file value from $_FILES
   */
  private function fetchValue($key, $default = NULL) {
    $name = clone $this->getName();
    $name->insertBefore(1, $key);
    return PapayaUtilArray::getRecursive($_FILES, iterator_to_array($name, FALSE), $default);
  }

  /**
   * Getter/Setter for the file system factory
   *
   * @param PapayaFileSystemFactory $fileSystem
   * @return PapayaFileSystemFactory
   */
  public function fileSystem(PapayaFileSystemFactory $fileSystem = NULL) {
    if (isset($fileSystem)) {
      $this->_fileSystem = $fileSystem;
    } elseif (NULL === $this->_fileSystem) {
      $this->_fileSystem = new PapayaFileSystemFactory();
    }
    return $this->_fileSystem;
  }
}