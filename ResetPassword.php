<?
include "UtilityFunctions.php";

$SessionId =$_GET["SessionId"];
verify_session($SessionId);

//the previous url, used in the back button
$PrevURL =$_POST["PrevURL"];
$UserId = $_POST["UserId"];
	
		$sql = "update Users " .
			"set Password = 'default' " .
			"where UserId = '$UserId'";

		$result_array = execute_sql_in_oracle ($sql);
		$result = $result_array["flag"];

		if ($result == false){
			display_oracle_error_message($cursor);
			die("Failed to update password");
		}
		else{
			echo("Password reset successful");
			echo("<br />");
		}
		

echo("<br />");
echo("<FORM name=\"Back\" method=\"post\" action=\"$PrevURL\"> 
	  <input type=\"hidden\" name=\"SessionId\" value=$SessionId>
	  <INPUT type=\"submit\" name=\"Back\" value=Back> 
	  </FORM>");
?>