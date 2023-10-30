<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/home.css">
    <title>Pantalla d'inici</title>
</head>

<body>
    <h2>Presentaciones</h2>
    <div id="global">
        <div id="añadirPresentacion">
            <a href="html/crear_p.html">
                <div class="caja_añadir">
                    <img src="icons/crear.svg" alt="Crear">
                </div>
            </a>
            <p id="añadir">AÑADIR</p>
        </div>
        <?php
        session_start();

        // Punto de entrada principal
        define('VALID_ENTRY_POINT', true);

        include 'php/config.php';

        // Incluir la clase de base de datos
        require_once ROOT_PATH . 'php/clases/db.php';

        // Obtener la única instancia de la base de datos
        $db = Database::getInstance();

        // Obtener la conexión a la base de datos
        $conn = $db->getConnection();

        // Consultar todas las presentaciones
        $query = "SELECT id, titulo FROM presentacion";
        $result = $conn->query($query);


        // Generar dinámicamente los divs con class="caja"
        while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
            <div class="caja">
                <p>
                    <?= htmlspecialchars($row['titulo']) ?>
                </p>
                <input type="hidden" name="id_presentacion" form="previewForm_<?= $row['id'] ?>" value="<?= $row['id'] ?>">
                <form hidden id="previewForm_<?= $row['id'] ?>" action="php/preview/preview.php" method="POST" hidden>
                </form>
                <div class="imagen-overlay" onclick="document.forms['previewForm_<?= $row['id'] ?>'].submit();">
                    <img src="../icons/ver.png" alt="">
                </div>
                <button class="opciones-btn clickable">+</button>
                <div class="opciones clickable" id="opciones<?= $row['id'] ?>">
                    <form action="php/editar/editar.php" method="POST">
                        <input type="hidden" name="presentacion_id" value="<?= $row['id'] ?>">
                        <button class="editar clickable"><img src="icons/editar.svg" alt="Editar">Editar</button>
                    </form>
                    <button class="clonar clickable"><img src="icons/clonar.svg" alt="Clonar">Clonar</button>
                    <form action="php/eliminar/eliminar.php" method="POST" onclick="mostrarConfirmacionEliminar(event, this)">
                        <input type="hidden" name="id_presentacion" value="<?= $row['id'] ?>">
                        <button type="submit" class="eliminar"><img src="icons/eliminar.svg" alt="Eliminar">Eliminar</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!--Mensaje para confirmar la eliminación de una presentación-->
    <dialog id="confirmarEliminar">
        <p>¿Estás seguro de que deseas eliminar esta presentación?</p>
        <form method="dialog">
            <button id="btn-aceptar">Aceptar</button>
            <button id="btn-cancelar">Cancelar</button>
        </form>
    </dialog>

    <dialog id="exito_eliminar">
        <p>La presentación se ha eliminado correctamente</p>
        <form method="dialog">
            <button id="btn-aceptar-exito">Aceptar</button>
        </form>
    </dialog>

    <script src="js/home.js"></script>

    <script>
        mostrarExitoEliminar();
        // Función para mostrar el diálogo de éxito al eliminar
        function mostrarExitoEliminar() {
            const exitoEliminarDialog = document.getElementById("exito_eliminar");
            <?php
            if (isset($_SESSION['eliminacion_exitosa']) && $_SESSION['eliminacion_exitosa'] === true) {
                echo 'exitoEliminarDialog.style.display = "block";';
                // Elimina la variable de sesión para evitar que se muestre nuevamente al recargar la página.
                unset($_SESSION['eliminacion_exitosa']);
            }
            ?>

            // Agrega un event listener al botón "Aceptar" en el diálogo de éxito
            const btnExitoAceptar = document.getElementById("btn-aceptar-exito");
            btnExitoAceptar.addEventListener("click", function () {
                // Oculta el diálogo de éxito
                exitoEliminarDialog.style.display = "none";

            });
        }
    </script>

</body>

</html>
