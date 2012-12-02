<?php

  require_once('markdown/markdown.php');
  date_default_timezone_set('UTC');

  class Post {

    // return ALL posts as DBPosts.
    public static function all() {
      return self::find();
    }

    // return all PUBLISHED posts as DBPosts.
    public static function published() {
      return self::find('where published=true');
    }

    public static function find_by_id($id) {
      return self::find("where id=$id")[0];
    }

    /*
      Takes in 0 or 1 params: a where clause (including the "where" part).
    */
    private static function find() {
      $num_args = func_get_args();

      $query = 'select id,title,published,pub_date,contents,author_id from posts';
      $query .= count($num_args) == 1 ? ' ' . func_get_arg(0) : '';
      $query .= ' order by pub_date;';

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
      $stmt->close();

      return $out;
    }

    /*
      Create a new post:

      Post::create($title, $contents, $author_id [, $published [, $pub_date]])

      Params:
        $title: string: name of post
        $contents: blob/text: the post's contents
        $author_id: int: id in users table of author
        $published: boolean: is is published
        $pub_date: long: time in seconds using unix epoch.
    */
    public static function create($title, $content, $author_id) {
      $num_args = func_get_args();

      $published = true;
      $pub_date = date('Y-m-d H:i:s');

      if (count($num_args) >= 4) {
        if (func_get_arg(3) == true  || func_get_arg(3) == false) {
          $published = func_get_arg(3);
        }
      }
      if (count($num_args) == 5) {
        $pub_date = date('Y-m-d H:i:s', func_get_args(4));
      }

      $pub_date = new DateTime($pub_date);

      // create the file
      $file = self::createFile(
        $title, 
        $content, 
        User::find_by_id($author_id),
        $published,
        $pub_date
      );

      $query = 'insert into posts (title, published, pub_date, contents, author_id)';
      $query .= " values (\"$title\", $published, \"";
      $query .= $pub_date->format('Y-m-d H:i:00');
      $query .= "\", \"$file\", $author_id);";

      BranchDB::query($query);

      $findQuery = "select id from posts where title = \"$title\" and contents = \"$file\";";
      $stmt = BranchDB::queryStmt($findQuery);
      $stmt->bind_result($id);
      $stmt->fetch();
      $stmt->close();

      return $id;
    }

    private static function createFile($title, $contents, $user, $published, $pub_date) {
      $filedir = $pub_date->format('Y/m/d/');
      $filename = $filedir . $pub_date->format('H-i-');
      $filename .= preg_replace('/[^\w]/', '-', strtolower($title));
      $filename .= '.md';


      // generate yaml
      $yaml = "---\ntitle: $title\npublished: $published\npub_date: ";
      $yaml .= $pub_date->format('Y-m-d H:i');
      $yaml .= "\nauthor: " . $user->username . "\n---";

      $contents = $yaml . "\n\n" . $contents;

      if (!is_dir('./contents/posts/' . $filedir)) {
        mkdir('./contents/posts/' . $filedir, 0777, true);
      }

      $file = fopen('contents/posts/' . $filename, 'c+');
      fwrite($file, $contents);
      fclose($file);
      chmod('./contents/posts/' . $filename, 0777);

      return $filename;
    }

    public static function delete($id) {
      $findQuery = "select contents from posts where id=$id;";
      $findStmt = BranchDB::queryStmt($findQuery);
      $findStmt->bind_result($filename);
      $findStmt->fetch();

      unlink("contents/posts/$filename");
      $findStmt->close();

      $query = "delete from posts where id=$id;";
      //echo $query;
      BranchDB::query($query);
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
      $yaml = "---" . $break[1];

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