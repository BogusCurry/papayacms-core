<?php
/**
* A single line input for password - the characters are not shown and the value is never read from
* data()
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
* @version $Id: Password.php 39403 2014-02-27 14:25:16Z weinert $
*/

/**
* A single line input for password - the characters are not shown and the value is never read from
* data() - only from parameters
*
* @package Papaya-Library
* @subpackage Ui
*/
class PapayaUiDialogFieldInputPassword extends PapayaUiDialogFieldInput {

  /**
  * Field type, used in template
  *
  * @var string
  */
  protected $_type = 'password';

  /**
   * Initialize object, set caption, field name and maximum length
   *
   * @param string|PapayaUiString $caption
   * @param string $name
   * @param integer $length
   * @param PapayaFilter|NULL $filter
   * @internal param mixed $default
   */
  public function __construct($caption, $name, $length = 1024, PapayaFilter $filter = NULL) {
    $this->setCaption($caption);
    $this->setName($name);
    $this->setMaximumLength($length);
    if (isset($filter)) {
      $this->setFilter($filter);
    } else {
      $this->setFilter(new PapayaFilterPassword());
    }
  }

  /**
  * Get the current field value.
  *
  * If the dialog object has a matching paremeter it is used. Unlike the other input fields
  * data is ignored to avoid displaying stored passwords. the default value will be ignored, too.
  *
  * If neither dialog parameter or data is available, the default value is returned.
  *
  * @return mixed
  */
  public function getCurrentValue() {
    $name = $this->getName();
    if ($this->hasCollection() &&
        $this->collection()->hasOwner() &&
        !empty($name)) {
      if (!$this->getDisabled() && $this->collection()->owner()->parameters()->has($name)) {
        return $this->collection()->owner()->parameters()->get($name);
      }
    }
    return NULL;
  }
}