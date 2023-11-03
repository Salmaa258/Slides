<?php
// Definir el punto de entrada
define('VALID_ENTRY_POINT', true);

// Incluir archivo de configuración
include '../../config.php';

// Incluir la clases.
require_once '../models/db.php';
require_once '../models/Presentacion.php';
require_once '../models/Diapositiva.php';
require_once '../models/TipoTitulo.php';
require_once '../models/TipoContenido.php';

// Obtener la única instancia de la base de datos
$db = Database::getInstance();

// Obtener la conexión a la base de datos
$conn = $db->getConnection();

$presentacion = Presentacion::getPresentacionBD($conn, $_POST['id_presentacion']);
$tema = $presentacion->getTema();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/preview.css">
    <title>Previsualización</title>
</head>

<body tema="<?= $tema ?>">
    <div id="diapositivaAnterior">
        <button onclick="anterior()"><img src="../assets/icons/leftArrow.svg"></button>
    </div>
    <div id="diapositivaPosterior">
        <button onclick="siguiente()"><img src="../assets/icons/rightArrow.svg"></button>
    </div>
    <div id="diapositivas">
        <?php
        $diapositivas = $presentacion->getDiapositivas();
        foreach ($diapositivas as $diapositiva) {
            echo $diapositiva->getDiapositivaPreview();
        }
        ?>
    </div>
    <script src="../js/preview.js"></script>
</body>

</html>