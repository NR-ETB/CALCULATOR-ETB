<?php
session_start(); // Iniciar la sesión antes de usar $_SESSION

include('Model/database/conexion.php');

function verificarUser($username, $password) {
    global $conn;
    $sql = "SELECT id_Usu FROM usuario WHERE usu_Usu = ? AND con_Usu = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id_Usu);
        $stmt->fetch();
        return $id_Usu; // Devolver el ID del usuario
    } else {
        return false;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $userId = verificarUser($username, $password);
    if ($userId) {
        // Almacenar el ID del usuario en la sesión
        $_SESSION['id_Usu'] = $userId;
        $_SESSION['username'] = $username;

        // Redireccionar al usuario a la página deseada
        header("Location: View/calculator/calcula.php");
        exit();
    } else {
        echo "Nombre de usuario o contraseña incorrectos.";
    }
}
?>