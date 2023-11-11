// Función para mostrar el diálogo al hacer clic en el botón de eliminar
function confirmDelete(event, diapositiva) {
    event.preventDefault();
    const dialog = document.getElementById("confirmarEliminar");
    dialog.style.display = "block";

    const dbId = diapositiva.getAttribute('data-db-id');
    
    const btnAceptar = document.getElementById("btn-aceptar");
    btnAceptar.onclick = function() {
        // Aquí debería ir la lógica para eliminar la diapositiva de la BD usando su ID
        // Por ejemplo, podrías hacer una solicitud AJAX al servidor para eliminar la diapositiva y luego manejar la respuesta
        
        diapositiva.remove(); // Elimina la diapositiva del DOM
        actualizarListaDiapositivas(); // Actualiza la lista de diapositivas

        dialog.style.display = "none";
    };

    const btnCancelar = document.getElementById("btn-cancelar");
    btnCancelar.onclick = function() {
        dialog.style.display = "none";
    };

    return false;
}
