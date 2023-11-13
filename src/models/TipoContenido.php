<?php

class TipoContenido extends Diapositiva
{
    private string $titulo;
    private string $contenido;

    public function __construct(int|null $id_diapositiva, string $titulo, string $contenido)
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

    public function setTitulo(string $titulo): void
    {
        $this->titulo = $titulo;
    }

    public function setContenido(string $contenido): void
    {
        $this->contenido = $contenido;
    }

    public function nuevaDiapositiva(PDO $conn, int $id_presentacion): void
    {
        $stmt = $conn->prepare("INSERT INTO diapositiva(presentacion_id, orden) VALUES (?, ?)");
        $stmt->bindParam(1, $id_presentacion);
        $stmt->bindParam(2, $this->orden);
        $stmt->execute();

        $id_diapositiva = $conn->lastInsertId();
        $this->setId($id_diapositiva);

        $stmt = $conn->prepare("INSERT INTO tipoContenido(diapositiva_id, presentacion_id, titulo, contenido) VALUES (?, ?, ?, ?)");
        $stmt->bindParam(1, $id_diapositiva);
        $stmt->bindParam(2, $id_presentacion);
        $stmt->bindParam(3, $this->titulo);
        $stmt->bindParam(4, $this->contenido);
        $stmt->execute();
    }

    public function actualizarDiapositiva(PDO $conn, int $id_presentacion): void
    {
        $id_diapositiva = $this->getId();

        $stmt = $conn->prepare("SELECT titulo, contenido FROM tipoContenido WHERE presentacion_id = ? AND diapositiva_id = ?;");
        $stmt->bindParam(1, $id_presentacion);
        $stmt->bindParam(2, $id_diapositiva);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row['titulo'] !== $this->getTitulo()) {
            $newTitulo = $this->getTitulo();

            $stmt = $conn->prepare("UPDATE tipoContenido SET titulo = ? WHERE presentacion_id = ? AND diapositiva_id = ?;");

            $stmt->bindParam(1, $newTitulo);
            $stmt->bindParam(2, $id_presentacion);
            $stmt->bindParam(3, $id_diapositiva);
            $stmt->execute();
        }

        if ($row['contenido'] !== $this->getContenido()) {
            $newTitulo = $this->getContenido();

            $stmt = $conn->prepare("UPDATE tipoContenido SET contenido = ? WHERE presentacion_id = ? AND diapositiva_id = ?;");

            $stmt->bindParam(1, $newTitulo);
            $stmt->bindParam(2, $id_presentacion);
            $stmt->bindParam(3, $id_diapositiva);
            $stmt->execute();
        }
    }

    public function getDiapositivaHTML(): string
    {
        return '
        <div class="d-container" data-id="' . $this->getId() . '">
            <div class="delete-slide-icon">
                <img src="../assets/icons/eliminar.svg" alt="Eliminar Diapositiva" onclick="confirmDelete(event, this.closest(\'.d-container\'))">
            </div>
        <input class="focus" type="text" form="data_p" value="' . $this->getTitulo() . '" autocomplete="off"
        name="d_titulo_' . $this->getId() . '" placeholder="Haz click para añadir un título..." />
        <textarea class="focus" form="data_p" autocomplete="off"
        name="d_contenido_' . $this->getId() . '" placeholder="Haz click para añadir un texto">' . $this->getContenido() . '</textarea>
      </div>';
    }

    public function getDiapositivaPreview(): string
    {
        return '
        <div class="d-container" style="display: none;">
            <h1 class="d_titulo_' . $this->getId() . '">' . $this->getTitulo() . '</h1>
            <p class="d_contenido_' . $this->getId() . '">' . $this->getContenido() . '</p>
        </div>';
    }
}
