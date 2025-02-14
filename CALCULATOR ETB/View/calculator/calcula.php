<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar los datos del formulario
    // Por ejemplo, insertar datos en la base de datos

    // Redirigir a la misma página para evitar reenvío de formulario
    header('Location: ' . $_SERVER['REQUEST_URI']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../images/flaticon/simbols.png" type="image/x-icon">
    <link rel="stylesheet" href="../css/style.css">
    <title>Calculator ETB</title>
</head>
<body>

<?php

    session_start();

    if (isset($_SESSION['id_Usu'])) {
        echo "<script>console.log('Sesión activa: Usuario ID " . $_SESSION['id_Usu'] . "');</script>";
    } else {
        echo "<script>console.log('No hay sesión activa.');</script>";
    }

    include('../../Model/database/conexion.php');
    include('../../Model/database/query/amount.php');
    include('../../Model/database/query/export.php');

?>

    <div class="container">

        <div class="fee">

            <h3>INCENTIVOS ETB</h3>

            <table class="tble-inc">
                <tr>
                    <th id="tble-tt">Clasificación</th>
                    <th>Producto</th>
                    <th>Comisión</th>
                </tr>

                <tbody>
                    <tr>
                        <td>LT DUO TRIO</td>
                        <td>FTTH</td>
                        <td>$ 7.000</td>
                    </tr>

                    <tr>
                        <td>UP GRANDES Y SVA</td>
                        <td>SVA</td>
                        <td>$ 700</td>
                    </tr>

                    <tr>
                        <td>UP GRANDES Y SVA</td>
                        <td>DGO</td>
                        <td>$ 500</td>
                    </tr>

                    <tr>
                        <td>MOVILES</td>
                        <td>POSPAGO</td>
                        <td>$ 30.000</td>
                    </tr>

                    <tr>
                        <td>MOVILES</td>
                        <td>DGO</td>
                        <td>$ 400</td>
                    </tr>
                </tbody>
            </table>

            <h3 id="ret">RETENCIONES</h3>

            <table class="tble-inc2">
                <tr>
                    <th id="tble-tt2">Cantidad</th>
                    <th>Comisión</th>
                </tr>

                <tbody>
                    <tr>
                        <td>1 a 90 <span>EFECTIVAS</span></td>
                        <td>$ 500</td>
                    </tr>

                    <tr>
                        <td>91 a 145 <span>EFECTIVAS</span></td>
                        <td>$ 3.000</td>
                    </tr>

                    <tr>
                        <td>146 <span>EN ADELANTE EFECTIVAS</span></td>
                        <td>$ 5.000</td>
                    </tr>
                </tbody>
            </table>

            <a href="Model/documents/manCalculator.pdf" target="_blank">¿Como usar la calculadora?</a>

        </div>

        <div class="calculator">

            <h3>CALCULADORA ETB</h3>

            <div class="inc">

                <img src="../images/icons/incentive.png" alt="">

                <form method="POST" action="">
                    <p>Agrega aqui tu mas reciente <span>INCENTIVO ETB:</span> <br>

                    <?php
                        $id_Usu = $_SESSION['id_Usu'];

                        // Consulta para obtener la suma de los incentivos del usuario
                        $sql = "SELECT SUM(i.com_Inc) AS total_incentivos
                                FROM usuario_incentivos ui
                                INNER JOIN incentivos i ON ui.id_Inc = i.id_Inc
                                WHERE ui.id_Usu = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param('i', $id_Usu);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $row = $result->fetch_assoc();
                        $total_incentivos = $row['total_incentivos'] ?? 0;
                    ?>

                    <span>Ganancias Generadas: <span id="earn_Inc">$<?php echo number_format($total_incentivos, 2); ?></span></p>
                    <?php

                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            // Verificar que el usuario esté autenticado y que se haya seleccionado un incentivo
                            if (isset($_SESSION['id_Usu']) && !empty($_POST['id_Inc'])) {
                                $id_Usu = $_SESSION['id_Usu'];
                                $id_Inc = $_POST['id_Inc'];
                        
                                // Validar que $id_Inc sea un número entero
                                if (filter_var($id_Inc, FILTER_VALIDATE_INT)) {
                                    // Preparar la consulta para insertar la relación en la tabla usuario_incentivos
                                    $sql = "INSERT INTO usuario_incentivos (id_Usu, id_Inc) VALUES (?, ?)";
                                    if ($stmt = $conn->prepare($sql)) {
                                        $stmt->bind_param('ii', $id_Usu, $id_Inc);
                                        if ($stmt->execute()) {
                                            // Incentivo añadido correctamente, ahora actualizar cantI_Usu
                                            $stmt->close();
                        
                                            // Contar el número total de incentivos para el usuario
                                            $countSql = "SELECT COUNT(*) FROM usuario_incentivos WHERE id_Usu = ?";
                                            if ($countStmt = $conn->prepare($countSql)) {
                                                $countStmt->bind_param('i', $id_Usu);
                                                $countStmt->execute();
                                                $countStmt->bind_result($incentiveCount);
                                                if ($countStmt->fetch()) {
                                                    $countStmt->close();
                        
                                                    // Actualizar la columna cantI_Usu en la tabla usuario
                                                    $updateSql = "UPDATE usuario SET cantI_Usu = ? WHERE id_Usu = ?";
                                                    if ($updateStmt = $conn->prepare($updateSql)) {
                                                        $updateStmt->bind_param('ii', $incentiveCount, $id_Usu);
                                                        if ($updateStmt->execute()) {
                                                            $message = "Incentivo añadido y cantI_Usu actualizado correctamente.";
                                                        } else {
                                                            $message = "Error al actualizar cantI_Usu: " . $updateStmt->error;
                                                        }
                                                        $updateStmt->close();
                                                    } else {
                                                        $message = "Error en la preparación de la consulta de actualización: " . $conn->error;
                                                    }
                                                } else {
                                                    $message = "Error al obtener el conteo de incentivos.";
                                                }
                                            } else {
                                                $message = "Error en la preparación de la consulta de conteo: " . $conn->error;
                                            }
                                        } else {
                                            $message = "Error al añadir el incentivo: " . $stmt->error;
                                        }
                                    } else {
                                        $message = "Error en la preparación de la consulta: " . $conn->error;
                                    }
                                } else {
                                    $message = "ID de incentivo no válido.";
                                }
                            } else {
                                $message = "Datos insuficientes o usuario no autenticado.";
                            }
                        }                       

                        // Obtener la lista de incentivos
                        $sql = "SELECT id_Inc, cla_Inc FROM incentivos";
                        $result = $conn->query($sql);
                    ?>
                    <select name="id_Inc" id="election">
                        <option name="id_Inc" value="">Seleccione...</option>
                        <?php
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo '<option name="incentivo" value="' . htmlspecialchars($row['id_Inc']) . '">' . htmlspecialchars($row['cla_Inc']) . '</option>';
                            }
                        } else {
                            echo '<option value="">No hay incentivos disponibles</option>';
                        }
                        ?>
                    </select>
                    <button id="add" type="submit">Añadir</button>
                    <?php
                        // Verificar si el usuario está autenticado
                        if (isset($_SESSION['id_Usu'])) {
                            $id_Usu = $_SESSION['id_Usu'];

                            // Verificar si se ha enviado el formulario para vaciar incentivos
                            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vaciar_incentivos'])) {
                                // Iniciar una transacción
                                $conn->begin_transaction();
                                try {
                                    // Eliminar todas las entradas de usuario_incentivos para el usuario actual
                                    $sqlDelete = "DELETE FROM usuario_incentivos WHERE id_Usu = ?";
                                    if ($stmtDelete = $conn->prepare($sqlDelete)) {
                                        $stmtDelete->bind_param('i', $id_Usu);
                                        if ($stmtDelete->execute()) {
                                            // Restablecer el contador de incentivos en la tabla usuario
                                            $sqlUpdate = "UPDATE usuario SET cantI_Usu = 0 WHERE id_Usu = ?";
                                            if ($stmtUpdate = $conn->prepare($sqlUpdate)) {
                                                $stmtUpdate->bind_param('i', $id_Usu);
                                                if ($stmtUpdate->execute()) {
                                                    // Confirmar la transacción
                                                    $conn->commit();
                                                    $message = "Todos los incentivos han sido eliminados correctamente.";
                                                } else {
                                                    throw new Exception("Error al restablecer el contador de incentivos: " . $stmtUpdate->error);
                                                }
                                                $stmtUpdate->close();
                                            } else {
                                                throw new Exception("Error en la preparación de la consulta de actualización: " . $conn->error);
                                            }
                                        } else {
                                            throw new Exception("Error al eliminar los incentivos: " . $stmtDelete->error);
                                        }
                                        $stmtDelete->close();
                                    } else {
                                        throw new Exception("Error en la preparación de la consulta de eliminación: " . $conn->error);
                                    }
                                } catch (Exception $e) {
                                    // Revertir la transacción en caso de error
                                    $conn->rollback();
                                    $message = $e->getMessage();
                                }
                            }
                        } else {
                            $message = "Usuario no autenticado.";
                        }

                        // Mostrar el mensaje si existe
                        if (isset($message)) {
                            echo "<p>$message</p>";
                        }
                    ?>
                    <form method="POST" action="">
                        <button id="add" name="vaciar_incentivos" type="submit">Vaciar</button>
                    </form>

                </form>

                <div class="lis">
                    <?php

                        // Verificar si el usuario ha iniciado sesión
                        if (isset($_SESSION['id_Usu'])) {
                            $userId = $_SESSION['id_Usu'];

                            // Consulta para obtener las clasificaciones de los incentivos asociados al usuario
                            $sql = "SELECT i.cla_Inc
                                    FROM incentivos i
                                    INNER JOIN usuario_incentivos ui ON i.id_Inc = ui.id_Inc
                                    WHERE ui.id_Usu = ?";
                            if ($stmt = $conn->prepare($sql)) {
                                $stmt->bind_param('i', $userId);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                // Verificar si se encontraron incentivos
                                if ($result->num_rows > 0) {
                                    echo '<ul>';
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<li>' . htmlspecialchars($row['cla_Inc']) . '</li>';
                                    }
                                    echo '</ul>';
                                } else {
                                    echo 'No se encontraron incentivos';
                                }
                                $stmt->close();
                            } else {
                                echo 'Error al preparar la consulta.';
                            }
                        } else {
                            echo 'Usuario no autenticado.';
                        }
                    ?>
                </div>

            </div>

            <div class="ret">

                <img src="../images/icons/retention.png" alt="">

                <form method="POST" action="">
                    <p>Agrega aqui tu mas reciente <span>RETENCION EFECTIVA:</span> <br>
                    <?php
                        if (isset($_SESSION['id_Usu'])) {
                            $id_Usu = $_SESSION['id_Usu'];
                            $sql = "SELECT IFNULL(SUM(r.com_Ret), 0) AS total_retenciones
                                    FROM usuario_retenciones ur
                                    INNER JOIN retenciones r ON ur.id_Ret = r.id_Ret
                                    WHERE ur.id_Usu = ?";
                            if ($stmt = $conn->prepare($sql)) {
                                $stmt->bind_param('i', $id_Usu);
                                $stmt->execute();
                                $stmt->bind_result($total_retenciones);
                                $stmt->fetch();
                                $stmt->close();
                            } else {
                                $message = "Error en la preparación de la consulta: " . $conn->error;
                            }
                        } else {
                            $total_retenciones = 0;
                        }
                    ?>
                    <p>Ganancias Generadas: <span id="earn_Ret">$<?php echo number_format($total_retenciones, 2); ?></span></p>
                    <?php

                    // Verificar si el formulario ha sido enviado
                    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                        // Verificar que el usuario esté autenticado y que se haya seleccionado una retención
                        if (isset($_SESSION['id_Usu']) && !empty($_POST['id_Ret'])) {
                            $id_Usu = $_SESSION['id_Usu'];
                            $id_Ret = $_POST['id_Ret'];

                            // Validar que $id_Ret sea un número entero
                            if (filter_var($id_Ret, FILTER_VALIDATE_INT)) {
                                // Iniciar una transacción
                                $conn->begin_transaction();
                                try {
                                    // Preparar la consulta para insertar la relación en la tabla usuario_retenciones
                                    $sqlInsert = "INSERT INTO usuario_retenciones (id_Usu, id_Ret) VALUES (?, ?)";
                                    if ($stmtInsert = $conn->prepare($sqlInsert)) {
                                        $stmtInsert->bind_param('ii', $id_Usu, $id_Ret);
                                        if ($stmtInsert->execute()) {
                                            // Preparar la consulta para incrementar el campo cantR_Usu en la tabla usuario
                                            $sqlUpdate = "UPDATE usuario SET cantR_Usu = COALESCE(cantR_Usu, 0) + 1 WHERE id_Usu = ?";
                                            if ($stmtUpdate = $conn->prepare($sqlUpdate)) {
                                                $stmtUpdate->bind_param('i', $id_Usu);
                                                if ($stmtUpdate->execute()) {
                                                    // Confirmar la transacción
                                                    $conn->commit();
                                                    $message = "Retención añadida y contador actualizado correctamente.";
                                                } else {
                                                    throw new Exception("Error al actualizar el contador: " . $stmtUpdate->error);
                                                }
                                                $stmtUpdate->close();
                                            } else {
                                                throw new Exception("Error en la preparación de la consulta de actualización: " . $conn->error);
                                            }
                                        } else {
                                            throw new Exception("Error al añadir la retención: " . $stmtInsert->error);
                                        }
                                        $stmtInsert->close();
                                    } else {
                                        throw new Exception("Error en la preparación de la consulta de inserción: " . $conn->error);
                                    }
                                } catch (Exception $e) {
                                    // Revertir la transacción en caso de error
                                    $conn->rollback();
                                    $message = $e->getMessage();
                                }
                            } else {
                                //"ID de retención no válido.";
                            }
                        } else {
                            //"Datos insuficientes o usuario no autenticado.";
                        }
                    } else {
                        // "No se ha enviado el formulario.";
                    }

                    // Obtener la lista de retenciones
                    $sql2 = "SELECT id_Ret, cant_Ret FROM retenciones";
                    $result2 = $conn->query($sql2);
                    ?>

                    <select name="id_Ret" id="election2">
                    <option value="">Seleccione...</option>
                    <?php
                    if ($result2->num_rows > 0) {
                        while($row = $result2->fetch_assoc()) {
                            echo '<option value="' . htmlspecialchars($row['id_Ret']) . '">' . htmlspecialchars($row['cant_Ret']) . '</option>';
                        }
                    } else {
                        echo '<option value="">No hay retenciones disponibles</option>';
                    }
                    ?>
                    </select>
                    <button id="add2" type="submit">Añadir</button>

                    <?php
                        // Verificar si el usuario está autenticado
                        if (isset($_SESSION['id_Usu'])) {
                            $id_Usu = $_SESSION['id_Usu'];

                            // Verificar si se ha enviado el formulario para vaciar retenciones
                            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['vaciar_retenciones'])) {
                                // Iniciar una transacción
                                $conn->begin_transaction();
                                try {
                                    // Eliminar todas las retenciones asociadas al usuario en la tabla usuario_retenciones
                                    $sqlDelete = "DELETE FROM usuario_retenciones WHERE id_Usu = ?";
                                    if ($stmtDelete = $conn->prepare($sqlDelete)) {
                                        $stmtDelete->bind_param('i', $id_Usu);
                                        if ($stmtDelete->execute()) {
                                            // Restablecer el contador de retenciones en la tabla usuario
                                            $sqlUpdate = "UPDATE usuario SET cantR_Usu = 0 WHERE id_Usu = ?";
                                            if ($stmtUpdate = $conn->prepare($sqlUpdate)) {
                                                $stmtUpdate->bind_param('i', $id_Usu);
                                                if ($stmtUpdate->execute()) {
                                                    // Confirmar la transacción
                                                    $conn->commit();
                                                    $message = "Todas las retenciones han sido eliminadas correctamente.";
                                                } else {
                                                    throw new Exception("Error al restablecer el contador de retenciones: " . $stmtUpdate->error);
                                                }
                                                $stmtUpdate->close();
                                            } else {
                                                throw new Exception("Error en la preparación de la consulta de actualización: " . $conn->error);
                                            }
                                        } else {
                                            throw new Exception("Error al eliminar las retenciones: " . $stmtDelete->error);
                                        }
                                        $stmtDelete->close();
                                    } else {
                                        throw new Exception("Error en la preparación de la consulta de eliminación: " . $conn->error);
                                    }
                                } catch (Exception $e) {
                                    // Revertir la transacción en caso de error
                                    $conn->rollback();
                                    $message = $e->getMessage();
                                }
                            }
                        } else {
                            $message = "Usuario no autenticado.";
                        }

                        // Mostrar el mensaje si existe
                        if (isset($message)) {
                            echo "<p>$message</p>";
                        }
                    ?>
                    <form method="POST" action="">
                        <button id="add2" name="vaciar_retenciones" type="submit">Vaciar</button>
                    </form>
                </form>

                <div class="lis2">
                    <?php

                        // Verificar si el usuario ha iniciado sesión
                        if (isset($_SESSION['id_Usu'])) {
                            $userId = $_SESSION['id_Usu'];

                            // Consulta para obtener las retenciones asociadas al usuario
                            $sql = "SELECT r.cant_Ret
                                    FROM retenciones r
                                    INNER JOIN usuario_retenciones ur ON r.id_Ret = ur.id_Ret
                                    WHERE ur.id_Usu = ?";
                            if ($stmt = $conn->prepare($sql)) {
                                $stmt->bind_param('i', $userId);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                // Verificar si se encontraron retenciones
                                if ($result->num_rows > 0) {
                                    echo '<ul>';
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<li>' . htmlspecialchars($row['cant_Ret']) . '</li>';
                                    }
                                    echo '</ul>';
                                } else {
                                    echo 'No se encontraron retenciones';
                                }
                                $stmt->close();
                            } else {
                                echo 'Error al preparar la consulta.';
                            }
                        } else {
                            echo 'Usuario no autenticado.';
                        }
                    ?>
                </div>
                
            </div>

            <div class="re">

                <img src="../images/icons/all.png" alt="">

                <p>Aqui esta el total de <span>INCENTIVOS Y RETENCIONES:</span> <br>

                <?php

                    // Verificar si el usuario está autenticado
                    if (isset($_SESSION['id_Usu'])) {
                        $userId = $_SESSION['id_Usu'];

                        // Inicializar la variable para las ganancias totales
                        $gananciasTotales = 0;

                        // Calcular la suma de los incentivos del usuario
                        $sqlIncentivos = "
                            SELECT SUM(i.com_Inc) AS total_incentivos
                            FROM usuario_incentivos ui
                            INNER JOIN incentivos i ON ui.id_Inc = i.id_Inc
                            WHERE ui.id_Usu = ?";
                        if ($stmt = $conn->prepare($sqlIncentivos)) {
                            $stmt->bind_param('i', $userId);
                            $stmt->execute();
                            $stmt->bind_result($totalIncentivos);
                            $stmt->fetch();
                            $stmt->close();
                            $gananciasTotales += $totalIncentivos;
                        } else {
                            echo "Error al preparar la consulta de incentivos.";
                        }

                        // Calcular la suma de las retenciones del usuario
                        $sqlRetenciones = "
                            SELECT SUM(r.com_Ret) AS total_retenciones
                            FROM usuario_retenciones ur
                            INNER JOIN retenciones r ON ur.id_Ret = r.id_Ret
                            WHERE ur.id_Usu = ?";
                        if ($stmt = $conn->prepare($sqlRetenciones)) {
                            $stmt->bind_param('i', $userId);
                            $stmt->execute();
                            $stmt->bind_result($totalRetenciones);
                            $stmt->fetch();
                            $stmt->close();
                            $gananciasTotales += $totalRetenciones;
                        } else {
                            echo "Error al preparar la consulta de retenciones.";
                        }
                    } else {
                        echo "Usuario no autenticado.";
                        $gananciasTotales = 0;
                    }
                ?>

                Ganancias Totales: <span id="earn_Re">$<?php echo number_format($gananciasTotales, 2); ?></span></p>
                <form method="POST" action="">
                    <button id="add3" name="download" type="submit">Descargar</button>
                </form>
                <input id="election3" type="text" value="<?php echo htmlspecialchars($cant_Cal); ?>" readonly>
            </div>

            <?php
                // Verificar si se ha enviado la solicitud de cierre de sesión
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
                    // Destruir todas las variables de sesión
                    $_SESSION = array();

                    // Si se desea destruir la sesión completamente, también se debe eliminar la cookie de sesión
                    if (ini_get("session.use_cookies")) {
                        $params = session_get_cookie_params();
                        setcookie(session_name(), '', time() - 42000,
                            $params["path"], $params["domain"],
                            $params["secure"], $params["httponly"]
                        );
                    }

                    // Finalmente, destruir la sesión
                    session_destroy();
                    exit();
                }
            ?>

            <div class="exit_Session">
                <form method="POST" action="../../index.php">
                    <button id="exitb" name="logout" type="submit">Cerrar Sesión</button>
                </form>
            </div>

        </div>

    </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>