# The Database

You can find an sql dump of my working database in the `./database` directory.

The database has five entity tables:

- Users
- Posts
- Tags
- Pages
- Attachments

And one join table:

- PostHasTag

## Users

```sql
create table users (
  id integer primary key autoincrement,
  username varchar(255) unique not null,
  name varchar(255),
  email varchar(255) not null,
  biography text
);
```

## Posts

```sql
create table posts (
  id integer primay key autoincrement,
  title varchar(255) not null,
  published boolean not null,
  timestamp datetime not null,
  contents varchar(255) not null,
  author_id integer not null,
  foreign key(author_id) references users(id)
);
```

## Tags

```sql
create table tags (
  id integer primary key autoincrement,
  name varchar(255) not null
);
```

## Pages

```sql
create table pages (
  id integer primary key autoincrement,
  title varchar(255) not null,
  published boolean not null,
  contents varchar(255) not null
);
```

## Attachments

```sql
create table attachments (
  id integer primary key autoincrement,
  name varchar(255) unique not null,
  file varchar(255) unique not null
);
```

## PostHasTag

```sql
create table postHasTag (
  id integer primary key autoincrement,
  post_id integer not null,
  tag_id integer not null,
  foreign key(post_id) references posts(id),
  foreign key(tag_id) references tags(id)
);
```

