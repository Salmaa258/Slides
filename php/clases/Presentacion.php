<?php

class Presentacion
{
    private int|null $id;
    private string $titulo;
    private string $descripcion;
    private array $diapositivas;

    public function __construct(int|null $id, string $titulo, string $descripcion, array $diapositivas)
    {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
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

    public static function getTituloBD(PDO $conn, int $id): string
    {
        $stmt = $conn->prepare("SELECT titulo FROM presentacion WHERE id = ?");
        $stmt->bindParam(1, $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row["titulo"];
    }

    public function getDescripcion(): string
    {
        return $this->descripcion;
    }

    public static function getDescripcionBD(PDO $conn, int $id): string
    {
        $stmt = $conn->prepare("SELECT descripcion FROM presentacion WHERE id = ?");
        $stmt->bindParam(1, $id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row["descripcion"];
    }

    public function getDiapositivas(): array
    {
        return $this->diapositivas;
    }

    public static function getDiapositivasBD(PDO $conn, int $id_presentacion): array
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

    public function actualizarInfo(PDO $conn): void
    {
        $id_presentacion = $this->getId();

        $stmt = $conn->prepare("SELECT titulo, descripcion FROM presentacion WHERE id = ?");
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
    }

    public function setDiapositivas(array $diapositivas): void
    {
        $this->diapositivas = $diapositivas;
    }

    public function añadirDiapositiva(Diapositiva $diapositiva): void
    {
        array_push($this->diapositivas, $diapositiva);
    }

    public function getLastDiapositivaId($conn): int
    {
        $id_presentacion = $this->getId();
        $stmt = $conn->prepare("SELECT id FROM diapositiva WHERE presentacion_id = ? ORDER BY id DESC");
        $stmt->bindParam(1, $id_presentacion);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        
        return $result['id'];
    }

    public function nuevaPresentacion(PDO $conn): void
    {
        $stmt = $conn->prepare("INSERT INTO presentacion(titulo, descripcion) VALUES (?, ?)");
        $stmt->bindParam(1, $this->titulo);
        $stmt->bindParam(2, $this->descripcion);
        $stmt->execute();

        $this->id = $conn->lastInsertId();

        foreach ($this->diapositivas as $diapositiva) {
            $diapositiva->nuevaDiapositiva($conn, $this->id);
        }
    }

    public function actualizaPresentacion(PDO $conn): void
    {
        $id_presentacion = $this->getId();

        $stmt = $conn->prepare("SELECT FROM presentacion(titulo, descripcion) VALUES (?)");
        $stmt->bindParam(1, $id_presentacion);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row['titulo'] !== $this->getTitulo()) {
            $this->setTitulo($row['titulo']);
        }

        if ($row['descripcion'] !== $this->getTitulo()) {
            $this->setTitulo($row['titulo']);
        }

        $diapositivas = $this->diapositivas;

        foreach ($diapositivas as $index => $diapositiva) {
            if ($diapositiva->exists()) {
                $diapositiva->actualizaDiapositiva($conn, $id_presentacion);
            } else {
                $diapositiva->nuevaDiapositiva($conn, $id_presentacion);
            }
        }
    }

    public static function getPresentacionBD(PDO $conn, int $id_presentacion): Presentacion
    {
        $stmt = $conn->prepare("SELECT id, titulo, descripcion FROM presentacion WHERE id = ?");
        $stmt->bindParam(1, $id_presentacion);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $presentacion = new Presentacion($row['id'], $row['titulo'], $row['descripcion'], []);

        $diapositivas = Presentacion::getDiapositivasBD($conn, $id_presentacion);
        $presentacion->setDiapositivas($diapositivas);
        return $presentacion;
    }

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
