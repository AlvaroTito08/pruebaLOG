<?php
require_once "ctrlUsuarios.php";

$controlador = new ctrlUsuarios();
$error = ""; // Variable para manejar errores
$success = ""; // Variable para manejar éxitos

// Manejo del inicio de sesión
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $nombre = $_POST['nombre'];
    $password = $_POST['password'];

    if ($controlador->iniciarSesion($nombre, $password)) {
        $success = "Sesión iniciada correctamente.";
        // Redirigir a una página de bienvenida o panel de control
        header("Location: bienvenido.php");
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}

// Manejo del registro de usuario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    $nombres = $_POST['nombres'];
    $a_paterno = $_POST['a_paterno'];
    $a_materno = $_POST['a_materno'];
    $correo = $_POST['correo'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $nombre = $_POST['nombre_registro'];
    $password = $_POST['password_registro'];

    if ($controlador->registrarUsuario($nombres, $a_paterno, $a_materno, $correo, $fecha_nacimiento, $nombre, $password)) {
        $success = "Usuario registrado correctamente.";
        header("Location: login&registro.php?success=1");
        exit();
    } else {
        $error = "Error al registrar el usuario.";
        echo $error; // Verificación del error
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login y Registro</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <!-- Mensajes de error o éxito -->
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= $error; ?></div>
                <?php endif; ?>
                <?php if (!empty($success)): ?>
                    <div class="alert alert-success"><?= $success; ?></div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header text-center">
                        <h4>Iniciar Sesión</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="nombre">Nombre:</label>
                                <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Ingresa tu nombre de usuario" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Contraseña:</label>
                                <input type="password" id="password" name="password" class="form-control" placeholder="Ingresa tu contraseña" required>
                            </div>
                            <button type="submit" name="login" class="btn btn-primary btn-block">Ingresar</button>
                            <a href="#" data-toggle="modal" data-target="#registerModal">¿No tienes una cuenta? Regístrate aquí.</a>
                        </form>
                    </div>
                </div>

                <!-- Modal para Registrarse -->
                <div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="registerModalLabel">Registrarse</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="">
                                    <div class="form-group">
                                        <label for="nombres">Nombre:</label>
                                        <input type="text" id="nombres" name="nombres" class="form-control" placeholder="Ingresa tu nombre" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="a_paterno">Apellido Paterno:</label>
                                        <input type="text" id="a_paterno" name="a_paterno" class="form-control" placeholder="Ingresa tu apellido paterno" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="a_materno">Apellido Materno:</label>
                                        <input type="text" id="a_materno" name="a_materno" class="form-control" placeholder="Ingresa tu apellido materno" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="correo">Correo:</label>
                                        <input type="email" id="correo" name="correo" class="form-control" placeholder="Ingresa tu correo electrónico" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="fecha_nacimiento">Fecha de Nacimiento:</label>
                                        <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" class="form-control" placeholder="Ingresa tu fecha de nacimiento" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="nombre_registro">Nombre de Usuario:</label>
                                        <input type="text" id="nombre_registro" name="nombre_registro" class="form-control" placeholder="Ingresa tu nombre de usuario" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="password_registro">Contraseña:</label>
                                        <input type="password" id="password_registro" name="password_registro" class="form-control" placeholder="Ingresa tu contraseña" required>
                                    </div>
                                    <button type="submit" name="register" class="btn btn-success btn-block">Registrarse</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enlace a Bootstrap JS y dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Abrir modal de registro automáticamente si hay un error -->
    <script>
        $(document).ready(function() {
            <?php if (isset($_POST['register']) && !empty($error)): ?>
                $('#registerModal').modal('show');
            <?php endif; ?>
        });
    </script>
</body>
</html>
