<?php

// Punto de entrada principal
define('VALID_ENTRY_POINT', true);

include '../config.php';

// Incluir la clase de base de datos
require_once ROOT_PATH . 'php/clases/db.php';

// Obtener la única instancia de la base de datos
$db = Database::getInstance();

// Obtener la conexión a la base de datos
$conn = $db->getConnection();

// Recuperar el título y la descripción del formulario
$titol = isset($_POST['p_titulo']) ? trim($_POST['p_titulo']) : '';
$descripcio = isset($_POST['p_descripcion']) ? trim($_POST['p_descripcion']) : '';

// Validar el título de la presentación
if (empty($titol) || !preg_match("/^[<a-zA-Z>s]+$/", $titol)) {
    die("El título de la presentación no es válido.");
}

// Insertar la nueva presentación en la base de datos
$stmt = $conn->prepare("INSERT INTO Presentacions (Títol, Descripció) VALUES (?, ?)");
$stmt->bindParam(1, $titol);
$stmt->bindParam(2, $descripcio);
$stmt->execute();

// Obtener el ID de la presentación que acabamos de insertar
$presentacio_id = $conn->lastInsertId();

// Recuperar todos los tipos de diapositivas del formulario
$d_tipos = isset($_POST['d_tipo']) && is_array($_POST['d_tipo']) ? $_POST['d_tipo'] : array();

// Iterar sobre cada tipo de diapositiva
foreach ($d_tipos as $index => $tipo) {
    // Validar el título de las diapositivas
    $titulo_diapositiva = isset($_POST['d_titulo_' . $index]) ? trim($_POST['d_titulo_' . $index]) : '';
    if (empty($titulo_diapositiva) || !preg_match("/^[a-zA-Z\s]+$/", $titulo_diapositiva)) {
        die("El título de la diapositiva no es válido.");
    }

    // Insertar la diapositiva en la base de datos con su tipo
    $stmt = $conn->prepare("INSERT INTO Diapositives (presentació_id, Tipus) VALUES (?, ?)");
    $stmt->bindParam(1, $presentacio_id);
    $stmt->bindParam(2, $tipo);
    $stmt->execute();

    // Obtener el ID de la diapositiva que acabamos de insertar
    $diapositives_id = $conn->lastInsertId();

    // Comprobar el tipo de diapositiva y actuar en consecuencia
    if ($tipo == 'T') {
        // Si es de tipo 'T', recuperar el título y insertarlo en la tabla correspondiente
        $stmt = $conn->prepare("INSERT INTO Diapositives_T (diapositives_id, Titulo) VALUES (?, ?)");
        $stmt->bindParam(1, $diapositives_id);
        $stmt->bindParam(2, $titulo_diapositiva);
        $stmt->execute();
    } elseif ($tipo == 'TC') {
        // Si es de tipo 'TC', recuperar el título y el contenido y insertarlos en la tabla correspondiente
        $contenido = $_POST['d_contenido_' . $index];
        $stmt = $conn->prepare("INSERT INTO Diapositives_TC (diapositives_id, Titulo, Contenido) VALUES (?, ?, ?)");
        $stmt->bindParam(1, $diapositives_id);
        $stmt->bindParam(2, $titulo_diapositiva);
        $stmt->bindParam(3, $contenido);
        $stmt->execute();
    }
}

// Redirigir al usuario de vuelta a la página de creación de presentaciones
header("Location: /grup4-alfred_emilio_salma/html/crear_p.html");
exit;
