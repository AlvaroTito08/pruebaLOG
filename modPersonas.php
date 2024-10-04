<?php
class modPersonas {
    private $conn;

    public $nombres;
    public $a_paterno;
    public $a_materno;
    public $correo;
    public $fecha_nacimiento;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function registrar() {
        $query = "INSERT INTO persona (nombres, a_paterno, a_materno, correo, fecha_nacimiento) 
                  VALUES (:nombres, :a_paterno, :a_materno, :correo, :fecha_nacimiento)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':nombres', $this->nombres);
        $stmt->bindParam(':a_paterno', $this->a_paterno);
        $stmt->bindParam(':a_materno', $this->a_materno);
        $stmt->bindParam(':correo', $this->correo);
        $stmt->bindParam(':fecha_nacimiento', $this->fecha_nacimiento);

        try {
            if ($stmt->execute()) {
                return $this->conn->lastInsertId();
            } else {
                echo "<div class='alert alert-danger'>Error: No se pudo registrar la persona. Verifica los datos.</div>";
                return false;
            }
        } catch (PDOException $e) {
            echo "<div class='alert alert-danger'>Error al registrar la persona: " . $e->getMessage() . "</div>";
            return false;
        }
    }
}
?>
