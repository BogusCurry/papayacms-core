<?php
/**
* Map unicode codepoints to ascii characters.
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
* @subpackage String
* @version $Id: Mapping.php 39301 2014-02-20 10:41:59Z weinert $
*/

/**
* Map unicode codepoints to ascii characters.
*
* This is used to generate an ascii representation of a unicode string. The mapping can be language
* specific. A German "ä" will be mapped to "ae" while an English "ä" will be mapped to "a".
*
* @package Papaya-Library
* @subpackage String
*/
class PapayaStringTransliterationAsciiMapping {

  private $_mappingTables = array();

  private $_mappingFilesPath = '';

  /**
  * Create object and store mapping file path
  */
  public function __construct() {
    $this->_mappingFilesPath = PapayaUtilFilePath::cleanup(
      dirname(__FILE__).'/../../../../utf8/external', FALSE
    );
  }

  /**
   * Get a mapping for a given codepoint. The mapping can be language specific
   *
   * @param integer $codepoint
   * @param string $language
   * @return null
   */
  public function get($codepoint, $language) {
    PapayaUtilConstraints::assertNotEmpty($language);
    $bank = $codepoint >> 8;
    $this->lazyLoad($bank, $language);
    $index = $codepoint & 255;
    if (isset($this->_mappingTables[$language][$bank][$index])) {
      return $this->_mappingTables[$language][$bank][$index];
    } elseif ($language != 'generic') {
      $this->lazyLoad($bank, 'generic');
    }
    if (isset($this->_mappingTables['generic'][$bank][$index])) {
      return $this->_mappingTables['generic'][$bank][$index];
    }
    return NULL;
  }

  /**
  * Clear the mapping data
  */
  public function clear() {
    $this->_mappingTables = array();
  }

  /**
   * Validate if the needed group of codepoint mappings is loaded.
   *
   * @param integer $bank
   * @param string $language
   * @return bool
   */
  public function isLoaded($bank, $language) {
    PapayaUtilConstraints::assertNotEmpty($language);
    return (
      isset($this->_mappingTables[$language]) &&
      is_array($this->_mappingTables[$language]) &&
      array_key_exists($bank, $this->_mappingTables[$language])
    );
  }

  /**
  * Load the needed group of codepoint mappings into the internal buffer. If the language
  * specific mapping does not exist the generic mapping will be loaded and referenced.
  *
  * @param integer $bank
  * @param string $language
  */
  public function lazyLoad($bank, $language) {
    if (!$this->isLoaded($bank, $language)) {
      $mappingFile = $this->getFile($bank, $language);
      if (file_exists($mappingFile)) {
        $UTF8_TO_ASCII = array();
        /** @noinspection PhpIncludeInspection */
        include($mappingFile);
        $this->add(
          $bank,
          $language,
          isset($UTF8_TO_ASCII[$bank]) ? $UTF8_TO_ASCII[$bank] : array()
        );
      } elseif (!(empty($language) || $language == 'generic') ) {
        $this->lazyLoad($bank, 'generic');
        $this->link($bank, $language, 'generic');
      }
    }
  }

  /**
  * Add a group of codepoint mappings to the internal buffer
  *
  * @param integer $bank
  * @param string $language
  * @param array $mapping
  */
  private function add($bank, $language, array $mapping) {
    $this->_mappingTables[$language][$bank] = $mapping;
  }

  /**
  * Link the codepoint groups to two languages, this is used to avoid unessesary loading tries
  *
  * @param integer $bank
  * @param string $languageTo
  * @param string $languageFrom
  */
  private function link($bank, $languageTo, $languageFrom) {
    if ($this->isLoaded($bank, $languageFrom)) {
      $this->_mappingTables[$languageTo][$bank] = &$this->_mappingTables[$languageFrom][$bank];
    }
  }

  /**
   * Get the filename for the given group of codepoint mappings
   *
   * @param integer $bank
   * @param string $language
   * @return string
   */
  public function getFile($bank, $language) {
    return $this->_mappingFilesPath.sprintf(
      "/%sx%02x.php",
      (empty($language) || $language == 'generic') ? '' : $language.'/',
      $bank
    );
  }
}