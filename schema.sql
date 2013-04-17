create table users (
  id integer primary key auto_increment,
  email varchar(255) unique not null,
  password varchar(255) not null,
  name varchar(255),
  biography text
);

create table categories (
  id integer primary key auto_increment,
  name varchar(255) not null
);

create table posts (
  id integer primary key auto_increment,
  title varchar(255) not null,
  published boolean not null,
  pub_date datetime not null,
  contents varchar(255) not null, 
  author_id integer not null,
  category_id integer not null,
  foreign key(author_id) references users(id),
  foreign key(category_id) references categories(id)
);

create table pages (
  id integer primary key auto_increment,
  title varchar(255) not null,
  published boolean not null,
  contents varchar(255) not null
);

create table attachments (
  id integer primary key auto_increment,
  name varchar(255) unique not null,
  file varchar(255) unique not null
);
