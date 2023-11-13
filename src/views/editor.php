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
require_once '../models/TipoImagen.php';

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
$url = 'null';
$icon = 'publicada';
$copy = 'flex';
$lastDiapositivaId = 0;
$diapositivas = null;

if (isset($_GET['presentacion_id'])) {
    $presentacion = Presentacion::getPresentacionBD($conn, $_GET['presentacion_id']);
    $id_presentacion = $presentacion->getId();
    $titulo = $presentacion->getTitulo();
    $descripcion = $presentacion->getDescripcion();
    $lastDiapositivaId = $presentacion->getLastDiapositivaId($conn);
    $tema = $presentacion->getTema();
    $url = $presentacion->getUrl();
    $diapositivas = $presentacion->getDiapositivas();
    $disabled = '';
}

if ($url === 'null') {
    $icon = 'noPublicada';
    $copy = 'none';
}
?>

<body>
    <div class="header">
        <input hidden <?= $disabled ?> type="text" form="data_p" name="presentacion_id" value="<?= $id_presentacion ?>">
        <input hidden type="text" form="data_p" name="tema" value="<?= $tema ?>">
        <input id="inputTitulo" type="text" form="data_p" class="input" maxlength="30" name="p_titulo"
            value="<?= $titulo ?>" placeholder="Añade un título..." required autocomplete="off" />
        <div class="headerButtons">
            <div class="descripcion-container">
                <div id="icon-presentaciones">
                    <a href="home.php">
                        <img src="../assets/icons/home.svg" alt="Icono Presentación" />
                    </a>
                </div>
                <form method="POST" id="data_p" enctype="multipart/form-data" action="../controllers/editor.controller.php">
                    <input type="text" class="input focus" maxlength="150" name="p_descripcion" value="<?= $descripcion ?>"
                        placeholder="Escribe una descripción..." autocomplete="off" />
                    <input type="hidden" name="ordenDiapositivas" id="ordenDiapositivas" value="">
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
                        <span onclick="mostrarConfirmacionNuevaDiapositivaTituloTextoImagen(event)">Título + Texto + Imagen</span>

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
                <span>Tema</span>
            </div>
            <form id="preview_form" action="preview.php" method="POST">
                <input hidden type="text" name="id_presentacion" value="<?= $id_presentacion ?>">
                <input hidden type="text" name="diapositiva_id" value="">
                <button class="button" type="submit">
                    <img src="../assets/icons/presentacion.svg" alt="Icono Presentación" />
                </button>
            </form>
            <div id="publicar_button">
                <input hidden type="text" name="url" form="data_p" value="<?= $url ?>">
                <button class="button" type="submit" form="data_p">
                    <img src="../assets/icons/<?= $icon ?>.svg" alt="Publicar Presentacion" />
                </button>
                <button id="copyUrlButton" class="button" style="display:<?= $copy ?>;">
                    <img src="../assets/icons/copy.svg" alt="Copiar URL" />
                </button>
            </div>
            <div class="actionButtons">
                <button id="btn-guardar" class="button" type="submit" form="data_p">Guardar</button>
            </div>
        </div>
    </div>
    <div class="content-wrapper">
        <div class="white-list">
            <div class="white-list-header">Diapositivas</div>
            <ul class="white-list-items">
            <li>Titulo 1</li>
            </ul>
        </div>
        <div id="diapositivas" tema="<?= $tema ?>" lastDiapositivaId="<?= $lastDiapositivaId ?>">
            <template id="d_titulo_template">
                <div class="d-container">
                    <div class="delete-slide-icon">
                        <img src="../assets/icons/eliminar.svg" alt="Eliminar Diapositiva" onclick="confirmDelete(event, this.closest('.d-container'))">
                    </div>
                    <input class="focus" type="text" form="data_p" maxlength="128" autocomplete="off"
                        placeholder="Haz click para añadir un título..." />
                </div>
            </template>
            <template id="d_titulo_texto_template">
                <div class="d-container">
                    <div class="delete-slide-icon">
                        <img src="../assets/icons/eliminar.svg" alt="Eliminar Diapositiva" onclick="confirmDelete(event, this.closest('.d-container'))">
                    </div>
                    <input class="focus" type="text" form="data_p" maxlength="128" autocomplete="off"
                        placeholder="Haz click para añadir un título..." />
                    <textarea class="focus" form="data_p" maxlength="1280" autocomplete="off"
                        placeholder="Haz click para añadir un texto"></textarea>
                </div>
            </template>
            <template id="d_titulo_texto_imagen_template">
                    <div class="d-containerImagen">
                        <div class="delete-slide-icon">
                            <img src="../assets/icons/eliminar.svg" alt="Eliminar Diapositiva" onclick="confirmDelete(event, this.closest('.d-containerImagen'))">
                        </div>
                        <input class="focus" type="text" form="data_p" autocomplete="off"
                            placeholder="Haz click para añadir un título..." />
                        <div class="d-containerImgText">
                            <textarea class="focus" form="data_p" autocomplete="off"
                                placeholder="Haz click para añadir un texto"></textarea>
                            <input class="imagen" type="file" form="data_p" name="d_imagen_"
                                accept="image/jpeg, image/png, image/jpg" />
                        </div>
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
            <button id="btn-aceptar-exito">Aceptar</button>
        </form>
    </dialog>

    <dialog id="confirmarEliminar">
        <p>¿Quieres eliminar la diapositiva?</p>
        <form method="dialog">
            <button id="btn-aceptar" onclick="eliminarDiapositiva()">Aceptar</button>
            <button id="btn-cancelar" onclick="cerrarConfirmacion()">Cancelar</button>
        </form>
    </dialog>

    <dialog id="exito_eliminar">
        <p>Se ha eliminado la diapositiva correctamente</p>
        <form method="dialog">
            <button id="btn-aceptar-exito" onclick="cerrarExito()">Aceptar</button>
        </form>
    </dialog>

    <dialog id="error_eliminar">
        <p>No se puede eliminar la diapositiva, no puede existir una peresentacion sin diapositivas.</p>
        <form method="dialog">
            <button id="btn-aceptar-error" onclick="cerrarError()">Aceptar</button>
        </form>
    </dialog>


    <?php
    // Verificar si la presentación ha sido cargada de la BD
    if ($id_presentacion !== null) {
        echo '<script src="../js/presentaciones_bd/lista_bd.js"></script>';
        echo '<script src="../js/presentaciones_bd/eliminar_d_bd.js"></script>';
        echo '<script src="../js/presentaciones_bd/editor_bd.js"></script>';
    } else {
        echo '<script src="../js/presentaciones/lista.js"></script>';
        echo '<script src="../js/presentaciones/eliminar_d.js"></script>';
        echo '<script src="../js/presentaciones/editor.js"></script>';
    }
    ?>

    <script>
        // Función para mostrar el diálogo de éxito al guardar
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
            const btnAceptarExito = document.getElementById("btn-aceptar-exito");
            btnAceptarExito.addEventListener("click", function () {
                // Oculta el diálogo de éxito
                exitoGuardarDialog.style.display = "none";
                overlay.style.display = "none";
            });
        }
        // Llama a la función mostrarExitoGuardar al cargar la página
        mostrarExitoGuardar();
    </script>


</body>

</html>