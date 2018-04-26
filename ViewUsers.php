<?

include "UtilityFunctions.php";

$SessionId =$_GET["SessionId"];
verify_session($SessionId);
verify_admin($SessionId);

$PrevURL = $_POST["PrevURL"];
$CurURL = $_SERVER['REQUEST_URI'];
$sql = "select * from Users";


function displayAll(&$displaystring){
	echo("<h2> All Users:</h2>");
	$sql = "select * from Users";
	$count = oci_fetch_array (execute_sql_in_oracle ("SELECT Count(*) FROM Users")["cursor"])[0];
	oci_free_statement($cursor);
	return statement_to_table($sql, $count, array("Name", "UserId", "User Type", "Password"));
}

function displaySearch(&$displaystring){
	echo("<h2> Search User:</h2>");
	$SearchId = $_POST['SearchId'];
	$sql = "select * from Users where UserId = '$SearchId'";
	$count = oci_fetch_array (execute_sql_in_oracle ("SELECT Count(*) FROM Users")["cursor"])[0];
	oci_free_statement($cursor);
	return statement_to_table($sql, $count, array("Name", "UserId", "User Type", "Password"));
}

function deleteRecord(&$displaystring){
	echo("<h2> Search User:</h2>");
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

function updateInfo(){
	$UserId = $_POST['UserId'];
	$updateId = $_POST['updateUserId'];
	$updateName = $_POST['updateName'];
	$updateType = $_POST['updateUserType'];
	$sql = "update Users set ";
	if($updateId){
		$sql .= " UserId = '$updateId'";
	}
	if($updateName){
		if($updateId){
			$sql .= ", ";
		}
		$sql .= " Name = '$updateName'";
	}
	if($updateName){
		if($updateId || $updateName){
			$sql .= ", ";
		}
		$sql .= " Type = '$updateType'";
	}
	$sql .= " where UserId = '$UserId'";
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

$buttonString = "<h2>View All or Search Users:</h2>" .
	"<FORM name=\"displayAll\" method=\"post\" action=\"ViewUsers.php?SessionId=$SessionId\"> " .	
	"<input type=\"submit\" class=\"button\" name=\"displayAll\" value=\"View All Users\" />" .
	"</FORM>" .
	
	"<br />" .

	
	"<FORM name=\"displaySearch\" method=\"post\" action=\"ViewUsers.php?SessionId=$SessionId\"> " .
	"User Id:  <INPUT type=\"text\" name=\"SearchId\" size=\"8\" maxlength=\"8\"> <br />" .
	"<input type=\"submit\" class=\"button\" name=\"displaySearch\" value=\"Search User\" />" .
	"</FORM>" .
	
	"<br />" .
	
	"<h3>Update User Info:</h3>" .
	
	"<FORM name=\"updateInfo\" method=\"post\" action=\"ViewUsers.php?SessionId=$SessionId\"> " .
	"Enter User Id to update:<br />" .
	"User Id:  <INPUT type=\"text\" name=\"UserId\" size=\"8\" maxlength=\"8\"> <br />" .
	"<br />Enter details:<br />" .
	"UserId:  <INPUT type=\"text\" name=\"updateUserId\" size=\"8\" maxlength=\"8\"> <br />" .
	"Name:  <INPUT type=\"text\" name=\"updateName\" size=\"8\" maxlength=\"8\"> <br />" .
	"User Type:  <INPUT type=\"text\" name=\"updateUserType\" size=\"8\" maxlength=\"8\"> <br />" .
	"<INPUT type=\"hidden\" name=\"SessionId\" value=$SessionId>" .
	"<INPUT type=\"submit\" name=\"updateInfo\" value=\"Update Info\" style=\"height:25px; width:150px\"> " .
	"</FORM>" .

	"<br />" . 
	
	"<h3>Add User Info:</h3>" .
	
	"<FORM name=\"addInfo\" method=\"post\" action=\"ViewUsers.php?SessionId=$SessionId\"> " .
	"Enter record to add:<br />" .
	"<br />Enter details:<br />" .
	"UserId:  <INPUT type=\"text\" name=\"addUserId\" size=\"8\" maxlength=\"8\"> <br />" .
	"Name:  <INPUT type=\"text\" name=\"addName\" size=\"8\" maxlength=\"8\"> <br />" .
	"User Type:  <INPUT type=\"text\" name=\"addUserType\" size=\"8\" maxlength=\"8\"> <br />" .
	"<INPUT type=\"hidden\" name=\"SessionId\" value=$SessionId>" .
	"<INPUT type=\"submit\" name=\"addInfo\" value=\"Add Info\" style=\"height:25px; width:150px\"> " .
	"</FORM>" .

	"<br />" . 
	
	"<h3>Delete Record:</h3>" .
	
	"<FORM name=\"deleteRecord\" method=\"post\" action=\"ViewUsers.php?SessionId=$SessionId\"> " .
	"User Id:  <INPUT type=\"text\" name=\"SearchId\" size=\"8\" maxlength=\"8\"> <br />" .
	"<input type=\"submit\" class=\"button\" name=\"deleteRecord\" value=\"Delete User\" />" .
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

	"<FORM name=\"Back\" method=\"post\" action=\"http://www.comsc.uco.edu/~gq011/UserManagement.php?SessionId=$SessionId&UserId=$UserId\"> " . 
	  "<input type=\"hidden\" name=\"SessionId\" value=$SessionId> ".
	  "<INPUT type=\"submit\" name=\"Back\" value=Back> ".
	  "</FORM>";

echo($headerString);

if(isset($_POST['displayAll'])){
	echo(displayAll($displaystring));
}
elseif(isset($_POST['displaySearch'])){
	echo(displaySearch($displaystring));
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
