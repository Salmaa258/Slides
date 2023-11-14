<?php
session_start();

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
    $imagesDirectory = '../imagenes';
    if (!file_exists($imagesDirectory)) {
        mkdir($imagesDirectory, 0777, true);
    }
    return $imagesDirectory;
}

function handleImageUpload($imagen)
{
    $ruta_imagen = createImagesFolder() . '/' . $imagen['name'];
    // Lógica para validar la imagen, cambiar el nombre, etc.
    move_uploaded_file($imagen['tmp_name'], $ruta_imagen);
    // Devuelve el nuevo nombre de la imagen
    return $imagen['name'];
}


if (isset($_POST['presentacion_id'])) {
    $id_presentacion = $_POST['presentacion_id'];
    $presentacionBD = Presentacion::getPresentacionBD($conn, $id_presentacion);

    $presentacionBD->setTitulo($_POST['p_titulo']);
    $presentacionBD->setDescripcion($_POST['p_descripcion']);
    $presentacionBD->setTema($_POST['tema']);
    $presentacionBD->actualizarInfo($conn);

    $ordenDiapositivas = isset($_POST['ordenDiapositivas']) ? explode(',', $_POST['ordenDiapositivas']) : [];

    // Obtén la lista de IDs de las diapositivas existentes en la base de datos para esta presentación
    $diapositivasExistentes = Diapositiva::obtenerIdsDiapositivasPorPresentacion($conn, $id_presentacion);

    // Compara y elimina las diapositivas que ya no existen
    foreach ($diapositivasExistentes as $idDiapositivaExistente) {
        if (!in_array($idDiapositivaExistente, $ordenDiapositivas)) {
            $diapositivaAEliminar = Diapositiva::getDiapositivaPorId($conn, $idDiapositivaExistente);
            if ($diapositivaAEliminar) {
                // Elimina la diapositiva y realiza cualquier otro proceso de eliminación necesario
                $mensaje = $diapositivaAEliminar->eliminarDiapositiva($conn, $id_presentacion);
                // Puedes manejar la respuesta, como mostrar un mensaje de éxito o error
            }
        }
    }

    foreach ($ordenDiapositivas as $orden => $idDiapositiva) {
        ++$orden; // Incrementa antes de usar
        if (strpos($idDiapositiva, 'new-') === false) {
            $editDiapositiva = Diapositiva::getDiapositivaPorId($conn, $idDiapositiva);
            if ($editDiapositiva) {
                $titulo = $_POST['d_titulo_' . $idDiapositiva] ?? '';
                $contenido = $_POST['d_contenido_' . $idDiapositiva] ?? '';
                $editDiapositiva->setTitulo($titulo);

                if ($editDiapositiva instanceof TipoContenido) {
                    $editDiapositiva->setContenido($contenido);
                } elseif ($editDiapositiva instanceof TipoImagen) {
                    if (isset($_FILES['d_imagen_' . $idDiapositiva])) {
                        $imagen = $_FILES['d_imagen_' . $idDiapositiva];
                        // Asumiendo que tienes una función para manejar la carga de la imagen
                        //$nombre_imagen = handleImageUpload($imagen);
                        $editDiapositiva->setNombre_imagen($imagen['name']);
                    }
                    $editDiapositiva->setContenido($contenido); // Si la imagen tiene contenido asociado
                }
                $editDiapositiva->setOrden($orden);
                $editDiapositiva->actualizarDiapositiva($conn, $id_presentacion);
                Diapositiva::actualizarOrdenDiapositiva($conn, $editDiapositiva->getId(), $orden);
            }
        } else {
            // Es una nueva diapositiva
            $tempId = str_replace('new-', '', $idDiapositiva);
            $titulo = $_POST['d_titulo_new-' . $tempId] ?? '';
            $contenido = $_POST['d_contenido_new-' . $tempId] ?? '';
            
            // Verifica si se ha subido una imagen
            if (isset($_FILES['d_imagen_new-' . $tempId])) {
                $imagen = $_FILES['d_imagen_new-' . $tempId];
                $nombre_imagen = $imagen['name'];
                
                $ext = pathinfo($nombre_imagen, PATHINFO_EXTENSION);
                if (in_array($ext, ['png', 'jpg', 'jpeg', 'webp'])) {

                    $unique_id = uniqid();
                    $unique_id = substr($unique_id, -3);
                    $nombre_imagen = "imagen_$tempId" . "_" . $unique_id . '.' . $ext;
                    $ruta_imagen = '../imagenes/' . $nombre_imagen;
                    //$url_temp = $_FILES['d_imagen_' . $tempId];
                    $url_temp = $_FILES['d_imagen_new-' . $tempId];

                    // $unique_id = uniqid();
                    // $unique_id = substr($unique_id, -3);
                    // $nombre_imagen = $tempId . "a" . $unique_id . '.' . $ext;
                    // $ruta_imagen = '../imagenes/' . $nombre_imagen;
                    // $url_temp = $_FILES['d_imagen_' . $tempId];

                    move_uploaded_file($url_temp['tmp_name'], $ruta_imagen);
                    
                    $nuevaDiapositiva = new TipoImagen(null, $titulo, $contenido, $nombre_imagen);
                } else {
                    $nuevaDiapositiva = new TipoImagen(null, $titulo, $contenido, 'null');
                }
                
            } elseif (!empty($contenido)) {
                $nuevaDiapositiva = new TipoContenido(null, $titulo, $contenido);
            } else {
                $nuevaDiapositiva = new TipoTitulo(null, $titulo);
            }
            //var_dump($nuevaDiapositiva);
            $nuevaDiapositiva->setOrden($orden);
            $nuevaDiapositiva->nuevaDiapositiva($conn, $id_presentacion);
        }
    }
} else {
    // Crear una nueva presentación
    $titulo = $_POST['p_titulo'] ?? '';
    $descripcion = $_POST['p_descripcion'] ?? '';
    $tema = $_POST['tema'] ?? '';
    $url = $_POST['url'];
    $diapositivas = [];

    $newPresentacion = new Presentacion(null, $titulo, $descripcion, $tema, $url, $diapositivas);
    $newPresentacion->guardarNuevaPresentacion($conn);
    $id_presentacion = $newPresentacion->getId();

    // Crear diapositivas para la nueva presentación
    $ordenDiapositivas = isset($_POST['ordenDiapositivas']) ? explode(',', $_POST['ordenDiapositivas']) : [];
    foreach ($ordenDiapositivas as $orden => $tempId) {
        $orden++; // Asegúrate de que el orden comience en 1
        $tempId = str_replace('new-', '', $tempId);
        $titulo = $_POST['d_titulo_new-' . $tempId] ?? '';
        $contenido = $_POST['d_contenido_new-' . $tempId] ?? '';

        // Verifica si se ha subido una imagen para la nueva diapositiva
        if (isset($_FILES['d_imagen_new-' . $tempId]) && $_FILES['d_imagen_new-' . $tempId]['error'] == UPLOAD_ERR_OK) {
            $imagen = $_FILES['d_imagen_new-' . $tempId];
            $nombre_imagen = $imagen['name'];
            $ext = pathinfo($nombre_imagen, PATHINFO_EXTENSION);

            if (in_array($ext, ['png', 'jpg', 'jpeg', 'webp'])) {
                // Genera un nombre de archivo único para la imagen
                $unique_id = uniqid('', true);
                $nombre_imagen = "imagen_$tempId" . "_" . $unique_id . '.' . $ext;
                $ruta_imagen = createImagesFolder() . '/' . $nombre_imagen;
                

                // Mueve la imagen al directorio de imágenes
                move_uploaded_file($imagen['tmp_name'], $ruta_imagen);

                // Crea una nueva diapositiva de tipo imagen y la añade a la presentación
                $nuevaDiapositiva = new TipoImagen(null, $titulo, $contenido, $nombre_imagen);
            } else {
                $nuevaDiapositiva = new TipoImagen(null, $titulo, $contenido, 'null');
            }
        } elseif (!empty($contenido)) {
            $nuevaDiapositiva = new TipoContenido(null, $titulo, $contenido);
        } else {
            $nuevaDiapositiva = new TipoTitulo(null, $titulo);
        }

        if (isset($nuevaDiapositiva)) {
            $nuevaDiapositiva->setOrden($orden);
            $nuevaDiapositiva->nuevaDiapositiva($conn, $id_presentacion);
        }
    }
    // Establece la variable de sesión para indicar que la creación fue exitosa
    $_SESSION['guardado_exitoso'] = true;

}

// Redirigir al usuario de vuelta a la página de creación de presentaciones
header("Location: ../views/editor.php?presentacion_id=" . $id_presentacion);
exit;