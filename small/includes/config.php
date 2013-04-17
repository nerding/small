<?php
  /*  The Config Object
        The config object holds the site's configuration data, as taken from
        [site root]/config.yml.php.

        If you put it in the config file, you can access it using
          Config::get([variable])
        You can even use list.item format if you want. It should work to any
        depth you happen to use. I can't be certain though.

        As for why this is abstracted, i) I'm a generic programmer, I like
        abstractions, ii) I would rather use Confg::get(x) than have to
        include Spyc in each file I want data from, and iii) why not?

        Everything in Config is static, because it's nicer to call Config::
        than Config->, and these things *shouldn't* change over the course of
        their live (which is admittedly, really short, because PHP, but that's
        another discussion).
  */

  // used to read the yaml
  require_once(__dir__ . '/Spyc.php');


  class Config {
    private static $config;

    // setup our nice Config object. Which happens down below...
    public static function init() {
      $configContents = file_get_contents(__dir__ . '/../../config.yml.php');
      $contentsArr = explode("<?php\n", $configContents);
      $rawYaml = $contentsArr[1];
      self::$config = Spyc::YAMLLoad($rawYaml);
    }

    // if you ever just want the hash that's used as the config,
    // this is it.
    public static function getConfig() {
      return self::$config;
    }

    // most used function in Config. Used to get an item from
    // the site's configuration.
    //
    // It'll also let you use . for children, because it's a nice
    // function. Yes it is, yes it is...
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

  // call init, because we want it initalized whenever it's going to be
  // used. It's a simple fact of the Config object. This also means that
  // we don't have to worry about if it's been placed in memory yet before
  // using Config::get().
  //
  // The reason we don't just have a constructor is because it doesn't
  // get called when you use a static method (because, go figure, static
  // methods are meant to be the same all the way through). So, we need this.
  Config::init();

?>