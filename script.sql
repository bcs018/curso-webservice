create database devstagram
default char set utf8
default collate utf8_general_ci;

use devstagram;

create table users(
	id int not null auto_increment,
    name varchar(50) not null,
    email varchar(100) not null,
	pass varchar(100) not null,
    avatar varchar(100),
    
    primary key (id)
 )default character set utf8;
 
 create table photos(
	id int not null auto_increment,
    id_user int not null,
    url varchar(100),
    
    primary key (id),
    foreign key (id_user) references users (id)
 )default character set utf8;
 
 create table photos_likes(
	id int not null auto_increment,
	id_user int not null,
    id_photo int not null,
    
    primary key (id),
    foreign key (id_user) references users (id),
    foreign key (id_photo) references photos (id)
 )default character set utf8;

create table photos_comments(
	id int not null auto_increment,
    id_user int not null,
    id_photo int not null,
    date_comment datetime not null,
    txt text,
    
    primary key (id),
    foreign key (id_user) references users (id),
    foreign key (id_photo) references photos (id)
)default character set utf8;

create table users_following(
	id int not null auto_increment,
	id_user_active int not null,
    id_user_passive int not null,
    
    primary key (id)
)default character set utf8;

insert into users (name, email, pass)
values ('Bruno', 'bcs018@hotmail.com', md5('1234'));

select * from users;



