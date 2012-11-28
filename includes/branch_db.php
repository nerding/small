<?php

  error_reporting(E_ALL);
  ini_set('display_errors','On');

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
      echo $queryString;

      if ($stmt = $this->db->prepare($queryString)) {
        $stmt->execute();
        $out = $stmt->get_result();
        $stmt->close();

        return $out;
      }

      echo "DEATH TO ALL";
      return false;
    }

  }

?>