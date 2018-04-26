<?
include "UtilityFunctions.php";

$UserId =$_POST["UserId"];
$SessionId =$_GET["SessionId"];

verify_session($SessionId);

$PrevURL = $_POST["PrevURL"];
$CurURL = $_SERVER['REQUEST_URI'];
$sql = "select Name, UserId, UserType, Password from Users";


function sectionsBySemester(&$displaystring){
	echo("<h2> Sections by semester:</h2>");
	$sql = "select * from Users";
	$sql = 	"select * from Course";
	$count = oci_fetch_array (execute_sql_in_oracle ("SELECT Count(*) FROM Users")["cursor"])[0];
	oci_free_statement($cursor);
	return statement_to_table($sql, $count, array("UserId", "Name", "Password", "User Type"));
}

function sectionsByPartialId(&$displaystring){
	echo("<h2> Search Sections:</h2>");
	$SearchId = $_POST['SearchId'];
	$sql = "select * from Course where Course_ID = '$SearchId'";
	$count = oci_fetch_array (execute_sql_in_oracle ("SELECT Count(*) FROM Users")["cursor"])[0];
	oci_free_statement($cursor);
	return statement_to_table($sql, $count, array("Course_ID", "Dept", "Max seats", "C_Num", "Title", "start", "end"));
}

function deleteRecord(&$displaystring){
	echo("<h2> Delete User:</h2>");
	$SearchId = $_POST['SearchId'];
	$sql = "delete from Users where UserId = '$SearchId'";
	$result_array = execute_sql_in_oracle ($sql);
	$result = $result_array["flag"];

	if ($result == false){
		display_oracle_error_message($cursor);
		die("Failed to delete record");
	}
	else{
		echo("Record deleted successfully");
		echo("<br />");
	}
}

function addInfo(){
	$addId = $_POST['addUserId'];
	$addName = $_POST['addName'];
	$addType = $_POST['addUserType'];
	$password = "default";
	$sql = "insert into Users (UserId, Name, Password, Type) ";
	$sql .= "values ('$addId', '$addName', '$password', '$addType')";
	$result_array = execute_sql_in_oracle ($sql);
	$result = $result_array["flag"];

	if ($result == false){
		display_oracle_error_message($cursor);
		die("Failed to update record");
	}
	else{
		echo("Record updated successfully");
		echo("<br />");
	}
}



$buttonString = 
	"<h2>View All or Search Sections:</h2>" .
	
	"<FORM name=\"allSections\" method=\"post\" action=\"SectionSearch.php?SessionId=$SessionId\"> " .	
		"Year:  <INPUT type=\"text\" name=\"SearchId\" size=\"8\" maxlength=\"8\"> <br />" .
		"Season:  <INPUT type=\"text\" name=\"SearchId\" size=\"8\" maxlength=\"8\"> <br />" .
		"<input type=\"submit\" class=\"button\" name=\"allSections\" value=\"View All Sections\" />" .
	"</FORM>" .
	
	"<FORM name=\"sectionsBySemester\" method=\"post\" action=\"SectionSearch.php?SessionId=$SessionId\"> " .	
		"Year:  <INPUT type=\"text\" name=\"SearchId\" size=\"8\" maxlength=\"8\"> <br />" .
		"Season:  <INPUT type=\"text\" name=\"SearchId\" size=\"8\" maxlength=\"8\"> <br />" .
		"<input type=\"submit\" class=\"button\" name=\"sectionsBySemester\" value=\"Search Sections by Semester\" />" .
	"</FORM>" .
	
	"<br />" .

	
	"<FORM name=\"sectionsByPartialId\" method=\"post\" action=\"SectionSearch.php?SessionId=$SessionId\"> " .
		"Section Id:  <INPUT type=\"text\" name=\"SearchId\" size=\"8\" maxlength=\"8\"> <br />" .
		"<input type=\"submit\" class=\"button\" name=\"sectionsByPartialId\" value=\"Search Section By Id\" />" .
	"</FORM>" .
	
	"<br />" .
	
	"<h3>Drop Section:</h3>" .
	
	"<FORM name=\"deleteRecord\" method=\"post\" action=\"ViewUsers.php?SessionId=$SessionId\"> " .
		"Section Id:  <INPUT type=\"text\" name=\"SearchId\" size=\"8\" maxlength=\"8\"> <br />" .
		"<input type=\"submit\" class=\"button\" name=\"deleteRecord\" value=\"Drop Section\" />" .
	"</FORM>" .
	
	"<br />" .
	
	"<h3>Reset User Password:</h3>" .
	
	"<FORM name=\"ResetPassword\" method=\"post\" action=\"ResetPassword.php?SessionId=$SessionId\"> " .
		"User Id:  <INPUT type=\"text\" name=\"UserId\" size=\"8\" maxlength=\"8\"> <br />" .
		"<INPUT type=\"hidden\" name=\"PrevURL\" value=$CurURL>" .
		"<INPUT type=\"hidden\" name=\"SessionId\" value=$SessionId>" .
		"<INPUT type=\"submit\" name=\"ResetPassword\" value=\"Reset Password\" style=\"height:25px; width:150px\"> " .
	"</FORM>" .

	"<br />" . 
	"<br />" . 
	"<br />" . 

	"<FORM name=\"Back\" method=\"post\" action=\"StudentManagement.php?SessionId=$SessionId&UserId=$UserId\"> " . 
		"<INPUT type=\"hidden\" name=\"SessionId\" value=$SessionId> ".
		"<INPUT type=\"hidden\" name=\"UserId\" value=$UserId> ".
		"<INPUT type=\"submit\" name=\"Back\" value=Back> ".
	"</FORM>" .
	  
	"<FORM name=\"Logout\" method=\"post\" action=\"LogoutAction.php?SessionId=$SessionId\"> " .
		"<INPUT type=\"hidden\" name=\"SessionId\" value=$SessionId>".
		"<INPUT type=\"submit\" name=\"Logout\" value=Logout> ".
	"</FORM>";

echo($headerString);

if(isset($_POST['displayAll'])){
	echo(displayAll($displaystring));
}
elseif(isset($_POST['sectionsByPartialId'])){
	echo(sectionsByPartialId($displaystring));
}
elseif(isset($_POST['updateInfo'])){
	updateInfo();
}
elseif(isset($_POST['addInfo'])){
	addInfo();
}
elseif(isset($_POST['deleteRecord'])){
	deleteRecord();
}

echo($buttonString);
?>