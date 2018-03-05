<?

include "UtilityFunctions.php";


// Get the client id and password and verify them
$UserId = $_POST["UserId"];
$Password = $_POST["Password"];

$sql = "select UserId, Type, Name from Users " .
       "where UserId ='$UserId' " .
       "and Password ='$Password'";
	   
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
	  $SessionType = $values[1];
	  
	  // create a new session for this client
	  $SessionId = md5(uniqid(rand()));

	  // store the link between the sessionid and the userid
	  // and when the session started in the session table

	  $sql = "insert into UserSession " .
		"(SessionId, UserId, SessionDate, SessionType) " .
		"values ('$SessionId', '$UserId', SysDate, '$SessionType')";

		
	  $result_array = execute_sql_in_oracle ($sql);
	  $result = $result_array["flag"];
		
	  if ($result == false){
		display_oracle_error_message($cursor);
		die("Failed to create a new session");
	  }
	  else {  
		// insert OK - we have created a new session
		header("Location:Welcome.php?SessionId=$SessionId&UserId=$UserId");
	}
}
else { 
	// client username not found
	die ('Login failed.  Click <A href="login.html">here</A> to go back to the login page.');
}
?>