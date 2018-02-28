<?
// include the verification PHP script
include "verifysession.php";

if ($sessionid == "") { 
  // no active session - clientid is unknown
  echo("Invalid user!");
} 
else {
  // here we can generate the content of the welcome page
  echo("Hello, welcome to my cool new  Website.");
}
?>

