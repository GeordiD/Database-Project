<?
include "UtilityFunctions.php";

$SessionId =$_GET["SessionId"];
verify_session($SessionId);

//the previous url, used in the back button
$PrevURL =$_POST["PrevURL"];

$NewPassword = $_POST["NewPassword"];
if($NewPassword != ""){
	$sql = "select UserId " .
		   "from UserSession " .
		   "where SessionId ='$SessionId' ";
		   
	$result_array = execute_sql_in_oracle ($sql);
	$result = $result_array["flag"];
	$cursor = $result_array["cursor"];


	if ($result == false){
	  display_oracle_error_message($cursor);
	  die("Client Query Failed.");
	}

	if($values = oci_fetch_array ($cursor)){
		oci_free_statement($cursor);

		// found the client
		$UserId = $values[0];

		$sql = "update Users " .
			"set Password = '$NewPassword' " .
			"where UserId = '$UserId'";

		$result_array = execute_sql_in_oracle ($sql);
		$result = $result_array["flag"];

		if ($result == false){
			display_oracle_error_message($cursor);
			die("Failed to update password");
		}
		else{
			echo("Password updated successfully");
			echo("<br />");
		}
	}
	else { 
		// client username not found
		die ('Cannot update password');
	}
}
else{
	echo("Password cannot be blank!");
	echo("<br />");
}

echo("<br />");
echo("<FORM name=\"Back\" method=\"post\" action=\"$PrevURL\"> 
	  <input type=\"hidden\" name=\"SessionId\" value=$SessionId>
	  <INPUT type=\"submit\" name=\"Back\" value=Back> 
	  </FORM>");
?>