<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/preview.css">
    <title>Preview <!-- P_NAME --></title>
</head>

<body>
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

    $id_presentacion = $_POST['id_presentacion'];
    $data_presentacion = $db->getDiapositivas($conn, $id_presentacion);

    $queryInfoPresentacion = "SELECT p.titulo as titulo, p.descripcion as descripcion
        FROM presentacion p
        WHERE p.id = ?;";
    $queryDataDiapositivaActual

    // $data_presentacion = $data_presentacion->fetch(PDO::FETCH_ASSOC);

    $diapositiva_actual = $data_presentacion['id'];
    $tipo_diapositiva_actual = $data_presentacion['tipo'];
    ?>
    <div id="diapositivas">
        <?php if ($tipo_diapositiva_actual === 'T') : ?>
            <?php ?>
            <div class="d-container">
                <p></p>
            </div>
        <?php else : ?>

            html code to run if condition is false

        <?php endif ?>
    </div>


    <template id="d_titulo_template">

    </template>
    <template id="d_titulo_texto_template">
        <div class="d-container">
            <p></p>
            <p></p>
        </div>
    </template>

</body>

</html>