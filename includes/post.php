<?php

  require_once('markdown/markdown.php');

  class Post {

    public static function all() {
      return self::find();
    }

    public static function find_all_published() {

    }

    private static function find() {
      $num_args = func_get_args();

      $query = 'select id,title,published,pub_date,contents,author_id from posts';
      $query .= $num_args == 1 ? ' ' . func_get_arg(0) : '';
      $query .= ';';

      $stmt = BranchDB::queryStmt($query);

      $out = array();
      $stmt->bind_result($id, $title, $published, $pub_date, $post_file, $auth_id);

      while ($stmt->fetch()) {
        array_push(
          $out, 
          new DBPost(
            $id, 
            $title, 
            $published, 
            $pub_date, 
            $post_file, 
            $auth_id
          )
        );
  
      }

      return $out;
    }
  }

  class DBPost {
    public $id;
    public $title;
    public $published;
    public $pub_date;
    public $post_file;
    public $author_id;
    public $author;


    public function DBPost() {
      $num_args = func_get_args();

      if ($num_args >= 1) {
        $this->id = func_get_arg(0);
      }
      if ($num_args >= 2) {
        $this->title = func_get_arg(1);
      }
      if ($num_args >= 3) {
        $this->published = func_get_arg(2);
      }
      if ($num_args >= 4) {
        $this->pub_date = func_get_arg(3);
      }
      if ($num_args >= 5) {
        $this->post_file = func_get_arg(4);
      }
      if ($num_args == 6) {
        $this->author_id = func_get_arg(5);
        $this->author = User::find_by_id($this->author_id);
      }
    }

    public function contents() {
      if ($this->post_file == null) {
        return false;
      }

      $filename = './contents/posts/' . $this->post_file;

      $file = fopen($filename, 'r');
      $cont = fread($file, filesize($filename));
      fclose($file);

      // parse out yaml
      $break = explode("---", $cont);
      $yaml = $break[1];

      $cont = '';
      for ($i = 2; $i < count($break); $i++) {
        $cont .= $break[$i];
        if ($i != count($break) - 1) {
          $cont .= '---';
        }
      }

      return Markdown($cont);
    }
  }

?>