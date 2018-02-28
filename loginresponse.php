<?
$clientid = $_POST["clientid"];

$connection = oci_connect ("gq024", "cqkrhy", "gqiannew2:1521/pdborcl");
if($connection == false){
  $e = oci_error(); 
  die($e['message']);
}

// connection OK - lookup the client
$sql = "select username " .
      "from Users " .
      "where username='$clientid'";
$cursor = oci_parse($connection, $sql);

if ($cursor == false) {
  $e = oci_error($connection);  
  echo $e['message']."<BR>";
  oci_close ($connection);
  // query failed - login impossible
  die("Client Query Failed");
}

// query is OK - If we have any rows in the result set, we have
// found the client
$result = oci_execute($cursor);
if ($result == false){
  $e = oci_error($cursor);  
  echo $e['message']."<BR>";
  oci_close($connection);
  die("Client Query Failed");
}

if(!$values = oci_fetch_array ($cursor)){
  oci_close ($connection);
  // client username not found
  echo $cursor;
  die ("sorry bruh, Client not found.");
}

oci_free_statement($cursor);

// found the client
$clientid = $values[0];

// create a new session for this visitor
$sessionid = md5(uniqid(rand()));

// store the link between the sessionid and the clientid
// and when the session started in the session table


$sql = "insert into project_sessions " .
  "(session_id, username, sessiondate) " .
  "values ('$sessionid', '$clientid', sysdate)";

$cursor = oci_parse($connection, $sql);

if($cursor == false){
  $e = oci_error($connection);  
  echo $e['message']."<BR>";
  oci_close ($connection);
  // insert Failed
  die ("Failed to create a new session");
}

$result = oci_execute($cursor);
if ($result == false){
  $e = oci_error($cursor);
  echo $e['message']."<BR>";
  oci_close($connection);
  die("Failed to create a new session");
}

// insert OK - we have created a new session
//oci_commit ($connection);
oci_close ($connection);
// jump to your welcome page
Header("Location:welcomepage.php?sessionid=$sessionid");
?>

