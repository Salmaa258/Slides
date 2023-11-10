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
require_once '../models/TipoImagen.php';

$db = Database::getInstance();
$conn = $db->getConnection();
$id_presentacion;

// Función para verificar y crear la carpeta de imágenes
function createImagesFolder()
{
    if (!file_exists('../imagenes')) {
        mkdir('../imagenes', 0777, true);
    }
}

if (isset($_POST['presentacion_id'])) {
    // Edición de una presentación existente
    $presentacionBD = Presentacion::getPresentacionBD($conn, $_POST['presentacion_id']);
    $id_presentacion = $presentacionBD->getId();

    // Actualiza el título, descripción, tema y url de la presentación
    $presentacionBD->setTitulo($_POST['p_titulo']);
    $presentacionBD->setDescripcion($_POST['p_descripcion']);
    $presentacionBD->setTema($_POST['tema']);
    $presentacionBD->setUrl($_POST['url']);
    $presentacionBD->actualizarInfo($conn);

    $lastDiapositivaId = $presentacionBD->getLastDiapositivaId($conn) + 1;

    foreach ($presentacionBD->getDiapositivas() as $diapositiva) {
        // Actualiza el contenido de diapositivas existentes si se proporciona
        if (isset($_POST['d_imagen_' . $diapositiva->getId() . ''])) {
            $diapositiva->setNombre_imagen($_POST['d_imagen_' . $diapositiva->getId() . '']);
        }

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
        $titulo = $_POST['d_titulo_' . $lastDiapositivaId];
        $contenido = $_POST['d_contenido_' . $lastDiapositivaId];

        // Verifica si se ha subido una imagen
        if (isset($_FILES['d_imagen_' . $lastDiapositivaId])) {
            $imagen = $_FILES['d_imagen_' . $lastDiapositivaId];
            $nombre_imagen = $imagen['name'];

            // Verifica que la imagen sea de tipo PNG o JPG
            $ext = pathinfo($nombre_imagen, PATHINFO_EXTENSION);
            if (in_array($ext, ['png', 'jpg', 'jpeg'])) {

                // Genera un nombre de archivo único para la imagen
                $unique_id = uniqid();
                $unique_id = substr($unique_id, -3);
                $nombre_imagen = $lastDiapositivaId . "a" . $unique_id . '.' . $ext;
                $ruta_imagen = '../imagenes/' . $nombre_imagen;
                $url_temp = $_FILES['d_imagen_' . $lastDiapositivaId];

                createImagesFolder();

                // Mueve la imagen al directorio de imágenes
                move_uploaded_file($url_temp['tmp_name'], $ruta_imagen);


                // $imagen_anterior = $diapositiva->getNombre_imagen();
                // if (!empty($imagen_anterior)) {
                //     // Elimina la imagen anterior del sistema de archivos
                //     $ruta_imagen_anterior = '../imagenes/' . $imagen_anterior;
                //     if (file_exists($ruta_imagen_anterior)) {
                //         unlink($ruta_imagen_anterior);
                //     }
                // }

                // Crea una nueva diapositiva con el nombre de la imagen
                $newDiapositiva = new TipoImagen(null, $titulo, $contenido, $nombre_imagen);
            }

            $newDiapositiva = new TipoImagen(null, $titulo, $contenido, $nombre_imagen);


        } else if (isset($_POST['d_contenido_' . $lastDiapositivaId])) {
            // Si no se proporcionó una imagen, crea una diapositiva sin imagen
            $newDiapositiva = new TipoContenido(null, $titulo, $contenido);

        } else {
            $newDiapositiva = new TipoTitulo(null, $titulo);
        }

        // Guarda la nueva diapositiva en la base de datos
        $newDiapositiva->nuevaDiapositiva($conn, $id_presentacion);

        $lastDiapositivaId++;
    }
} else {
    // Creación de una nueva presentación
    $titulo = isset($_POST['p_titulo']) ? trim($_POST['p_titulo']) : '';
    $descripcion = isset($_POST['p_descripcion']) ? trim($_POST['p_descripcion']) : '';
    $tema = $_POST['tema'];
    $url = $_POST['url'];

    $diapositivas = [];

    $count_diapositiva = 0;

    // Crea diapositivas basadas en los valores proporcionados en el formulario
    while (isset($_POST['d_titulo_' . $count_diapositiva])) {

        if (isset($_FILES['d_imagen_' . $count_diapositiva])) {
    
            $imagen = $_FILES['d_imagen_' . $count_diapositiva];
            $nombre_imagen = $imagen['name'];
    
            // Verifica que la imagen sea de tipo PNG o JPG
            $ext = pathinfo($nombre_imagen, PATHINFO_EXTENSION);
            if (in_array($ext, ['png', 'jpg', 'jpeg'])) {
                // Genera un nombre de archivo único para la imagen
                $unique_id = uniqid();
                $unique_id = substr($unique_id, -3);
                $nombre_imagen = $count_diapositiva . "a" . $unique_id . '.' . $ext;
                $ruta_imagen = '../imagenes/' . $nombre_imagen;
                $url_temp = $_FILES['d_imagen_' . $count_diapositiva];
    
                createImagesFolder();
    
                // Mueve la imagen al directorio de imágenes
                move_uploaded_file($url_temp['tmp_name'], $ruta_imagen);
    
                // Añade la diapositiva con imagen solo si hay imagen
                array_push(
                    $diapositivas,
                    new TipoImagen(
                        $count_diapositiva,
                        $_POST['d_titulo_' . $count_diapositiva],
                        $_POST['d_contenido_' . $count_diapositiva],
                        $nombre_imagen
                    )
                );
            }
            
            array_push(
                $diapositivas,
                new TipoImagen(
                    $count_diapositiva,
                    $_POST['d_titulo_' . $count_diapositiva],
                    $_POST['d_contenido_' . $count_diapositiva],
                    isset($nombre_imagen) ? $nombre_imagen : null
                )
            );
            
    
        } else if (isset($_POST['d_contenido_' . $count_diapositiva])) {
            // Añade la diapositiva con contenido solo si hay contenido
            array_push(
                $diapositivas,
                new TipoContenido(
                    $count_diapositiva,
                    $_POST['d_titulo_' . $count_diapositiva],
                    $_POST['d_contenido_' . $count_diapositiva]
                )
            );
        } else {
            // Añade la diapositiva con título solo si no hay ni imagen ni contenido
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
    $newPresentacion = new Presentacion(null, $titulo, $descripcion, $tema, $url, $diapositivas);
    $newPresentacion->guardarNuevaPresentacion($conn);
    $id_presentacion = $newPresentacion->getId();

    // Establece la variable de sesión para indicar que la creación fue exitosa
    $_SESSION['guardado_exitoso'] = true;

}

// Redirigir al usuario de vuelta a la página de creación de presentaciones
header("Location: ../views/editor.php?presentacion_id=" . $id_presentacion);
exit;

