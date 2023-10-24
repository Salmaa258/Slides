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
                <div class="imagen-overlay">
                    <img src="../icons/ver.png" alt="">
                </div>
                <button class="opciones-btn" onclick="mostrarOpciones(event)">+</button>
                <div class="opciones" id="opciones<?= $row['id'] ?>">
                    <button class="editar"><img src="icons/editar.svg" alt="Editar">Editar</button>
                    <button class="clonar"><img src="icons/clonar.svg" alt="Clonar">Clonar</button>
                    <form action="php/delete/eliminar.php" method="POST" onsubmit="return confirmarEliminacion()">
                        <input type="hidden" name="presentation_id" value="<?= $row['id'] ?>">
                        <button type="submit" class="eliminar"><img src="icons/eliminar.svg" alt="Eliminar">Eliminar</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </div>


    <script src="js/home.js"></script>

</body>

</html>