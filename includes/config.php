<?php

  error_reporting(E_ALL);
  ini_set('display_errors','On');

  require_once('Spyc.php');

  class Config {
    private $config;

    public function Config() {
      $this->config = Spyc::YAMLLoad("config.yml");
    }

    public function getConfig() {
      return $this->config;
    }

  }

?>