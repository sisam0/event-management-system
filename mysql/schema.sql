use event_db;

create table service(
service_id int primary key auto_increment,
ser_name varchar(200)
);

insert into service(ser_name)
values ("catering"),
("hall"),
("packages");

create table hall(
hall_id int primary key auto_increment,
hall_name varchar(50),
seat_capacity int,
space_type varchar(20),
without_f varchar(10),
dj varchar(10) ,
pvt_room int,
description text
);

create table photos(
pic_id int primary key auto_increment,
photo text,
hall_id int,
foreign key (hall_id) references hall(hall_id)
);

create table user(
user_id int primary key auto_increment,
email varchar(30) unique,
password varchar(255) not null,
fname varchar(30),
lname varchar(30),
open_email varchar(30),
pic text
);

create table menu(
food_id int primary key auto_increment,
service_id int,
name varchar(255),
rate int,
type varchar(255),
foreign key(service_id) references service(service_id)
);

create table booking(
booking_id int primary key auto_increment,
user_id int,
date date,
guest_count int,
status varchar(255),
message text,
total int,
foreign key(user_id) references user(user_id)
);

create table booking_service(
bookingSer_id int primary key auto_increment,
booking_id int,
service_id int,
price int,
date date,
foreign key(booking_id) references booking(booking_id),
foreign key(service_id) references service(service_id)
);

create table admin(
admin_id int primary key auto_increment,
photo text,
email varchar(255),
password varchar(255)
);

create table unavailable(
unavailable_id int primary key auto_increment,
service_id int,
date date,
foreign key(service_id) references service(service_id)
);

create table packages(
package_id int primary key auto_increment,
service_id int,
hall_id int,
name varchar(200),
description text,
price int,
foreign key(service_id) references service(service_id),
foreign key(hall_id) references hall(hall_id)
);

CREATE TABLE package_details (
    detail_id INT AUTO_INCREMENT PRIMARY KEY,
    package_id INT NOT NULL,
	service_id int not null,
    detail_text VARCHAR(255) NOT NULL,
    FOREIGN KEY (package_id) REFERENCES packages(package_id) ON DELETE CASCADE
    FOREIGN KEY (service_id) REFERENCES service(service_id) ON DELETE CASCADE
);


CREATE TABLE food_types (
    type_id INT AUTO_INCREMENT PRIMARY KEY,
    type_name VARCHAR(50) NOT NULL UNIQUE 
);

CREATE TABLE package_food_types (
	package_food_id int primary key auto_increment,
    package_id INT NOT NULL,
    type_id INT NOT NULL,
    FOREIGN KEY (package_id) REFERENCES packages(package_id),
    FOREIGN KEY (type_id) REFERENCES food_types(type_id)
);
