<?
include "UtilityFunctions.php";

function custome_printtable($sql, $labels) {
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
		$displayString .= "<tr>";
		$iterator = 0;
		foreach($values as $element){
			$iterator++;
			if($iterator == 11) {
				if($element == 0) {
					$element = 'no';
				} else if($element == 1) {
					$element = 'yes';
				}
			}
			$displayString .= "<td>$element</td>"; 	
		}
		$displayString .= "</tr>";
	}
		
	$displayString .= "<table>";
	$displayString .= "<br />";
	$displayString .= "<br />";
	
	oci_free_statement($cursor);
	return $displayString;
}


//Verify the user session
$SessionId =$_GET["SessionId"];
$UserId =$_GET["UserId"];

verify_session($SessionId);

    
//the previous url, used in the back button
$PrevURL = "http://www.comsc.uco.edu/~" . get_sql_username() . "/Welcome.php?SessionId=" . $SessionId . "&UserId=" . $UserId;
$CurURL = "http://www.comsc.uco.edu/~" . get_sql_username() . "/StudentManagement.php?SessionId=" . $SessionId . "&UserId=" . $UserId;

// Here we can generate the content of the welcome page
echo("<h2>Student Management:</h2>");

echo ("<h3>Student Information</h3>");

$sql_student_info = "select * from student where userid='$UserId'"; 

$return_array = execute_sql_in_oracle($sql_student_info);
$cursor = $result_array["cursor"];

$sql_stud_info = "select * from student where userid = '$UserId'";

echo custome_printtable($sql_stud_info, array('StudentID', 'Username', 'FirstName', 'Last Name', 'Age', 'Street Address', 'City', 'State', 'Zip Code', 'Student Type', 'On Probation'));

echo "----<br>";

echo("<FORM name=\"SectionSearch\" method=\"post\" action=\"SectionSearch.php?SessionId=$SessionId\"> 
	  <INPUT type=\"hidden\" name=\"UserId\" value=$UserId>
	  <INPUT type=\"submit\" name=\"SectionSearch\" value=\"Enroll in a section\" style=\"height:25px; width:150px\"> 
	  </FORM>");


echo("<br />");
echo("<br />");

echo("<FORM name=\"Back\" method=\"post\" action=$PrevURL> 
	  <INPUT type=\"hidden\" name=\"SessionId\" value=$SessionId>
	  <INPUT type=\"hidden\" name=\"UserId\" value=$UserId>
	  <INPUT type=\"submit\" name=\"Back\" value=\"Back to welcome page\"> 
	  </FORM>");

echo("<br />");
echo("<br />");
echo("<br />");

echo("<FORM name=\"Logout\" method=\"post\" action=\"LogoutAction.php?SessionId=$SessionId\"> 
	  <INPUT type=\"hidden\" name=\"SessionId\" value=$SessionId>
	  <INPUT type=\"submit\" name=\"Logout\" value=Logout> 
	  </FORM>");
?>
