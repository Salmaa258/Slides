<?php

class TipoContenido extends Diapositiva
{
    private string $titulo;
    private string $contenido;

    public function __construct(int $id_diapositiva, string $titulo, string $contenido)
    {
        parent::__construct($id_diapositiva);
        $this->titulo = $titulo;
        $this->contenido = $contenido;
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function getContenido(): string
    {
        return $this->contenido;
    }

    public function exists($conn, $id_presentacion): bool
    {
        $id_diapositiva = $this->getId();
        $stmt = $conn->prepare("SELECT FROM tipoContenido WHERE presentacion_id = ? AND diapositiva_id = ?");
        $stmt->bindParam(1, $id_presentacion);
        $stmt->bindParam(2, $id_diapositiva);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return empty($result);
    }

    public function nuevaDiapositiva(PDO $conn, int $id_presentacion): void
    {
        $stmt = $conn->prepare("INSERT INTO diapositiva(presentacion_id) VALUES (?)");
        $stmt->bindParam(1, $id_presentacion);
        $stmt->execute();

        $id_diapositiva = $conn->lastInsertId();

        $stmt = $conn->prepare("INSERT INTO tipoContenido(diapositiva_id, presentacion_id, titulo, contenido) VALUES (?, ?, ?, ?)");
        $stmt->bindParam(1, $id_diapositiva);
        $stmt->bindParam(2, $id_presentacion);
        $stmt->bindParam(3, $this->titulo);
        $stmt->bindParam(4, $this->contenido);
        $stmt->execute();
    }

    public function actualizaDiapositiva(PDO $conn, int $id_presentacion): void
    {
        $id_diapositiva = $this->getId();

        $stmt = $conn->prepare("SELECT FROM tipoContenido(titulo, contenido) WHERE presentacion_id = ? AND diapositiva_id = ?");
        $stmt->bindParam(1, $id_presentacion);
        $stmt->bindParam(2, $id_diapositiva);
        $stmt->execute();

        $row = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($row['titulo'] !== $this->getTitulo()) {
            $newTitulo = $this->getTitulo();

            $stmt = $conn->prepare("UPDATE tipoTitulo SET titulo = ? WHERE presentacion_id = ? AND diapositiva_id = ?");

            $stmt->bindParam(1, $newTitulo);
            $stmt->bindParam(1, $id_presentacion);
            $stmt->bindParam(2, $id_diapositiva);
            $stmt->execute();
        }

        if ($row['contenido'] !== $this->getContenido()) {
            $newTitulo = $this->getContenido();

            $stmt = $conn->prepare("UPDATE tipoContenido SET contenido = ? WHERE presentacion_id = ? AND diapositiva_id = ?");

            $stmt->bindParam(1, $newTitulo);
            $stmt->bindParam(1, $id_presentacion);
            $stmt->bindParam(2, $id_diapositiva);
            $stmt->execute();
        }
    }

    public static function nuevaDiapositivaBD(PDO $conn, int $id_presentacion, string $titulo, string $contenido): void
    {
        $stmt = $conn->prepare("INSERT INTO diapositiva(presentacion_id) VALUES (?)");
        $stmt->bindParam(1, $id_presentacion);
        $stmt->execute();

        $id_diapositiva = $conn->lastInsertId();

        $stmt = $conn->prepare("INSERT INTO tipoTitulo(diapositiva_id, presentacion_id, titulo, contenido) VALUES (?, ?, ?, ?)");
        $stmt->bindParam(1, $id_diapositiva);
        $stmt->bindParam(2, $id_presentacion);
        $stmt->bindParam(3, $titulo);
        $stmt->bindParam(4, $contenido);
        $stmt->execute();
    }
}
