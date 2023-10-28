<?php
define('VALID_ENTRY_POINT', true);
include '../config.php';
require_once ROOT_PATH . 'php/clases/db.php';
require_once ROOT_PATH . 'php/clases/Presentacion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $presentation_id = $_POST['presentation_id'];

    $db = Database::getInstance();
    $conn = $db->getConnection();

    Presentacion::eliminarPresentacionBD($conn, $presentation_id);
}

header("Location: /../../home.php");
exit;
