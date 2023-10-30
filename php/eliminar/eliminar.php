<?php
session_start();

define('VALID_ENTRY_POINT', true);
include '../config.php';
require_once ROOT_PATH . 'php/clases/db.php';

//var_dump($_POST);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $presentation_id = $_POST['id_presentacion'];

    // Obtén la conexión a la base de datos
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

        // Establece la variable de sesión para indicar que la eliminación fue exitosa
        $_SESSION['eliminacion_exitosa'] = true;
        
        header("Location: ../../home.php");

    } catch (PDOException $e) {
        $conn->rollBack();
    }
}
?>
