<?php

$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$database = "calculator"; 

// Crear una conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>