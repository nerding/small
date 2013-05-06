<?php
  /*  The Post Objects
        The Post object is used, like all other pseudo-ORM objects to
        retrieve its DB cousin, which is the thing that can really be
        played with.

        Considering that posts are what it's all about, this is pretty
        important.
  */


  // Required for using markdown, which is what posts are supposed to
  // be in. Supposedly. I mean, someone could just write pure HTML if
  // they wanted to.
  require_once(__dir__ . '/markdown/markdown.php');
  
  class Post {

    // return ALL posts as DBPosts. This is just an alias for find()
    // without any parameters. But, aliases are nice...
    public static function all() {
      return self::find();
    }

    // return all PUBLISHED posts as DBPosts.
    public static function published() {
      return self::find('where published=true');
    }

    // search for a post by its id
    public static function find_by_id($id) {
      //return self::find("where id=$id")[0];
      $in = self::find("where id=$id");
      return $in[0];
    }


    // Queries posts. If you pass in a string with a where clause,
    // including the "where" part, it'll do that. You could do more
    // than just a where, but there probably isn't much use,
    // considering that this produces DBPost Objects.
    public static function find() {
      $num_args = func_get_args();

      $query = 'select id,title,published,pub_date,contents,author_id';
      $query .= ' from posts ';
      $query .= count($num_args) == 1 ? ' ' . func_get_arg(0) : '';
      $query .= ' order by pub_date;';

      $stmt = SmallDB::queryStmt($query);

      $out = array();
      $stmt->bind_result(
        $id, $title, $published, $pub_date, $post_file, $auth_id);

      while ($stmt->fetch()) {
        array_push(
          $out, 
          new DBPost($id, $title, $published, $pub_date, $post_file, $auth_id)
        );
  
      }
      $stmt->close();

      return $out;
    }

    /*
      Create a new post:
      Post::create($title, $contents, $author_id [, $published [, $pub_date]])

      Returns the post's ID when added to the database.

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

      // if $published is passed in (boolean)
      if (count($num_args) >= 4) {
        if (func_get_arg(3) == true  || func_get_arg(3) == false) {
          $published = func_get_arg(3);
        }
      }

      // if $pub_date is passed in (timestamp)
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

      // the insert query
      $query = 'insert into posts ';
      $query .= '(title, published, pub_date, contents, author_id)';
      $query .= " values (\"$title\", $published, \"";
      $query .= $pub_date->format('Y-m-d H:i:00');
      $query .= "\", \"$file\", $author_id);";
      SmallDB::query($query);

      // the get the ID query. So that we can return it.
      $findQuery = 'select id from posts ';
      $findQuery .= "where title = \"$title\" and contents = \"$file\";";
      $stmt = SmallDB::queryStmt($findQuery);
      $stmt->bind_result($id);
      $stmt->fetch();
      $stmt->close();

      return $id;
    }

    // create the post's file. It *should* have a copy of the post's metadata
    // because eventually there'll be an import from directory function,
    // which'll use the information in the file. Other than that, the metadata
    // isn't used by Small.
    //
    // Returns the newly created file's path
    private static function createFile($title, $contents, $user, $published, $pub_date) {
      $filedir = $pub_date->format('Y/m/d/');
      $filename = $filedir . $pub_date->format('H-i-');
      $filename .= preg_replace('/[^\w]/', '-', strtolower($title));
      $filename .= '.md';


      // generate yaml
      $yaml = "---\ntitle: $title\npublished: $published\npub_date: ";
      $yaml .= $pub_date->format('Y-m-d H:i');
      $yaml .= "\nauthor: " . $user->email . "\n---";

      $contents = $yaml . "\n\n" . $contents;

      if (!is_dir('./content/posts/' . $filedir))
        if (!mkdir('./content/posts/' . $filedir, 0777, true))
          die ("So... we couldn't make the post's file...");
      

      $file = fopen('content/posts/' . $filename, 'c+');
      fwrite($file, $contents);
      fclose($file);
      chmod('./content/posts/' . $filename, 0777);

      return $filename;
    }

    // so you don't want a file anymore, huh?
    public static function delete($id) {
      // get the file's path
      $findQuery = "select contents from posts where id=$id;";
      $findStmt = SmallDB::queryStmt($findQuery);
      $findStmt->bind_result($filename);
      $findStmt->fetch();

      // and delete it
      unlink("content/posts/$filename");
      $findStmt->close();

      // now get rid of the database entry
      $query = "delete from posts where id=$id;";
      SmallDB::query($query);
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

      return Markdown($this->markdown());
    }

    public function markdown() {
      if ($this->post_file == null) {
        return false;
      }

      $filename = './content/posts/' . $this->post_file;

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

      return $cont;
    }
  }

?>
