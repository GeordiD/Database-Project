prompt ------------------------------;
prompt --Creating new schema;
prompt --Project 1 Schema;
prompt ------------------------------;
prompt ;

set verify off;

prompt ------;
prompt --Drop Users;
drop table Users cascade constraints;
prompt --Create Users;
create table Users (
  UserID varchar2(20) primary key,
  Password varchar2(12),
  isAdmin number
);

prompt ----;
prompt --Drop UserSession
drop table UserSession cascade constraints;
prompt --Create UserSession;
create table UserSession (
  SessionId varchar2(32) primary key,
  UserId varchar2(20),
  SessionDate date,
  SessionType varchar2(12),
  foreign key (UserId) references Users
  on delete cascade
);

prompt ----;
prompt --Drop Student;
drop table Student cascade constraints;
prompt --Create Student;
create table Student(
  Student_ID number primary key,
  UserID varchar2(20),
  Fname varchar2(32),
  LName varchar2(32),
  Age number,
  Street_Address varchar2(64),
  City varchar2(32),
  State varchar2(2),
  Zip_Code varchar2(5),
  Student_Type varchar2(10),
  On_Probation number,
  foreign key(UserID) references Users
  on delete cascade
);

prompt ----;
prompt --Drop Semester;
drop table Semester cascade constraints;
prompt --Create Semester;
create table Semester(
  Year number,
  Season varchar2(3),
  Deadline date,
  primary key(year, season)
);

prompt ---;
prompt --Drop Course;
drop table Course cascade constraints;
prompt --Create Course;
create table Course(
  Course_ID number primary key,
  Dept_ID varchar2(4),
  Max_Seats number,
  C_Num number,
  Title varchar2(32),
  Start_Time varchar2(5),
  End_Time varchar2(5)
);

prompt ---;
prompt --Drop Course;
drop table Prereq cascade constraints;
prompt --Create Prereq
create table Prereq(
  post_courseid number,
  pre_courseid number,
  primary key(post_courseid, pre_courseid),
  foreign key(post_courseid) references Course,
  foreign key(pre_courseid) references Course
);

prompt --Drop Enrollment;
drop table Enrollment cascade constraints;
prompt --Create Enrollment;
create table Enrollment (
  Course_ID number,
  Student_ID number,
  Season varchar2(3),
  Year number,
  Grade number,
  primary key(Course_ID, Student_ID),
  foreign key(course_id) references Course,
  foreign key(student_id) references Student,
  foreign key(season,year) references Semester(season, year)
);


prompt -------;
prompt Inserting into Tables;

insert into Users (UserID, password, isadmin) values ('george', '1234', 1);
insert into Users (UserID, password, isadmin) values ('fred', '1234', 0);
insert into Users (UserID, password, isadmin) values ('greg', '1234', 0);

insert into Student (Student_ID, UserID, Fname, Lname, Age, Street_Address, City, State, Zip_Code, Student_Type, On_Probation) 
	values ('12345', 'george', 'George', 'Dosher', 21, '1234 Broadway Ext', 'Edmond', 'OK', '73003', 'undergrad', 0);

insert into Course (Course_ID, Dept_ID, Max_Seats, C_Num, Title, Start_Time, End_Time) 
	values ('0001', 'CMSC', 20, 1053, 'Computer Technology', '09:00', '10:00');
insert into Course (Course_ID, Dept_ID, Max_Seats, C_Num, Title, Start_Time, End_Time) 
	values ('0002', 'CMSC', 25, 1513, 'Beginning Programming', '11:00', '13:00');
insert into Course (Course_ID, Dept_ID, Max_Seats, C_Num, Title, Start_Time, End_Time) 
	values ('0003', 'CMSC', 15, 4173, 'Translator Design', '18:00', '19:00');

prompt ---------------------;
prompt Heres all the tables;
select table_name from user_tables;

commit;

prompt ;
prompt ;
prompt ;
prompt ;








