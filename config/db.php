<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "estoque";

$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Conexão falhou: " . mysqli_connect_error());
}
?>