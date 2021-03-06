<?php
/**
* Callbacks that are used by the dialog command
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
* @version $Id: Callbacks.php 35992 2011-07-28 16:35:55Z weinert $
*/

/**
* Callbacks that are used by the dialog command
*
* @package Papaya-Library
* @subpackage Ui
*
* @property PapayaObjectCallback onCreateDialog
* @property PapayaObjectCallback onExecuteSuccessful
* @property PapayaObjectCallback onExecuteFailed
* @method boolean onCreateDialog
* @method boolean onExecuteSuccessful
* @method boolean onExecuteFailed
*/
class PapayaUiControlCommandDialogCallbacks extends PapayaObjectCallbacks {

  /**
  * Initialize object and set callback definition
  */
  public function __construct() {
    parent::__construct(
      array(
        'onCreateDialog' => NULL,
        'onExecuteSuccessful' => NULL,
        'onExecuteFailed' => NULL
      )
    );
  }
}