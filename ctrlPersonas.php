<?php
require_once "config.php";
require_once "modPersonas.php";

class ctrlPersonas {
    private $db;
    private $persona;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->persona = new modPersonas($this->db);
    }

    public function registrarPersona($nombres, $a_paterno, $a_materno, $correo, $fecha_nacimiento) {
        if (empty($nombres) || empty($a_paterno) || empty($correo) || empty($fecha_nacimiento)) {
            echo "<div class='alert alert-danger'>Error: Todos los campos obligatorios deben ser llenados.</div>";
            return false;
        }

        $this->persona->nombres = $nombres;
        $this->persona->a_paterno = $a_paterno;
        $this->persona->a_materno = $a_materno;
        $this->persona->correo = $correo;
        $this->persona->fecha_nacimiento = $fecha_nacimiento;

        try {
            $persona_id = $this->persona->registrar();

            if ($persona_id) {
                echo "<div class='alert alert-success'>Persona registrada con éxito. ID Persona: $persona_id</div>";
                return $persona_id;
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
