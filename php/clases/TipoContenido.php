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

    public function nuevaDiapositiva(PDO $conn, int $id_presentacion)
    {
        $stmt = $conn->prepare("INSERT INTO diapositiva(presentacion_id) VALUES (?)");
        $stmt->bindParam(1, $id_presentacion);
        $stmt->execute();

        $id_diapositiva = $conn->lastInsertId();

        $stmt = $conn->prepare("INSERT INTO tipoTitulo(diapositiva_id, presentacion_id, titulo, contenido) VALUES (?, ?, ?, ?)");
        $stmt->bindParam(1, $id_diapositiva);
        $stmt->bindParam(2, $id_presentacion);
        $stmt->bindParam(3, $this->titulo);
        $stmt->bindParam(4, $this->contenido);
        $stmt->execute();
    }

    public static function nuevaDiapositivaBD(PDO $conn, int $id_presentacion, string $titulo, string $contenido)
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
