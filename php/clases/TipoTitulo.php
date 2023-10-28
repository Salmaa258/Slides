<?php

class TipoTitulo extends Diapositiva
{
    private string $titulo;

    public function __construct(int $id_diapositiva, string $titulo)
    {
        parent::__construct($id_diapositiva);
        $this->titulo = $titulo;
    }

    public function nuevaDiapositiva(PDO $conn, int $id_presentacion){
        $stmt = $conn->prepare("INSERT INTO diapositiva(presentacion_id) VALUES (?)");
        $stmt->bindParam(1, $id_presentacion);
        $stmt->execute();

        $id_diapositiva = $conn->lastInsertId();

        $stmt = $conn->prepare("INSERT INTO tipoTitulo(diapositiva_id, presentacion_id, titulo) VALUES (?, ?, ?)");
        $stmt->bindParam(1, $id_diapositiva);
        $stmt->bindParam(2, $id_presentacion);
        $stmt->bindParam(3, $this->titulo);
        $stmt->execute();
    }

    public static function nuevaDiapositivaBD(PDO $conn, int $id_presentacion, string $titulo)
    {
        $stmt = $conn->prepare("INSERT INTO diapositiva(presentacion_id) VALUES (?)");
        $stmt->bindParam(1, $id_presentacion);
        $stmt->execute();

        $id_diapositiva = $conn->lastInsertId();

        $stmt = $conn->prepare("INSERT INTO tipoTitulo(diapositiva_id, presentacion_id, titulo) VALUES (?, ?, ?)");
        $stmt->bindParam(1, $id_diapositiva);
        $stmt->bindParam(2, $id_presentacion);
        $stmt->bindParam(3, $titulo);
        $stmt->execute();
    }
}
