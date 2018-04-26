<?
include "UtilityFunctions.php";

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

$return_array = execute_sql_in_oracle("select * from student where userid='$UserId'");
$cursor = $result_array["cursor"];

while($row = oci_fetch_array($cursor)) {
  oci_free_statement($cursor);
  echo "student = " . $row[0] . "<br>";
}

echo "----";

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
