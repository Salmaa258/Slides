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

if (!isset($_POST['presentacion_id'])) {
    $presentacionBD = Presentacion::getPresentacionBD($conn, $_POST['presentacion_id']);
    $id_presentacion = $presentacionBD->getId();

    foreach ($presentacionBD->getDiapositivas() as $diapositiva) {
        if ($diapositiva->existsDiapositiva($conn, $id_presentacion, $diapositiva->getId())) {
            $titulo = $_POST['d_titulo_' . $diapositiva->getId() . ''];
            $contenido = $_POST['d_contenido_' . $diapositiva->getId() . ''];

            if (isset($contenido)) {
                $diapositiva->setTitulo($diapositiva->getContenido() !== $contenido ? $contenido : $diapositiva->getContenido());
            }

            if (isset($titulo)) {
                $diapositiva->setDiapositiva($diapositiva->getTitulo() !== $titulo ? $titulo : $diapositiva->getTitulo());
            }

            $diapositiva->actualizaDiapositiva($conn, $id_presentacion);
        }
    }

} else {
    $titulo = isset($_POST['p_titulo']) ? trim($_POST['p_titulo']) : '';
    $descripcion = isset($_POST['p_descripcion']) ? trim($_POST['p_descripcion']) : '';

    $presentacio_id = $conn->lastInsertId();

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

    Presentacion::nuevaPresentacionBD($conn, $titulo, $descripcion, $diapositivas);

}




// Redirigir al usuario de vuelta a la página de creación de presentaciones
header("Location: /../../html/crear_p.html");
exit;

