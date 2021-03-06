<?php
/**
* Encapsulation for translated phrases (get text like system)
*
* @copyright 2014 by papaya Software GmbH - All rights reserved.
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
* @subpackage Content
* @version $Id: Phrases.php 39766 2014-04-28 14:24:05Z weinert $
*/

/**
* Encapsulation for translated phrases (get text like system)
*
* @package Papaya-Library
* @subpackage Content
*/
class PapayaContentPhrases extends PapayaDatabaseRecords {

  /**
  * Map field names to more convinient property names
  *
  * @var array(string=>string)
  */
  protected $_fields = array(
    'id' => 'p.phrase_id',
    'identifier' => 'p.phrase_text_lower',
    'text' => 'p.phrase_text',
    'translation' => 'pt.translation',
    'language_id' => 'pt.lng_id'
  );

  protected $_itemClass = 'PapayaContentPhrase';

  public function load($filter = NULL, $limit = NULL, $offset = NULL) {
    $fields = implode(', ', $this->mapping()->getFields());
    $databaseAccess = $this->getDatabaseAccess();
    if (isset($filter['group'])) {
      $group = $filter['group'];
      unset($filter['group']);
      $sql = "SELECT $fields
                FROM (%s AS p, %s AS g, %s AS grel)
                LEFT JOIN %s AS pt ON (pt.phrase_id = p.phrase_id AND pt.lng_id = '%d')
               WHERE g.module_title_lower = '%s'
                 AND grel.module_id = g.module_id
                 AND p.phrase_id = grel.phrase_id";
      $sql .= PapayaUtilString::escapeForPrintf(
        $this->_compileCondition($filter, ' AND ').$this->_compileOrderBy()
      );
      $parameters = array(
        $databaseAccess->getTableName(PapayaContentTables::PHRASES),
        $databaseAccess->getTableName(PapayaContentTables::PHRASE_GROUPS),
        $databaseAccess->getTableName(PapayaContentTables::PHRASE_GROUP_LINKS),
        $databaseAccess->getTableName(PapayaContentTables::PHRASE_TRANSLATIONS),
        PapayaUtilArray::get($filter, 'language_id', 0),
        $group
      );
    } else {
      $sql = "SELECT $fields
                FROM %s AS p
                LEFT JOIN %s AS pt ON (pt.phrase_id = p.phrase_id AND pt.lng_id = '%d')";
      $sql .= PapayaUtilString::escapeForPrintf(
        $this->_compileCondition($filter).$this->_compileOrderBy()
      );
      $parameters = array(
        $databaseAccess->getTableName(PapayaContentTables::PHRASES),
        $databaseAccess->getTableName(PapayaContentTables::PHRASE_TRANSLATIONS),
        PapayaUtilArray::get($filter, 'language_id', 0)
      );
    }
    return $this->_loadRecords($sql, $parameters, $limit, $offset, $this->_identifierProperties);
  }
}