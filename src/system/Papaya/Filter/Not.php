<?php
/**
* Abstract filter class implementing logical not, wrapping another filter.
*
* @copyright 2010 by papaya Software GmbH - All rights reserved.
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
* @subpackage Filter
* @version $Id: Not.php 38143 2013-02-19 14:58:24Z weinert $
*/

/**
* Abstract filter class implementing logical not, wrapping another filter.
*
* @package Papaya-Library
* @subpackage Filter
*/
class PapayaFilterNot implements PapayaFilter {

  /**
  * Wrappend filter
  * @var PapayaFilter
  */
  protected $_filter = NULL;

  /**
  * Construct object and store subfilter
  *
  * @param PapayaFilter $filter
  */
  public function __construct(PapayaFilter $filter) {
    $this->_filter = $filter;
  }

  /**
  * Validate the input value using the defined wrapped filter object. If it matches
  * throw an exception. In result the wrapped filter is used as a negative criterion.
  *
  * @throws PapayaFilterExceptionInvalid
  * @param string $value
  * @return TRUE
  */
  public function validate($value) {
    try {
      $this->_filter->validate($value);
    } catch (Exception $e) {
      return TRUE;
    }
    throw new PapayaFilterExceptionInvalid($value);
  }

  /**
  * The filter function is used to read a input value if it is valid.
  *
  * @param string $value
  * @return string|NULL
  */
  public function filter($value) {
    return $value;
  }
}
