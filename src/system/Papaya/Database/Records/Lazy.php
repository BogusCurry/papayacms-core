<?php
/**
* Papaya Database List, represents a list of records fetched from the database. Additionally
* to the loading prozess is triggered by access the data.
*
* @copyright 2002-2009 by papaya Software GmbH - All rights reserved.
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
* @subpackage Database
* @version $Id: Lazy.php 39092 2014-01-30 17:06:00Z weinert $
*/

/**
* Papaya Database List, represents a list of records fetched from the database. Additionally
* to the loading prozess is triggered by access the data.
*
* It allows to define how data should be loaded without loading them at that point of
* the execution process.
*
* If activateLazyLoad is called, the loaded records are resettet and the parameters are stored for
* a later loading. The loading is trigered by any method that accesses the data.
*
* @package Papaya-Library
* @subpackage Database
*/
abstract class PapayaDatabaseRecordsLazy extends PapayaDatabaseRecords {

  private $_loadingParameters = NULL;

  /**
  * Define lazy load parameters and activate it.
  *
  * @param array $filter
  * @param NULL|integer $limit
  * @param NULL|integer $offset
  */
  public function activateLazyLoad($filter = array(), $limit = NULL, $offset = NULL) {
    $this->_loadingParameters = func_get_args();
    $this->_records = NULL;
  }

  /**
   * Return the lazy loading parameters, NULL if here are none.
   *
   * @return array|NULL
   */
  public function getLazyLoadParameters() {
    return $this->_loadingParameters;
  }
  /**
  * If loading parameters are stored, use them to load the records.
  */
  protected function lazyLoad() {
    if (isset($this->_loadingParameters)) {
      call_user_func_array(array($this, 'load'), $this->_loadingParameters);
      $this->_loadingParameters = NULL;
    }
  }

  /**
  * If records are loaded, delete the stored loading parameters. So a direct call to load()
  * will reset them, too.
  */
  protected function _loadRecords($sql, $parameters, $limit, $offset, $idProperties = array()) {
    $this->_loadingParameters = NULL;
    return parent::_loadRecords($sql, $parameters, $limit, $offset, $idProperties);
  }

  /**
   *Absolute Count for limited results
   *
   * @return int
   * @internal param mixed $offset
   */
  public function absCount() {
    $this->lazyLoad();
    return parent::absCount();
  }

  /**
  * Get records as array, trigger lazy load
  *
  * @return array
  */
  public function toArray() {
    $this->lazyLoad();
    return parent::toArray();
  }

  /**
  * Get records as iterator, trigger lazy load
  *
  * @return Iterator
  */
  public function getIterator() {
    $this->lazyLoad();
    return parent::getIterator();
  }

  /**
  * Get Record count, trigger lazy load
  *
  * @return integer
  */
  public function count() {
    $this->lazyLoad();
    return parent::count();
  }

  /**
  * ArrayAccess Interface, validate if record exists, after triggering lazy load.
  *
  * @param mixed $offset
  * @return boolean
  */
  public function offsetExists($offset) {
    $this->lazyLoad();
    return parent::offsetExists($offset);
  }

  /**
  * ArrayAccess Interface, return record, after triggering lazy load.
  *
  * @param mixed $offset
  * @return array
  */
  public function offsetGet($offset) {
    $this->lazyLoad();
    return parent::offsetGet($offset);
  }

  /**
  * ArrayAccess Interface, set record, after triggering lazy load.
  *
  * @param mixed $offset
  * @param array $value
  */
  public function offsetSet($offset, $value) {
    $this->lazyLoad();
    parent::offsetSet($offset, $value);
  }

  /**
  * ArrayAccess Interface, remove record, after triggering lazy load.
  *
  * @param mixed $offset
  */
  public function offsetUnset($offset) {
    $this->lazyLoad();
    parent::offsetUnset($offset);
  }
}