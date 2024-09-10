<?php

if(isset($_GET["location"]))
    $location = $_GET["location"];
else
    $location = "Labai";

if(isset($_GET["type"]))
    $type = $_GET["type"];
else
    $type = "Computador";

if(isset($_GET["tag"]))
    $tag = $_GET["tag"];
else
    $tag = "PC00";

include 'mysql.php';

$page = $_SERVER['PHP_SELF'];
$sec = "10";
header("Refresh: $sec; url=$page");

function PrintLocation($now, $time, $location, $type, $tag)
{
  $datetime = new DateTime($time, new DateTimeZone('America/Sao_Paulo'));
  $interval = $now->diff($datetime);
  $elapsed = $interval->format('%a dias, %h horas, %i minutos e %s segundos');
  $dias = $interval->format('%a');
  $horas = $interval->format('%h');
  $minutos = $interval->format('%i');
  $segundos = $interval->format('%s');
  $typestr = "Computador está ligado";

  if (($dias == 0 && $horas < 3 && $minutos > 59 && $segundos > 45) || ($dias == 0 && $horas == 0 && $minutos < 2))
  { 
    switch ($type) 
    {
        case "Computador":
            $typestr = "Computador está ligado";
            break;
        case "Projetor":
            $typestr = "Projetor está ligado";
            break;
        case "ArCondicionado":
            $typestr = "Ar condicionado está ligado";
            break;
        case "Janela":
            $typestr = "Janela está aberta";
            break;
        case "Porta":
            $typestr = "Porta está aberta";
            break;
        case "Luminaria":
            $typestr = "Iluminação está ligada";
            break;
    }

    echo "<div style='background-color:red;'><b><center>Última comunicação do " . $tag . " foi há ". $elapsed . ". 	" . $typestr . "!</center></b></div><br>"; 
  }
  else 
  { 
    switch ($type) 
    {
        case "Computador":
            $typestr = "Computador está desligado";
            break;
        case "Projetor":
            $typestr = "Projetor está desligado";
            break;
        case "ArCondicionado":
            $typestr = "Ar condicionado está desligado";
            break;
        case "Janela":
            $typestr = "Janela está fechada";
            break;
        case "Porta":
            $typestr = "Porta está fechada";
            break;
        case "Luminaria":
            $typestr = "Iluminação está desligada";
            break;
    }

    echo "<center>Última comunicação do " . $tag . " foi há ". $elapsed . ". " . $typestr . "!</center><br>"; 
  }
}

function SQLSelect($conn, $tablename, $now, $location)
{
  $sql = "SELECT time, location, type, tag FROM $tablename WHERE location='$location' ORDER BY tag";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) 
  { 
    while($row = $result->fetch_assoc()) { PrintLocation($now, $row["time"], $row["location"], $row["type"], $row["tag"]); }
  } 
  else { echo "0 results"; }
}

echo "<br><h1><center>Labai - Laboratório de Automação Industrial</center></h1><br>";

$starttime = microtime(true);

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$now = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));

SQLSelect($conn, $tablename, $now, $location);

$conn->close();

$endtime = microtime(true);
echo "<center>Acesso ao banco de dados executado em " . number_format(($endtime - $starttime) * 1000, 1, '.', '.') . " ms.</center><br>";
echo "<center>Página desenvolvida por Sergio A. B. Petrovcic, Dr. Eng.</center><br>";
echo "<center>Última atualização: " . $now->format('Y-m-d H:i:s') . ". Última versão da página: 2024-04-29 16:56:14.</center><br>";
?>