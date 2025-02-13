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

                <img src="" alt="">

                <form method="POST" action="">
                    <p>Agrega aqui tu mas reciente <span>INCENTIVO ETB:</span> <br>
                    <span>Ganancias Genradas: <span id="earn_Inc">$49.000</span></span></p>
                    <?php
                        // Verificar si el formulario ha sido enviado
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
                                            $message = "Incentivo añadido correctamente.";
                                        } else {
                                            $message = "Error al añadir el incentivo: " . $stmt->error;
                                        }
                                        $stmt->close();
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

                <img src="" alt="">

                <form method="POST" action="">
                    <p>Agrega aqui tu mas reciente <span>RETENCION EFECTIVA:</span> <br>
                    <span>Ganancias Genradas: <span id="earn_Ret">$49.000</span></span></p>
                    <button id="add2" type="submit">Añadir</button>

                    <?php
                    
                        // Verificar si el formulario ha sido enviado
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            // Verificar que el usuario esté autenticado y que se haya seleccionado una retención
                            if (isset($_SESSION['id_Usu']) && !empty($_POST['id_Ret'])) {
                                $id_Usu = $_SESSION['id_Usu'];
                                $id_Ret = $_POST['id_Ret'];
                        
                                // Validar que $id_Ret sea un número entero
                                if (filter_var($id_Ret, FILTER_VALIDATE_INT)) {
                                    // Preparar la consulta para insertar la relación en la tabla usuario_retenciones
                                    $sql = "INSERT INTO usuario_retenciones (id_Usu, id_Ret) VALUES (?, ?)";
                                    if ($stmt = $conn->prepare($sql)) {
                                        // Vincular los parámetros
                                        $stmt->bind_param('ii', $id_Usu, $id_Ret);
                                        // Ejecutar la consulta
                                        if ($stmt->execute()) {
                                            $message =  "Retención añadida correctamente.";
                                        } else {
                                            $message =  "Error al ejecutar la consulta: " . $stmt->error;
                                        }
                                        // Cerrar la declaración
                                        $stmt->close();
                                    } else {
                                        $message =  "Error al preparar la consulta: " . $conn->error;
                                    }
                                } else {
                                    $message =  "ID de retención no válido.";
                                }
                            } else {
                                $message =  "Datos insuficientes o usuario no autenticado.";
                            }
                        } else {
                            $message =  "No se ha enviado el formulario.";
                        }
                        
                            // Obtener la lista de incentivos
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
                <p>Aqui esta el total de <span>INCENTIVOS Y RETENCIONES:</span> <br>
                <span>Ganancias Totales: <span id="earn_Re">$49.000</span></span></p>
                <button id="add3">Descargar</button>
                <input id="election3" type="text" value="<?php echo htmlspecialchars($cant_Cal); ?>" readonly>
            </div>

        </div>

    </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>