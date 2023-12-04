<?php

class psAdminThemePluginConfiguration extends sfPluginConfiguration
{
  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    if (!sfConfig::get('sf_bootstrap_admin_module_web_dir'))
    {
      sfConfig::set('sf_bootstrap_admin_module_web_dir', '/psAdminThemePlugin');
    }
    
    if (!sfConfig::get('sf_bootstrap_admin_module_responsive'))
    {
      sfConfig::set('sf_bootstrap_admin_module_responsive', false);
    }
  }
}