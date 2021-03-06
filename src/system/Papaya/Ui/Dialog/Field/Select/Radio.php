<?php
/**
* A selection field displayed as radio boxes, only a single value can be selected.
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
* @subpackage Ui
* @version $Id: Radio.php 36413 2011-11-11 11:02:32Z weinert $
*/

/**
* A selection field displayed as radio boxes, only a single value can be selected.
*
* @package Papaya-Library
* @subpackage Ui
*/
class PapayaUiDialogFieldSelectRadio extends PapayaUiDialogFieldSelect {

  /**
  * type of the select control, used in the xslt template
  *
  * @var string
  */
  protected $_type = 'radio';
}