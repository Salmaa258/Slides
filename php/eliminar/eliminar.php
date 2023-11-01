<?php
session_start();

// Punto de entrada principal
define('VALID_ENTRY_POINT', true);
include '../config.php';

require_once ROOT_PATH . 'php/clases/db.php';
require_once ROOT_PATH . 'php/clases/Presentacion.php';
require_once ROOT_PATH . 'php/clases/Diapositiva.php';
require_once ROOT_PATH . 'php/clases/TipoTitulo.php';
require_once ROOT_PATH . 'php/clases/TipoContenido.php';

// Incluir la clase de base de datos
require_once ROOT_PATH . 'php/clases/db.php';

// Obtener la única instancia de la base de datos
$db = Database::getInstance();

// Obtener la conexión a la base de datos
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $presentation_id = $_POST['id_presentacion'];

    Presentacion::eliminarPresentacionBD($conn, $presentation_id);

    // Establece la variable de sesión para indicar que la eliminación fue exitosa
    $_SESSION['eliminacion_exitosa'] = true;
}

header("Location: ../../home.php");
?>