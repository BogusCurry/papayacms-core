<?php
/**
* Papaya Message Dispatcher Database, handles messages logged to the database
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
* @subpackage Messages
* @version $Id: Database.php 39468 2014-02-28 19:51:17Z weinert $
*/

/**
* Papaya Message Dispatcher Database, handles messages logged to the database
*
* Make sure that the dispatcher does not initialize it's resources only if needed,
* It will be created at the start of the script, unused initialzation will slow the script down.
*
* @package Papaya-Library
* @subpackage Messages
*/
class PapayaMessageDispatcherDatabase
  extends PapayaDatabaseObject
  implements PapayaMessageDispatcher {

  /**
  * Name of logging table
  * @var string
  */
  private $_logTableName = 'log';

  /**
  * Used to prevent DB errors from recursion
  * @var boolean
  */
  protected $_preventMessageRecursion = FALSE;

  /**
  * Log messages to database
  *
  * @param PapayaMessage $message
  * @return boolean
  */
  public function dispatch(PapayaMessage $message) {
    if ($message instanceof PapayaMessageLogable) {
      if ($this->allow($message)) {
        return $this->save($message);
      }
    }
    return FALSE;
  }

  /**
   * Check if the current message should be logged
   *
   * @param \PapayaMessage|\PapayaMessageLogable $message
   * @return bool
   */
  public function allow(PapayaMessageLogable $message) {
    $options = $this->papaya()->options;
    if ($options->get('PAPAYA_PROTOCOL_DATABASE', FALSE)) {
      switch ($message->getType()) {
      case PapayaMessage::SEVERITY_DEBUG:
        return $options->get('PAPAYA_PROTOCOL_DATABASE_DEBUG', FALSE);
      }
      return TRUE;
    } else {
      return FALSE;
    }
  }

  /**
   * Save the message to database
   *
   * @param \PapayaMessage|\PapayaMessageLogable $message
   * @return bool
   */
  protected function save(PapayaMessageLogable $message) {
    $url = new PapayaUrlCurrent();
    $options = $this->papaya()->options;
    $details = '<p>'.$message->getMessage().'</p>';
    if ($message->context() instanceof PapayaMessageContextInterfaceXhtml) {
      $details .= $message->context()->asXhtml();
    }
    $cookies = ($message instanceof PapayaMessagePhpError && !empty($_SERVER['HTTP_COOKIE']))
      ? $_SERVER['HTTP_COOKIE'] :  '';
    $values = array(
      'log_time' => time(),
      'log_msgtype' => $message->getGroup(),
      'log_msgno' => $message->getType(),
      'log_msg_short' => $message->getMessage(),
      'log_msg_long' => $details,
      'log_msg_uri' => $url->getUrl(),
      'log_msg_referer' => empty($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['HTTP_REFERER'],
      'log_msg_cookies' => $cookies,
      'log_msg_script' => empty($_SERVER['SCRIPT_FILENAME']) ? '' : $_SERVER['SCRIPT_FILENAME'],
      'log_msg_from_ip' => empty($_SERVER['REMOTE_ADDR']) ? '' : $_SERVER['REMOTE_ADDR'],
      'log_version_papaya' => $options->get('PAPAYA_VERSION_STRING', ''),
      'log_version_project' => $options->get('PAPAYA_WEBSITE_REVISION', '')
    );
    if ($this->papaya()->hasObject('AdministrationUser', FALSE) &&
        $this->papaya()->administrationUser->isLoggedIn()) {
      $values['user_id'] = $this->papaya()->administrationUser->getUserId();
      $values['username'] = $this->papaya()->administrationUser->getDisplayName();
    }
    if (!$this->_preventMessageRecursion) {
      $this->_preventMessageRecursion = TRUE;
      $result = $this->databaseInsertRecord(
        $this->databaseGetTableName($this->_logTableName, TRUE), NULL, $values
      );
      $this->_preventMessageRecursion = FALSE;
      return FALSE !== $result;
    }
    return FALSE;
  }
}
