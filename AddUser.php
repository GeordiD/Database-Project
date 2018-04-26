<?
include "UtilityFunctions.php";

$UserId =$_GET["UserId"];
$SessionId =$_GET["SessionId"];

verify_session($SessionId);

$PrevURL = $_POST["PrevURL"];
$CurURL = $_SERVER['REQUEST_URI'];

echo "user: " . $UserId;
echo "session: " . $SessionId;

echo "<h2>Add User</h2>";

echo "<FORM name=\"AddUser\" method=\"post\" action=\"UserManagement.php?SessionId=$SessionID&UserId=$UserId\"> " .
	"<INPUT type=\"hidden\" name=\"SessionId\" value=$SessionId>" .
	"<INPUT type=\"submit\" name=\"Submit\" value=\"Submit\"> " .
	"</FORM>";

?>
