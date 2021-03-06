<?php
/**
* A field that output a message inside the dialog
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
* @version $Id: Message.php 39430 2014-02-28 09:21:51Z weinert $
*/

/**
* A field that output a message inside the dialog
*
* @package Papaya-Library
* @subpackage Ui
*/
class PapayaUiDialogFieldMessage extends PapayaUiDialogFieldInformation {

  /**
  * Message image
  *
  * @var string
  */
  private $_images = array(
    PapayaMessage::SEVERITY_INFO => 'status-dialog-information',
    PapayaMessage::SEVERITY_WARNING => 'status-dialog-warning',
    PapayaMessage::SEVERITY_ERROR => 'status-dialog-error'
  );

  /**
   * Create object and assign needed values
   *
   * @param PapayaUiString|string $severity
   * @param string|PapayaUiString $message
   * @internal param string $image
   */
  public function __construct($severity, $message) {
    $severity = isset($this->_images[$severity]) ? $severity : PapayaMessage::SEVERITY_INFO;
    parent::__construct($message, $this->_images[$severity]);
  }
}