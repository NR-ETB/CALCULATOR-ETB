<?php
session_start(); // Iniciar la sesión

// Incluir el archivo de conexión a la base de datos
include('Model/database/conexion.php');

// Inicializar la variable $cant_Cal
$cant_Cal = null;

// Verificar si la variable de sesión 'id_Usu' está definida
if (isset($_SESSION['id_Usu'])) {
    $userId = $_SESSION['id_Usu'];

    // Preparar la consulta para obtener 'cant_Cal'
    $sql = "SELECT cant_Cal FROM calculadora WHERE id_Usu = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $stmt->bind_result($cant_Cal);
        if (!$stmt->fetch()) {
            // No se encontró el registro
            $cant_Cal = null;
        }
        $stmt->close();
    } else {
        echo "Error al preparar la consulta.";
    }
} else {
    echo "Usuario no autenticado.";
}
?>
