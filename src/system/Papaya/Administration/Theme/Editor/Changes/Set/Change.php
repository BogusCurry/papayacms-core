<?php
/**
* Dialog command that allows to edit the the set title and add new sets
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
* @subpackage Administration
* @version $Id: Change.php 39430 2014-02-28 09:21:51Z weinert $
*/

/**
* Dialog command that allows to edit the dynamic values on on page, the groups are field groups
*
* @package Papaya-Library
* @subpackage Administration
*/
class PapayaAdministrationThemeEditorChangesSetChange
  extends PapayaUiControlCommandDialogDatabaseRecord {

  /**
   * Create dialog and add fields for the dynamic values defined by the current theme values page
   *
   * @see PapayaUiControlCommandDialog::createDialog()
   * @return PapayaUiDialog
   */
  public function createDialog() {
    $setId = $this->parameters()->get('set_id', 0);
    $dialogCaption = 'Add theme set';
    $buttonCaption = 'Add';
    if ($setId > 0) {
      if ($this->record()->load($setId)) {
        $dialogCaption = 'Change theme set';
        $buttonCaption = 'Save';
      } else {
        $setId = 0;
      }
    }
    $dialog = new PapayaUiDialogDatabaseSave($this->record());
    $dialog->papaya($this->papaya());
    $dialog->parameterGroup($this->parameterGroup());
    $dialog->parameters($this->parameters());
    $dialog->hiddenFields()->merge(
      array(
        'cmd' => 'set_edit',
        'theme' => $this->parameters()->get('theme', ''),
        'set_id' => $setId
      )
    );
    $dialog->caption = new PapayaUiStringTranslated($dialogCaption);
    $dialog->fields[] = $field = new PapayaUiDialogFieldInput(
      new PapayaUiStringTranslated('Title'), 'title', 200, '', new PapayaFilterText()
    );
    $field->setMandatory(TRUE);
    $dialog->buttons[] = new PapayaUiDialogButtonSubmit(
      new PapayaUiStringTranslated($buttonCaption)
    );
    $this->callbacks()->onExecuteSuccessful = array($this, 'callbackSaveValues');
    $this->callbacks()->onExecuteFailed = array($this, 'callbackShowError');
    return $dialog;
  }

  /**
   * Save data from dialog
   */
  public function callbackSaveValues() {
    $this->papaya()->messages->dispatch(
      new PapayaMessageDisplayTranslated(
        PapayaMessage::SEVERITY_INFO,
        'Theme set saved.'
      )
    );
  }

  /**
   * Save data from dialog
   *
   * @param object $context
   * @param PapayaUiDialog $dialog
   */
  public function callbackShowError($context, $dialog) {
    $this->papaya()->messages->dispatch(
      new PapayaMessageDisplayTranslated(
        PapayaMessage::SEVERITY_ERROR,
        'Invalid input. Please check the field(s) "%s".',
        array(implode(', ', $dialog->errors()->getSourceCaptions()))
      )
    );
  }
}
