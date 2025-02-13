<?php

include('../../Model/database/conexion.php');

// Verificar si el usuario ha iniciado sesión y si se ha enviado el formulario con 'id_Ret'
if (isset($_SESSION['id_Usu']) && isset($_POST['id_Ret'])) {
    $id_Usu = $_SESSION['id_Usu'];
    $id_Ret = $_POST['id_Ret'];

    // Preparar la consulta para insertar la relación en la tabla usuario_retenciones
    $sql = "INSERT INTO usuario_retenciones (id_Usu, id_Ret) VALUES (?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        // Vincular los parámetros
        $stmt->bind_param('ii', $id_Usu, $id_Ret);
        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo "Retención añadida correctamente.";
        } else {
            echo "Error al ejecutar la consulta: " . $stmt->error;
        }
        // Cerrar la declaración
        $stmt->close();
    } else {
        echo "Error al preparar la consulta: " . $conn->error;
    }
} else {
    echo "Datos insuficientes o usuario no autenticado.";
}
?>
