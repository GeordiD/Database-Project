<?
include "UtilityFunctions.php";

$SessionId =$_GET["SessionId"];
echo($SessionId);
verify_session($SessionId);


// connection OK - delete the session.
$sql = "delete from UserSession where SessionId = '$SessionId'";

$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];
$cursor = $result_array["cursor"];
if ($result == false){
  display_oracle_error_message($cursor);
  die("Session removal failed");
}

// jump to login page
header("Location:login.html");
?>