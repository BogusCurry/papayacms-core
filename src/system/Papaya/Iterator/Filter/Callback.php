<?php
/**
* An filter iterator to filter an given iterator using a callback function.
*
* @copyright 2012 by papaya Software GmbH - All rights reserved.
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
* @subpackage Iterator
* @version $Id: Callback.php 39721 2014-04-07 13:13:23Z weinert $
*/

/**
* An filter iterator to filter an given iterator using a callback function.
*
* Unlike PHP 5.4 {@see FilterIteratorCallback} this class allows to iterate a traversable, too.
*
* @package Papaya-Library
* @subpackage Iterator
*/
class PapayaIteratorFilterCallback extends FilterIterator {

  private $_callback = NULL;

  /**
  * Create filter iterator and store values, if the provided Iterator is only a
  * Traversable, wrap it using IteratorIterator.
  *
  *
  * @param Traversable $iterator
  * @param callable $callback
  */
  public function __construct(Traversable $iterator, $callback) {
    parent::__construct(
      $iterator instanceof Iterator ? $iterator : new IteratorIterator($iterator)
    );
    $this->setCallback($callback);
  }

  /**
  * Validate and store the callback
  *
  * @param callable $callback
  */
  public function setCallback($callback) {
    PapayaUtilConstraints::assertCallable($callback);
    $this->_callback = $callback;
  }


  /**
  * return stored the callback
  *
  * @return callable
  */
  public function getCallback() {
    return $this->_callback;
  }

  /**
  * Use the callback to validate an element
  *
  * @return boolean
  */
  public function accept() {
    return call_user_func(
      $this->_callback,
      $this->getInnerIterator()->current(),
      $this->getInnerIterator()->key()
    );
  }
}