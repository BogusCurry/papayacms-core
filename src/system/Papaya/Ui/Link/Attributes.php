<?php
/**
* An control part that append link attributes like class, target and a popup configuration to
* an parent xml element.
*
* Allows to load pages and provides basic function for the working copy and publication.
*
* This is an abstract superclass, please use {@see PapayaContentPageWork} to modify the
* working copy of a page or {@see PapayaContentPagePublication} to use the published page.
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
* @version $Id: Attributes.php 39403 2014-02-27 14:25:16Z weinert $
*/

/**
 * An control part that append link attributes like class, target and a popup configuration to
* an parent xml element.
*
* @property string $class
* @property string $target
* @property boolean $isPopup
* @property string $popupWidth
* @property string $popupHeight
* @property string $popupTop
* @property string $popupLeft
* @property integer $popupOptions
*
* @package Papaya-Library
* @subpackage Ui
*/
class PapayaUiLinkAttributes extends PapayaUiControlPart {

  const OPTION_RESIZEABLE = 1;
  const OPTION_SCROLLBARS_AUTO = 2;
  const OPTION_SCROLLBARS_ALWAYS = 4;
  const OPTION_SCROLLBARS_NEVER = 8;
  const OPTION_TOOLBAR = 16;
  const OPTION_MENUBAR = 32;
  const OPTION_LOCATIONBAR = 64;
  const OPTION_STATUSBAR = 128;

  protected $_class = '';
  protected $_target = '_self';

  protected $_isPopup = FALSE;
  protected $_popupWidth = '50%';
  protected $_popupHeight = '50%';
  protected $_popupTop = NULL;
  protected $_popupLeft = NULL;
  protected $_popupOptions = self::OPTION_SCROLLBARS_NEVER;

  private $_attributeNames = array(
    'class' => 'class',
    'target' => 'target',
    'popup' => 'data-popup'
  );

  protected $_declaredProperties = array(
    'class' => array('_class', '_class'),
    'target' => array('_target', '_target'),
    'isPopup' => array('isPopup'),
    'popupWidth' => array('_popupWidth', '_popupWidth'),
    'popupHeight' => array('_popupHeight', '_popupHeight'),
    'popupTop' => array('_popupTop', '_popupTop'),
    'popupLeft' => array('_popupLeft', '_popupLeft'),
    'popupOptions' => array('_popupOptions', 'setPopupOptions')
  );

  /**
  * Return true if the attribute contain a popup configuration
  *
  * @return boolean
  */
  public function isPopup() {
    return $this->_isPopup;
  }

  /**
  * Remove the popup configuration. Keep the class and reset the target to "_self".
  */
  public function removePopup() {
    $this->_isPopup = FALSE;
    $this->_popupOptions = self::OPTION_SCROLLBARS_NEVER;
    $this->_target = '_self';
  }

  /**
  * Set the basic data for a popup
  *
  * @param string $target
  * @param string|integer $width
  * @param string|integer $height
  * @param string|integer $top
  * @param string|integer $left
  * @param integer $options
  */
  public function setPopup($target, $width, $height, $top = NULL, $left = NULL, $options = NULL) {
    $this->_isPopup = TRUE;
    $this->_target = $target;
    $this->_popupWidth = $width;
    $this->_popupHeight = $height;
    $this->_popupLeft = $left;
    $this->_popupTop = $top;
    if (isset($options)) {
      $this->setPopupOptions($options);
    }
  }

  /**
   * Validate and set the popup options bitmask. This will throw an exception if
   * more then one scrollbars option is set.
   *
   * @param integer $options
   * @throws InvalidArgumentException
   */
  public function setPopupOptions($options) {
    $counter = 0;
    $counter += PapayaUtilBitwise::inBitmask(self::OPTION_SCROLLBARS_AUTO, $options) ? 1 : 0;
    $counter += PapayaUtilBitwise::inBitmask(self::OPTION_SCROLLBARS_ALWAYS, $options) ? 1 : 0;
    $counter += PapayaUtilBitwise::inBitmask(self::OPTION_SCROLLBARS_NEVER, $options) ? 1 : 0;
    if ($counter > 1) {
      throw new InvalidArgumentException(
        'Invalid options definition: only one scrollbars option can be set.'
      );
    }
    $this->_popupOptions = (int)$options;
  }

  /**
  * Return the popup options as an array. "appendTo()" will use this method to fetch the array and
  * serialize it to json for a data-* attribute.
  *
  * @return array
  */
  public function getPopupOptionsArray() {
    $data = array(
      'width' => $this->_popupWidth,
      'height' => $this->_popupHeight
    );
    if (isset($this->_popupTop)) {
      $data['top'] = $this->_popupTop;
    }
    if (isset($this->_popupLeft)) {
      $data['left'] = $this->_popupLeft;
    }
    $popupOptions = $this->popupOptions;
    $data['resizeable'] = PapayaUtilBitwise::inBitmask(self::OPTION_RESIZEABLE, $popupOptions);
    $data['toolBar'] = PapayaUtilBitwise::inBitmask(self::OPTION_TOOLBAR, $popupOptions);
    $data['menuBar'] = PapayaUtilBitwise::inBitmask(self::OPTION_MENUBAR, $popupOptions);
    $data['locationBar'] = PapayaUtilBitwise::inBitmask(self::OPTION_LOCATIONBAR, $popupOptions);
    $data['statusBar'] = PapayaUtilBitwise::inBitmask(self::OPTION_STATUSBAR, $popupOptions);
    if (PapayaUtilBitwise::inBitmask(self::OPTION_SCROLLBARS_ALWAYS, $popupOptions)) {
      $data['scrollBars'] = 'yes';
    } elseif (PapayaUtilBitwise::inBitmask(self::OPTION_SCROLLBARS_NEVER, $popupOptions)) {
      $data['scrollBars'] = 'no';
    } else {
      $data['scrollBars'] = 'auto';
    }
    return $data;
  }

  /**
  * The object append the link attributes to a given element.
  *
  * @param PapayaXmlElement $parent
  * @return PapayaXmlElement
  */
  public function appendTo(PapayaXmlElement $parent) {
    $class = $this->class;
    if (!empty($class)) {
      $parent->setAttribute($this->_attributeNames['class'], $class);
    }
    $target = $this->target;
    if (!empty($target) && $target != '_self') {
      $parent->setAttribute($this->_attributeNames['target'], $target);
    }
    if ($this->isPopup()) {
      $parent->setAttribute(
        $this->_attributeNames['popup'], json_encode($this->getPopupOptionsArray())
      );
    }
    return $parent;
  }
}