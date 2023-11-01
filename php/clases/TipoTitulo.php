<?php

class TipoTitulo extends Diapositiva
{
    private string $titulo;

    public function __construct(int | null $id_diapositiva, string $titulo)
    {
        parent::__construct($id_diapositiva);
        $this->titulo = $titulo;
    }

    public function getTitulo(): string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): void
    {
        $this->titulo = $titulo;
    }

    public function nuevaDiapositiva(PDO $conn, int $id_presentacion): void
    {
        $stmt = $conn->prepare("INSERT INTO diapositiva(presentacion_id) VALUES (?)");
        $stmt->bindParam(1, $id_presentacion);
        $stmt->execute();

        $id_diapositiva = $conn->lastInsertId();
        $this->setId($id_diapositiva);

        $stmt = $conn->prepare("INSERT INTO tipoTitulo(diapositiva_id, presentacion_id, titulo) VALUES (?, ?, ?)");
        $stmt->bindParam(1, $id_diapositiva);
        $stmt->bindParam(2, $id_presentacion);
        $stmt->bindParam(3, $this->titulo);
        $stmt->execute();
    }

    public function actualizaDiapositiva(PDO $conn, int $id_presentacion): void
    {
        $id_diapositiva = $this->getId();

        $stmt = $conn->prepare("SELECT titulo FROM tipoTitulo WHERE presentacion_id = ? AND diapositiva_id = ?");
        $stmt->bindParam(1, $id_presentacion);
        $stmt->bindParam(2, $id_diapositiva);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row['titulo'] !== $this->getTitulo()) {
            $newTitulo = $this->getTitulo();

            $stmt = $conn->prepare("UPDATE tipoTitulo SET titulo = ? WHERE presentacion_id = ? AND diapositiva_id = ?");

            $stmt->bindParam(1, $newTitulo);
            $stmt->bindParam(2, $id_presentacion);
            $stmt->bindParam(3, $id_diapositiva);
            $stmt->execute();
        }
    }

    public function getDiapositivaHTML(): string
    {
        return '<div class="d-container">
        <input class="focus" type="text" form="data_p" value="' . $this->getTitulo() . '" autocomplete="off"
          name="d_titulo_' . $this->getId() . '" placeholder="Haz click para añadir un título..." />
      </div>';
    }

    public static function existsDiapositiva(PDO $conn, int $id_presentacion, int $id_diapositiva): bool
    {
        $stmt = $conn->prepare("SELECT * FROM tipoTitulo WHERE presentacion_id = ? AND diapositiva_id = ?");
        $stmt->bindParam(1, $id_presentacion);
        $stmt->bindParam(2, $id_diapositiva);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return empty($result);
    }
}
