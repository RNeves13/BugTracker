<?php

header('Content-Type: text/html; charset=utf-8');
//include 'config.php';


$ligacao=mysqli_connect("localhost","root","","BugTracker");
mysqli_set_charset($ligacao,"utf8");

session_start();

$salt = "123";

//explode converte uma string num array, tendo por base um separador -> /
$partes=explode("/", substr($_SERVER["PATH_INFO"],1));
$msg=Array("error"=>"true","message"=>"Link Incorreto");


$nomeDoModulo = $partes[0]. ".php";
if ( file_exists($nomeDoModulo) ) {
    include $nomeDoModulo;
} 

echo json_encode($msg);

?>


