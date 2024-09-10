<?php

//Exemplo: update.php?location[]=Labai&type[]=Computador&tag[]=PC04&location[]=Labai&type[]=Janela&tag[]=JN01

include('mysql.php');

$location = $types = $tags = "";

$location = $_GET['location'];
$type = $_GET['type'];
$tag = $_GET['tag'];

$values = "";
$starttime = microtime(true);

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$now = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));

$counter = 0;
echo "Locations: " . count($location) . "<br>";
for($i = 0; $i < count($location); $i++) 
{
	$sql = "UPDATE " . $tablename . " SET time=\"". $now->format('Y-m-d H:i:s') . "\" WHERE location=\"" . $location[$i] . "\" AND type=\"" . $type[$i] . "\" AND tag=\"" . $tag[$i] . "\"";

	echo "SQL: " . $sql . "<br>";

	if ($conn->query($sql) === TRUE) 
	{
	  $counter += 1;
	} else 
	{
	  echo "Error updating record: " . $conn->error;
	  echo -1;
	  exit();
	}
}
echo $counter;

$conn->close();
?>

