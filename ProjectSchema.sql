prompt ------------------------------;
prompt --Creating new schema;
prompt --Project 1 Schema;
prompt ------------------------------;
prompt ;

set verify off;

prompt ------;
prompt --Drop Users;
drop table Users;
prompt --Create Users;
create table Users (
  UserID varchar2(20) primary key,
  Password varchar2(12),
  isAdmin number
);

prompt ----;
prompt --Drop UserSession
drop table UserSession;
prompt --Create UserSession;
create table UserSession (
  SessionId varchar2(32) primary key,
  UserId varchar(20),
  SessionDate date,
  SessionType varchar2(12),
  foreign key (UserId) references Users
);

prompt -------;
prompt Inserting into Tables;

insert into Users (UserID, password, isadmin) values ('george', '1234', 1);
insert into Users (UserID, password, isadmin) values ('fred', '1234', 0);


prompt ---------------------;
prompt Heres all the tables;
select table_name from user_tables;


prompt ;
prompt ;
prompt ;
prompt ;








