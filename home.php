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
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) : ?>
            <div class="caja">
                <p><?= htmlspecialchars($row['titulo']) ?></p>
                <input type="hidden" name="id_presentacion" form="previewForm_<?= $row['id'] ?>" value="<?= $row['id'] ?>">
                <form hidden id="previewForm_<?= $row['id'] ?>" action="php/preview/preview.php" method="POST" hidden></form>
                <div class="imagen-overlay" onclick="document.forms['previewForm_<?= $row['id'] ?>'].submit();">
                    <img src="../icons/ver.png" alt="">
                </div>
                <button class="opciones-btn clickable">+</button>
                <div class="opciones clickable" id="opciones<?= $row['id'] ?>">
                    <button class="editar clickable"><img src="icons/editar.svg" alt="Editar">Editar</button>
                    <button class="clonar clickable"><img src="icons/clonar.svg" alt="Clonar">Clonar</button>
                    <form action="php/eliminar/eliminar.php" method="POST" onsubmit="return confirmarEliminacion()">
                        <input type="hidden" name="presentation_id" value="<?= $row['id'] ?>">
                        <button type="submit clickable" class="eliminar"><img src="icons/eliminar.svg" alt="Eliminar">Eliminar</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    <script src="js/home.js"></script>
</body>

</html>