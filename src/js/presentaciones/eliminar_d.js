// Función para mostrar el diálogo al hacer clic en el botón de eliminar
function confirmDelete(event, diapositiva) {
    event.preventDefault();
    const dialog = document.getElementById("confirmarEliminar");
    // Muestra el diálogo
    dialog.style.display = "block";

    const btnAceptar = document.getElementById("btn-aceptar");
    btnAceptar.onclick = function() {
        const diapositivas = document.querySelectorAll('.d-container');
        // Encuentra el índice de la diapositiva a eliminar
        const index = Array.from(diapositivas).indexOf(diapositiva);

        diapositiva.remove();
        numDiapositivas--; // Disminuir el contador aquí
        actualizarListaDiapositivas();

        // Si se eliminó la primera diapositiva, muestra la nueva primera diapositiva
        if (index === 0 && numDiapositivas > 0) {
            mostrarDiapositiva(0);
        // Si no, muestra la diapositiva anterior a la eliminada
        } else if (index > 0) {
            mostrarDiapositiva(index - 1);
        }

        dialog.style.display = "none";
    };

    const btnCancelar = document.getElementById("btn-cancelar");
    btnCancelar.onclick = function() {
        dialog.style.display = "none";
    };

    return false;
}
