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
  yr number,
  Season varchar2(3),
  Deadline date,
  primary key(yr, season)
);

prompt ---;
prompt --Drop Course;
drop table Course cascade constraints;
prompt --Create Course;
create table Course(
  Course_ID number primary key,
  Max_Seats number,
  C_Num varchar2(8),
  Title varchar2(32),
  Credits number,
  Start_Time varchar2(5),
  End_Time varchar2(5),
  yr number,
  Season varchar2(3),
  foreign key(yr, season) references Semester(yr, season)
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
  Grade number,
  primary key(Course_ID, Student_ID),
  foreign key(course_id) references Course,
  foreign key(student_id) references Student
);


prompt -------;
prompt Inserting into Tables;

insert into Users (UserID, password, isadmin) values ('george', '1234', 1);
insert into Users (UserID, password, isadmin) values ('fred', '1234', 0);
insert into Users (UserID, password, isadmin) values ('greg', '1234', 0);

insert into Student (Student_ID, UserID, Fname, Lname, Age, Street_Address, City, State, Zip_Code, Student_Type, On_Probation) 
	values ('12345', 'george', 'George', 'Dosher', 21, '1234 Broadway Ext', 'Edmond', 'OK', '73003', 'undergrad', 0);
insert into Student (Student_ID, UserID, Fname, Lname, Age, Street_Address, City, State, Zip_Code, Student_Type, On_Probation) 
	values ('54321', 'fred', 'Fred', 'Flintstone', 23, '4321 Broadway Ext', 'Edmond', 'OK', '73003', 'undergrad', 0);
	
insert into Semester(yr, Season, Deadline) values ('2018', 'Fal', TO_DATE('09/01/2018', 'mm/dd/yyyy'));
insert into Semester(yr, Season, Deadline) values ('2019', 'Spr', TO_DATE('1/01/2019', 'mm/dd/yyyy'));	

	
insert into Course (Course_ID, Max_Seats, C_Num, Title, credits, Start_Time, End_Time, yr, Season) 
	values ('1000', 20, 'CMSC1053', 'Computer Technology', 3, '09:00', '10:00', 2018, 'Fal');
insert into Course (Course_ID, Max_Seats, C_Num, Title, credits, Start_Time, End_Time, yr, Season) 
	values ('2000',  25, 'CMSC1513', 'Beginning Programming', 3, '11:00', '13:00', 2018, 'Fal');
insert into Course (Course_ID, Max_Seats, C_Num, Title, credits, Start_Time, End_Time, yr, Season) 
	values ('3000',  25, 'CMSC1513', 'Programming I', 3, '11:00', '13:00', 2018, 'Fal');
insert into Course (Course_ID, Max_Seats, C_Num, Title, credits, Start_Time, End_Time, yr, Season) 
	values ('4000', 15, 'CMSC4173', 'Translator Design', 3, '18:00', '19:00', 2019, 'Spr');

insert into Prereq(post_courseid, pre_courseid) values ('3000', '2000');
insert into Prereq(post_courseid, pre_courseid) values ('4000', '2000');
insert into Prereq(post_courseid, pre_courseid) values ('4000', '3000');
	
insert into Enrollment (Course_id, student_id, grade) values ('1000', '12345', -1);
insert into Enrollment (Course_id, student_id, grade) values ('2000', '12345', 4);
insert into Enrollment (Course_id, student_id, grade) values ('3000', '12345', 2);
	

prompt ---------------------;
prompt Heres all the tables;
select table_name from user_tables;

commit;

prompt ;
prompt ;
prompt ;
prompt ;








