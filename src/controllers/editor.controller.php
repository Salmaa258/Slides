<?php
session_start();

// Punto de entrada principal
define('VALID_ENTRY_POINT', true);

include '../../config.php';

require_once '../models/db.php';
require_once '../models/Presentacion.php';
require_once '../models/Diapositiva.php';
require_once '../models/TipoTitulo.php';
require_once '../models/TipoContenido.php';

$db = Database::getInstance();
$conn = $db->getConnection();
$id_presentacion;

if (isset($_POST['presentacion_id'])) {
    // Edición de una presentación existente
    $presentacionBD = Presentacion::getPresentacionBD($conn, $_POST['presentacion_id']);
    $id_presentacion = $presentacionBD->getId();

    // Actualiza el título, descripción y tema de la presentación
    $presentacionBD->setTitulo($_POST['p_titulo']);
    $presentacionBD->setDescripcion($_POST['p_descripcion']);
    $presentacionBD->setTema($_POST['tema']);
    $presentacionBD->actualizarInfo($conn);

    $lastDiapositivaId = $presentacionBD->getLastDiapositivaId($conn) + 1;

    foreach ($presentacionBD->getDiapositivas() as $diapositiva) {
        // Actualiza el contenido de diapositivas existentes si se proporciona
        if (isset($_POST['d_contenido_' . $diapositiva->getId() . ''])) {
            $diapositiva->setContenido($_POST['d_contenido_' . $diapositiva->getId() . '']);
        }

        // Actualiza el título de diapositivas existentes o las elimina si no se proporciona un título
        if (isset($_POST['d_titulo_' . $diapositiva->getId() . ''])) {
            $diapositiva->setTitulo($_POST['d_titulo_' . $diapositiva->getId() . '']);
            $diapositiva->actualizaDiapositiva($conn, $id_presentacion);
        } else {
            $diapositiva->eliminarDiapositiva($conn, $id_presentacion);
        }
    }

    // Agrega nuevas diapositivas si se proporciona un título para ellas en el formulario
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
    // Creación de una nueva presentación
    $titulo = isset($_POST['p_titulo']) ? trim($_POST['p_titulo']) : '';
    $descripcion = isset($_POST['p_descripcion']) ? trim($_POST['p_descripcion']) : '';
    $tema = $_POST['tema'];

    $diapositivas = [];

    $count_diapositiva = 0;

    // Crea diapositivas basadas en los valores proporcionados en el formulario
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

    // Crea una nueva presentación y la guarda en la base de datos
    $newPresentacion = new Presentacion(null, $titulo, $descripcion, $tema, $diapositivas);
    $newPresentacion->guardarNuevaPresentacion($conn);
    $id_presentacion = $newPresentacion->getId();

    // Establece la variable de sesión para indicar que la creación fue exitosa
    $_SESSION['guardado_exitoso'] = true;
    
}

// Redirigir al usuario de vuelta a la página de creación de presentaciones
header("Location: ../views/editor.php?presentacion_id=" . $id_presentacion);
exit;
