<?php
require_once "config.php";
require_once "modUsuario.php";
require_once "modPersonas.php";
require_once "ctrlPersonas.php"; 

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

    public function registrarUsuario($nombres, $a_paterno, $a_materno, $correo, $fecha_nacimiento, $nombre_usuario, $password) {
        try {
            $persona_id = $this->persona->registrarPersona($nombres, $a_paterno, $a_materno, $correo, $fecha_nacimiento);

            if ($persona_id) {
                $password_hash = password_hash($password, PASSWORD_BCRYPT);

                $this->usuarios->nombre = $nombre_usuario;
                $this->usuarios->password = $password_hash; 

                if ($this->usuarios->registrar($persona_id)) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    public function iniciarSesion($nombre, $password) {
        $user_data = $this->usuarios->obtenerUsuarioPorNombre($nombre);

        if ($user_data) {
            if (password_verify($password, $user_data['password'])) {
                if ($user_data['actividad'] == 0) {
                    $this->activarUsuario($nombre);
                }

                session_start();
                $_SESSION['usuarios'] = $user_data['nombre'];

                header("Location: bienvenido.php");
                exit();
            } else {
                header("Location: login&registro.php?error=contraseña");
                exit();
            }
        } else {
            header("Location: login&registro.php?error=usuario");
            exit();
        }
    }

    public function activarUsuario($nombre) {
        if ($this->usuarios->activarUsuario($nombre)) {
            return true;
        } else {
            return false;
        }
    }

    public function cerrarSesion() {
        session_start();
        if (isset($_SESSION['usuarios'])) {
            $usuario_nombre = $_SESSION['usuarios'];
            $this->usuarios->desactivarUsuario($usuario_nombre);
        }
        session_destroy();
        header("Location: login&registro.php");
        exit();
    }
}

if (isset($_GET['action'])) {
    $usuarioCtrl = new ctrlUsuarios();

    if ($_GET['action'] == 'logout') {
        $usuarioCtrl->cerrarSesion();
    }

    if ($_GET['action'] == 'login' && isset($_POST['nombre_usuario'], $_POST['password'])) {
        $nombre_usuario = $_POST['nombre_usuario'];
        $password = $_POST['password'];
        $usuarioCtrl->iniciarSesion($nombre_usuario, $password);
    }

    if ($_GET['action'] == 'activar' && isset($_GET['nombre'])) {
        $nombre = $_GET['nombre'];
        $usuarioCtrl->activarUsuario($nombre);
    }
}
?>
