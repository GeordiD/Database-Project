<?
include "UtilityFunctions.php";

//Verify the user session
$SessionId =$_GET["SessionId"];
$UserId =$_GET["UserId"];
verify_session($SessionId);
verify_admin($SessionId);

//the previous url, used in the back button
$PrevURL = "http://www.comsc.uco.edu/~gq011/Welcome.php?SessionId=" . $SessionId . "&UserId=" . $UserId;
$CurURL = "http://www.comsc.uco.edu/~gq011/UserManagement.php?SessionId=" . $SessionId . "&UserId=" . $UserId;

// Here we can generate the content of the welcome page
echo("<h2>Manage Users:</h2>");

echo("<FORM name=\"ViewUsers\" method=\"post\" action=\"ViewUsers.php?SessionId=$SessionId\"> 
	  <INPUT type=\"hidden\" name=\"PrevURL\" value=$CurURL>
	  <INPUT type=\"hidden\" name=\"SessionId\" value=$SessionId>
	  <INPUT type=\"submit\" name=\"ViewAllUsers\" value=\"View Users\" style=\"height:25px; width:150px\"> 
	  </FORM>");

echo("<FORM name=\"SearchUser\" method=\"post\" action=\"SearchUser.php?SessionId=$SessionId\"> 
	  <INPUT type=\"hidden\" name=\"PrevURL\" value=$CurURL>
	  <INPUT type=\"hidden\" name=\"SessionId\" value=$SessionId>
	  <INPUT type=\"submit\" name=\"SearchUser\" value=\"Search User\" style=\"height:25px; width:150px\"> 
	  </FORM>");

echo("<br />");
echo("<br />");
echo("<br />");	  

echo("<FORM name=\"ViewCurrentSessions\" method=\"post\" action=\"ViewCurrentSessions.php?SessionId=$SessionId\"> 
	  <INPUT type=\"hidden\" name=\"PrevURL\" value=$CurURL>
	  <INPUT type=\"hidden\" name=\"SessionId\" value=$SessionId>
	  <INPUT type=\"submit\" name=\"ViewCurrentSessions\" value=\"View Current Sessions\" style=\"height:25px; width:150px\"> 
	  </FORM>");
	  
echo("<br />");
	  
echo("<h2>Manage Students:</h2>");
echo("<FORM name=\"StudentManagement\" method=\"post\" action=\"StudentManagement.php?SessionId=$SessionId\"> 
	  <INPUT type=\"hidden\" name=\"PrevURL\" value=$CurURL>
	  <INPUT type=\"hidden\" name=\"SessionId\" value=$SessionId>
	  <INPUT type=\"submit\" name=\"StudentManagement\" value=\"Student Management\" style=\"height:25px; width:150px\"> 
	  </FORM>");

echo("<br />");
echo("<br />");

echo("<FORM name=\"Back\" method=\"post\" action=$PrevURL> 
	  <INPUT type=\"hidden\" name=\"SessionId\" value=$SessionId>
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