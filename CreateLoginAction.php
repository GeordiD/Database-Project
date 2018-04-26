<?
include "UtilityFunctions.php";

// Get the client id and password and verify them
$Name = $_POST["CreateName"];
$Password = $_POST["CreatePassword"];
$UserId = "A" . mt_rand(1000, 9999);
$GeneratedId = false;

while($GeneratedId == false){
	$sqlcheckId = 	"select * from Users " .
					"where UserId ='$UserId' ";  
	$result_array = execute_sql_in_oracle ($sqlcheckId);
	$result = $result_array["flag"];
	$cursor = $result_array["cursor"];
	if ($result == false){
		display_oracle_error_message($cursor);
		die("Client Query Failed.");
	}
	if($values = oci_fetch_array ($cursor)){		
		$GeneratedId = false;
		$UserId = "A" . mt_rand(1000, 9999);
	}
	else{
		$GeneratedId = true;
	}
}

$sql = "insert into Users " .
		"(UserId, Password, isAdmin) " .
		"values ('$UserId', '$Password', 0)";
	   
$result_array = execute_sql_in_oracle ($sql);
$result = $result_array["flag"];

if ($result == false){
  display_oracle_error_message($cursor);
  die("Client Query Failed.");
}
?>
