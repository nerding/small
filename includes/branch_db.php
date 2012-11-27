<?php

  class BranchDB { 
    private $db;
    private $config;

    public function BranchDB($config) {
      $nArgs = func_get_args();

      $this->config = $config->getConfig();

      /*if ($nArgs == 1) {
        $dbname = func_get_args(0);
      }*/

      $this->db = new mysqli(
        $this->config['db_host'],
        $this->config['db_user'],
        $this->config['db_password'],
        $this->config['db_database']
      );
    }

    public function query($queryString) {
      return $this->db->query($queryString);
    }

  }

?>