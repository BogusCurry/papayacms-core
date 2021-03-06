<?php
/**
* Papaya filter class that uses validates and array of bits agains a list and converts into
* a single bitmask value.
*
* @copyright 2011 by papaya Software GmbH - All rights reserved.
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
* @version $Id: Bitmask.php 38143 2013-02-19 14:58:24Z weinert $
*/

/**
* Papaya filter class that uses validates and array of bits agains a list and converts into
* a single bitmask value.
*
* @package Papaya-Library
* @subpackage Filter
*/
class PapayaFilterBitmask implements PapayaFilter {

  /**
  * List of valid bits
  *
  * @var array(integer)
  */
  protected $_bits = array();

  /**
  * Initialize object and store bit list
  *
  * @param array(integer) $bits
  */
  public function __construct(array $bits) {
    $this->_bits = $bits;
  }

  /**
  * Validate the input value using the function and
  * throw an exception if the validation has failed.
  *
  * @throws PapayaFilterException
  * @param string $value
  * @return TRUE
  */
  public function validate($value) {
    if (empty($value)) {
      return TRUE;
    } elseif (preg_match('(^[+-]?\d+$)D', $value)) {
      $bits = (int)$value;
      foreach ($this->_bits as $bit) {
        $bits &= ~$bit;
      }
      if ($bits === 0) {
        return TRUE;
      }
      throw new PapayaFilterExceptionInvalid($value);
    }
    throw new PapayaFilterExceptionType('integer number');
  }

  /**
  * The filter function is used to read a input value if it is valid.
  *
  * @param string $value
  * @return string|NULL
  */
  public function filter($value) {
    $value = (int)$value;
    try {
      $this->validate($value);
      return $value;
    } catch (PapayaFilterException $e) {
      return NULL;
    }
  }
}