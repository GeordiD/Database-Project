prompt ------------------------------;
prompt --Creating new schema;
prompt --Project 1 Schema;
prompt ------------------------------;
prompt ;

set verify off;

define type_student = "student";
define type_admin = "admin";
define type_student_admin = "s_admin";

prompt Dropping Tables;
prompt ---------------------;
prompt Dropping Table: DDoffers;
drop table DDoffers cascade constraints;

prompt ---------------------;
prompt Dropping Table: DegreeProgram;
drop table DegreeProgram cascade constraints;

prompt ---------------------;
prompt Dropping Table: Student;
drop table Student cascade constraints;

prompt ---------------------;
prompt Dropping Table: Department;
drop table Department cascade constraints;

prompt ---------------------;
prompt Dropping Table: Faculty;
drop table Faculty cascade constraints;

prompt ---------------------;
prompt Dropping Table: CourseDescription;
drop table CourseDescription cascade constraints;

prompt ---------------------;
prompt Dropping Table: CourseOffering;
drop table CourseOffering cascade constraints;

prompt ---------------------;
prompt Dropping Table: Taken;
drop table Taken cascade constraints;

prompt ---------------------;
prompt Dropping Table: Users;
drop table Users cascade constraints;

prompt ---------------------;
prompt Dropping Table: UserSession;
drop table UserSession cascade constraints;

prompt ---------------------;
prompt Creating Table: Users;
create table Users (
  UserId varchar2(5) primary key,
  Name varchar2(30),
  Password varchar2(12),
  Type varchar2(12)
);

prompt ---------------------;
prompt Creating Table: UserSession;
create table UserSession (
  SessionId varchar2(32) primary key,
  UserId varchar2(5),
  SessionDate date,
  SessionType varchar2(12),
  foreign key (UserId) references Users
);

prompt ---------------------;
prompt Creating Table: Department;
create table Department(
	DeptId varchar2(5) primary key,
	DeptName varchar2(20),
	DeptAddress varchar2(20),
	Chair varchar2(9)
);

prompt ---------------------;
prompt Creating Table: Faculty;
create table Faculty(
	FacName varchar2(20),
	FacSSNo varchar2(9) primary key,
	OfficeAddress varchar2(20),
	Worksfor varchar2(5),
	Foreign key (Worksfor) references Department(DeptId)
);

prompt ---------------------;
prompt Add foreign key to Department table;
alter table Department	
	add foreign key (Chair) references Faculty(FacSSNo);

prompt ---------------------;
prompt Creating Table: DegreeProgram;
create table DegreeProgram(
	ProgId varchar2(4) primary key,
	ProgramName Varchar2(40),
	ProgType varchar2(10),
	Coordinator varchar2(9),
	UnivReq varchar2(20),
	CollReq varchar2(20),
	Foreign key (Coordinator) references Faculty(FacSSNo)
);

prompt ---------------------;
prompt Creating Table: Student;
create table Student(
	Sid varchar2(5) primary key,
	SSNo varchar2(9) unique,
	Sname varchar2(20),
	CurAddress varchar2(20),
	Major varchar2(4),
	StuLevel number(1),
	Gpa number(2,1),
	Foreign key (Major) references DegreeProgram(ProgId)
);

prompt ---------------------;
prompt Creating Table: DDoffers;
create table DDoffers(
	DeptId varchar2(5),
	ProgId varchar2(4),
	DeptReqrmnt varchar2(20),
	Primary key (DeptId, ProgId),
	Foreign key (DeptId) references Department(DeptId),
	Foreign key (ProgId) references DegreeProgram(ProgId)
);
	
prompt ---------------------;
prompt Creating Table: CourseDescription;
create table CourseDescription(
	Cno varchar2(10) primary key,
	Title varchar2(100),
	Credits number(1),
	Description varchar2(100),
	Offers varchar2(5),
	foreign key (Offers) references Department(DeptId)
);

prompt ---------------------;
prompt Creating Table: CourseOffering;
create table CourseOffering(
	SeqID varchar2(5) primary key,
	SectionNo varchar2(2),
	Cno varchar2(10),
	Semester varchar2(10),
	Year number(4),
	Instructor varchar2(9),
	foreign key (Cno) references CourseDescription(Cno),
	foreign key (Instructor) references Faculty(FacSSNo)
);

prompt ---------------------;
prompt Creating Table: Taken;
create table Taken(
	Sid varchar2(5),
	SeqID varchar2(5),
	Grade number(2,1),
	primary key(Sid, SeqID),
	foreign key (Sid) references Student(sid),
	foreign key (SeqID) references CourseOffering(SeqID)
);
	
prompt ---------------------;
prompt Inserting into Faculty;
insert into faculty (facname, facssno, officeaddress) values ('Peter', '014575454', 'Babbage Tower');
insert into faculty (facname, facssno, officeaddress) values ('JoAnn', '735623954', 'Edison Bldg');
insert into faculty (facname, facssno, officeaddress) values ('Malcolm', '032562344', 'Babbage Tower');
insert into faculty (facname, facssno, officeaddress) values ('Cathy', '056707794', 'Mack Hall');
insert into faculty (facname, facssno, officeaddress) values ('Robbins', '331405635', 'Babbage Tower');
insert into faculty (facname, facssno, officeaddress) values ('Ramon', '349045050', 'Edison Bldg');

prompt ---------------------;
prompt Inserting into Department;
insert into department (deptid, deptname, deptaddress, chair) values ('CS', 'Computer Science', 'OKC', '014575454');
insert into department (deptid, deptname, deptaddress, chair) values ('EE', 'Electrical', 'Tulsa', '349045050');
insert into department (deptid, deptname, deptaddress, chair) values ('ME', 'Mechanical', 'OKC', '056707794');

prompt ---------------------;
prompt Updating faculty worksfor;
update faculty set worksfor='CS' where facssno='014575454';
update faculty set worksfor='EE' where facssno='735623954';
update faculty set worksfor='CS' where facssno='032562344';
update faculty set worksfor='ME' where facssno='056707794';
update faculty set worksfor='CS' where facssno='331405635';
update faculty set worksfor='EE' where facssno='349045050';

prompt ---------------------;
prompt Inserting into DegreeProgram;
insert into degreeprogram (progid, programname, progtype, coordinator) values ('P000', 'Computer Science', 'BS', '014575454');
insert into degreeprogram (progid, programname, progtype, coordinator) values ('P001', 'Computer Science', 'PhD', '032562344'); 
insert into degreeprogram (progid, programname, progtype, coordinator) values ('P010', 'Electrical Engineering', 'BS', '735623954');
insert into degreeprogram (progid, programname, progtype, coordinator) values ('P014', 'Electrical Engineering', 'PhD', '349045050');
insert into degreeprogram (progid, programname, progtype, coordinator) values ('P200', 'Mechanical Engineering', 'BS', '056707794'); 
insert into degreeprogram (progid, programname, progtype, coordinator) values ('P050', 'Computer Science ' || chr(38) || ' Engineering', 'BS', '014575454');

prompt ---------------------;
prompt Inserting into Student;
insert into student (sid, ssno, sname, curaddress, major, stulevel) values ('A1111', '044505555', 'Greg', 'OKC', 'P000', 3);
insert into student (sid, ssno, sname, curaddress, major, stulevel) values ('A2344', '044505777', 'John', 'OKC', 'P000', 3);
insert into student (sid, ssno, sname, curaddress, major, stulevel) values ('A1234', '455706547', 'David', 'Lawton', 'P000', 4);
insert into student (sid, ssno, sname, curaddress, major, stulevel) values ('A3245', '322695645', 'Randy', 'OKC', 'P010', 2);
insert into student (sid, ssno, sname, curaddress, major, stulevel) values ('A4344', '304455799', 'Cindy', 'Tulsa', 'P200', 3);
insert into student (sid, ssno, sname, curaddress, major, stulevel) values ('A5466', '405676777', 'Susan', 'OKC', 'P000', 1);
insert into student (sid, ssno, sname, curaddress, major, stulevel) values ('A4567', '657894555', 'Walid', 'OKC', 'P001', 6);      
insert into student (sid, ssno, sname, curaddress, major, stulevel) values ('A3523', '334594792', 'Jinhua', 'OKC', 'P001', 6);

prompt ---------------------;
prompt Inserting into Users;
insert into Users (UserId, Name, Password, type) values ('A1111', 'Greg', '1234', '&type_student_admin');
insert into Users (UserId, Name, Password, type) values ('A2344', 'John', '1234', '&type_admin');
insert into Users (UserId, Name, Password, type) values ('A1234', 'David', '1234', '&type_student');
insert into Users (UserId, Name, Password, type) values ('A3245', 'Randy', '1234', '&type_student');
insert into Users (UserId, Name, Password, type) values ('A4344', 'Cindy', '1234', '&type_student');
insert into Users (UserId, Name, Password, type) values ('A5466', 'Susan', '1234', '&type_student');
insert into Users (UserId, Name, Password, type) values ('A4567', 'Walid', '1234', '&type_student');
insert into Users (UserId, Name, Password, type) values ('A3523', 'Jinhua', '1234', '&type_student');

prompt ---------------------;
prompt Inserting into DDoffers;
insert into ddoffers values ('CS', 'P000', 'CS180');
insert into ddoffers values ('CS', 'P050', 'CS480');
insert into ddoffers values ('EE', 'P050', 'EE100');
insert into ddoffers values ('EE', 'P010', 'EE100');
insert into ddoffers values ('ME', 'P200', 'ME100');
insert into ddoffers values ('CS', 'P001', '');     
insert into ddoffers values ('EE', 'P014', '');   
  
prompt ---------------------;
prompt Inserting into CourseDescription;
insert into coursedescription (cno, title, credits, offers) values ('CS480', 'Database systems', 4, 'CS');
insert into coursedescription (cno, title, credits, offers) values ('CS880', 'Advanced DBMS', 3, 'CS');
insert into coursedescription (cno, title, credits, offers) values ('ME310', 'Thermodynamics', 1, 'ME');
insert into coursedescription (cno, title, credits, offers) values ('EE210', 'Digital Logic', 2, 'EE');
insert into coursedescription (cno, title, credits, offers) values ('CS331', 'Algorithms', 3, 'CS');
insert into coursedescription (cno, title, credits, offers) values ('CS410', 'Operating Systems', 4, 'CS');

prompt ---------------------;
prompt Inserting into CourseOffering;
insert into courseoffering values ('00001', '01', 'CS480', 'Fall', 2005, '014575454');
insert into courseoffering values ('00002', '02', 'CS480', 'Fall', 2005, '014575454');
insert into courseoffering values ('00103', '01', 'CS880', 'Fall', 2005, '056707794');
insert into courseoffering values ('02002', '01', 'CS480', 'Spring', 2006, '032562344');
insert into courseoffering values ('51205', '01', 'EE210', 'Spring', 2006, '735623954');
insert into courseoffering values ('00328', '01', 'CS331', 'Fall', 2004, '056707794');
insert into courseoffering values ('00255', '01', 'CS331', 'Fall', 2003, '056707794');

prompt ---------------------;
prompt Inserting into Taken;
insert into taken values ('A2344', '00001', 4.0);
insert into taken values ('A1234', '00001', 1.0);
insert into taken values ('A3245', '00001', 4.0);
insert into taken values ('A4344', '00001', 4.0);
insert into taken values ('A5466', '00002', 4.0);
insert into taken values ('A4567', '00103', 4.0);
insert into taken values ('A2344', '51205', 4.0);
insert into taken values ('A1234', '51205', 2.0);
insert into taken values ('A2344', '00328', 4.0);
insert into taken values ('A1234', '00255', 2.0);
insert into taken values ('A3245', '00328', 3.5);
insert into taken values ('A4344', '00328', 4.0);

prompt ---------------------;
prompt Commiting Schema;
commit;
prompt ;
prompt ;


prompt ---------------------;
prompt Heres all the tables;
select table_name from user_tables;


prompt ;
prompt ;
prompt ;
prompt ;









