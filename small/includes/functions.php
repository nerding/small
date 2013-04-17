<?php
/*
  This is a collection of miscellaneous functions for use all over small
  sites.

  If you didn't figure it out yet, there aren't that many that we need. For
  the most part, there's just the randString thing for creating a nounce.
  Technically it could just go in the ajax file, but you never know, it might
  be useful somewhere else...
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