create database dbase;

create user 'username'@'localhost' identified by 'password';
grant all privileges on dbase to username@localhost;

flush privileges;

use dbase;

create table users (
  id int auto_increment primary key,
  username varchar(128) not null,
  email varchar(50) not null,
  userinfo varchar(1024)
);
