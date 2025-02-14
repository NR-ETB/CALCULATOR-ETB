<?php
include('../../Model/database/conexion.php');

// Verificar si se ha enviado una solicitud POST y si el botón de descarga fue presionado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['download'])) {
    // Verificar si la sesión contiene el ID de usuario
    if (!isset($_SESSION['id_Usu'])) {
        die("Error: No hay una sesión activa.");
    }

    $id_Usu = $_SESSION['id_Usu'];

    // Validar que el ID del usuario sea un número entero
    if (!filter_var($id_Usu, FILTER_VALIDATE_INT)) {
        die("Error: ID de usuario no válido.");
    }

    // Consulta SQL para obtener los datos del usuario
    $sql = "
        SELECT 
            u.nom_Usu AS Nombre,
            u.cel_Usu AS Teléfono,
            u.usu_Usu AS Usuario,
            COALESCE(COUNT(DISTINCT ui.id_Inc), 0) AS Cantidad_Incentivos,
            COALESCE(SUM(i.com_Inc), 0) AS Valor_Total_Incentivos,
            COALESCE(COUNT(DISTINCT ur.id_Ret), 0) AS Cantidad_Retenciones,
            COALESCE(SUM(r.com_Ret), 0) AS Valor_Total_Retenciones,
            COALESCE(c.cant_Cal, 0) AS Valor_Total_Calculadora
        FROM usuario u
        LEFT JOIN usuario_Incentivos ui ON u.id_Usu = ui.id_Usu
        LEFT JOIN incentivos i ON ui.id_Inc = i.id_Inc
        LEFT JOIN usuario_Retenciones ur ON u.id_Usu = ur.id_Usu
        LEFT JOIN retenciones r ON ur.id_Ret = r.id_Ret
        LEFT JOIN calculadora c ON u.id_Usu = c.id_Usu
        WHERE u.id_Usu = ?
        GROUP BY u.id_Usu, c.cant_Cal
    ";

    // Preparar y ejecutar la consulta
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('i', $id_Usu);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Obtener los datos del usuario
            $fila = $result->fetch_assoc();

            // Establecer las cabeceras para la descarga del archivo CSV
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=usuario_' . $id_Usu . '_datos.csv');

            // Abrir el flujo de salida
            $salida = fopen('php://output', 'w');

            // Escribir los encabezados de las columnas
            fputcsv($salida, array_keys($fila));

            // Escribir los datos del usuario
            fputcsv($salida, $fila);

            // Cerrar el flujo de salida
            fclose($salida);
            exit();
        } else {
            echo "No se encontraron registros para el usuario con ID: " . htmlspecialchars($id_Usu);
        }

        $stmt->close();
    } else {
        echo "Error al preparar la consulta: " . $conn->error;
    }

    // Cerrar la conexión a la base de datos
    $conn->close();
} else {
    echo "Acceso no autorizado.";
}
?>