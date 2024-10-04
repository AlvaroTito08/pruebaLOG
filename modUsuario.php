<?php
class modUsuario {
    private $conn;

    public $nombre;
    public $password;
    public function __construct($db) {
        $this->conn = $db;
    }

    // Método para registrar un nuevo usuario
    public function registrar($persona_idPersona) {
        $query = "INSERT INTO usuario (nombre, password, actividad, persona_idPersona) 
                  VALUES (:nombre, :password, 1, :persona_idPersona)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $this->nombre);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':persona_idPersona', $persona_idPersona);
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
        $query = "SELECT * FROM usuario WHERE nombre = :nombre LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        try {
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            echo "Error al obtener el usuario: " . $e->getMessage();
        }
        
        return false; 
    }

    // Método para activar un usuario
    public function activarUsuario($nombre) {
        $query = "UPDATE usuario SET actividad = 1 WHERE nombre = :nombre";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        try {
            if ($stmt->execute()) {
                return true; 
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
        try {
            if ($stmt->execute()) {
                return true;
            }
        } catch (PDOException $e) {
            echo "Error al desactivar el usuario: " . $e->getMessage();
        }
        return false;
    }

    // Método para verificar el inicio de sesión
    public function verificarLogin($nombre, $password) {
        $usuario = $this->obtenerUsuarioPorNombre($nombre);

        // Verificar que el usuario exista y que esté activo
        if ($usuario) {
            if ($usuario['actividad'] == 1) {
                if (password_verify($password, $usuario['password'])) {
                    return $usuario; 
                }
            }
        }

        return false;
    }
}
?>
