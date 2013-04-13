<?php
/*
  This is a collection of miscellaneous functions for use all over branch
  sites.
*/

  function randString($length, $charset='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789`~!@#$%^&*()_+-=/?|;:') {
      $str = '';
      $count = strlen($charset);
      while ($length--) {
          $str .= $charset[mt_rand(0, $count-1)];
      }
      return $str;
  }


?>