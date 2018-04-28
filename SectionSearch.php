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
	$labels = array("Course_ID", "Max seats", 'Seats Available', "C_Num", "Title", "Credits", "Start Time", "End Time", "Year", "Season", "Deadline");
	$displayString = "<table border=\"1\">";	
	$displayString .= "<tr>";
	foreach($labels as $label){
		$displayString .= "<col width=\"130\">";
	}
	foreach($labels as $label){
		$displayString .= "<td><b>$label</b></td>";
	}
	$displayString .= "</tr>";
	
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
				$query = execute_sql_in_oracle(
					"select count(*) from enrollment where Course_ID = '$courseId'");
				$rows = oci_fetch_array($query["cursor"]);
				$seatsTaken = $rows[0];
				
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
	$searchYear = $_POST["SearchYear"];
	$searchSeason = $_POST["SearchSeason"];
	echo("<h2> All Sections:</h2>");
	echo("<h3> Enroll:</h3>");
	echo("<FORM name=\"enroll\" method=\"post\" > " .
		"Course Id:  <INPUT type=\"text\" name=\"enrollId\" size=\"8\" maxlength=\"8\"> " .
		"<input type=\"submit\" class=\"button\" name=\"enroll\" value=\"Enroll\" />" .
	"</FORM>");
	
	$sql = 	"select * from Course c1 join Semester s1 on c1.yr=s1.yr and c1.season=s1.season" .
			" where c1.yr=$searchYear and c1.season='$searchSeason'";
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
	$labels = array("Course_ID", "Max seats", 'Seats Available', "C_Num", "Title", "Credits", "Start Time", "End Time", "Year", "Season", "Deadline");
	$displayString = "<table border=\"1\">";	
	$displayString .= "<tr>";
	foreach($labels as $label){
		$displayString .= "<col width=\"130\">";
	}
	foreach($labels as $label){
		$displayString .= "<td><b>$label</b></td>";
	}
	$displayString .= "</tr>";
	
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
				$query = execute_sql_in_oracle(
					"select count(*) from enrollment where Course_ID = '$courseId'");
				$rows = oci_fetch_array($query["cursor"]);
				$seatsTaken = $rows[0];
				
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

function sectionsByPartialId(&$displaystring){
	$SearchId = $_POST['SearchId'];
	
	echo("<h2> All Sections:</h2>");
	echo("<h3> Enroll:</h3>");
	echo("<FORM name=\"enroll\" method=\"post\" > " .
		"Course Id:  <INPUT type=\"text\" name=\"enrollId\" size=\"8\" maxlength=\"8\"> " .
		"<input type=\"submit\" class=\"button\" name=\"enroll\" value=\"Enroll\" />" .
	"</FORM>");
	
	$sql = 	"select * from Course where '$SearchId' like C_num";
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
	$labels = array("Course_ID", "Max seats", 'Seats Available', "C_Num", "Title", "Credits", "Start Time", "End Time", "Year", "Season", "Deadline");
	$displayString = "<table border=\"1\">";	
	$displayString .= "<tr>";
	foreach($labels as $label){
		$displayString .= "<col width=\"130\">";
	}
	foreach($labels as $label){
		$displayString .= "<td><b>$label</b></td>";
	}
	$displayString .= "</tr>";
	
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
				$query = execute_sql_in_oracle(
					"select count(*) from enrollment where Course_ID = '$courseId'");
				$rows = oci_fetch_array($query["cursor"]);
				$seatsTaken = $rows[0];
				
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
	
	$seatsAvailable = checkAvailableSeats($enrollId);
	$courseNeeded = checkCourseNeeded($enrollId, $studentId);
	$hasPrereqs = checkPrereq($enrollId, $studentId);
	
	if($seatsAvailable == false){
		echo("The class is full");
		return;
	}
	
	if($courseNeeded == false){
		echo("You dont need to take this class");
		return;
	}
	
	if($hasPrereqs == false){
		echo("You dont have the prerequisite courses");
		return;
	}
		
	$sql = "insert into enrollment (Course_ID, Student_ID, Grade) ";
	$sql .= "values ('$enrollId', '$studentId', -1)";
	$result_array = execute_sql_in_oracle ($sql);
	$result = $result_array["flag"];
	echo("Successfully enrolled in course");
}

function checkAvailableSeats($enrollId){
	$query = execute_sql_in_oracle(
		"select Max_Seats from Course where Course_ID = '$enrollId'");
	$values = oci_fetch_array($query["cursor"]);
	$maxSeats = $values[0];
	
	$query = execute_sql_in_oracle(
		"select count(*) from enrollment where Course_ID = '$enrollId'");
	$values = oci_fetch_array($query["cursor"]);
	$seatsTaken = $values[0];
	
	$availableSeats = $maxSeats - $seatsTaken;
	if($availableSeats > 0){
		return true;
	}
	else{
		return false;
	}
}

function checkCourseNeeded($enrollId, $studentId){
	$query = execute_sql_in_oracle(
		"select Course_id from Enrollment 
		where Course_id = '$enrollId' and student_id = '$studentId'");
	$course = oci_fetch_array($query["cursor"]);
	if($course[0]){
		
		return false;
	}
	else{
		return true;
	}
}

function checkPrereq($enrollId, $studentId){
	$query = execute_sql_in_oracle(
		"select pre_courseid from Prereq where post_courseid = '$enrollId'");
	$cursor = $query["cursor"];
	$test = 0;
	while($values = oci_fetch_assoc ($cursor)){
		foreach($values as $element){		
			$query2 = execute_sql_in_oracle(
				"select Course_id from Enrollment where student_id = '$studentId' and grade > 0");
			$cursor2 = $query2["cursor"];
			$found = false;
			while($values2 = oci_fetch_assoc ($cursor2)){
				foreach($values2 as $element2){
					if($element == $element2){
						$found = true;
					}
				}
			}
			if($found == false){
				return false;
			}
		}
	}
	return true;
}


$studentId = getStudentID($UserId);
$buttonString = 
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