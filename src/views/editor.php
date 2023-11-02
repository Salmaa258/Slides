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
?>

<!DOCTYPE html>
<html lang="ca">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="../assets/css/editor.css" />
    <title>Editor de Presentaciones</title>
</head>

<!-- Capa de superposición -->
<div class="overlay" id="overlay"></div>


<?php
session_start();

$presentacion = null;
$id_presentacion = null;
$disabled = 'disabled';
$titulo = null;
$descripcion = null;
$tema = 'oscuro';
$lastDiapositivaId = 0;
$diapositivas = null;

if (isset($_GET['presentacion_id'])) {
    $presentacion = Presentacion::getPresentacionBD($conn, $_GET['presentacion_id']);
    $id_presentacion = $presentacion->getId();
    $titulo = $presentacion->getTitulo();
    $descripcion = $presentacion->getDescripcion();
    $lastDiapositivaId = $presentacion->getLastDiapositivaId($conn);
    $tema = $presentacion->getTema();
    $diapositivas = $presentacion->getDiapositivas();
    $disabled = '';
}

?>

<body>
    <div class="header">
        <input hidden <?= $disabled ?> type="text" form="data_p" name="presentacion_id" value="<?= $id_presentacion ?>">
        <input hidden type="text" form="data_p" name="tema" value="<?= $tema ?>">
        <input id="inputTitulo" type="text" form="data_p" class="input" name="p_titulo" value="<?= $titulo ?>"
            placeholder="Añade un título..." required autocomplete="off" />
        <div class="headerButtons">
            <div class="descripcion-container">
                <div id="icon-presentaciones">
                    <img src="../assets/icons/presentacio.svg" alt="Icono Presentación" />
                </div>
                <form method="POST" id="data_p" action="../controllers/editor.controller.php">
                    <input type="text" class="input focus" name="p_descripcion" value="<?= $descripcion ?>"
                        placeholder="Escribe una descripción..." autocomplete="off" />
                </form>
            </div>
            <div id="nova-diapositiva">
                <div class="dropdown">
                    <button id="nova-diapositiva-button" class="button" onclick="showDropdown(event)">
                        <img id="nueva-diapositiva" src="../assets/icons/add.svg" />
                    </button>
                    <div class="dropdown-content">
                        <span onclick="mostrarConfirmacionNuevaDiapositivaTitulo(event)">Título</span>
                        <span onclick="mostrarConfirmacionNuevaDiapositivaTituloTexto(event)">Título + Texto</span>
                    </div>
                </div>
                <span>Añadir diapositiva</span>
            </div>
            <div id="tema-seleccion">
                <div class="dropdown">
                    <button id="tema-button" class="button" onclick="showDropdown(event)">
                        <img id="icono-tema" src="../assets/icons/white_black_box.svg" />
                    </button>
                    <div class="dropdown-content">
                        <span onclick="setClaro()"><img id="icono-tema" src="../assets/icons/white.svg" />Claro</span>
                        <span onclick="setOscuro()"><img id="icono-tema" src="../assets/icons/black.svg" />Oscuro</span>
                    </div>
                </div>
                <span>Seleccionar Tema</span>
            </div>
            <div class="actionButtons">
                <a href="home.php">
                    <button class="button">Cancelar</button>
                </a>
                <button id="btn-guardar" class="button" type="submit" form="data_p">Guardar</button>
            </div>
        </div>
    </div>

    <div id="diapositivas" tema="<?= $tema ?>" lastDiapositivaId="<?= $lastDiapositivaId ?>">
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
        if ($lastDiapositivaId !== 0) {
            foreach ($diapositivas as $diapositiva) {
                echo $diapositiva->getDiapositivaHTML();
            }
        }
        ?>
    </div>


    <!--Contenedores para la funcionalidad de feedback-->
    <dialog id="confirmarGuardar">
        <p>¿Quieres guardar tu progreso?</p>
        <form method="dialog">
            <button id="btn-aceptar" type="submit" form="data_p">Aceptar</button>
            <button id="btn-cancelar">Cancelar</button>
        </form>
    </dialog>

    <dialog id="exito_guardar">
        <p>Se ha guardado la presentación correctamente</p>
        <form method="dialog">
            <button id="btn-aceptar-exito" type="submit" form="data_p">Aceptar</button>
        </form>
    </dialog>

    <script src="../js/editor.js"></script>

    <script>
        mostrarExitoGuardar();
        // Función para mostrar el diálogo de éxito al eliminar
        function mostrarExitoGuardar() {
            const exitoGuardarDialog = document.getElementById("exito_guardar");
            const overlay = document.getElementById("overlay");
            <?php
            if (isset($_SESSION['guardado_exitoso']) && $_SESSION['guardado_exitoso'] === true) {
                echo 'exitoGuardarDialog.style.display = "block";';
                echo 'overlay.style.display = "block";';
                // Elimina la variable de sesión para evitar que se muestre nuevamente al recargar la página.
                unset($_SESSION['guardado_exitoso']);
            }
            ?>

            // Agrega un event listener al botón "Aceptar" en el diálogo de éxito
            const btnExitoAceptar = document.getElementById("btn-aceptar-exito");
            btnExitoAceptar.addEventListener("click", function () {
                // Oculta el diálogo de éxito
                exitoGuardarDialog.style.display = "none";
                overlay.style.display = "none";

            });
        }
    </script>
</body>

</html>