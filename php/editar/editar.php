<?php
// Definir el punto de entrada
define('VALID_ENTRY_POINT', true);

// Incluir archivo de configuración
include '../config.php';

// Incluir la clases.
require_once '../clases/db.php';
require_once '../clases/Presentacion.php';
require_once '../clases/Diapositiva.php';
require_once '../clases/TipoTitulo.php';
require_once '../clases/TipoContenido.php';

// Obtener la única instancia de la base de datos
$db = Database::getInstance();

// Obtener la conexión a la base de datos
$conn = $db->getConnection();
?>

<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../../css/editar.css" />
    <title>Nueva Presentación</title>
</head>


<body>
    <div class="header">
        <input hidden type="text" form="data_p" name="presentacion_id" value="<?= $_POST['presentacion_id'] ?>">
        <input id="inputTitulo" type="text" form="data_p" class="input" name="p_titulo"
            value="<?= Presentacion::getDescripcionBD($conn, $_POST['presentacion_id']) ?>"
            placeholder="Añade un título..." required autocomplete="off" />
        <div class="headerButtons">
            <div class="descripcion-container">
                <div id="icon-presentaciones">
                    <img src="../../icons/presentacio.svg" alt="Icono Presentación" />
                </div>
                <form method="POST" id="data_p" action="editar.controller.php">
                    <input type="text" class="input focus" name="p_descripcion"
                        value="<?= Presentacion::getTituloBD($conn, $_POST['presentacion_id']) ?>"
                        placeholder="Escribe una descripción..." autocomplete="off" />
                </form>
            </div>
            <div id="nova-diapositiva">
                <div class="dropdown" onclick="showDropdown()">
                    <button id="nova-diapositiva-button" class="button">
                        <img id="nueva-diapositiva" src="../../icons/add.svg" />
                    </button>
                    <div class="dropdown-content">
                        <span onclick="newDiapositivaTitulo()">Título</span>
                        <span onclick="newDiapositivaTituloTexto()">Título + Texto</span>
                    </div>
                </div>
                <span>Añadir diapositiva</span>
            </div>
            <div class="actionButtons">
                <a href="../../home.php">
                    <button class="button">Cancelar</button>
                </a>
                <button class="button" type="submit" form="data_p">Guardar</button>
            </div>
        </div>
    </div>

    <div id="diapositivas">
        <template id="d_titulo_template">
            <div class="d-container">
                <input class="focus" type="text" form="data_p" autocomplete="off"
                    placeholder="Haz click para añadir un título..." />
            </div>
        </template>
        <template id="d_titulo_texto_template">
            <div class="d-container">
                <input class="focus" type="text" form="data_p" autocomplete="off"
                    placeholder="Haz click para añadir un título..." />
                <textarea class="focus" form="data_p" autocomplete="off"
                    placeholder="Haz click para añadir un texto"></textarea>
            </div>
        </template>

        <?php
        $presentacion = Presentacion::getPresentacionBD($conn, $_POST['presentacion_id']);
        $diapositivas = $presentacion->getDiapositivas();

        foreach ($diapositivas as $diapositiva) {
            echo $diapositiva->getDiapositivaHTML();
        }
        ?>
    </div>
    <script src="../../js/crear_p.js"></script>
    <script src="../../js/save_p.js"></script>
</body>

</html>