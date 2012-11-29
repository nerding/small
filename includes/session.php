<?php
  session_start();
  
  class Session {
    public static function start() {
      if (session_id() == '') {
        return false;
      }

      $fingerprint = Config::get('site.salt') . $_SERVER['HTTP_USER_AGENT'] . session_id();
      $_SESSION['fingerprint'] = md5($fingerprint);

      return true;
    }

    public static function end() {
      //unset($this->sessions[$_SESSION['fingerprint']]);
      unset($_SESSION['fingerprint']);
      unset($_SESSION['username']);
      unset($_SESSION['user_id']);
      unset($_SESSION['name']);
      session_destroy();
    }

    public static function set($key, $value) {
      if (!self::hasSession()) {
        return false;
      }

      $_SESSION[$key] = $value;
    }

    public static function get($key) {
      if (!self::hasSession()) {
        return false;
      }

      return $_SESSION[$key];
    }

    public static function hasSession() {
      if (session_id() == '' || !isset($_SESSION['fingerprint'])) {
        return false;
      }

      $fingerprint = Config::get('site.salt') . $_SERVER['HTTP_USER_AGENT'] . session_id();

      return $_SESSION['fingerprint'] == md5($fingerprint);
    }
  }
?>