<?php
require_once "config.php"; // Asegúrate de que esta ruta sea correcta
require_once "modPersonas.php"; // Cambié a modPersona para coincidir con el nombre correcto del modelo

// Controlador de Persona
class ctrlPersonas {
    private $db;
    private $persona;

    public function __construct() {
        // Inicializar la conexión a la base de datos
        $database = new Database();
        $this->db = $database->getConnection();
        $this->persona = new modPersonas($this->db); // Asegúrate de que modPersona esté bien definido
    }

    // Método para registrar una nueva persona
    public function registrarPersona($nombres, $a_paterno, $a_materno, $correo, $fecha_nacimiento) {
        // Validar los datos antes de intentar registrar
        if (empty($nombres) || empty($a_paterno) || empty($correo) || empty($fecha_nacimiento)) {
            echo "<div class='alert alert-danger'>Error: Todos los campos obligatorios deben ser llenados.</div>";
            return false;
        }

        // Asignar valores a la clase modPersona
        $this->persona->nombres = $nombres;
        $this->persona->a_paterno = $a_paterno;
        $this->persona->a_materno = $a_materno;
        $this->persona->correo = $correo;
        $this->persona->fecha_nacimiento = $fecha_nacimiento;

        // Intentar registrar la persona
        try {
            // Registrar la persona y obtener el ID
            $persona_id = $this->persona->registrar(); // Cambié el return para obtener el ID directamente

            if ($persona_id) {
                echo "<div class='alert alert-success'>Persona registrada con éxito. ID Persona: $persona_id</div>";
                return $persona_id; // Retorna el ID de la persona registrada
            } else {
                echo "<div class='alert alert-danger'>Error: No se pudo registrar la persona. Verifica los datos.</div>";
                return false;
            }
        } catch (PDOException $e) {
            // Mostrar mensaje de error detallado en caso de excepción
            echo "<div class='alert alert-danger'>Error al registrar la persona: " . $e->getMessage() . "</div>";
            return false;
        }
    }
}
?>