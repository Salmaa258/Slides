<?php

// Punto de entrada principal
define('VALID_ENTRY_POINT', true);

include '../config.php';

require_once ROOT_PATH . 'php/clases/db.php';
require_once ROOT_PATH . 'php/clases/Presentacion.php';
require_once ROOT_PATH . 'php/clases/Diapositiva.php';
require_once ROOT_PATH . 'php/clases/TipoTitulo.php';
require_once ROOT_PATH . 'php/clases/TipoContenido.php';

$db = Database::getInstance();
$conn = $db->getConnection();

if (isset($_POST['presentacion_id'])) {
    $presentacionBD = Presentacion::getPresentacionBD($conn, $_POST['presentacion_id']);
    $id_presentacion = $presentacionBD->getId();

    $presentacionBD->setTitulo($_POST['p_titulo']);
    $presentacionBD->setDescripcion($_POST['p_descripcion']);

    $presentacionBD->actualizarInfo($conn);

    $lastDiapositivaId = $presentacionBD->getLastDiapositivaId($conn) + 1;

    foreach ($presentacionBD->getDiapositivas() as $diapositiva) {
        if (isset($_POST['d_contenido_' . $diapositiva->getId() . ''])) {
            $diapositiva->setContenido($_POST['d_contenido_' . $diapositiva->getId() . '']);
        }

        if (isset($_POST['d_titulo_' . $diapositiva->getId() . ''])) {
            $diapositiva->setTitulo($_POST['d_titulo_' . $diapositiva->getId() . '']);

            $diapositiva->actualizaDiapositiva($conn, $id_presentacion);
        } else {
            $diapositiva->eliminarDiapositivaBD($conn, $id_presentacion, $diapositiva->getId());
        }
    }

    while (isset($_POST['d_titulo_' . $lastDiapositivaId])) {
        if (isset($_POST['d_contenido_' . $lastDiapositivaId . ''])) {
            $contenido = $_POST['d_contenido_' . $lastDiapositivaId];
            $newDiapositiva = new TipoContenido(null, '', $contenido);
        } else {
            $newDiapositiva = new TipoTitulo(null, '');
        }

        $titulo = $_POST['d_titulo_' . $lastDiapositivaId];
        $newDiapositiva->setTitulo($titulo);

        $newDiapositiva->nuevaDiapositiva($conn, $id_presentacion);

        $lastDiapositivaId++;
    }
} else {
    $titulo = isset($_POST['p_titulo']) ? trim($_POST['p_titulo']) : '';
    $descripcion = isset($_POST['p_descripcion']) ? trim($_POST['p_descripcion']) : '';

    $diapositivas = [];

    $count_diapositiva = 0;

    while (isset($_POST['d_titulo_' . $count_diapositiva])) {
        if (isset($_POST['d_contenido_' . $count_diapositiva])) {
            array_push(
                $diapositivas,
                new TipoContenido(
                    $count_diapositiva,
                    $_POST['d_titulo_' . $count_diapositiva],
                    $_POST['d_contenido_' . $count_diapositiva]
                )
            );
        } else {
            array_push(
                $diapositivas,
                new TipoTitulo(
                    $count_diapositiva,
                    $_POST['d_titulo_' . $count_diapositiva]
                )
            );
        }
        $count_diapositiva++;
    }

    $newPresentacion = new Presentacion(null, $titulo, $descripcion, $diapositivas);

    $newPresentacion->nuevaPresentacion($conn);

}

// Redirigir al usuario de vuelta a la página de creación de presentaciones
header("Location: /../../html/crear_p.html");
exit;

