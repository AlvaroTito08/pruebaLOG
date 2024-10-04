<?php
require_once "config.php";
require_once "modUsuario.php";
require_once "modPersonas.php";
require_once "ctrlPersonas.php"; // Asegúrate de que esta ruta sea correcta

// Controlador de Usuario
class ctrlUsuarios {
    private $db;
    private $usuarios;
    private $persona;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->usuarios = new modUsuario($this->db);
        $this->persona = new ctrlPersonas(); 
    }

    // Método para manejar el registro de usuarios
    public function registrarUsuario($nombres, $a_paterno, $a_materno, $correo, $fecha_nacimiento, $nombre_usuario, $password) {
        try {
            // Intentar registrar la persona usando el controlador de Persona
            $persona_id = $this->persona->registrarPersona($nombres, $a_paterno, $a_materno, $correo, $fecha_nacimiento);

            // Verificar si se obtuvo un ID válido
            if ($persona_id) {
                // Encriptar la contraseña
                $password_hash = password_hash($password, PASSWORD_BCRYPT);

                // Registrar el usuario con el ID de persona
                $this->usuarios->nombre = $nombre_usuario;
                $this->usuarios->password = $password_hash; // Guardar la contraseña encriptada

                if ($this->usuarios->registrar($persona_id)) {
                    return true; // Usuario registrado correctamente
                } else {
                    return false; // Error al registrar el usuario
                }
            } else {
                return false; // No se obtuvo un ID válido de la persona registrada
            }
        } catch (PDOException $e) {
            return false; // Error en la inserción de usuario
        }
    }

    // Método para manejar el inicio de sesión
    public function iniciarSesion($nombre, $password) {
        // Obtener datos del usuario
        $user_data = $this->usuarios->obtenerUsuarioPorNombre($nombre);

        // Verificar si el usuario existe
        if ($user_data) {
            // Verificar si la contraseña es correcta
            if (password_verify($password, $user_data['password'])) {
                // Si el usuario está inactivo, activarlo
                if ($user_data['actividad'] == 0) {
                    $this->activarUsuario($nombre); // Activar al usuario
                }

                session_start();
                $_SESSION['usuarios'] = $user_data['nombre']; // Guardar el nombre de usuario en la sesión

                header("Location: bienvenido.php"); // Redirigir a la página de bienvenida
                exit();
            } else {
                // Contraseña incorrecta
                header("Location: login&registro.php?error=contraseña");
                exit();
            }
        } else {
            // Usuario no encontrado
            header("Location: login&registro.php?error=usuario");
            exit();
        }
    }

    // Método para activar un usuario
    public function activarUsuario($nombre) {
        if ($this->usuarios->activarUsuario($nombre)) {
            // Usuario activado correctamente
            return true;
        } else {
            return false; // Error al activar el usuario
        }
    }

    // Método para cerrar sesión
    public function cerrarSesion() {
        session_start();  // Iniciar sesión
        if (isset($_SESSION['usuarios'])) {
            // Desactivar al usuario
            $usuario_nombre = $_SESSION['usuarios'];  // Guardar el nombre de usuario de la sesión
            $this->usuarios->desactivarUsuario($usuario_nombre);
        }
        session_destroy(); // Destruir la sesión
        header("Location: login&registro.php"); // Redirigir al login después de cerrar sesión
        exit();
    }
}

// Manejo de acciones desde la URL
if (isset($_GET['action'])) {
    $usuarioCtrl = new ctrlUsuarios();

    // Acción para cerrar sesión
    if ($_GET['action'] == 'logout') {
        $usuarioCtrl->cerrarSesion();
    }

    // Acción para iniciar sesión
    if ($_GET['action'] == 'login' && isset($_POST['nombre_usuario'], $_POST['password'])) {
        $nombre_usuario = $_POST['nombre_usuario'];
        $password = $_POST['password'];
        $usuarioCtrl->iniciarSesion($nombre_usuario, $password);
    }

    // Acción para activar usuario (puedes llamarla desde la URL)
    if ($_GET['action'] == 'activar' && isset($_GET['nombre'])) {
        $nombre = $_GET['nombre'];
        $usuarioCtrl->activarUsuario($nombre);
    }
}
?>
