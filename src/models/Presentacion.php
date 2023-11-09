<?php

class Presentacion
{
    private int|null $id;
    private string $titulo;
    private string $descripcion;
    private string $tema;
    private string $url;
    private array $diapositivas;

    public function __construct(int|null $id, string $titulo, string $descripcion, string $tema, string $url, array $diapositivas)
    {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->tema = $tema;
        $this->url = $url;
        $this->diapositivas = $diapositivas;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    public function getTema(): string
    {
        return $this->tema;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getDiapositivas(): array
    {
        return $this->diapositivas;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function setTitulo(string $titulo): void
    {
        $this->titulo = $titulo;
    }

    public function setDescripcion(string $descripcion): void
    {
        $this->descripcion = $descripcion;
    }

    public function setTema(string $tema): void
    {
        $this->tema = $tema;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function setDiapositivas(array $diapositivas): void
    {
        $this->diapositivas = $diapositivas;
    }

    /**
     * Funcion que actualiza la informacion de la presentacion.
     * @param PDO $conn Objeto PDO de la conexion a la base de datos.
     * @return void
     */
    public function actualizarInfo(PDO $conn): void
    {
        $id_presentacion = $this->getId();

        $stmt = $conn->prepare("SELECT titulo, descripcion, tema, url FROM presentacion WHERE id = ?");
        $stmt->bindParam(1, $id_presentacion);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($this->getTitulo() !== $row['titulo']) {
            $newTitulo = $this->getTitulo();

            $stmt = $conn->prepare("UPDATE presentacion SET titulo = ? WHERE id = ?");
            $stmt->bindParam(1, $newTitulo);
            $stmt->bindParam(2, $id_presentacion);
            $stmt->execute();
        }

        if ($this->getDescripcion() !== $row['descripcion']) {
            $newDescripcion = $this->getDescripcion();

            $stmt = $conn->prepare("UPDATE presentacion SET descripcion = ? WHERE id = ?");
            $stmt->bindParam(1, $newDescripcion);
            $stmt->bindParam(2, $id_presentacion);
            $stmt->execute();
        }

        if ($this->getTema() !== $row['tema']) {
            $newTema = $this->getTema();

            $stmt = $conn->prepare("UPDATE presentacion SET tema = ? WHERE id = ?");
            $stmt->bindParam(1, $newTema);
            $stmt->bindParam(2, $id_presentacion);
            $stmt->execute();
        }

        if ($this->getUrl() != $row['url']) {
            $newUrl = $this->getUrl();

            $stmt = $conn->prepare("UPDATE presentacion SET url = ? WHERE id = ?");
            $stmt->bindParam(1, $newUrl);
            $stmt->bindParam(2, $id_presentacion);
            $stmt->execute();
        }
    }

    /**
     * Funcion que obtiene el último ID de las diapositivas de la presentación.
     * @param PDO $conn Objeto PDO de la conexion a la base de datos.
     * @return int
     */
    public function getLastDiapositivaId($conn): int
    {
        $id_presentacion = $this->getId();
        $stmt = $conn->prepare("SELECT id FROM diapositiva WHERE presentacion_id = ? ORDER BY id DESC");
        $stmt->bindParam(1, $id_presentacion);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);


        return $result['id'];
    }

    /**
     * Funcion que crea una nueva presentacion en la BD.
     * @param PDO $conn Objeto PDO de la conexion a la base de datos.
     * @return void
     */
    public function guardarNuevaPresentacion(PDO $conn): void
    {
        $stmt = $conn->prepare("INSERT INTO presentacion(titulo, descripcion, tema, url) VALUES (?, ?, ?, ?)");
        $stmt->bindParam(1, $this->titulo);
        $stmt->bindParam(2, $this->descripcion);
        $stmt->bindParam(3, $this->tema);
        $stmt->bindParam(4, $this->url);
        $stmt->execute();

        $this->id = $conn->lastInsertId();

        foreach ($this->diapositivas as $diapositiva) {
            $diapositiva->nuevaDiapositiva($conn, $this->id);
        }
    }

    /**
     * Funcion que obtiene el array de diapositivas de una presentacion.
     * @param PDO $conn Objeto PDO de la conexion a la base de datos.
     * @param int $id_presentacion ID de la presentacion que queremos consultar.
     * @return array
     */
    private static function getDiapositivasBD(PDO $conn, int $id_presentacion): array
    {
        $stmt = $conn->prepare(
            "SELECT dt.id as diapositiva_id, COALESCE(tt.titulo, tc.titulo) AS titulo, tc.contenido
            FROM presentacion p 
                LEFT JOIN diapositiva dt ON p.id = dt.presentacion_id
                LEFT JOIN tipoTitulo tt ON dt.id = tt.diapositiva_id AND dt.presentacion_id = tt.presentacion_id
                LEFT JOIN tipoContenido tc ON dt.id = tc.diapositiva_id AND dt.presentacion_id = tc.presentacion_id
            WHERE p.id = ?;"
        );
        $stmt->bindParam(1, $id_presentacion);
        $stmt->execute();

        $diapositivas = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if ($row['contenido'] === null) {
                array_push($diapositivas, new TipoTitulo($row['diapositiva_id'], $row['titulo']));
            } else {
                array_push($diapositivas, new TipoContenido($row['diapositiva_id'], $row['titulo'], $row['contenido']));
            }
        }

        return $diapositivas;
    }

    /**
     * Funcion que retorna una presentacion instanciada de la BD.
     * @param PDO $conn Objeto PDO de la conexion a la base de datos.
     * @param int $id_presentacion ID de la presentacion que queremos instanciar.
     * @return Presentacion
     */
    public static function getPresentacionBD(PDO $conn, int $id_presentacion): Presentacion
    {
        $stmt = $conn->prepare("SELECT id, titulo, descripcion, tema, url FROM presentacion WHERE id = ?");
        $stmt->bindParam(1, $id_presentacion);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $presentacion = new Presentacion($row['id'], $row['titulo'], $row['descripcion'], $row['tema'], $row['url'], []);

        $diapositivas = Presentacion::getDiapositivasBD($conn, $id_presentacion);
        $presentacion->setDiapositivas($diapositivas);
        return $presentacion;
    }

    /**
     * Funcion que retorna una presentacion instanciada de la BD en base a la URL publica.
     * @param PDO $conn Objeto PDO de la conexion a la base de datos.
     * @param int $id_presentacion ID de la presentacion que queremos instanciar.
     * @return Presentacion
     */
    public static function getPresentacionByURL(PDO $conn, int $url): Presentacion|null
    {
        $stmt = $conn->prepare("SELECT id FROM presentacion WHERE url = ?");
        $stmt->bindParam(1, $url);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (isset($row["id"])) {
            return self::getPresentacionBD($conn, $row["id"]);
        } else {
            return null;
        }

    }

    /**
     * Funcion que elimina una presentacion de la BD.
     * @param PDO $conn Objeto PDO de la conexion a la base de datos.
     * @param int $id_presentacion ID de la presentacion a eliminar.
     * @return string
     */
    public static function eliminarPresentacionBD(PDO $conn, int $id): string
    {
        try {
            $conn->beginTransaction();

            // Finalmente, elimina la presentación de la tabla Presentacions
            $query = "DELETE FROM presentacion WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(1, $id);
            $stmt->execute();

            $conn->commit();
            return 'La presentación se ha eliminado correctamente.';
        } catch (PDOException $e) {
            $conn->rollBack();
            return 'Error al eliminar la presentación.';
        }
    }
}
