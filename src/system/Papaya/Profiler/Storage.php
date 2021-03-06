<?php
/**
* Interface for profiler storage objects.
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
* @subpackage Profiler
* @version $Id: Storage.php 36246 2011-09-27 19:26:09Z weinert $
*/

/**
* Interface for profiler storage objects.
*
* @package Papaya-Library
* @subpackage Profiler
*/
interface PapayaProfilerStorage {

  /**
  * Save profiling data run
  *
  * @param array $data
  * @param string $type
  */
  function saveRun($data, $type);
}