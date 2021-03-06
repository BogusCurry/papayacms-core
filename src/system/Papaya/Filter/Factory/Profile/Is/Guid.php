<?php
/**
* Profile creating an guid filter, accepting 16 byte random byte strings encoded hexadecimal
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
* @subpackage Filter
* @version $Id: Guid.php 37407 2012-08-15 21:21:51Z weinert $
*/

/**
* Profile creating an guid filter, accepting 16 byte random byte strings encoded hexadecimal
*
* @package Papaya-Library
* @subpackage Filter
*/
class PapayaFilterFactoryProfileIsGuid extends PapayaFilterFactoryProfile {

  /**
   * @see PapayaFilterFactoryProfile::getFilter()
   */
  public function getFilter() {
    return new PapayaFilterPcre('(^[a-fA-F\d]{32}$)Du');
  }
}
