<?php

include('../../Model/database/conexion.php');

if (isset($_SESSION['id_Usu']) && isset($_POST['id_Inc'])) {
    $id_Usu = $_SESSION['id_Usu'];
    $id_Inc = $_POST['id_Inc'];

    // Insertar la relación en la tabla usuario_incentivos
    $sql = "INSERT INTO usuario_incentivos (id_Usu, id_Inc) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $id_Usu, $id_Inc);
    if ($stmt->execute()) {
        echo "Incentivo añadido correctamente.";
    } else {
        echo "Error al añadir el incentivo.";
    }
    $stmt->close();
} else {
    echo "Datos insuficientes.";
}
?>