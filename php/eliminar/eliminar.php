<?php
define('VALID_ENTRY_POINT', true);
include '../config.php';
require_once ROOT_PATH . 'php/clases/db.php';

var_dump($_POST);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $presentation_id = $_POST['presentation_id'];

    // Conexión a la base de datos
    $db = Database::getInstance();
    $conn = $db->getConnection();

    try {
        $conn->beginTransaction();

        // Finalmente, elimina la presentación de la tabla Presentacions
        $query = "DELETE FROM presentacion WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(1, $presentation_id);
        $stmt->execute();

        $conn->commit();
        echo 'La presentación y las diapositivas asociadas se han eliminado correctamente.';
    } catch (PDOException $e) {
        $conn->rollBack();
        echo 'Error al eliminar la presentación y las diapositivas: ' . $e->getMessage();
    }
}
?>
