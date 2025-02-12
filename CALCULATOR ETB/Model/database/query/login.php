<?php
include('Model/database/conexion.php');

function verificarUser($username, $password) {
    global $conn;
    $sql = "SELECT * FROM usuario WHERE usu_Usu='$username' AND con_Usu='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "Usuario Autenticado";
        return true;
    } else {
        echo "Usuario NO Autenticado";
        return false;
    }
}

function obtenerIdUsuario($username) {
    global $conn;
    // Preparar la consulta para obtener el ID del usuario
    $sql = "SELECT id_Usu FROM usuario WHERE usu_Usu = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->store_result();

        // Verificar si se encontró el usuario
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id_Usu);
            $stmt->fetch();
            return $id_Usu;
        } else {
            // Usuario no encontrado
            return null;
        }
        $stmt->close();
    } else {
        // Error al preparar la consulta
        return null;
    }
}

// Verificar si se han enviado los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (verificarUser($username, $password)) {
        // Obtener el ID del usuario
        $userId = obtenerIdUsuario($username);
        if ($userId !== null) {
            // Almacenar el ID del usuario en la sesión
            $_SESSION['id_User'] = $userId;
            $_SESSION['username'] = $username;

            // Redireccionar si el usuario está autenticado
            header("Location: View/calculator/calcula.php");
            exit(); // Asegurarse de que el script se detenga después de la redirección
        } else {
            // No se pudo obtener el ID del usuario
            echo "Error al obtener el ID del usuario.";
        }
    } else {
        // Mostrar mensaje de error si la autenticación falla
        echo "Nombre de usuario o contraseña incorrectos.";
    }
}
?>
