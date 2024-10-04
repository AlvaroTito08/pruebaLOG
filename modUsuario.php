<?php
// Clase para manejar los usuarios
class modUsuario {
    private $conn;

    public $nombre;
    public $password;

    // Constructor que recibe la conexión a la base de datos
    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para registrar un nuevo usuario
    public function registrar($persona_idPersona) {
        // Consulta SQL para insertar un nuevo usuario con el estado de actividad (1)
        $query = "INSERT INTO usuario (nombre, password, actividad, persona_idPersona) 
                  VALUES (:nombre, :password, 1, :persona_idPersona)";
        $stmt = $this->conn->prepare($query);

        // Enlazar parámetros
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':persona_idPersona', $persona_idPersona);

        // Ejecutar consulta y devolver el resultado
        try {
            if ($stmt->execute()) {
                return true;
            }
        } catch (PDOException $e) {
            echo "Error al registrar el usuario: " . $e->getMessage();
        }

        return false;
    }

    // Método para obtener el usuario por su nombre
    public function obtenerUsuarioPorNombre($nombre) {
        // Consulta SQL para obtener los datos del usuario por nombre
        $query = "SELECT * FROM usuario WHERE nombre = :nombre LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        
        // Ejecutar y manejar posibles errores
        try {
            $stmt->execute();

            // Verificar si existe al menos un resultado
            if ($stmt->rowCount() > 0) {
                return $stmt->fetch(PDO::FETCH_ASSOC); // Devolver los datos del usuario
            }
        } catch (PDOException $e) {
            echo "Error al obtener el usuario: " . $e->getMessage();
        }
        
        return false; // Retornar false si no se encuentra el usuario o ocurre un error
    }

    // Método para activar un usuario
    public function activarUsuario($nombre) {
        $query = "UPDATE usuario SET actividad = 1 WHERE nombre = :nombre";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $nombre);

        // Manejar posibles errores
        try {
            if ($stmt->execute()) {
                return true; // Retornar true si la activación fue exitosa
            }
        } catch (PDOException $e) {
            echo "Error al activar el usuario: " . $e->getMessage();
        }
        return false;
    }

    // Método para desactivar un usuario
    public function desactivarUsuario($nombre) {
        $query = "UPDATE usuario SET actividad = 0 WHERE nombre = :nombre";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $nombre);

        // Manejar posibles errores
        try {
            if ($stmt->execute()) {
                return true; // Retornar true si la desactivación fue exitosa
            }
        } catch (PDOException $e) {
            echo "Error al desactivar el usuario: " . $e->getMessage();
        }
        return false;
    }

    // Método para verificar el inicio de sesión
    public function verificarLogin($nombre, $password) {
        // Obtener los datos del usuario
        $usuario = $this->obtenerUsuarioPorNombre($nombre);

        // Verificar que el usuario exista y que esté activo
        if ($usuario) {
            if ($usuario['actividad'] == 1) {
                // Comparar la contraseña encriptada
                if (password_verify($password, $usuario['password'])) {
                    return $usuario; // Login exitoso, devolver datos del usuario
                }
            }
        }

        return false; // Login fallido o usuario inactivo
    }
}
?>
