<?php

class PapayaPluginFilterContentRecords extends PapayaPluginFilterContentGroup {

  private $_viewConfigurations = NULL;
  private $_loaded = FALSE;

  public function records(PapayaContentViewConfigurations $configurations = NULL) {
    if (isset($configurations)) {
      $this->_viewConfigurations = $configurations;
    } elseif (NULL == $this->_viewConfigurations) {
      $this->_viewConfigurations = new PapayaContentViewConfigurations();
      $this->_viewConfigurations->activateLazyLoad(
        array(
          'id' => $this->getPage()->getPageViewId(),
          'type' => 'datafilter'
        )
      );
    }
    return $this->_viewConfigurations;
  }

  public function getIterator() {
    if (!$this->_loaded) {
      foreach ($this->records() as $record) {
        $plugin = $this->papaya()->plugins->get(
          $record['module_guid'], $this->getPage(), $record['options']
        );
        if ($plugin) {
          $this->add($plugin);
        }
      }
      $this->_loaded = TRUE;
    }
    return parent::getIterator();
  }
}
