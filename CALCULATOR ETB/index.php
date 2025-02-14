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
    <link rel="icon" href="View/images/flaticon/simbols.png" type="image/x-icon">
    <link rel="stylesheet" href="View/css/login.css">
    <title>Calculator ETB</title>
</head>
<body>

<?php

    session_start();

    if (isset($_SESSION['user'])) {
        echo "<script>console.log('Sesión activa: " . $_SESSION['user'] . "');</script>";
    } else {
        echo "<script>console.log('No hay sesión activa.');</script>";
    }

    session_unset();
    session_destroy();

    include('Model/database/conexion.php');
    include('Model/database/query/login.php');
    include('Model/database/query/create.php');
?>

    <div class="container-login">

        <div class="login" id="into">

            <div class="l-login">
                <h1>Iniciar Sesion <br>(I & R)</h1>

                <form action="" method="POST">
                    <div>
                        <img src="View/images/icons/email.png" alt="">
                        <input type="text" name="username" placeholder="Ingresa tu Usuario" required>
                    </div>

                    <div>
                        <img src="View/images/icons/pass.png" alt="">
                        <input type="password" name="password" placeholder="Ingresa tu Contraseña" required>
                    </div>

                    <div class="buttons-act">
                        <button class="act" type="submit">Iniciar</button>
                    </div>
                </form>

            </div>
            

            <div class="r-login">
                <div class="well">
                    <h1>Hola, Amig@!</h1>
                    <p>Ingresa tus datos personales y empiza <br> jornada con nosotros :D</p>
                    <button class="act-2" onclick="create_Account();">Crear Cuenta</button>
                </div>
            </div>

        </div>

        <div class="login" id="create" style="display: none;">

            <div class="l-login2">

                <div class="well">
                    <h1>Bienvenido de <br> Nuevo!</h1>
                    <p>Para mantenerse conectado, inicie sesión <br> con su información personal.</p>
                    <button class="act-2" onclick="login_Account();">Iniciar Sesion</button>
                </div>

            </div>

            <div class="r-login2">

                <form action="" method="POST">
                    <h1>Crear Cuenta <br>(I & R)</h1>

                    <div>
                        <img src="View/images/icons/user.png" alt="">
                        <input type="text" name="names" placeholder="Ingresa tu Nombre" required>
                    </div>

                    <div>
                        <img src="View/images/icons/cel.png" alt="">
                        <input type="number" name="cel" placeholder="Ingresa tu Celular" required>
                    </div>

                    <div>
                        <img src="View/images/icons/email.png" alt="">
                        <input type="text" name="user" placeholder="Ingresa tu Usuario" required>
                    </div>

                    <div>
                        <img src="View/images/icons/pass.png" alt="">
                        <input type="password" name="pass" placeholder="Ingresa tu Contraseña" required>
                    </div>

                    <div class="buttons-act2">
                        <button class="act" type="submit">Crear</button>
                    </div>
                </form>
                
            </div>

        </div>

        <div class="backdrop"></div>
    </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="Controller/login.js"></script>
</body>
</html>