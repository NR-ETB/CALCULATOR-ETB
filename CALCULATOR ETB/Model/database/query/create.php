<?php
// Verificar si se han enviado los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user']) && isset($_POST['names']) && isset($_POST['cel']) && isset($_POST['pass'])) {
    // Obtener los datos del formulario
    $user = $_POST['user'];
    $names = $_POST['names'];
    $cel = $_POST['cel'];
    $pass = $_POST['pass'];

    // Insertar los datos en la base de datos
    include('Model/database/conexion.php'); // Incluir el archivo de conexión a la base de datos

    // Preparar la consulta SQL para insertar un nuevo usuario
    $sql = "INSERT INTO usuario (nom_Usu, cel_Usu, usu_Usu, con_Usu, cantI_Usu, cantR_Usu) VALUES (?, ?, ?, ?, 0, 0)"; 
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $names, $cel, $user, $pass); // Bind de los parámetros
    $stmt->execute(); // Ejecutar la consulta

    // Verificar si la inserción fue exitosa
    if ($stmt->affected_rows > 0) {
    
    } else {
        
    }

    // Cerrar la conexión y liberar los recursos
    $stmt->close();
    $conn->close();
}
?>