<?php
session_start();
if (!isset($_SESSION['usuarios'])) {
    header("Location: login&registro.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header text-center">
                        <h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['usuarios']); ?>!</h2>
                    </div>
                    <div class="card-body text-center">
                        <p>Has iniciado sesión correctamente.</p>
                        <!-- Redirigir a ctrlUsuarios.php para manejar el cierre de sesión -->
                        <a href="ctrlUsuarios.php?action=logout" class="btn btn-danger btn-lg">Cerrar Sesión</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enlace a Bootstrap JS y dependencias -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
