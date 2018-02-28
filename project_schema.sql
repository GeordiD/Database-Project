drop table Sessions cascade constraints;
drop table Users cascade constraints;

create table Users(
	Username varchar2(15) primary key,
	Password varchar2(15)
);

create table Student_Users(
	Username varchar2(15) primary key,
	foreign key (Username) references Users
);

create table Admin_Users(
	Username varchar2(15) primary key,
	foreign key (Username) references Users
);

create table Project_Sessions(
	Session_id varchar2(32) primary key,
	Username varchar2(15),
	sessiondate date,
	foreign key (Username) references Users
);

