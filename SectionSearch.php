<?
include "UtilityFunctions.php";

$UserId =$_GET["UserId"];
$SessionId =$_GET["SessionId"];

verify_session($SessionId);

$PrevURL = $_POST["PrevURL"];
$CurURL = $_SERVER['REQUEST_URI'];
$sql = "select Name, UserId, UserType, Password from Users";

function allSections(&$displaystring){
	echo("<h2> All Sections:</h2>");
	echo("<h3> Enroll:</h3>");
	echo("<FORM name=\"enroll\" method=\"post\" > " .
		"Course Id:  <INPUT type=\"text\" name=\"enrollId\" size=\"8\" maxlength=\"8\"> " .
		"<input type=\"submit\" class=\"button\" name=\"enroll\" value=\"Enroll\" />" .
	"</FORM>");
	
	$sql = 	"select * from Course c1 join Semester s1 on c1.yr=s1.yr and c1.season=s1.season";
	$result_array = execute_sql_in_oracle ($sql);
	$result = $result_array["flag"];
	$cursor = $result_array["cursor"];
	
	if ($result == false){
		display_oracle_error_message($cursor);
		die("SQL Execution problem.");
	}
	
	if ($cursor == false) {
		display_oracle_error_message($connection);
		oci_close ($connection);
		// sql failed 
		die("SQL Parsing Failed");
	}	
	
	$displayString = "<table border=\"1\">";	
	$displayString .= "<tr>";
	foreach($labels as $label){
		$displayString .= "<col width=\"130\">";
	}
	foreach($labels as $label){
		$displayString .= "<td><b>$label</b></td>";
	}
	$displayString .= "</tr>";

	while($values = oci_fetch_assoc ($cursor)){
		$index = 0;
		$displayString .= "<tr>";
		$courseId; 
		foreach($values as $element){
			if($index == 0){
				$courseId = $element;
			}
			$displayString .= "<td>$element</td>"; 	
			if($index == 1){
				$studentId = getStudentID($UserId);
			
				/*$query = execute_sql_in_oracle(
					"select Max_Seats from Course where Course_ID = '$element[0]'");
				$rows = oci_fetch_array($query["cursor"]);
				$maxSeats = $rows[0];*/
				
				$query = execute_sql_in_oracle(
					"select count(*) from enrollment where Course_ID = '$courseId'");
				$rows = oci_fetch_array($query["cursor"]);
				$seatsTaken = $rows[0];
				echo("The Number of seats taken are: $seatsTaken  ");
				
				$availableSeats = $element - $seatsTaken;

				$displayString .= "<td>$availableSeats</td>";
			}
			$index++;
		}
		$displayString .= "</tr>";
	}
		
	$displayString .= "<table>";
	$displayString .= "<br />";
	$displayString .= "<br />";
	oci_free_statement($cursor);
	return $displayString;
}


function sectionsBySemester(&$displaystring){
	echo("<h2> Sections by semester:</h2>");
	echo("<h3> Enroll:</h3>");
	echo("<FORM name=\"enroll\" method=\"post\" action=\"SectionSearch.php?SessionId=$SessionId&UserId=$UserId\"> " .
		"Course Id:  <INPUT type=\"text\" name=\"enrollId\" size=\"8\" maxlength=\"8\"> " .
		"<input type=\"submit\" class=\"button\" name=\"enroll\" value=\"Enroll\" />" .
	"</FORM>");
	$searchYear = $_POST["SearchYear"];
	$searchSeason = $_POST["SearchSeason"];
	$sql = 	"select * from Course c1 join Semester s1 on c1.yr=s1.yr and c1.season=s1.season " .
			"where c1.yr=$searchYear and c1.season='$searchSeason'";
	$count = oci_fetch_array (execute_sql_in_oracle ("SELECT Count(*) FROM Course")["cursor"])[0];
	oci_free_statement($cursor);
	return statement_to_table($sql, $count, array("Course_ID", "Max seats", "C_Num", "Title", "Credits", "Start Time", "End Time", "Year", "Season", "Deadline"));
}

function sectionsByPartialId(&$displaystring){
	echo("<h2> Search Sections:</h2>");
	echo("<h3> Enroll:</h3>");
	echo("<FORM name=\"enroll\" method=\"post\" action=\"SectionSearch.php?SessionId=$SessionId&UserId=$UserId\"> " .
		"Course Id:  <INPUT type=\"text\" name=\"enrollId\" size=\"8\" maxlength=\"8\"> " .
		"<input type=\"submit\" class=\"button\" name=\"enroll\" value=\"Enroll\" />" .
	"</FORM>");
	$SearchId = $_POST['SearchId'];
	$sql = "select * from Course where C_Num = '%$SearchId%'";
	$count = oci_fetch_array (execute_sql_in_oracle ("SELECT Count(*) FROM Users")["cursor"])[0];
	oci_free_statement($cursor);
	return statement_to_table($sql, $count, array("Course_ID", "Max seats", "C_Num", "Title", "Credits", "Start Time", "End Time", "Year", "Season", "Deadline"));
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

function enroll(){
	$enrollId = $_POST['enrollId'];
	$UserId =$_GET["UserId"];
	$studentId = getStudentID($UserId);
	
	$query = execute_sql_in_oracle(
		"select Max_Seats from Course where Course_ID = '$enrollId'");
	$values = oci_fetch_array($query["cursor"]);
	$maxSeats = $values[0];
	
	$query = execute_sql_in_oracle(
		"select count(*) from enrollment where Course_ID = '$enrollId'");
	$values = oci_fetch_array($query["cursor"]);
	$seatsTaken = $values[0];
	
	$availableSeats = $maxSeats - $seatsTaken;
	
	
	echo("Available seats: $availableSeats");
	
	if($availableSeats > 0){
		$sql = "insert into enrollment (Course_ID, Student_ID, Grade) ";
		$sql .= "values ('$enrollId', '$studentId', -1)";
		$result_array = execute_sql_in_oracle ($sql);
		$result = $result_array["flag"];
		if ($result == false){
			display_oracle_error_message($cursor);
			echo("Record update failed");
			die("Failed to enroll in class");
		}
		else{
			echo("Record updated successfully");
			echo("<br />");
		}
	}
	else{
		echo("The class is full");
	}


}


$studentId = getStudentID($UserId);
$buttonString = 
	"<h2>$SessionId:</h2>" .
	"<h2>$UserId:</h2>" .
	"<h2>$studentId:</h2>" .
	
	"<br />" .
	"<br />" .
	"<h2>View All or Search Sections:</h2>" .
	
	"<FORM name=\"allSections\" method=\"post\" action=\"SectionSearch.php?SessionId=$SessionId&UserId=$UserId\"> " .	
		"<input type=\"submit\" class=\"button\" name=\"allSections\" value=\"View All Sections\" />" .
	"</FORM>" .
	
	"<br />" .
	"<br />" .
	
	"<FORM name=\"sectionsBySemester\" method=\"post\" action=\"SectionSearch.php?SessionId=$SessionId&UserId=$UserId\"> " .	
		"Year:  <INPUT type=\"text\" name=\"SearchYear\" size=\"8\" maxlength=\"8\"> <br />" .
		"Season:  <INPUT type=\"text\" name=\"SearchSeason\" size=\"8\" maxlength=\"8\"> <br />" .
		"<input type=\"submit\" class=\"button\" name=\"sectionsBySemester\" value=\"Search Sections by Semester\" />" .
	"</FORM>" .
	
	"<br />" .
	"<br />" .

	
	"<FORM name=\"sectionsByPartialId\" method=\"post\" action=\"SectionSearch.php?SessionId=$SessionId&UserId=$UserId\"> " .
		"Section Id:  <INPUT type=\"text\" name=\"SearchId\" size=\"8\" maxlength=\"8\"> <br />" .
		"<input type=\"submit\" class=\"button\" name=\"sectionsByPartialId\" value=\"Search Section By Id\" />" .
	"</FORM>" .
	

	"<br />" .
	"<br />" .
	
	"<h3>Drop Section:</h3>" .
	
	"<FORM name=\"deleteRecord\" method=\"post\" action=\"SectionSearch.php?SessionId=$SessionId&UserId=$UserId\"> " .
		"Section Id:  <INPUT type=\"text\" name=\"SearchId\" size=\"8\" maxlength=\"8\"> <br />" .
		"<input type=\"submit\" class=\"button\" name=\"deleteRecord\" value=\"Drop Section\" />" .
	"</FORM>" .
	
	"<br />" .
	"<br />" .
	
	"<h3>Reset User Password:</h3>" .
	
	"<FORM name=\"ResetPassword\" method=\"post\" action=\"SectionSearch.php?SessionId=$SessionId&UserId=$UserId\"> " .
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
	
	"<br />" .
	"<br />" .
	  
	"<FORM name=\"Logout\" method=\"post\" action=\"LogoutAction.php?SessionId=$SessionId\"> " .
		"<INPUT type=\"hidden\" name=\"SessionId\" value=$SessionId>".
		"<INPUT type=\"submit\" name=\"Logout\" value=Logout> ".
	"</FORM>";

echo($headerString);

if(isset($_POST['allSections'])){
	echo(allSections($displaystring));
}
elseif(isset($_POST['sectionsBySemester'])){
	echo(sectionsBySemester($displaystring));
}
elseif(isset($_POST['sectionsByPartialId'])){
	echo(sectionsByPartialId($displaystring));
}
elseif(isset($_POST['enroll'])){
	enroll();
}
elseif(isset($_POST['deleteRecord'])){
	deleteRecord();
}

echo($buttonString);
?>