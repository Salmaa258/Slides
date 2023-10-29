<?php

class Presentacion
{
    private int $id;
    private string $titulo;
    private string $descripcion;
    private array $diapositivas;

    public function __construct(int $id, string $titulo, string $descripcion, array $diapositivas)
    {
        $this->id = $id;
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->diapositivas = $diapositivas;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitulo()
    {
        return $this->titulo;
    }

    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function getDiapositivas()
    {
        return $this->diapositivas;
    }

    public function setId(int $id)
    {
        $this->id = $id;
    }

    public function setTitulo(string $titulo)
    {
        $this->titulo = $titulo;
    }

    public function setDescripcion(string $descripcion)
    {
        $this->descripcion = $descripcion;
    }

    public function setDiapositivas(array $diapositivas)
    {
        $this->diapositivas = $diapositivas;
    }

    public function añadirDiapositiva(Diapositiva $diapositiva)
    {
        array_push($this->diapositivas, $diapositiva);
    }

    public function eliminarDiapositiva(int $id_diapositiva)
    {
        foreach ($this->diapositivas as $key => $diapositiva) {
            if ($diapositiva->getId() === $id_diapositiva) {
                unset($this->diapositivas[$key]);
            }
        }
    }

    public function nuevaPresentacion(PDO $conn)
    {
        $stmt = $conn->prepare("INSERT INTO presentacion(titulo, descripcion) VALUES (?, ?)");
        $stmt->bindParam(1, $this->titulo);
        $stmt->bindParam(2, $this->descripcion);
        $stmt->execute();

        $id_presentacion = $conn->lastInsertId();

        foreach ($this->diapositivas as $index => $diapositiva) {
            $diapositiva->nuevaDiapositivaBD($conn, $id_presentacion);
        }
    }

    public static function nuevaPresentacionBD(PDO $conn, string $titulo, string $descripcion, array $diapositivas)
    {
        $stmt = $conn->prepare("INSERT INTO presentacion(titulo, descripcion) VALUES (?, ?)");
        $stmt->bindParam(1, $titulo);
        $stmt->bindParam(2, $descripcion);
        $stmt->execute();

        $id_presentacion = $conn->lastInsertId();

        foreach ($diapositivas as $index => $diapositiva) {
            $diapositiva->nuevaDiapositiva($conn, $id_presentacion);
        }
    }

    public static function getPresentacionBD(PDO $conn, int $id_presentacion): Presentacion
    {
        $stmt = $conn->prepare("SELECT (id, titulo, descripcion) FROM presentacion WHERE id = ?");
        $stmt->bindParam(1, $id_presentacion);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $presentacion = new Presentacion($row['id'], $row['titulo'], $row['descripcion'], []);

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
                $diapositivas[$row["diapositiva_id"]] = new TipoTitulo($row['diapositiva_id'], $row['titulo']);
            } else {
                $diapositivas[$row["diapositiva_id"]] = new TipoContenido($row['diapositiva_id'], $row['titulo'], $row['contenido']);
            }
        }
        $presentacion->setDiapositivas($diapositivas);
        return $presentacion;
    }

    public static function eliminarPresentacionBD(PDO $conn, int $id)
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
