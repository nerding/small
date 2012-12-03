<?php

  error_reporting(E_ALL);
  ini_set('display_errors','On');

  require_once('Spyc.php');

  class Config {
    private static $config;

    public static function init() {
      self::$config = Spyc::YAMLLoad("config.yml");
    }

    public static function getConfig() {
      return self::$config;
    }

    public static function get($key) {
      if (strpos($key, '.')) {
        $exp = explode('.', $key);
        
        $out = self::$config[$exp[0]];

        for ($i = 1; $i < count($exp); $i++) {
          $out = $out[$exp[$i]];
        }
      }
      else {
        $out = self::$config[$key];
      }

      return $out;
    }
  }

  Config::init();

?>