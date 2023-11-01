<?php

abstract class Diapositiva extends Presentacion
{
    private int|null $id;

    public function __construct(int|null $id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    abstract public function nuevaDiapositiva(PDO $conn, int $id_presentacion): void;
    abstract public function actualizaDiapositiva(PDO $conn, int $id_presentacion): void;
    abstract public function getDiapositivaHTML(): string;

    public function eliminarDiapositiva(PDO $conn, int $id_presentacion): string
    {
        $id_diapositiva = $this->id;

        try {
            $conn->beginTransaction();

            $query = "DELETE FROM diapositiva WHERE id = ? AND presentacion_id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(1, $id_diapositiva);
            $stmt->bindParam(2, $id_presentacion);
            $stmt->execute();

            $conn->commit();
            return 'La diapositiva se ha eliminado correctamente.';
        } catch (PDOException $e) {
            $conn->rollBack();
            return 'Error al eliminar la diapositiva.';
        }
    }
}
