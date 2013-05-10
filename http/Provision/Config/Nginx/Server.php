<?php

/**
 * Nginx server level configuration file class.
 */
class Provision_Config_Nginx_Server extends Provision_Config_Http_Server {
  function process() {
    parent::process();
    $this->data['extra_config'] = "# Extra configuration from modules:\n";
    $this->data['extra_config'] .= join("\n", drush_command_invoke_all('provision_nginx_server_config', $this->data));
  }
}

/**
 * Nginx specific config includes.
 */
class Provision_Config_Nginx_Includes extends Provision_Config_Http_Server {
  public $template = 'vhost_include.tpl.php';
  public $description = 'Nginx web server configuration include file';

  function write() {
    parent::write();

    if (isset($this->data['application_name'])) {
      $file = $this->data['application_name'] . '_vhost_common.conf';
      $legacy_simple_file = $this->data['application_name'] . '_simple_include.conf';
      $legacy_advanced_file = $this->data['application_name'] . '_advanced_include.conf';
      // We link both legacy files on the remote server to the right version.
      $cmda = sprintf('ln -sf %s %s',
        escapeshellarg($this->data['server']->include_path . '/' . $file),
        escapeshellarg($this->data['server']->include_path . '/' . $legacy_simple_file)
      );
      $cmdb = sprintf('ln -sf %s %s',
        escapeshellarg($this->data['server']->include_path . '/' . $file),
        escapeshellarg($this->data['server']->include_path . '/' . $legacy_advanced_file)
      );
      if ($this->data['server']->shell_exec($cmda)) {
        drush_log(dt("Created legacy_simple_file symlink for %file on %server", array(
          '%file' => $file,
          '%server' => $this->data['server']->remote_host,
        )));
      };
      if ($this->data['server']->shell_exec($cmdb)) {
        drush_log(dt("Created legacy_advanced_file symlink for %file on %server", array(
          '%file' => $file,
          '%server' => $this->data['server']->remote_host,
        )));
      };
    }
  }

  function filename() {
    if (isset($this->data['application_name'])) {
      $file = $this->data['application_name'] . '_vhost_common.conf';
      return $this->data['server']->include_path . '/' . $file
    }
    else {
      return FALSE;
    }
  }
}
